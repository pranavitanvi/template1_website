@extends('layouts.app')

@section('title', 'Aura Fine Jewellery | Luxury E-Commerce')
@section('meta_description', 'Discover timeless luxury jewellery pieces crafted to celebrate every moment. Explore our elegant collections.')

@push('styles')
<style>
    @php
        $heroSlides = $banners['hero_slides'] ?? $banners['hero_sliders'] ?? [];
    @endphp
    @if(!empty($heroSlides) && count($heroSlides) > 0)
        @foreach($heroSlides as $idx => $slide)
            #slide-{{ $idx + 1 }} {
                background-image: url('{{ $slide['desktop_image_url'] }}');
                background-position: center;
                background-size: cover;
            }
        @endforeach

        @media (max-width: 1024px) {
            @foreach($heroSlides as $idx => $slide)
                #slide-{{ $idx + 1 }} {
                    background-image: url('{{ $slide['mobile_image_url'] ?: $slide['desktop_image_url'] }}');
                    background-position: center;
                }
            @endforeach
        }
    @else
        #slide-1 { background-image: url('{{ asset("assets/images/hero/hero_main.jpg") }}'); }
        #slide-2 { background-image: url('{{ asset("assets/images/hero/hersection_3.png") }}'); background-position: 70% 15%; }
        #slide-3 { background-image: url('{{ asset("assets/images/hero/herobanner_4.png") }}'); background-position: right 15%; }
        #slide-4 { background-image: url('{{ asset("assets/images/hero/herobanner_5.png") }}?v=2'); background-position: right 10%; }

        @media (max-width: 1024px) {
            #slide-1 { background-image: url('{{ asset("assets/images/hero/mobile_hero_main.jpg") }}'); }
            #slide-2 { background-image: url('{{ asset("assets/images/hero/mobile_hero_everyday.jpg") }}'); background-position: center; }
            #slide-3 { background-image: url('{{ asset("assets/images/hero/mobile_hero_diamonds.jpg") }}'); background-position: center; }
            #slide-4 { background-image: url('{{ asset("assets/images/hero/mobile_hero_bridal.jpg") }}'); background-position: center; }
        }
    @endif
</style>
@endpush

@section('content')
<!-- HERO SLIDER -->
<section class="hero-slider">
    @if(!empty($heroSlides) && count($heroSlides) > 0)
        @foreach($heroSlides as $idx => $slide)
            @php
                $btn1Text = $slide['btn_text_1'] ?? ($slide['buttons'][0]['text'] ?? null);
                $btn1Url = $slide['btn_url_1'] ?? ($slide['buttons'][0]['url'] ?? route('shop'));
                $btn2Text = $slide['btn_text_2'] ?? ($slide['buttons'][1]['text'] ?? null);
                $btn2Url = $slide['btn_url_2'] ?? ($slide['buttons'][1]['url'] ?? route('new-arrivals'));
                $tag = $slide['eyebrow_tag'] ?? ($slide['tag'] ?? null);
            @endphp
            <div id="slide-{{ $idx + 1 }}" class="hero-slide {{ $idx === 0 ? 'active' : '' }}">
                <div class="hero-slide-overlay"></div>
                <div class="container hero-slide-content">
                    <div style="max-width: 600px; color: var(--white);">
                        @if(!empty($tag))
                            <div style="font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; color: #c0a062; margin-bottom: 0.5rem; font-weight: 600;">
                                {{ $tag }}
                            </div>
                        @endif
                        <h1 class="slide-title">{{ $slide['title'] }}</h1>
                        <p class="slide-text">{{ $slide['subtitle'] }}</p>
                        <div class="slide-buttons">
                            @if(!empty($btn1Text))
                                <a href="{{ $btn1Url ?: route('shop') }}" class="btn btn-primary">{{ $btn1Text }}</a>
                            @endif
                            @if(!empty($btn2Text))
                                <a href="{{ $btn2Url ?: route('new-arrivals') }}" class="btn btn-outline-light">{{ $btn2Text }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <!-- Fallback Default Slides -->
        <div id="slide-1" class="hero-slide active">
            <div class="hero-slide-overlay"></div>
            <div class="container hero-slide-content">
                <div style="max-width: 600px; color: var(--white);">
                    <h1 class="slide-title">Jewellery That Tells Your Story</h1>
                    <p class="slide-text">Discover timeless pieces crafted to celebrate every moment with exceptional artistry and elegance.</p>
                    <div class="slide-buttons">
                        <a href="{{ route('shop') }}" class="btn btn-primary">Explore Collection</a>
                        <a href="{{ route('new-arrivals') }}" class="btn btn-outline-light">Shop New Arrivals</a>
                    </div>
                </div>
            </div>
        </div>
        <div id="slide-2" class="hero-slide">
            <div class="hero-slide-overlay"></div>
            <div class="container hero-slide-content">
                <div style="max-width: 600px; color: var(--white);">
                    <h1 class="slide-title">Everyday Elegance</h1>
                    <p class="slide-text">Subtle, sophisticated jewellery designed to be worn and loved daily.</p>
                    <div class="slide-buttons">
                        <a href="{{ route('shop', ['collection' => 'everyday']) }}" class="btn btn-primary">Shop The Look</a>
                        <a href="{{ route('shop') }}" class="btn btn-outline-light">View All</a>
                    </div>
                </div>
            </div>
        </div>
        <div id="slide-3" class="hero-slide">
            <div class="hero-slide-overlay"></div>
            <div class="container hero-slide-content">
                <div style="max-width: 600px; color: var(--white);">
                    <h1 class="slide-title">Diamond Classics</h1>
                    <p class="slide-text">Timeless diamond designs that capture the light and your heart.</p>
                    <div class="slide-buttons">
                        <a href="{{ route('shop', ['collection' => 'diamonds']) }}" class="btn btn-primary">Discover Diamonds</a>
                        <a href="{{ route('category', 'rings') }}" class="btn btn-outline-light">Shop Rings</a>
                    </div>
                </div>
            </div>
        </div>
        <div id="slide-4" class="hero-slide">
            <div class="hero-slide-overlay"></div>
            <div class="container hero-slide-content">
                <div style="max-width: 600px; color: var(--white);">
                    <h1 class="slide-title">The Bridal Edit</h1>
                    <p class="slide-text">Exquisite heirloom pieces designed for your most treasured moments.</p>
                    <div class="slide-buttons">
                        <a href="{{ route('bridal') }}" class="btn btn-primary">Shop Bridal</a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-light">Book Consultation</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>

<!-- STORY CATEGORY REEL -->
<div class="category-story-wrapper fade-in">
    <div class="category-story-scroll">
        <!-- Original Items -->
        <a href="{{ route('shop') }}" class="category-story-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/categories/RING.png') }}" alt="All"></div>
            <span class="category-story-title">All</span>
        </a>
        <a href="{{ route('category', 'rings') }}" class="category-story-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/categories/rings.jpg') }}" alt="Rings"></div>
            <span class="category-story-title">Rings</span>
        </a>
        <a href="{{ route('category', 'earrings') }}" class="category-story-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/categories/earrings.jpg') }}" alt="Earrings"></div>
            <span class="category-story-title">Earrings</span>
        </a>
        <a href="{{ route('category', 'pendants') }}" class="category-story-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/categories/pendant-01.png') }}" alt="Pendants"></div>
            <span class="category-story-title">Pendants</span>
        </a>
        <a href="{{ route('category', 'necklaces') }}" class="category-story-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/categories/necklaces.jpg') }}" alt="Necklaces"></div>
            <span class="category-story-title">Necklaces</span>
        </a>
        <a href="{{ route('category', 'bangles') }}" class="category-story-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/products/bangle_1.jpg') }}" alt="Bangles"></div>
            <span class="category-story-title">Bangles</span>
        </a>
        <a href="{{ route('category', 'bracelets') }}" class="category-story-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/categories/bracelets.jpg') }}" alt="Bracelets"></div>
            <span class="category-story-title">Bracelets</span>
        </a>
        <a href="{{ route('category', 'mangalsutras') }}" class="category-story-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/products/mangalsutra_1.jpg') }}" alt="Mangalsutra"></div>
            <span class="category-story-title">Mangalsutra</span>
        </a>
        <a href="{{ route('category', 'mens') }}" class="category-story-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/categories/men_product1.png') }}" alt="Men's"></div>
            <span class="category-story-title">Men's</span>
        </a>

        <!-- Cloned Items for Marquee -->
        <a href="{{ route('shop') }}" class="category-story-item clone-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/categories/RING.png') }}" alt="All"></div>
            <span class="category-story-title">All</span>
        </a>
        <a href="{{ route('category', 'rings') }}" class="category-story-item clone-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/categories/rings.jpg') }}" alt="Rings"></div>
            <span class="category-story-title">Rings</span>
        </a>
        <a href="{{ route('category', 'earrings') }}" class="category-story-item clone-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/categories/earrings.jpg') }}" alt="Earrings"></div>
            <span class="category-story-title">Earrings</span>
        </a>
        <a href="{{ route('category', 'pendants') }}" class="category-story-item clone-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/categories/pendant-01.png') }}" alt="Pendants"></div>
            <span class="category-story-title">Pendants</span>
        </a>
        <a href="{{ route('category', 'necklaces') }}" class="category-story-item clone-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/categories/necklaces.jpg') }}" alt="Necklaces"></div>
            <span class="category-story-title">Necklaces</span>
        </a>
        <a href="{{ route('category', 'bangles') }}" class="category-story-item clone-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/products/bangle_1.jpg') }}" alt="Bangles"></div>
            <span class="category-story-title">Bangles</span>
        </a>
        <a href="{{ route('category', 'bracelets') }}" class="category-story-item clone-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/categories/bracelets.jpg') }}" alt="Bracelets"></div>
            <span class="category-story-title">Bracelets</span>
        </a>
        <a href="{{ route('category', 'mangalsutras') }}" class="category-story-item clone-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/products/mangalsutra_1.jpg') }}" alt="Mangalsutra"></div>
            <span class="category-story-title">Mangalsutra</span>
        </a>
        <a href="{{ route('shop', ['category' => 'anklets']) }}" class="category-story-item clone-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/products/anklet_1.jpg') }}" alt="Anklets"></div>
            <span class="category-story-title">Anklets</span>
        </a>
        <a href="{{ route('category', 'mens') }}" class="category-story-item clone-item">
            <div class="category-story-img-wrap"><img src="{{ asset('assets/images/categories/men_product1.png') }}" alt="Men's"></div>
            <span class="category-story-title">Men's</span>
        </a>
    </div>
</div>

<!-- PROMOTIONAL BANNER -->
@php
    $festive = $banners['festive_offer_banner'] ?? ($banners['promotional_banners']['festive_offer_banner'] ?? null);
    $showFestive = !isset($banners) || !empty($festive);
@endphp
@if($showFestive)
<section class="section container">
    <div style="background-image: url('{{ !empty($festive['background_image_url']) ? $festive['background_image_url'] : (!empty($festive['image_url']) ? $festive['image_url'] : asset('assets/images/products/offer.png')) }}'); background-size: cover; background-position: center left; border-radius: 30px; overflow: hidden; position: relative; display: flex; justify-content: flex-end; align-items: center; padding: 2.5rem 5%; min-height: 320px; box-shadow: 0 15px 30px rgba(0,0,0,0.1);">
        <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(to right, rgba(183, 121, 83, 0.1) 0%, rgba(183, 121, 83, 0.95) 100%);"></div>
        <div style="position: relative; z-index: 1; max-width: 500px; text-align: left; color: var(--white); padding: 1rem 2rem;">
            <h2 class="scroll-reveal" style="font-size: 2.4rem; margin-bottom: 1rem; color: var(--white); font-family: var(--font-secondary); line-height: 1.2;">
                {!! nl2br(e($festive['title'] ?? "More Than a Rakhi,\nA Celebration in a Box.")) !!}
            </h2>
            <p class="scroll-reveal" style="font-size: 1.05rem; margin-bottom: 1.5rem; color: #fdfdfd;">
                {!! nl2br(e($festive['subtitle'] ?? "Honor the beautiful bond of love with a gift as timeless as your relationship.\nEnjoy 15% off on our exquisite Rakhi Edit.")) !!}
            </p>
            <a href="{{ !empty($festive['btn_url_1']) ? $festive['btn_url_1'] : (!empty($festive['button']['url']) ? $festive['button']['url'] : route('shop')) }}" class="btn btn-primary scroll-reveal" style="background-color: var(--white); color: var(--text-primary); border: none; font-weight: 600; padding: 0.9rem 2rem;">
                {{ $festive['btn_text_1'] ?? ($festive['button']['text'] ?? 'View Product') }}
            </a>
        </div>
    </div>
</section>
@endif

<!-- OUR COLLECTIONS ARC CAROUSEL -->
@php
    $homeCarouselConf = $collectionsPage['home_carousel'] ?? [];
    $showHomeCarousel = isset($homeCarouselConf['show_on_home']) ? (bool)$homeCarouselConf['show_on_home'] : true;
    $homeCollections = collect($homeCarouselConf['items'] ?? ($collectionsPage['collections'] ?? []));
    $themeClasses = ['theme-peach', 'theme-pink', 'theme-purple', 'theme-blue', 'theme-olive', 'theme-sage'];
@endphp

@if($showHomeCarousel && $homeCollections->count() > 0)
<section class="collections-arc-wrap">
    <div class="col-arc-header">
        <div class="col-arc-eyebrow">{{ $homeCarouselConf['eyebrow'] ?? 'Our Collections' }}</div>
        <h2 class="col-arc-title">{{ $homeCarouselConf['title'] ?? 'Timeless Elegance, Crafted for You' }}</h2>
        <p class="col-arc-subtitle">{{ $homeCarouselConf['subtitle'] ?? 'Discover diamonds that become part of your precious moments.' }}</p>
    </div>

    <div class="col-arc-stage-wrap">
        <button class="col-arc-arrow col-arc-prev" aria-label="Previous"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></button>

        <div class="col-arc-stage">
            @foreach($homeCollections as $idx => $card)
                @php
                    $theme = $themeClasses[$idx % count($themeClasses)];
                    $arcClass = ($idx === 0) ? 'arc-active' : (($idx === 1) ? 'arc-next' : (($idx === 2) ? 'arc-next2' : (($idx === $homeCollections->count() - 1) ? 'arc-prev' : (($idx === $homeCollections->count() - 2) ? 'arc-prev2' : 'arc-hidden'))));
                @endphp
                <div class="col-arc-card {{ $theme }} {{ $arcClass }}" data-index="{{ $idx }}">
                    <div class="col-arc-inner">
                        <div class="col-arc-img-wrap">
                            <img src="{{ $card['image_url'] }}" alt="{{ $card['name'] }}" loading="lazy">
                            <div class="col-arc-img-overlay"></div>
                        </div>
                        <div class="col-arc-card-content">
                            @if(!empty($card['occasion']))
                                <span class="col-arc-card-tag">{{ $card['occasion'] }}</span>
                            @endif
                            <h3 class="col-arc-card-name">{{ $card['name'] }}</h3>
                            <p class="col-arc-card-desc">{{ $card['description'] ?: ($card['occasion'] ? ($card['occasion'] . ' Collection') : '') }}</p>
                            @php
                                $cSlug = !empty($card['slug']) ? $card['slug'] : \Illuminate\Support\Str::slug($card['name'] ?? '');
                                $cUrl = (!empty($card['link']) && !in_array($card['link'], ['#', '', 'shop.html', '/shop', 'shop']))
                                    ? $card['link']
                                    : route('collection', $cSlug ?: 'all');
                            @endphp
                            <a href="{{ $cUrl }}" class="col-arc-card-btn">Explore Collection &rarr;</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button class="col-arc-arrow col-arc-next" aria-label="Next"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></button>
    </div>

    <div class="col-arc-dots">
        @foreach($homeCollections as $idx => $card)
            <button class="col-arc-dot {{ $idx === 0 ? 'active' : '' }}" data-goto="{{ $idx }}"></button>
        @endforeach
    </div>
</section>
@endif

<!-- GIFTS SECTION -->
<section class="section container">
    <div class="section-header" style="margin-bottom: 3rem;">
        <h2 class="section-title" style="font-size: 2.8rem; text-transform: uppercase;">Find Your Perfect Gift</h2>
        <div class="section-subtitle-fancy">
            <span style="font-size: 1.2rem; margin: 0 10px; color: #c0a062;">&#10022;</span>
        </div>
        <p style="color: var(--text-secondary); margin-top: 1rem;">Timeless diamonds for every story, every style, every moment.</p>
    </div>
    
    <div class="perfect-gift-section">
        <a href="{{ route('category', 'mens') }}" class="perfect-gift-card">
            <div class="perfect-gift-img-wrapper"><img src="{{ asset('assets/images/categories/GFT_for_him.png') }}" alt="Gifts for Him" class="perfect-gift-image"></div>
            <div class="perfect-gift-overlay">
                <h3 class="perfect-gift-title">FOR HIM</h3>
                <p class="perfect-gift-subtitle">BOLD. MODERN. TIMELESS.</p>
                <span class="btn-outline-gold">EXPLORE MEN'S JEWELLERY &rarr;</span>
            </div>
        </a>
        
        <a href="{{ route('category', 'womens') }}" class="perfect-gift-card">
            <div class="perfect-gift-img-wrapper"><img src="{{ asset('assets/images/categories/gift_for_her.png') }}" alt="Gifts for Her" class="perfect-gift-image"></div>
            <div class="perfect-gift-overlay">
                <h3 class="perfect-gift-title">FOR HER</h3>
                <p class="perfect-gift-subtitle">ELEGANT. TIMELESS. UNFORGETTABLE.</p>
                <span class="btn-outline-gold">EXPLORE WOMEN'S JEWELLERY &rarr;</span>
            </div>
        </a>
    </div>
</section>

<!-- SHOP BY OCCASION SECTION -->
@php
    $occSettings = $occasions['settings'] ?? ($occasions['section'] ?? null);
    $occCards = $occasions['occasions'] ?? null;
    $occActive = $occSettings['is_active'] ?? ($occSettings['enabled'] ?? true);
@endphp
@if($occActive)
<section class="section container fade-in">
    <div class="section-header" style="text-align: center; margin-bottom: 2rem;">
        <div class="section-subtitle-fancy" style="margin-bottom: 1rem; color: #c0a062; font-size: 0.9rem; letter-spacing: 2px; text-transform: uppercase;">
            <span style="margin: 0 10px;">&#10022;</span> {{ $occSettings['eyebrow'] ?? 'SHOP BY OCCASION' }} <span style="margin: 0 10px;">&#10022;</span>
        </div>
        <h2 class="section-title" style="font-size: 3rem; text-transform: uppercase;">
            {{ $occSettings['title'] ?? 'JEWELLERY FOR EVERY MOMENT' }}
        </h2>
        <p class="occasion-header-subtitle">
            {{ $occSettings['subtitle'] ?? "Find the perfect piece for life's most memorable moments." }}
        </p>
    </div>
    
    <div class="occasion-grid">
        @if(!empty($occCards) && count($occCards) > 0)
            @foreach($occCards as $card)
                <a href="{{ $card['link'] ?? ($card['link_url'] ?? ($card['button_url'] ?? route('shop'))) }}" class="occasion-card">
                    <div class="occasion-card-img-wrap">
                        <img src="{{ $card['image_url'] }}" alt="{{ $card['title'] }}">
                    </div>
                    <div class="occasion-card-content">
                        <i class="ph {{ $card['icon'] ?? ($card['icon_class'] ?? 'ph-sparkle') }} occasion-icon"></i>
                        <h3 class="occasion-title">{{ $card['title'] }}</h3>
                        <span class="occasion-link">{{ $card['button_text'] ?? ($card['link_text'] ?? 'SHOP NOW') }} &rarr;</span>
                    </div>
                </a>
            @endforeach
        @else
            <!-- Fallback Default Cards -->
            <a href="{{ route('collection', 'engagement') }}" class="occasion-card">
                <div class="occasion-card-img-wrap">
                    <img src="{{ asset('assets/images/hero/Engagement.png') }}" alt="Engagement Jewellery">
                </div>
                <div class="occasion-card-content">
                    <i class="ph ph-sketch-logo occasion-icon"></i>
                    <h3 class="occasion-title">ENGAGEMENT</h3>
                    <span class="occasion-link">SHOP NOW &rarr;</span>
                </div>
            </a>
            
            <a href="{{ route('bridal') }}" class="occasion-card">
                <div class="occasion-card-img-wrap">
                    <img src="{{ asset('assets/images/hero/weeding.png') }}" alt="Wedding Jewellery">
                </div>
                <div class="occasion-card-content">
                    <i class="ph ph-crown occasion-icon"></i>
                    <h3 class="occasion-title">WEDDING</h3>
                    <span class="occasion-link">SHOP NOW &rarr;</span>
                </div>
            </a>
            
            <a href="{{ route('collection', 'gifting') }}" class="occasion-card">
                <div class="occasion-card-img-wrap">
                    <img src="{{ asset('assets/images/hero/Gifting.png') }}" alt="Gifting">
                </div>
                <div class="occasion-card-content">
                    <i class="ph ph-gift occasion-icon"></i>
                    <h3 class="occasion-title">GIFTING</h3>
                    <span class="occasion-link">SHOP NOW &rarr;</span>
                </div>
            </a>
            
            <a href="{{ route('collection', 'everyday') }}" class="occasion-card">
                <div class="occasion-card-img-wrap">
                    <img src="{{ asset('assets/images/hero/everyday.png') }}" alt="Everyday Jewellery">
                </div>
                <div class="occasion-card-content">
                    <i class="ph ph-sparkle occasion-icon"></i>
                    <h3 class="occasion-title">EVERYDAY</h3>
                    <span class="occasion-link">SHOP NOW &rarr;</span>
                </div>
            </a>
            
            <a href="{{ route('collection', 'festive') }}" class="occasion-card">
                <div class="occasion-card-img-wrap">
                    <img src="{{ asset('assets/images/hero/festive.png') }}" alt="Festive Jewellery">
                </div>
                <div class="occasion-card-content">
                    <i class="ph ph-moon-stars occasion-icon"></i>
                    <h3 class="occasion-title">FESTIVE</h3>
                    <span class="occasion-link">SHOP NOW &rarr;</span>
                </div>
            </a>
        @endif
    </div>
</section>
@endif

<!-- FEATURED COLLECTION -->
<section class="section container" style="background-color: var(--bg-secondary);">
    <div class="section-header">
        <h2 class="section-title">Curated For You</h2>
        <p class="section-subtitle">Our most beloved pieces, selected just for you.</p>
    </div>
    
    <div class="grid responsive-product-grid" id="featured-products">
        <!-- Products loaded dynamically by JS -->
    </div>
    <div class="text-center" style="margin-top: var(--space-lg);">
        <a href="{{ route('shop') }}" class="btn btn-secondary">View All Jewellery</a>
    </div>
</section>

<!-- PROMOTIONAL HERO BANNER (Timeless Diamonds) -->
@php
    $timeless = $banners['timeless_diamonds_banner'] ?? ($banners['luxury_parallax_banner'] ?? ($banners['promotional_banners']['luxury_parallax_banner'] ?? ($banners['promotional_banners']['timeless_diamonds_banner'] ?? null)));
    $showTimeless = !isset($banners) || !empty($timeless);
@endphp
@if($showTimeless)
<section class="section" style="padding: 0;">
    <div style="background-image: url('{{ !empty($timeless['background_image_url']) ? $timeless['background_image_url'] : (!empty($timeless['image_url']) ? $timeless['image_url'] : asset('assets/images/hero/hero_banner.png')) }}'); background-size: cover; background-position: center; background-attachment: fixed; padding: var(--space-xxl) 5%; text-align: center; color: var(--white); position: relative;">
        <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.4);"></div>
        <div style="position: relative; z-index: 1; max-width: 800px; margin: 0 auto;">
            <h2 class="scroll-reveal" style="font-size: 3.5rem; margin-bottom: var(--space-md); color: var(--white); letter-spacing: 0.1em;">
                {{ $timeless['title'] ?? 'Timeless Diamonds' }}
            </h2>
            <p class="scroll-reveal" style="font-size: 1.2rem; margin-bottom: var(--space-lg); transition-delay: 0.2s;">
                {!! nl2br(e($timeless['subtitle'] ?? "Designed to shine today.\nMade to be treasured forever.")) !!}
            </p>
            <a href="{{ !empty($timeless['btn_url_1']) ? $timeless['btn_url_1'] : (!empty($timeless['button']['url']) ? $timeless['button']['url'] : route('shop', ['collection' => 'diamonds'])) }}" class="btn btn-primary scroll-reveal" style="background-color: var(--white); color: var(--text-primary); transition-delay: 0.4s;">
                {{ $timeless['btn_text_1'] ?? ($timeless['button']['text'] ?? 'Discover Diamonds') }}
            </a>
        </div>
    </div>
</section>
@endif

<!-- BRIDAL SPOTLIGHT -->
@php
    $bridalSpot = $banners['bridal_spotlight_banner'] ?? ($banners['promotional_banners']['bridal_spotlight_banner'] ?? null);
    $showBridal = !isset($banners) || !empty($bridalSpot);
@endphp
@if($showBridal)
<section class="section container">
    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: var(--space-xl);">
        <div style="flex: 1 1 300px;">
            <img src="{{ !empty($bridalSpot['background_image_url']) ? $bridalSpot['background_image_url'] : (!empty($bridalSpot['image_url']) ? $bridalSpot['image_url'] : asset('assets/images/products/bradal_hrobaneer.png')) }}" alt="{{ $bridalSpot['title'] ?? 'Indian Bride Jewellery' }}" style="border-radius: 4px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); width: 100%;">
        </div>
        <div style="flex: 1 1 300px;">
            <h2 class="section-title">{{ $bridalSpot['title'] ?? 'Made For Your Forever' }}</h2>
            <p style="font-size: 1.1rem; margin-bottom: var(--space-md);">
                {{ $bridalSpot['subtitle'] ?? "Discover exquisite bridal jewellery created for the moments you'll remember forever. Our modern Indian aesthetic blends traditional craftsmanship with contemporary elegance." }}
            </p>
            <a href="{{ !empty($bridalSpot['btn_url_1']) ? $bridalSpot['btn_url_1'] : (!empty($bridalSpot['button']['url']) ? $bridalSpot['button']['url'] : route('bridal')) }}" class="btn btn-gold">
                {{ $bridalSpot['btn_text_1'] ?? ($bridalSpot['button']['text'] ?? 'Explore Bridal Collection') }}
            </a>
        </div>
    </div>
</section>
@endif

<!-- WHY SHOP WITH US -->
<section class="section" style="background-color: var(--text-primary); color: var(--white); text-align: center;">
    <div class="container">
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-lg);">
            <div>
                <i class="ph ph-certificate" style="font-size: 2.5rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                <h4 style="color: var(--white); margin-bottom: 0.5rem;">Certified Jewellery</h4>
                <p style="color: #B0B0B0; font-size: 0.9rem;">100% certified diamonds and hallmarked gold.</p>
            </div>
            <div>
                <i class="ph ph-shield-check" style="font-size: 2.5rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                <h4 style="color: var(--white); margin-bottom: 0.5rem;">Secure Payments</h4>
                <p style="color: #B0B0B0; font-size: 0.9rem;">Multiple safe and encrypted payment options.</p>
            </div>
            <div>
                <i class="ph ph-truck" style="font-size: 2.5rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                <h4 style="color: var(--white); margin-bottom: 0.5rem;">Complimentary Shipping</h4>
                <p style="color: #B0B0B0; font-size: 0.9rem;">Free insured delivery on all orders across India.</p>
            </div>
            <div>
                <i class="ph ph-arrow-u-up-left" style="font-size: 2.5rem; color: var(--accent-gold); margin-bottom: 1rem;"></i>
                <h4 style="color: var(--white); margin-bottom: 0.5rem;">Easy Returns</h4>
                <p style="color: #B0B0B0; font-size: 0.9rem;">15-day no-questions-asked return policy.</p>
            </div>
        </div>
    </div>
</section>

<!-- NEWSLETTER SECTION -->
<section class="section container text-center" style="max-width: 600px;">
    <h2 class="section-title">Stay In The Know</h2>
    <p>Be the first to discover new collections, exclusive offers and jewellery stories.</p>
    <form style="display: flex; margin-top: var(--space-md); gap: 0.5rem;" onsubmit="event.preventDefault(); alert('Subscribed successfully!');">
        <input type="email" placeholder="Your email address" class="input-field" required>
        <button type="submit" class="btn btn-primary">Subscribe</button>
    </form>
</section>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/products_v4.js') }}?v=11"></script>
<script>
/* ---- Our Collections Arc Carousel ---- */
(function arcCarousel() {
    var stage = document.querySelector(".col-arc-stage");
    if (!stage) return;

    var cards      = Array.from(document.querySelectorAll(".col-arc-card"));
    var dots       = Array.from(document.querySelectorAll(".col-arc-dot"));
    var prevBtn    = document.querySelector(".col-arc-prev");
    var nextBtn    = document.querySelector(".col-arc-next");
    var total      = cards.length;
    var current    = 0;
    var timer      = null;
    var DELAY      = 3500;
    var busy       = false;

    function mod(n, m) { return ((n % m) + m) % m; }

    function updateCards() {
        var p2 = mod(current - 2, total);
        var p1 = mod(current - 1, total);
        var n1 = mod(current + 1, total);
        var n2 = mod(current + 2, total);

        cards.forEach(function(card, i) {
            card.classList.remove("arc-active", "arc-prev", "arc-next", "arc-prev2", "arc-next2", "arc-hidden", "pos-active", "pos-next", "pos-prev");
            if (i === current) {
                card.classList.add("arc-active", "pos-active");
            } else if (i === n1) {
                card.classList.add("arc-next", "pos-next");
            } else if (i === n2) {
                card.classList.add("arc-next2");
            } else if (i === p1) {
                card.classList.add("arc-prev", "pos-prev");
            } else if (i === p2) {
                card.classList.add("arc-prev2");
            } else {
                card.classList.add("arc-hidden");
            }
        });

        dots.forEach(function(dot, i) {
            dot.classList.toggle("active", i === current);
        });
    }

    function goto(idx) {
        if (busy) return;
        busy = true;
        current = mod(idx, total);
        updateCards();
        setTimeout(function() { busy = false; }, 420);
    }

    function next() { goto(current + 1); }
    function prev() { goto(current - 1); }

    function startAuto() {
        stopAuto();
        timer = setInterval(next, DELAY);
    }

    function stopAuto() {
        if (timer) clearInterval(timer);
    }

    if (nextBtn) nextBtn.addEventListener("click", function() { stopAuto(); next(); startAuto(); });
    if (prevBtn) prevBtn.addEventListener("click", function() { stopAuto(); prev(); startAuto(); });

    cards.forEach(function(card, idx) {
        card.addEventListener("click", function() {
            if (current !== idx) {
                stopAuto();
                goto(idx);
                startAuto();
            }
        });
    });

    dots.forEach(function(dot) {
        dot.addEventListener("click", function() {
            var idx = parseInt(this.getAttribute("data-goto"), 10);
            stopAuto();
            goto(idx);
            startAuto();
        });
    });

    stage.addEventListener("mouseenter", stopAuto);
    stage.addEventListener("mouseleave", startAuto);

    updateCards();
    startAuto();
})();
</script>
@endpush
