<?php

namespace Tests\Feature;

use Tests\TestCase;

class WebsiteRoutesTest extends TestCase
{
    /**
     * Test all primary routes return 200 OK
     */
    public function test_primary_routes_return_ok(): void
    {
        $routes = [
            '/',
            '/shop',
            '/bridal',
            '/new-arrivals',
            '/product-details',
            '/rings',
            '/earrings',
            '/necklaces',
            '/bracelets',
            '/category/rings',
            '/cart',
            '/checkout',
            '/order-success',
            '/wishlist',
            '/about',
            '/contact',
            '/collections',
            '/craftsmanship',
            '/customer-care',
            '/stores',
            '/faqs',
            '/size-guide',
            '/shipping',
            '/returns',
            '/jewellery-care',
            '/login',
            '/register',
        ];

        foreach ($routes as $uri) {
            $response = $this->get($uri);
            $response->assertStatus(200);
        }
    }

    /**
     * Test legacy .html redirects work properly
     */
    public function test_legacy_html_redirects(): void
    {
        $this->get('/index.html')->assertRedirect('/');
        $this->get('/shop.html')->assertRedirect('/shop');
        $this->get('/rings.html')->assertRedirect('/category/rings');
        $this->get('/cart.html')->assertRedirect('/cart');
    }
}
