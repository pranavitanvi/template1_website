<?php

namespace App\Http\Controllers;

use App\Services\CmsApiService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    /**
     * Category metadata dictionary.
     */
    protected array $categories = [
        'rings' => [
            'title' => 'Rings',
            'tagline' => 'Discover refined rings crafted for timeless everyday elegance.',
            'category_key' => 'rings',
        ],
        'earrings' => [
            'title' => 'Earrings',
            'tagline' => 'From subtle studs to statement drops, handcrafted in precious gold and diamonds.',
            'category_key' => 'earrings',
        ],
        'necklaces' => [
            'title' => 'Necklaces',
            'tagline' => 'Exquisite chains, chokers, and diamond pendants made to cherish forever.',
            'category_key' => 'necklaces',
        ],
        'bracelets' => [
            'title' => 'Bracelets',
            'tagline' => 'Graceful tennis bracelets and delicate chains designed for understated luxury.',
            'category_key' => 'bracelets',
        ],
        'bangles' => [
            'title' => 'Bangles',
            'tagline' => 'Traditional artistry reimagined into modern everyday luxury bangles.',
            'category_key' => 'bangles',
        ],
        'chains' => [
            'title' => 'Chains',
            'tagline' => 'Impeccably finished gold and platinum chains suited for every occasion.',
            'category_key' => 'chains',
        ],
        'mangalsutras' => [
            'title' => 'Mangalsutras',
            'tagline' => 'Sacred symbolism crafted with contemporary elegance and diamond accents.',
            'category_key' => 'mangalsutra',
        ],
        'pendants' => [
            'title' => 'Pendants',
            'tagline' => 'Luminous pendants capturing bespoke stories and everlasting beauty.',
            'category_key' => 'pendants',
        ],
        'mens' => [
            'title' => "Men's Jewellery",
            'tagline' => "Sophisticated rings, cuffs, and chains curated for the modern gentleman.",
            'category_key' => 'mens',
        ],
        'womens' => [
            'title' => "Women's Jewellery",
            'tagline' => "Explore our comprehensive catalog of fine gold, diamond, and platinum creations.",
            'category_key' => 'all',
        ],
    ];

    /**
     * Shop index page.
     */
    public function index(Request $request): View
    {
        $collection = $request->query('collection');
        $search = $request->query('q');
        $category = $request->query('category', 'all');

        return view('pages.shop', compact('collection', 'search', 'category'));
    }

    /**
     * Category page.
     */
    public function category(string $category): View
    {
        $slug = strtolower($category);
        $categoryInfo = $this->categories[$slug] ?? [
            'title' => ucfirst($category),
            'tagline' => 'Explore our bespoke fine jewellery pieces.',
            'category_key' => $slug,
        ];

        return view('pages.category', [
            'categorySlug' => $slug,
            'categoryInfo' => $categoryInfo,
        ]);
    }

    /**
     * Bridal Collection page.
     */
    public function bridal(): View
    {
        $bridalPage = CmsApiService::getBridal();

        return view('pages.bridal', compact('bridalPage'));
    }

    /**
     * New Arrivals page.
     */
    public function newArrivals(): View
    {
        $newArrivalsPage = CmsApiService::getNewArrivals();

        return view('pages.new-arrivals', compact('newArrivalsPage'));
    }

    /**
     * Product details page.
     */
    public function productDetails(Request $request, ?int $id = null): View
    {
        $productId = $id ?? $request->query('id');

        return view('pages.product-details', compact('productId'));
    }
}
