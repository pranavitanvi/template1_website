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

// Dynamic Categories
Route::get('/category/{category}', [ShopController::class, 'category'])->name('category');

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

// Authentication & Customer Portal Pages
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::post('/login', [PageController::class, 'submitLogin'])->name('login.submit');
Route::get('/register', [PageController::class, 'register'])->name('register');
Route::post('/register', [PageController::class, 'submitRegister'])->name('register.submit');
Route::post('/logout', [PageController::class, 'logout'])->name('logout');
Route::get('/account', [PageController::class, 'account'])->name('account');

// Backward Compatibility redirects for legacy .html requests
Route::get('/{page}.html', function ($page) use ($categoryShortcuts) {
    if ($page === 'index') return redirect()->route('home');
    if ($page === 'new_arrivals') return redirect()->route('new-arrivals');
    if ($page === 'order-success') return redirect()->route('order-success');
    if ($page === 'customer-care') return redirect()->route('customer-care');
    if ($page === 'size-guide') return redirect()->route('size-guide');
    if ($page === 'jewellery-care') return redirect()->route('jewellery-care');
    if ($page === 'product-details') return redirect()->route('product.details', request()->query());
    if (in_array($page, $categoryShortcuts)) return redirect()->route('category', $page);
    if (Route::has($page)) return redirect()->route($page);
    return redirect()->route('home');
});
