@extends('layouts.app')

@php
    $hero = $newArrivalsPage['hero'] ?? [];
    $sec = $newArrivalsPage['section'] ?? [];
    $seo = $newArrivalsPage['seo'] ?? [];
@endphp

@section('title', !empty($seo['meta_title']) ? $seo['meta_title'] : 'New Arrivals | Aura Fine Jewellery')
@section('meta_description', !empty($seo['meta_description']) ? $seo['meta_description'] : 'Meet the latest jewellery designs from Aura - crafted with precious metals and radiant diamonds.')
@section('main_style', 'margin-top: 110px;')

@section('content')
<div style="background-color: #fbf9f6; padding-bottom: 5rem;">
    <!-- EDITORIAL OVERLAP HERO -->
    <section style="background-image: url('{{ !empty($hero['background_image_url']) ? $hero['background_image_url'] : asset('assets/images/hero/hero_banner.png') }}'); background-size: cover; background-position: center right; margin-bottom: 5rem; min-height: 700px; position: relative; display: flex; align-items: center; overflow: hidden;">
        
        <!-- White Text Box -->
        <div class="mobile-full-width" style="background-color: white; padding: 4rem 3rem 4rem 5%; width: 45%; min-width: 450px; max-width: 650px; z-index: 3; box-shadow: 0 10px 30px rgba(0,0,0,0.05); position: absolute; left: 0; top: 50%; transform: translateY(-50%);">
            <div style="color: #c0a062; font-size: 0.9rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 1.5rem; position: relative; display: inline-block;">
                {{ $hero['eyebrow'] ?? 'JUST IN' }}
                <span style="position: absolute; left: 0; bottom: -10px; width: 40px; height: 1px; background-color: #c0a062;"></span>
            </div>
            <h1 style="font-size: 3.5rem; font-family: var(--font-secondary); line-height: 1.1; margin-bottom: 1.5rem; color: #2C2C2C; margin-top: 1.5rem;">
                {!! nl2br(e($hero['title'] ?? "NEW\nARRIVALS")) !!}
            </h1>
            <p style="color: #595959; font-size: 1.05rem; line-height: 1.7; margin-bottom: 2.5rem; max-width: 400px;">
                {{ $hero['subtitle'] ?? 'Meet the latest expressions of AURA - thoughtfully designed, beautifully crafted, and made for your next unforgettable moment.' }}
            </p>
            @php
                $btn1 = $hero['buttons']['primary'] ?? ($hero['buttons'][0] ?? []);
                $btn2 = $hero['buttons']['secondary'] ?? ($hero['buttons'][1] ?? []);
                $overlapImg = $hero['overlapping_image_url'] ?? ($hero['secondary_image_url'] ?? null);
            @endphp
            <div style="display: flex; flex-direction: column; gap: 1rem; max-width: 350px;">
                <a href="{{ !empty($btn1['url']) ? $btn1['url'] : '#latest-drop' }}" class="btn" style="text-align: center; border-radius: 0; padding: 1.2rem; font-size: 0.9rem; letter-spacing: 1px; font-weight: 600; background-color: #1a1a1a; color: white; border: none; text-decoration: none;">
                    {{ $btn1['text'] ?? 'SHOP NEW ARRIVALS' }}
                </a>
                <a href="{{ !empty($btn2['url']) ? $btn2['url'] : route('collections') }}" class="btn" style="text-align: center; border-radius: 0; padding: 1.2rem; font-size: 0.9rem; letter-spacing: 1px; font-weight: 600; border: 1px solid #d1d1d1; color: #2c2c2c; background: transparent; text-decoration: none;">
                    {{ $btn2['text'] ?? 'EXPLORE COLLECTIONS' }}
                </a>
            </div>
        </div>

        <!-- Small Overlapping Image -->
        <div class="mobile-hidden" style="position: absolute; left: calc(max(450px, 45%) - 120px); top: 55%; transform: translateY(-50%); width: 320px; aspect-ratio: 1/1; z-index: 4; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
            <img src="{{ !empty($overlapImg) ? $overlapImg : asset('assets/images/hero/Engagement.png') }}" alt="New Arrivals Collection" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    </section>

    <!-- NEW THIS WEEK SECTION -->
    <section id="latest-drop" class="section container" style="padding-bottom: 5rem; text-align: center;">
        <div class="container text-center">
            <h2 class="section-title" style="font-size: 2.2rem; font-family: var(--font-secondary); margin-bottom: 0.5rem; text-transform: uppercase;">
                {{ $sec['title'] ?? 'NEW THIS WEEK' }}
            </h2>
            <p style="color: var(--text-light); margin-bottom: 2rem;">
                {{ $sec['subtitle'] ?? 'Fresh designs, timeless character.' }}
            </p>
            
            <!-- Category Tabs -->
            <div style="display: flex; justify-content: center; gap: 3rem; margin-bottom: 3rem; border-bottom: 1px solid #eaeaea; padding-bottom: 1rem;">
                <a href="{{ route('shop', ['category' => 'all']) }}" style="text-decoration: none; color: var(--accent-gold); font-weight: 600; font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase; border-bottom: 2px solid var(--accent-gold); padding-bottom: 1rem; margin-bottom: -1rem;">ALL</a>
                <a href="{{ route('category', 'womens') }}" style="text-decoration: none; color: var(--text-secondary); font-weight: 600; font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase;">WOMEN</a>
                <a href="{{ route('category', 'mens') }}" style="text-decoration: none; color: var(--text-secondary); font-weight: 600; font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase;">MEN</a>
                <a href="{{ route('shop', ['collection' => 'diamonds']) }}" style="text-decoration: none; color: var(--text-secondary); font-weight: 600; font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase;">DIAMONDS</a>
                <a href="{{ route('bridal') }}" style="text-decoration: none; color: var(--text-secondary); font-weight: 600; font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase;">BRIDAL</a>
            </div>

            <!-- Product Grid -->
            <div style="position: relative; margin-bottom: 3rem;">
                <div class="grid responsive-product-grid" id="new-arrivals-page-grid">
                    <!-- Loaded dynamically via JS -->
                </div>
            </div>

            <a href="{{ !empty($sec['bottom_button']['url']) ? $sec['bottom_button']['url'] : route('shop') }}" class="btn" style="border: 1px solid #ccc; background: white; color: #2c2c2c; padding: 1rem 3rem; font-weight: 600; letter-spacing: 1px; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px;">
                {{ $sec['bottom_button']['text'] ?? 'VIEW ALL NEW ARRIVALS' }} <i class="ph ph-arrow-right"></i>
            </a>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/products_v4.js') }}"></script>
@endpush
