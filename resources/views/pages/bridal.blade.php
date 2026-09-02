@extends('layouts.app')

@php
    $hero = $bridalPage['hero'] ?? [];
    $trousseau = $bridalPage['trousseau_section'] ?? ($bridalPage['curated_section'] ?? []);
    $curatedCards = $trousseau['categories'] ?? ($trousseau['cards'] ?? []);
    $engagement = $bridalPage['engagement_spotlight'] ?? [];
    $heirloom = $bridalPage['heirloom_section'] ?? [];
    $seo = $bridalPage['seo'] ?? [];
@endphp

@section('title', !empty($seo['meta_title']) ? $seo['meta_title'] : 'The Bridal Edit | Aura Fine Jewellery')
@section('meta_description', !empty($seo['meta_description']) ? $seo['meta_description'] : 'Discover heirloom-inspired bridal jewellery created for your forever moments.')

@section('content')
<!-- BRIDAL HERO -->
<section class="hero-slider" style="height: 90vh; background-color: #170d06;">
    <div class="hero-slide active" style="background-image: linear-gradient(to bottom, #170d06 0%, transparent 15%), url('{{ !empty($hero['background_image_url']) ? $hero['background_image_url'] : asset('assets/images/products/bradal_hrobaneer.png') }}'); background-position: center top, center 49px; background-size: cover, cover; background-repeat: no-repeat;">
        <div class="hero-slide-overlay" style="background: linear-gradient(to right, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.1) 100%);"></div>
        <div class="container hero-slide-content">
            <div style="max-width: 600px; color: var(--white);">
                <h1 class="slide-title" style="font-size: 4rem; line-height: 1.1; margin-bottom: 1rem; color: #fdfaf4; font-family: var(--font-secondary);">
                    {{ $hero['title'] ?? 'THE BRIDAL EDIT' }}
                </h1>
                <p style="font-size: 1.3rem; margin-bottom: 2rem; color: #f0e9dc; font-family: var(--font-primary); font-weight: 300;">
                    {{ $hero['subtitle'] ?? 'Heirloom-inspired jewellery for the beginning of forever.' }}
                </p>
                <div class="slide-buttons">
                    <a href="{{ !empty($hero['button']['url']) ? $hero['button']['url'] : '#bridal-collections' }}" class="btn btn-primary" style="background-color: #c0a062; border-color: #c0a062; color: white;">
                        {{ $hero['button']['text'] ?? 'SHOP THE COLLECTION' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CURATED FOR THE BRIDE (CATEGORIES) -->
<section id="bridal-collections" class="section container" style="padding-top: 5rem; padding-bottom: 3rem;">
    <div class="section-header" style="text-align: center; margin-bottom: 3rem;">
        <h2 class="section-title" style="font-size: 2.2rem; text-transform: uppercase;">
            {{ $trousseau['title'] ?? 'CURATED FOR THE BRIDE' }}
        </h2>
        <p style="color: var(--text-secondary); margin-top: 0.5rem; font-size: 1.1rem;">
            {{ $trousseau['subtitle'] ?? 'Discover pieces designed to complete your bridal trousseau.' }}
        </p>
    </div>
    
    <div class="grid responsive-product-grid">
        @if(!empty($curatedCards) && count($curatedCards) > 0)
            @foreach($curatedCards as $card)
                <a href="{{ $card['link'] ?: route('shop', ['collection' => 'bridal']) }}" class="clean-cat-card" style="background-color: #fdfcfb;">
                    <div class="clean-cat-img" style="aspect-ratio: 3/4;">
                        <img src="{{ $card['image_url'] }}" alt="{{ $card['title'] }}" style="object-position: center top;">
                    </div>
                    <div class="clean-cat-title" style="padding: 1rem;">{{ $card['title'] }}</div>
                </a>
            @endforeach
        @else
            <!-- Fallback Default 4 Cards -->
            <a href="{{ route('category', 'necklaces') }}?collection=bridal" class="clean-cat-card" style="background-color: #fdfcfb;">
                <div class="clean-cat-img" style="aspect-ratio: 3/4;"><img src="{{ asset('assets/images/products/braidal_neckless.jpg') }}" alt="Bridal Necklaces" style="object-position: center top;"></div>
                <div class="clean-cat-title" style="padding: 1rem;">BRIDAL NECKLACES</div>
            </a>
            <a href="{{ route('category', 'earrings') }}?collection=bridal" class="clean-cat-card" style="background-color: #fdfcfb;">
                <div class="clean-cat-img" style="aspect-ratio: 3/4;"><img src="{{ asset('assets/images/products/jumka.jpg') }}" alt="Earrings"></div>
                <div class="clean-cat-title" style="padding: 1rem;">EARRINGS & JHUMKAS</div>
            </a>
            <a href="{{ route('category', 'bangles') }}?collection=bridal" class="clean-cat-card" style="background-color: #fdfcfb;">
                <div class="clean-cat-img" style="aspect-ratio: 3/4;"><img src="{{ asset('assets/images/products/bangle_1.jpg') }}" alt="Bangles & Kadas"></div>
                <div class="clean-cat-title" style="padding: 1rem;">BANGLES & KADAS</div>
            </a>
            <a href="{{ route('category', 'mangalsutras') }}?collection=bridal" class="clean-cat-card" style="background-color: #fdfcfb;">
                <div class="clean-cat-img" style="aspect-ratio: 3/4;"><img src="{{ asset('assets/images/products/mangalsutra_1.jpg') }}" alt="Mangalsutras"></div>
                <div class="clean-cat-title" style="padding: 1rem;">MANGALSUTRAS</div>
            </a>
        @endif
    </div>
</section>

<!-- THE ENGAGEMENT EDIT -->
<section class="section container" style="margin-top: 4rem; background-color: #fbf9f6; padding: 4rem 2rem; border-radius: 12px;">
    <div class="grid responsive-product-grid" style="align-items: center;">
        <div style="border-radius: 12px; overflow: hidden; height: 500px; min-width: 300px;">
            <img src="{{ !empty($engagement['image_url']) ? $engagement['image_url'] : asset('assets/images/hero/Engagement.png') }}" alt="{{ $engagement['title'] ?? 'Engagement Rings' }}" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div>
            <h2 style="font-family: var(--font-secondary); font-size: 2.8rem; margin-bottom: 1rem; color: var(--text-primary);">
                {{ $engagement['title'] ?? 'THE ENGAGEMENT EDIT' }}
            </h2>
            <p style="color: var(--text-secondary); font-size: 1.1rem; line-height: 1.6; margin-bottom: 2rem;">
                {{ $engagement['description'] ?? 'A promise of forever begins with the perfect ring. Explore our curated selection of solitaires and couple bands, crafted with the finest diamonds to catch the light from every angle.' }}
            </p>
            
            <div class="grid responsive-product-grid" id="engagement-mini-grid" style="margin-bottom: 2rem;"></div>
            
            <a href="{{ !empty($engagement['button']['url']) ? $engagement['button']['url'] : route('category', 'rings') . '?collection=engagement' }}" class="btn btn-outline" style="border-color: #c0a062; color: #c0a062;">
                {{ $engagement['button']['text'] ?? 'SHOP ENGAGEMENT RINGS' }}
            </a>
        </div>
    </div>
</section>

<!-- HEIRLOOM COLLECTION -->
<section class="section container" style="margin-top: 5rem; margin-bottom: 5rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
        <div>
            <h2 class="section-title" style="margin-bottom: 0.5rem; font-size: 2rem;">
                {{ $heirloom['title'] ?? 'HEIRLOOM COLLECTION' }}
            </h2>
            <p style="color: var(--text-secondary);">
                {{ $heirloom['subtitle'] ?? 'Our finest masterpieces for the bride.' }}
            </p>
        </div>
        <a href="{{ !empty($heirloom['link']['url']) ? $heirloom['link']['url'] : route('shop', ['collection' => 'bridal']) }}" style="color: #c0a062; font-weight: 600; text-decoration: none; font-size: 0.9rem; letter-spacing: 1px;">
            {{ $heirloom['link']['text'] ?? 'VIEW ALL BRIDAL →' }}
        </a>
    </div>
    
    <div class="grid responsive-product-grid" id="bridal-heirloom-grid"></div>
</section>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/products_v4.js') }}"></script>
@endpush
