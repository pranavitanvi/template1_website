<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CmsApiService
{
    /**
     * In-memory request cache to prevent duplicate HTTP calls within the same page request.
     */
    protected static array $requestCache = [];

    /**
     * Get API base URL for current store.
     */
    protected static function getBaseUrl(): string
    {
        $apiUrl = rtrim(config('cms.api_url', 'http://Jewellerysoft.test/api/v1/cms'), '/');
        $storeId = config('cms.store_id', 2);
        return $apiUrl . '/' . $storeId;
    }

    /**
     * Fetch JSON data from endpoint live on each request with session fallback.
     */
    protected static function fetch(string $endpoint): ?array
    {
        // 1. In-memory cache for the current page request (prevents duplicate API calls within same page load)
        if (isset(self::$requestCache[$endpoint])) {
            return self::$requestCache[$endpoint];
        }

        $sessionKey = 'cms_session_' . str_replace(['/', '-', '?'], '_', $endpoint);

        $isProductOrCart = str_starts_with($endpoint, 'products') || str_starts_with($endpoint, 'cart');

        // 2. Fetch live data from API with real-time cache busting
        try {
            $url = self::getBaseUrl() . '/' . ltrim($endpoint, '/');
            $cacheBust = (str_contains($url, '?') ? '&' : '?') . '_t=' . round(microtime(true) * 1000);
            $response = Http::timeout(6)
                ->withHeaders([
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                ])
                ->get($url . $cacheBust);

            if ($response->successful()) {
                $json = $response->json();
                $data = $json['data'] ?? [];
                if (is_array($data)) {
                    if (isset($json['store_name'])) {
                        $data['store_name'] = $json['store_name'];
                    }
                    if (isset($json['store_id'])) {
                        $data['store_id'] = $json['store_id'];
                    }
                }

                // Cache in memory for this request
                self::$requestCache[$endpoint] = $data;

                // Save to session as fallback (only for static layout endpoints)
                if (!$isProductOrCart) {
                    try {
                        if (function_exists('session') && session()->isStarted()) {
                            session([$sessionKey => $data]);
                        }
                    } catch (\Throwable $se) {
                        // Ignore session error in CLI / unstarted contexts
                    }
                }

                return $data;
            }

            Log::warning('CMS API request failed for ' . $url . ': status ' . $response->status() . ' body: ' . substr($response->body(), 0, 500));
        } catch (\Throwable $e) {
            Log::error('CMS API exception for ' . $endpoint . ': ' . $e->getMessage());
        }

        // 3. Fallback to session only for static layout endpoints if API is temporarily unreachable
        if (!$isProductOrCart) {
            try {
                if (function_exists('session') && session()->isStarted() && session()->has($sessionKey)) {
                    $fallbackData = session($sessionKey);
                    self::$requestCache[$endpoint] = $fallbackData;
                    return $fallbackData;
                }
            } catch (\Throwable $se) {
                // Ignore
            }
        }

        return null;
    }

    /**
     * Get Header & Navigation Data.
     */
    public static function getHeader(): ?array
    {
        $data = self::fetch('header');
        if (!$data) {
            return null;
        }

        // Normalize store logo
        if (!isset($data['store_logo_url']) && isset($data['logo_url'])) {
            $data['store_logo_url'] = $data['logo_url'];
        }
        if (!isset($data['logo_url']) && isset($data['store_logo_url'])) {
            $data['logo_url'] = $data['store_logo_url'];
        }

        // Normalize shop menu
        if (isset($data['shop_menu']) && is_array($data['shop_menu'])) {
            if (!isset($data['shop_menu']['title']) && isset($data['shop_menu']['label'])) {
                $data['shop_menu']['title'] = $data['shop_menu']['label'];
            }
            if (!isset($data['shop_menu']['label']) && isset($data['shop_menu']['title'])) {
                $data['shop_menu']['label'] = $data['shop_menu']['title'];
            }
            if (!isset($data['shop_menu']['featured_image_url']) && isset($data['shop_menu']['promo_image_url'])) {
                $data['shop_menu']['featured_image_url'] = $data['shop_menu']['promo_image_url'];
            }
            if (!isset($data['shop_menu']['promo_image_url']) && isset($data['shop_menu']['featured_image_url'])) {
                $data['shop_menu']['promo_image_url'] = $data['shop_menu']['featured_image_url'];
            }

            // Automatically create / resolve collection URLs if not created or empty
            if (isset($data['shop_menu']['collections']) && is_array($data['shop_menu']['collections'])) {
                foreach ($data['shop_menu']['collections'] as &$col) {
                    $slug = !empty($col['slug']) ? $col['slug'] : Str::slug($col['name'] ?? '');
                    $col['slug'] = $slug;
                    $col['url'] = self::resolveCollectionUrl($col['url'] ?? null, $slug);
                }
            }
        }

        return $data;
    }

    /**
     * Get Banners & Sliders Data.
     */
    public static function getBanners(): ?array
    {
        $data = self::fetch('banners');
        if (!$data) {
            return null;
        }

        // Normalize hero slides
        $slides = $data['hero_slides'] ?? $data['hero_sliders'] ?? [];
        foreach ($slides as &$slide) {
            if (!isset($slide['eyebrow_tag']) && isset($slide['tag'])) {
                $slide['eyebrow_tag'] = $slide['tag'];
            }
            if (!isset($slide['tag']) && isset($slide['eyebrow_tag'])) {
                $slide['tag'] = $slide['eyebrow_tag'];
            }
            if (isset($slide['buttons']) && is_array($slide['buttons'])) {
                if (isset($slide['buttons'][0])) {
                    $slide['btn_text_1'] = $slide['btn_text_1'] ?? $slide['buttons'][0]['text'] ?? null;
                    $slide['btn_url_1'] = $slide['btn_url_1'] ?? $slide['buttons'][0]['url'] ?? null;
                }
                if (isset($slide['buttons'][1])) {
                    $slide['btn_text_2'] = $slide['btn_text_2'] ?? $slide['buttons'][1]['text'] ?? null;
                    $slide['btn_url_2'] = $slide['btn_url_2'] ?? $slide['buttons'][1]['url'] ?? null;
                }
            }
        }
        unset($slide);
        $data['hero_slides'] = $slides;
        $data['hero_sliders'] = $slides;

        // Normalize promotional banners
        $promo = $data['promotional_banners'] ?? [];
        if (!empty($promo)) {
            // Festive Offer Banner
            $festive = $promo['festive_offer_banner'] ?? $data['festive_offer_banner'] ?? null;
            if ($festive) {
                $festive['background_image_url'] = $festive['background_image_url'] ?? $festive['image_url'] ?? null;
                $festive['image_url'] = $festive['image_url'] ?? $festive['background_image_url'] ?? null;
                $festive['btn_text_1'] = $festive['btn_text_1'] ?? ($festive['button']['text'] ?? 'View Product');
                $festive['btn_url_1'] = $festive['btn_url_1'] ?? ($festive['button']['url'] ?? null);
                $festive['is_active'] = $festive['is_active'] ?? true;
                $data['festive_offer_banner'] = $festive;
                $data['promotional_banners']['festive_offer_banner'] = $festive;
            }

            // Timeless Diamonds / Luxury Parallax Banner
            $timeless = $promo['luxury_parallax_banner'] ?? $promo['timeless_diamonds_banner'] ?? $data['timeless_diamonds_banner'] ?? null;
            if ($timeless) {
                $timeless['background_image_url'] = $timeless['background_image_url'] ?? $timeless['image_url'] ?? null;
                $timeless['image_url'] = $timeless['image_url'] ?? $timeless['background_image_url'] ?? null;
                $timeless['btn_text_1'] = $timeless['btn_text_1'] ?? ($timeless['button']['text'] ?? 'Discover Diamonds');
                $timeless['btn_url_1'] = $timeless['btn_url_1'] ?? ($timeless['button']['url'] ?? null);
                $timeless['is_active'] = $timeless['is_active'] ?? true;
                $data['timeless_diamonds_banner'] = $timeless;
                $data['luxury_parallax_banner'] = $timeless;
                $data['promotional_banners']['luxury_parallax_banner'] = $timeless;
                $data['promotional_banners']['timeless_diamonds_banner'] = $timeless;
            }

            // Bridal Spotlight Banner
            $bridal = $promo['bridal_spotlight_banner'] ?? $data['bridal_spotlight_banner'] ?? null;
            if ($bridal) {
                $bridal['background_image_url'] = $bridal['background_image_url'] ?? $bridal['image_url'] ?? null;
                $bridal['image_url'] = $bridal['image_url'] ?? $bridal['background_image_url'] ?? null;
                $bridal['btn_text_1'] = $bridal['btn_text_1'] ?? ($bridal['button']['text'] ?? 'Explore Bridal Collection');
                $bridal['btn_url_1'] = $bridal['btn_url_1'] ?? ($bridal['button']['url'] ?? null);
                $bridal['is_active'] = $bridal['is_active'] ?? true;
                $data['bridal_spotlight_banner'] = $bridal;
                $data['promotional_banners']['bridal_spotlight_banner'] = $bridal;
            }
        }

        return $data;
    }

    /**
     * Get Shop By Occasion Data.
     */
    public static function getOccasions(): ?array
    {
        $data = self::fetch('occasions');
        if (!$data) {
            return null;
        }

        // Normalize section <-> settings
        $sec = $data['section'] ?? $data['settings'] ?? [];
        if (!isset($sec['is_active']) && isset($sec['enabled'])) {
            $sec['is_active'] = (bool) $sec['enabled'];
        }
        $data['section'] = $sec;
        $data['settings'] = $sec;

        // Normalize occasions list items
        if (!empty($data['occasions']) && is_array($data['occasions'])) {
            foreach ($data['occasions'] as &$occ) {
                if (!isset($occ['link']) && isset($occ['link_url'])) {
                    $occ['link'] = $occ['link_url'];
                }
                if (!isset($occ['button_url']) && isset($occ['link_url'])) {
                    $occ['button_url'] = $occ['link_url'];
                }
                if (!isset($occ['button_text']) && isset($occ['link_text'])) {
                    $occ['button_text'] = $occ['link_text'];
                }
                if (!isset($occ['link_url']) && isset($occ['link'])) {
                    $occ['link_url'] = $occ['link'];
                }
                if (!isset($occ['link_text']) && isset($occ['button_text'])) {
                    $occ['link_text'] = $occ['button_text'];
                }
            }
            unset($occ);
        }

        return $data;
    }

    /**
     * Get New Arrivals Page Data.
     */
    public static function getNewArrivals(): ?array
    {
        $data = self::fetch('pages/new-arrivals');
        if (!$data) {
            return null;
        }

        if (isset($data['hero']) && is_array($data['hero'])) {
            if (!isset($data['hero']['overlapping_image_url']) && isset($data['hero']['secondary_image_url'])) {
                $data['hero']['overlapping_image_url'] = $data['hero']['secondary_image_url'];
            }
            if (!isset($data['hero']['secondary_image_url']) && isset($data['hero']['overlapping_image_url'])) {
                $data['hero']['secondary_image_url'] = $data['hero']['overlapping_image_url'];
            }

            // Normalize buttons
            if (isset($data['hero']['buttons']) && is_array($data['hero']['buttons'])) {
                if (isset($data['hero']['buttons'][0])) {
                    $data['hero']['buttons']['primary'] = $data['hero']['buttons'][0];
                }
                if (isset($data['hero']['buttons'][1])) {
                    $data['hero']['buttons']['secondary'] = $data['hero']['buttons'][1];
                }
            }
        }

        return $data;
    }

    /**
     * Get The Bridal Edit Page Data.
     */
    public static function getBridal(): ?array
    {
        $data = self::fetch('pages/bridal');
        if (!$data) {
            return null;
        }

        // Normalize trousseau_section <-> curated_section
        $trousseau = $data['trousseau_section'] ?? $data['curated_section'] ?? [];
        if (!empty($trousseau)) {
            $categories = $trousseau['categories'] ?? $trousseau['cards'] ?? [];
            $trousseau['categories'] = $categories;
            $trousseau['cards'] = $categories;
            $data['trousseau_section'] = $trousseau;
            $data['curated_section'] = $trousseau;
        }

        return $data;
    }

    /**
     * Get About Us Page Data.
     */
    public static function getAbout(): ?array
    {
        return self::fetch('pages/about');
    }

    /**
     * Get Contact Us Page Data.
     */
    public static function getContact(): ?array
    {
        return self::fetch('pages/contact');
    }

    /**
     * Get Footer & Social Links Data.
     */
    public static function getFooter(): ?array
    {
        $data = self::fetch('footer');
        if (!$data) {
            return null;
        }

        if (isset($data['store_name']) && in_array(strtolower(trim((string)$data['store_name'])), ['test', 'test store', 'default', 'default store'])) {
            $data['store_name'] = $data['contact_person'] ?? $data['jeweller_name'] ?? $data['owner_name'] ?? 'Kalbhorer Omkar';
        }

        return $data;
    }

    /**
     * Get Storefront Theme & Colors Data.
     */
    public static function getTheme(): ?array
    {
        return self::fetch('theme');
    }

    /**
     * Get Customer Care & Policies Page Data.
     */
    public static function getCustomerCare(): ?array
    {
        return self::fetch('pages/customer-care');
    }

    /**
     * Helper to resolve or automatically create a proper URL for a collection.
     */
    public static function resolveCollectionUrl(?string $existingUrl, string $slug): string
    {
        $cleanSlug = strtolower(trim($slug));
        if (empty($existingUrl) || in_array(trim($existingUrl), ['#', 'shop.html', '/shop', 'shop', ''])) {
            return route('collection', $cleanSlug);
        }

        // If it's a legacy shop.html?collection=... or shop?collection=... link, convert it to clean collection route
        if (str_contains($existingUrl, 'collection=')) {
            $parsed = parse_url($existingUrl);
            if (!empty($parsed['query'])) {
                parse_str($parsed['query'], $queryParams);
                if (!empty($queryParams['collection'])) {
                    return route('collection', strtolower(trim($queryParams['collection'])));
                }
            }
        }

        return $existingUrl;
    }

    /**
     * Get Collections Page Data.
     */
    public static function getCollections(): ?array
    {
        $data = self::fetch('pages/collections');
        if (!$data) {
            return null;
        }

        if (isset($data['collections']) && is_array($data['collections'])) {
            foreach ($data['collections'] as &$col) {
                $slug = !empty($col['slug']) ? $col['slug'] : Str::slug($col['name'] ?? '');
                $col['slug'] = $slug;
                $col['link'] = self::resolveCollectionUrl($col['link'] ?? ($col['url'] ?? null), $slug);
                $col['url'] = $col['link'];
            }
        }

        if (isset($data['home_carousel']['items']) && is_array($data['home_carousel']['items'])) {
            foreach ($data['home_carousel']['items'] as &$item) {
                $slug = !empty($item['slug']) ? $item['slug'] : Str::slug($item['name'] ?? '');
                $item['slug'] = $slug;
                $item['link'] = self::resolveCollectionUrl($item['link'] ?? ($item['url'] ?? null), $slug);
                $item['url'] = $item['link'];
            }
        }

        return $data;
    }

    /**
     * Submit Contact Inquiry directly to Jewellerysoft ERP.
     */
    public static function submitContactEnquiry(array $data): array
    {
        try {
            $apiUrl = rtrim(config('cms.api_url', 'http://Jewellerysoft.test/api/v1/cms'), '/');
            $storeId = config('cms.store_id', 2);
            $url = $apiUrl . '/' . $storeId . '/contact-enquiry';

            $response = Http::timeout(6)->asJson()->post($url, $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => $response->json('message') ?? 'Thank you! Your inquiry has been submitted.',
                ];
            }

            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Failed to submit inquiry. Please try again.',
                'errors' => $response->json('errors') ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('CMS API submitContactEnquiry exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Unable to connect to the store server. Please try again later.',
            ];
        }
    }

    /**
     * Customer Register directly in Jewellerysoft ERP.
     */
    public static function customerRegister(array $data): array
    {
        try {
            $apiUrl = rtrim(config('cms.api_url', 'http://Jewellerysoft.test/api/v1/cms'), '/');
            $storeId = config('cms.store_id', 2);
            $url = $apiUrl . '/' . $storeId . '/customer/register';

            $response = Http::timeout(8)->asJson()->post($url, $data);
            $json = $response->json();

            if ($response->successful() && ($json['success'] ?? false)) {
                return [
                    'success' => true,
                    'message' => $json['message'] ?? 'Registration successful!',
                    'token' => $json['token'] ?? null,
                    'customer' => $json['customer'] ?? null,
                ];
            }

            return [
                'success' => false,
                'message' => $json['message'] ?? 'Registration failed. Please check the entered details.',
                'errors' => $json['errors'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('CMS API customerRegister error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Unable to connect to registration server: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Customer Login against Jewellerysoft ERP.
     */
    public static function customerLogin(array $credentials): array
    {
        try {
            $apiUrl = rtrim(config('cms.api_url', 'http://Jewellerysoft.test/api/v1/cms'), '/');
            $storeId = config('cms.store_id', 2);
            $url = $apiUrl . '/' . $storeId . '/customer/login';

            $response = Http::timeout(8)->asJson()->post($url, $credentials);
            $json = $response->json();

            if ($response->successful() && ($json['success'] ?? false)) {
                return [
                    'success' => true,
                    'message' => $json['message'] ?? 'Login successful!',
                    'token' => $json['token'] ?? null,
                    'customer' => $json['customer'] ?? null,
                ];
            }

            return [
                'success' => false,
                'message' => $json['message'] ?? 'Invalid credentials.',
                'errors' => $json['errors'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('CMS API customerLogin error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Unable to connect to authentication server.',
            ];
        }
    }

    /**
     * Get Customer Profile from Jewellerysoft ERP.
     */
    public static function customerProfile(string $token): array
    {
        try {
            $apiUrl = rtrim(config('cms.api_url', 'http://Jewellerysoft.test/api/v1/cms'), '/');
            $storeId = config('cms.store_id', 2);
            $url = $apiUrl . '/' . $storeId . '/customer/profile';

            $response = Http::timeout(6)->withToken($token)->get($url);
            $json = $response->json();

            if ($response->successful() && ($json['success'] ?? false)) {
                return [
                    'success' => true,
                    'customer' => $json['customer'] ?? null,
                ];
            }

            return [
                'success' => false,
                'message' => $json['message'] ?? 'Unable to retrieve customer profile.',
            ];
        } catch (\Throwable $e) {
            Log::error('CMS API customerProfile error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Customer Logout in Jewellerysoft ERP.
     */
    public static function customerLogout(string $token): array
    {
        try {
            $apiUrl = rtrim(config('cms.api_url', 'http://Jewellerysoft.test/api/v1/cms'), '/');
            $storeId = config('cms.store_id', 2);
            $url = $apiUrl . '/' . $storeId . '/customer/logout';

            $response = Http::timeout(6)->withToken($token)->post($url);
            return $response->json() ?? ['success' => true];
        } catch (\Throwable $e) {
            return ['success' => false];
        }
    }

    /**
     * Get Products Catalog from Jewellerysoft ERP.
     */
    public static function getProducts(array $params = []): array
    {
        $params['_t'] = round(microtime(true) * 1000);
        $queryStr = '?' . http_build_query($params);
        $res = self::fetch('products' . $queryStr);
        $products = [];
        if (is_array($res)) {
            if (isset($res[0])) {
                $products = $res;
            } elseif (isset($res['data']) && is_array($res['data'])) {
                $products = $res['data'];
            }
        }

        // Strict filter: Never return sold-out items to storefront
        return array_values(array_filter($products, function($p) {
            if (!empty($p['is_sold_out'])) return false;
            if (isset($p['product_sold_out_status']) && ($p['product_sold_out_status'] === true || $p['product_sold_out_status'] === 1 || $p['product_sold_out_status'] === '1')) return false;
            if (isset($p['availability']) && in_array(strtolower((string)$p['availability']), ['sold_out', 'out_of_stock'])) return false;
            return true;
        }));
    }

    /**
     * Get Single Product Details from Jewellerysoft ERP.
     */
    public static function getProduct(string|int $idOrSlug): ?array
    {
        $res = self::fetch('products/' . $idOrSlug . '?_t=' . round(microtime(true) * 1000));
        if (is_array($res)) {
            if (!empty($res['is_sold_out'])) {
                return null;
            }
            if (isset($res['data']) && is_array($res['data'])) {
                if (!empty($res['data']['is_sold_out']) || in_array(strtolower((string)($res['data']['availability'] ?? '')), ['sold_out', 'out_of_stock'])) {
                    return null;
                }
                return $res['data'];
            }
            if (isset($res['id'])) {
                if (in_array(strtolower((string)($res['availability'] ?? '')), ['sold_out', 'out_of_stock'])) {
                    return null;
                }
                return $res;
            }
        }
        return null;
    }

    /**
     * Get Customer Shopping Cart from ERP.
     */
    public static function getCart(array $params = [], ?string $token = null): array
    {
        try {
            $apiUrl = rtrim(config('cms.api_url', 'http://Jewellerysoft.test/api/v1/cms'), '/');
            $storeId = config('cms.store_id', 2);
            $url = $apiUrl . '/' . $storeId . '/cart' . (!empty($params) ? '?' . http_build_query($params) : '');

            $http = Http::timeout(6);
            if ($token) $http = $http->withToken($token);

            $response = $http->get($url);
            return $response->json() ?? ['success' => false];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Add Product to Cart in ERP.
     */
    public static function addToCart(array $data, ?string $token = null): array
    {
        try {
            $apiUrl = rtrim(config('cms.api_url', 'http://Jewellerysoft.test/api/v1/cms'), '/');
            $storeId = config('cms.store_id', 2);
            $url = $apiUrl . '/' . $storeId . '/cart/add';

            $http = Http::timeout(6);
            if ($token) $http = $http->withToken($token);

            $response = $http->post($url, $data);
            return $response->json() ?? ['success' => false];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Update Cart Item Quantity in ERP.
     */
    public static function updateCartItem(string|int $itemId, array $data, ?string $token = null): array
    {
        try {
            $apiUrl = rtrim(config('cms.api_url', 'http://Jewellerysoft.test/api/v1/cms'), '/');
            $storeId = config('cms.store_id', 2);
            $url = $apiUrl . '/' . $storeId . '/cart/' . $itemId;

            $http = Http::timeout(6);
            if ($token) $http = $http->withToken($token);

            $response = $http->put($url, $data);
            return $response->json() ?? ['success' => false];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Remove Item from Cart in ERP.
     */
    public static function removeCartItem(string|int $itemId, array $params = [], ?string $token = null): array
    {
        try {
            $apiUrl = rtrim(config('cms.api_url', 'http://Jewellerysoft.test/api/v1/cms'), '/');
            $storeId = config('cms.store_id', 2);
            $url = $apiUrl . '/' . $storeId . '/cart/' . $itemId . (!empty($params) ? '?' . http_build_query($params) : '');

            $http = Http::timeout(6);
            if ($token) $http = $http->withToken($token);

            $response = $http->delete($url);
            return $response->json() ?? ['success' => false];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Clear Cart in ERP.
     */
    public static function clearCart(array $params = [], ?string $token = null): array
    {
        try {
            $apiUrl = rtrim(config('cms.api_url', 'http://Jewellerysoft.test/api/v1/cms'), '/');
            $storeId = config('cms.store_id', 2);
            $url = $apiUrl . '/' . $storeId . '/cart/clear';

            $http = Http::timeout(6);
            if ($token) $http = $http->withToken($token);

            $response = $http->post($url, $params);
            return $response->json() ?? ['success' => false];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Bulk Sync Cart to ERP.
     */
    public static function syncCart(array $items, array $params = [], ?string $token = null): array
    {
        try {
            $apiUrl = rtrim(config('cms.api_url', 'http://Jewellerysoft.test/api/v1/cms'), '/');
            $storeId = config('cms.store_id', 2);
            $url = $apiUrl . '/' . $storeId . '/cart/sync';

            $http = Http::timeout(6);
            if ($token) $http = $http->withToken($token);

            $response = $http->post($url, array_merge($params, ['items' => $items]));
            return $response->json() ?? ['success' => false];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}