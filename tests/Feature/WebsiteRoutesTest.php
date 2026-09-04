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
            '/privacy-policy',
            '/terms-and-conditions',
            '/login',
            '/register',
            '/collection/royal-heritage',
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
        $this->get('/privacy.html')->assertRedirect('/privacy-policy');
        $this->get('/terms.html')->assertRedirect('/terms-and-conditions');
    }

    /**
     * Test that guests cannot add products to cart without logging in
     */
    public function test_guest_cannot_add_product_to_cart(): void
    {
        $response = $this->postJson('/api/cart/add', [
            'product_id' => 1,
            'quantity' => 1
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'requires_login' => true,
            ]);
    }

    /**
     * Test that guests cannot sync cart without logging in
     */
    public function test_guest_cannot_sync_cart(): void
    {
        $response = $this->postJson('/api/cart/sync', [
            'items' => []
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'requires_login' => true,
            ]);
    }

    /**
     * Test captcha generation endpoint returns SVG image
     */
    public function test_captcha_generation_returns_svg(): void
    {
        $response = $this->get('/captcha/generate');

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'image/svg+xml');
        $this->assertStringContainsString('<svg', $response->getContent());
    }

    /**
     * Test that login fails when captcha is missing or incorrect
     */
    public function test_login_rejects_invalid_captcha(): void
    {
        $response = $this->postJson('/login', [
            'login' => 'user@example.com',
            'password' => 'secret123',
            'captcha' => 'WRONG',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid security captcha. Please enter the characters shown.',
            ]);
    }

    /**
     * Test that register fails when captcha is missing or incorrect
     */
    public function test_register_rejects_invalid_captcha(): void
    {
        $response = $this->postJson('/register', [
            'name' => 'Priya Sharma',
            'phone' => '9876543210',
            'email' => 'priya@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'captcha' => 'INVALID',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid security captcha. Please enter the characters shown.',
            ]);
    }

    /**
     * Test that contact fails when captcha is missing or incorrect
     */
    public function test_contact_rejects_invalid_captcha(): void
    {
        $response = $this->postJson('/contact', [
            'name' => 'Priya Sharma',
            'email' => 'priya@example.com',
            'message' => 'Inquiry about bridal sets',
            'captcha' => 'BADCODE',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid security captcha. Please enter the characters shown.',
            ]);
    }

    /**
     * Test that submitting correct captcha passes captcha verification
     */
    public function test_valid_captcha_passes_verification(): void
    {
        $response = $this->withSession(['custom_captcha' => 'GOLD7'])
            ->postJson('/login', [
                'login' => 'dummy@example.com',
                'password' => 'password',
                'captcha' => 'gold7', // Case-insensitive
            ]);

        // Captcha passed, so the error message must NOT be the captcha error
        $data = $response->json();
        $this->assertNotEquals('Invalid security captcha. Please enter the characters shown.', $data['message'] ?? '');
    }
}

