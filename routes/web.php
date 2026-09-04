<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Aura Fine Jewellery
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [PageController::class, 'home'])->name('home');

// Catalog & Shopping
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/bridal', [ShopController::class, 'bridal'])->name('bridal');
Route::get('/new-arrivals', [ShopController::class, 'newArrivals'])->name('new-arrivals');
Route::get('/product-details', [ShopController::class, 'productDetails'])->name('product.details');
Route::get('/product/{id}', [ShopController::class, 'productDetails'])->name('product.view');


// Live Products E-Commerce APIs
Route::get('/api/products', function (\Illuminate\Http\Request $request) {
    $products = \App\Services\CmsApiService::getProducts($request->all());
    $filtered = array_values(array_filter($products, function($p) {
        if (!empty($p['is_sold_out'])) return false;
        if (isset($p['product_sold_out_status']) && ($p['product_sold_out_status'] === true || $p['product_sold_out_status'] === 1 || $p['product_sold_out_status'] === '1')) return false;
        if (isset($p['availability']) && in_array(strtolower((string)$p['availability']), ['sold_out', 'out_of_stock'])) return false;
        return true;
    }));
    return response()->json($filtered)
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
})->name('api.storefront.products');

Route::get('/api/products/{id}', function ($id) {
    $product = \App\Services\CmsApiService::getProduct($id);
    if ($product && empty($product['is_sold_out']) && !in_array(strtolower((string)($product['availability'] ?? '')), ['sold_out', 'out_of_stock'])) {
        return response()->json(['success' => true, 'data' => $product])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
    return response()->json([
        'success' => false, 
        'message' => 'Product is sold out or unavailable.',
        'is_sold_out' => true
    ], 404)
    ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0')
    ->header('Pragma', 'no-cache')
    ->header('Expires', '0');
})->name('api.storefront.product.details');

// Helper to get or create a consistent cart session ID
if (!function_exists('getStorefrontCartSessionId')) {
    function getStorefrontCartSessionId(\Illuminate\Http\Request $request): string {
        $sid = $request->header('X-Cart-Session') 
            ?: ($request->input('session_id') 
            ?: ($request->query('session_id') 
            ?: session('cart_session_id')));

        if (!$sid) {
            $sid = 'aura_' . bin2hex(random_bytes(16));
            session(['cart_session_id' => $sid]);
            session()->save();
        }
        return $sid;
    }
}

// Live Shopping Cart E-Commerce APIs
Route::get('/api/cart', function (\Illuminate\Http\Request $request) {
    $token = session('customer_token');
    $customer = session('customer');
    $params = $request->all();
    $sessionId = getStorefrontCartSessionId($request);
    $params['session_id'] = $sessionId;
    if ($customer && !isset($params['customer_id'])) {
        $params['customer_id'] = $customer['id'] ?? null;
    }
    $res = \App\Services\CmsApiService::getCart($params, $token);
    if (is_array($res)) {
        $res['session_id'] = $sessionId;
    }
    return response()->json($res)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0')->header('Pragma', 'no-cache')->header('Expires', '0');
})->name('api.storefront.cart.get');

Route::post('/api/cart/add', function (\Illuminate\Http\Request $request) {
    if (!session()->has('customer') && !session()->has('customer_token')) {
        return response()->json([
            'success' => false,
            'requires_login' => true,
            'redirect' => route('login'),
            'message' => 'Please sign in to add items to your shopping bag.'
        ], 401)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0')->header('Pragma', 'no-cache')->header('Expires', '0');
    }

    $token = session('customer_token');
    $customer = session('customer');
    $data = $request->all();
    $sessionId = getStorefrontCartSessionId($request);
    $data['session_id'] = $sessionId;
    if ($customer && !isset($data['customer_id'])) {
        $data['customer_id'] = $customer['id'] ?? null;
    }
    if (isset($data['product_id']) && is_numeric($data['product_id'])) {
        $data['product_id'] = (int)$data['product_id'];
    }
    if (isset($data['quantity']) && is_numeric($data['quantity'])) {
        $data['quantity'] = (int)$data['quantity'];
    }
    $res = \App\Services\CmsApiService::addToCart($data, $token);
    if (is_array($res)) {
        $res['session_id'] = $sessionId;
    }
    return response()->json($res)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0')->header('Pragma', 'no-cache')->header('Expires', '0');
})->name('api.storefront.cart.add');

Route::put('/api/cart/{id}', function (\Illuminate\Http\Request $request, $id) {
    $token = session('customer_token');
    $data = $request->all();
    $sessionId = getStorefrontCartSessionId($request);
    $data['session_id'] = $sessionId;
    $res = \App\Services\CmsApiService::updateCartItem($id, $data, $token);
    if (is_array($res)) {
        $res['session_id'] = $sessionId;
    }
    return response()->json($res)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0')->header('Pragma', 'no-cache')->header('Expires', '0');
})->name('api.storefront.cart.update');

Route::delete('/api/cart/{id}', function (\Illuminate\Http\Request $request, $id) {
    $token = session('customer_token');
    $params = $request->all();
    $sessionId = getStorefrontCartSessionId($request);
    $params['session_id'] = $sessionId;
    $res = \App\Services\CmsApiService::removeCartItem($id, $params, $token);
    if (is_array($res)) {
        $res['session_id'] = $sessionId;
    }
    return response()->json($res)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0')->header('Pragma', 'no-cache')->header('Expires', '0');
})->name('api.storefront.cart.remove');

Route::post('/api/cart/clear', function (\Illuminate\Http\Request $request) {
    $token = session('customer_token');
    $customer = session('customer');
    $data = $request->all();
    $sessionId = getStorefrontCartSessionId($request);
    $data['session_id'] = $sessionId;
    if ($customer && !isset($data['customer_id'])) {
        $data['customer_id'] = $customer['id'] ?? null;
    }
    $res = \App\Services\CmsApiService::clearCart($data, $token);
    if (is_array($res)) {
        $res['session_id'] = $sessionId;
    }
    return response()->json($res)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0')->header('Pragma', 'no-cache')->header('Expires', '0');
})->name('api.storefront.cart.clear');

Route::post('/api/cart/sync', function (\Illuminate\Http\Request $request) {
    if (!session()->has('customer') && !session()->has('customer_token')) {
        return response()->json([
            'success' => false,
            'requires_login' => true,
            'redirect' => route('login'),
            'message' => 'Please sign in to sync your shopping bag.'
        ], 401)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0')->header('Pragma', 'no-cache')->header('Expires', '0');
    }

    $token = session('customer_token');
    $customer = session('customer');
    $data = $request->all();
    $sessionId = getStorefrontCartSessionId($request);
    $data['session_id'] = $sessionId;
    if ($customer && !isset($data['customer_id'])) {
        $data['customer_id'] = $customer['id'] ?? null;
    }
    $items = $request->input('items', []);
    $res = \App\Services\CmsApiService::syncCart($items, $data, $token);
    if (is_array($res)) {
        $res['session_id'] = $sessionId;
    }
    return response()->json($res)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0')->header('Pragma', 'no-cache')->header('Expires', '0');
})->name('api.storefront.cart.sync');

// Dynamic Categories & Collections
Route::get('/category/{category}', [ShopController::class, 'category'])->name('category');
Route::get('/collection/{collection}', [ShopController::class, 'collection'])->name('collection');
Route::get('/collections/{collection}', [ShopController::class, 'collection'])->name('collection.show');

// Direct category shortcut routes (e.g. /rings, /earrings)
$categoryShortcuts = ['rings', 'earrings', 'necklaces', 'bracelets', 'bangles', 'chains', 'mangalsutras', 'pendants', 'mens', 'womens'];
foreach ($categoryShortcuts as $cat) {
    Route::get('/' . $cat, fn() => app(ShopController::class)->category($cat))->name('category.' . $cat);
}

// Shopping Bag & Checkout
Route::get('/cart', [CartController::class, 'cart'])->name('cart');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::get('/order-success', [CartController::class, 'orderSuccess'])->name('order-success');
Route::get('/wishlist', [CartController::class, 'wishlist'])->name('wishlist');

// Editorial & Informational Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/collections', [PageController::class, 'collections'])->name('collections');
Route::get('/craftsmanship', [PageController::class, 'craftsmanship'])->name('craftsmanship');
Route::get('/customer-care', [PageController::class, 'customerCare'])->name('customer-care');
Route::get('/stores', [PageController::class, 'stores'])->name('stores');
Route::get('/faqs', [PageController::class, 'faqs'])->name('faqs');
Route::get('/size-guide', [PageController::class, 'sizeGuide'])->name('size-guide');
Route::get('/shipping', [PageController::class, 'shipping'])->name('shipping');
Route::get('/returns', [PageController::class, 'returns'])->name('returns');
Route::get('/jewellery-care', [PageController::class, 'jewelleryCare'])->name('jewellery-care');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/privacy', fn() => redirect()->route('privacy-policy'));
Route::get('/terms-and-conditions', [PageController::class, 'termsConditions'])->name('terms-conditions');
Route::get('/terms-conditions', fn() => redirect()->route('terms-conditions'));
Route::get('/terms', fn() => redirect()->route('terms-conditions'));

// Authentication & Customer Portal Pages
Route::get('/captcha/generate', [PageController::class, 'generateCaptcha'])->name('captcha.generate');
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::post('/login', [PageController::class, 'submitLogin'])->name('login.submit');
Route::get('/register', [PageController::class, 'register'])->name('register');
Route::post('/register', [PageController::class, 'submitRegister'])->name('register.submit');
Route::post('/logout', [PageController::class, 'logout'])->name('logout');
Route::get('/account', [PageController::class, 'account'])->name('account');

// Backward Compatibility redirects for legacy .html requests
Route::get('/{page}.html', function ($page) use ($categoryShortcuts) {
    $query = request()->query();
    if ($page === 'index') return redirect()->route('home', $query);
    if ($page === 'new_arrivals') return redirect()->route('new-arrivals', $query);
    if ($page === 'order-success') return redirect()->route('order-success', $query);
    if ($page === 'customer-care') return redirect()->route('customer-care', $query);
    if ($page === 'size-guide') return redirect()->route('size-guide', $query);
    if ($page === 'jewellery-care') return redirect()->route('jewellery-care', $query);
    if ($page === 'privacy' || $page === 'privacy-policy') return redirect()->route('privacy-policy', $query);
    if ($page === 'terms' || $page === 'terms-conditions' || $page === 'terms-and-conditions') return redirect()->route('terms-conditions', $query);
    if ($page === 'product-details') return redirect()->route('product.details', $query);
    if (in_array($page, $categoryShortcuts)) return redirect()->route('category', array_merge(['category' => $page], $query));
    if (Route::has($page)) return redirect()->route($page, $query);
    return redirect()->route('home');
});
