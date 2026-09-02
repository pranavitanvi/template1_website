@extends('layouts.app')

@php
    $hero = $aboutPage['hero'] ?? [];
    $blocks = $aboutPage['story_blocks'] ?? [];
    $b1 = $blocks[0] ?? [];
    $b2 = $blocks[1] ?? [];
    $core = $aboutPage['core_values_section'] ?? [];
    $seo = $aboutPage['seo'] ?? [];
@endphp

@section('title', !empty($seo['meta_title']) ? $seo['meta_title'] : 'Our Story | Aura Fine Jewellery')
@section('meta_description', !empty($seo['meta_description']) ? $seo['meta_description'] : 'Learn about the legacy, design philosophy, and master craftsmanship behind Aura Fine Jewellery.')
@section('main_style', 'margin-top: 0;')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/editorial_pages.css') }}">
<style>
    .aura-about-page {
        background-color: #FAF8F4;
        color: #1a1814;
    }
    .abt-serif { font-family: 'Cinzel', serif; }
    .abt-sans { font-family: 'Montserrat', sans-serif; }
    .abt-script { font-family: 'Great Vibes', cursive; }
    .abt-gold { color: #9c7f4c; }

    .abt-hero {
        position: relative;
        height: 80vh;
        min-height: 550px;
        background-image: url('{{ !empty($hero['background_image_url']) ? $hero['background_image_url'] : asset("assets/images/hero/hero_banner.png") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        margin-top: 110px;
    }
    .abt-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.65) 100%);
        z-index: 1;
    }
    .abt-hero-content {
        position: relative;
        z-index: 2;
        color: #fff;
        padding: 0 20px;
    }
    .abt-hero-eyebrow {
        font-size: 0.85rem;
        letter-spacing: 0.25em;
        text-transform: uppercase;
        margin-bottom: 1.5rem;
        color: #d8c397;
    }
    .abt-hero-title {
        font-size: clamp(3rem, 6vw, 5.5rem);
        font-weight: 400;
        letter-spacing: 0.05em;
        text-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    .abt-editorial-section {
        padding: 80px 5%;
        max-width: 1400px;
        margin: 0 auto;
    }
    .abt-overlap-block {
        display: grid;
        grid-template-columns: 1fr 1fr;
        align-items: center;
        gap: 0;
        margin-bottom: 120px;
    }
    .abt-overlap-block.reverse {
        direction: rtl;
    }
    .abt-overlap-block.reverse > * {
        direction: ltr;
    }
    .abt-overlap-img-wrap {
        position: relative;
        width: 100%;
        padding-bottom: 110%;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        z-index: 1;
        border-radius: 4px;
        overflow: hidden;
    }
    .abt-overlap-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .abt-overlap-text-wrap {
        background: #fff;
        padding: 4rem 3.5rem;
        position: relative;
        z-index: 2;
        margin-left: -10%;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        border-radius: 4px;
    }
    .abt-overlap-block.reverse .abt-overlap-text-wrap {
        margin-left: 0;
        margin-right: -10%;
    }
    .abt-overlap-text-wrap h2 {
        font-size: 2.2rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
        color: #1a1814;
    }
    .abt-overlap-text-wrap p {
        font-size: 1rem;
        line-height: 2;
        color: #555;
        margin-bottom: 1.5rem;
    }
    .abt-overlap-text-wrap .script-quote {
        font-size: 2rem;
        color: #9c7f4c;
        line-height: 1.4;
        margin-top: 1.5rem;
        border-left: 2px solid #9c7f4c;
        padding-left: 1.5rem;
    }
    .abt-values-section {
        background: #1a1814;
        color: #fff;
        padding: 90px 5%;
        text-align: center;
    }
    .abt-values-header {
        margin-bottom: 4rem;
    }
    .abt-values-header h2 {
        font-size: 2.5rem;
        font-weight: 400;
        color: #d8c397;
        margin-bottom: 1rem;
    }
    .abt-values-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }
    .abt-value-card {
        padding: 2.5rem 2rem;
        border: 1px solid rgba(216, 195, 151, 0.2);
        transition: transform 0.3s ease, border-color 0.3s ease;
    }
    .abt-value-card:hover {
        transform: translateY(-8px);
        border-color: rgba(216, 195, 151, 0.6);
    }
    .abt-value-card i {
        font-size: 2.5rem;
        color: #d8c397;
        margin-bottom: 1.5rem;
    }
    .abt-value-card h3 {
        font-size: 1.1rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 1rem;
        color: #fff;
    }
    .abt-value-card p {
        font-size: 0.95rem;
        line-height: 1.8;
        color: #aaa;
    }
    @media (max-width: 1024px) {
        .abt-overlap-block {
            grid-template-columns: 1fr;
            margin-bottom: 70px;
        }
        .abt-overlap-text-wrap, .abt-overlap-block.reverse .abt-overlap-text-wrap {
            margin: -40px 5% 0 5%;
            padding: 2.5rem 1.5rem;
        }
        .abt-values-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="aura-about-page">
    <!-- Cinematic Hero -->
    <section class="abt-hero">
        <div class="abt-hero-content">
            <div class="abt-hero-eyebrow abt-sans">{{ $hero['eyebrow'] ?? 'THE LEGACY OF AURA' }}</div>
            <h1 class="abt-hero-title abt-serif">{{ $hero['title'] ?? 'OUR STORY' }}</h1>
        </div>
    </section>

    <!-- Overlapping Editorial Grid -->
    <section class="abt-editorial-section">
        <!-- Block 1 -->
        <div class="abt-overlap-block">
            <div class="abt-overlap-img-wrap">
                <img src="{{ !empty($b1['image_url']) ? $b1['image_url'] : asset('assets/images/hero/everyday.png') }}" alt="{{ $b1['title'] ?? 'Heritage of Excellence' }}" class="abt-overlap-img">
            </div>
            <div class="abt-overlap-text-wrap">
                <h2 class="abt-serif">{{ $b1['title'] ?? 'Heritage of Excellence' }}</h2>
                <p class="abt-sans">{{ $b1['text'] ?? 'Aura was founded on a simple yet profound belief: that fine jewellery should be an intimate expression of personal style, crafted with uncompromising quality.' }}</p>
                @if(!empty($b1['quote']))
                    <div class="script-quote abt-script">
                        "{{ $b1['quote'] }}"
                    </div>
                @endif
            </div>
        </div>

        <!-- Block 2 (Reverse) -->
        <div class="abt-overlap-block reverse">
            <div class="abt-overlap-img-wrap">
                <img src="{{ !empty($b2['image_url']) ? $b2['image_url'] : asset('assets/images/hero/Engagement.png') }}" alt="{{ $b2['title'] ?? 'The Design Philosophy' }}" class="abt-overlap-img">
            </div>
            <div class="abt-overlap-text-wrap">
                <h2 class="abt-serif">{{ $b2['title'] ?? 'The Design Philosophy' }}</h2>
                <p class="abt-sans">{{ $b2['text'] ?? 'Our aesthetic lies at the intersection of bold innovation and classic restraint. We design for the modern individual who appreciates subtlety but demands distinction.' }}</p>
                @if(!empty($b2['quote']))
                    <div class="script-quote abt-script" style="border-left: none; border-right: 2px solid #9c7f4c; padding-left: 0; padding-right: 1.5rem; text-align: right;">
                        "{{ $b2['quote'] }}"
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Craftsmanship & Values Section -->
    <section class="abt-values-section">
        <div class="abt-values-header">
            <h2 class="abt-serif">{{ $core['title'] ?? 'Our Core Values' }}</h2>
            <p class="abt-sans" style="color: #aaa; max-width: 600px; margin: 0 auto; line-height: 1.8;">
                {{ $core['subtitle'] ?? 'The principles that guide every sketch, every stone sourced, and every finished masterpiece.' }}
            </p>
        </div>
        
        <div class="abt-values-grid">
            @if(!empty($core['values']) && count($core['values']) > 0)
                @foreach($core['values'] as $val)
                    <div class="abt-value-card">
                        <i class="ph {{ $val['icon'] ?: 'ph-sparkle' }}"></i>
                        <h3 class="abt-serif">{{ $val['title'] }}</h3>
                        <p class="abt-sans">{{ $val['desc'] }}</p>
                    </div>
                @endforeach
            @else
                <!-- Fallback 3 Values -->
                <div class="abt-value-card">
                    <i class="ph ph-leaf"></i>
                    <h3 class="abt-serif">Ethically Sourced</h3>
                    <p class="abt-sans">We are committed to sourcing diamonds and precious stones only from suppliers who adhere to the highest ethical and environmental standards.</p>
                </div>
                <div class="abt-value-card">
                    <i class="ph ph-hands-clapping"></i>
                    <h3 class="abt-serif">Master Artisans</h3>
                    <p class="abt-sans">Our jewelry is brought to life by master craftsmen who have spent decades honing their skills, ensuring unparalleled attention to detail.</p>
                </div>
                <div class="abt-value-card">
                    <i class="ph ph-hourglass-high"></i>
                    <h3 class="abt-serif">Timeless Design</h3>
                    <p class="abt-sans">We eschew fleeting trends in favor of enduring elegance, creating heirloom pieces that can be cherished and passed down through generations.</p>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
