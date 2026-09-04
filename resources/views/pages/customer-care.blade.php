@extends('layouts.app')

@php
    $hero = $careData['hero'] ?? [];
    $hub = $careData['hub_cards'] ?? [];
@endphp

@section('title', ($careData['seo']['meta_title'] ?? null) ?: 'Customer Care | Aura Fine Jewellery')
@section('meta_description', ($careData['seo']['meta_description'] ?? null) ?: 'Thoughtful service, from your first discovery to every moment after. Explore Aura customer care.')
@section('main_style', 'margin-top: 110px;')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/editorial_pages.css') }}">
<style>
    .cc-card {
        text-align: center;
        padding: 3rem 2rem;
        transition: transform 0.3s ease, border-color 0.3s ease;
        text-decoration: none;
        color: inherit;
        border: 1px solid #e8e2d8;
        border-radius: 8px;
        background: #fff;
        display: block;
    }
    .cc-card:hover {
        transform: translateY(-6px);
        border-color: #c0a062;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    .cc-card i {
        font-size: 2.5rem;
        color: #c0a062;
        margin-bottom: 1.2rem;
        display: block;
    }
    .cc-card h3 {
        font-family: 'Cinzel', serif;
        font-size: 1.2rem;
        margin-bottom: 0.8rem;
    }
    .cc-card p {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.6;
    }
</style>
@endpush

@section('content')
<div>
    <div class="ed-hero" style="background: #faf8f5; padding: 4rem 2rem; text-align: center; border-bottom: 1px solid #f0ece4;">
        <div class="ed-hero-content" style="max-width: 700px; margin: 0 auto;">
            @if(!empty($hero['eyebrow']))
                <div style="font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase; color: #c0a062; margin-bottom: 0.5rem; font-weight: 600;">
                    {{ $hero['eyebrow'] }}
                </div>
            @endif
            <h1 class="ed-hero-title" style="font-family: 'Cinzel', serif; font-size: 2.8rem; margin-bottom: 0.8rem;">
                {{ $hero['title'] ?? 'Customer Care' }}
            </h1>
            <p class="ed-hero-subtitle" style="color: #666; font-size: 1.1rem;">
                {{ $hero['subtitle'] ?? 'Thoughtful service, from your first discovery to every moment after.' }}
            </p>
        </div>
    </div>

    <main class="container" style="padding: 4rem 1.5rem 6rem;">
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            <a href="{{ route('contact') }}" class="cc-card">
                <i class="ph ph-envelope-open"></i>
                <h3>Contact Us</h3>
                <p>{{ $hub['contact'] ?? 'Get in touch with our dedicated concierge team.' }}</p>
            </a>
            <a href="{{ route('shipping') }}" class="cc-card">
                <i class="ph ph-truck"></i>
                <h3>Shipping & Delivery</h3>
                <p>{{ $hub['shipping'] ?? 'Learn about our complimentary insured shipping and tracking.' }}</p>
            </a>
            <a href="{{ route('returns') }}" class="cc-card">
                <i class="ph ph-arrow-counter-clockwise"></i>
                <h3>Returns & Exchanges</h3>
                <p>{{ $hub['returns'] ?? 'Our hassle-free 14-day return and exchange policy.' }}</p>
            </a>
            <a href="{{ route('size-guide') }}" class="cc-card">
                <i class="ph ph-ruler"></i>
                <h3>Size Guide</h3>
                <p>{{ $hub['size_guide'] ?? 'Find the perfect fit for your rings, necklaces, and bangles.' }}</p>
            </a>
            <a href="{{ route('jewellery-care') }}" class="cc-card">
                <i class="ph ph-sparkle"></i>
                <h3>Jewellery Care</h3>
                <p>{{ $hub['jewellery_care'] ?? 'Expert tips to maintain the brilliance and luster of your pieces.' }}</p>
            </a>
            <a href="{{ route('faqs') }}" class="cc-card">
                <i class="ph ph-question"></i>
                <h3>FAQs</h3>
                <p>{{ $hub['faqs'] ?? 'Answers to your most frequently asked questions.' }}</p>
            </a>
            <a href="{{ route('privacy-policy') }}" class="cc-card">
                <i class="ph ph-shield-check"></i>
                <h3>Privacy Policy</h3>
                <p>{{ $hub['privacy_policy'] ?? 'Our commitment to discretion, data security, and zero data-sale.' }}</p>
            </a>
            <a href="{{ route('terms-conditions') }}" class="cc-card">
                <i class="ph ph-file-text"></i>
                <h3>Terms &amp; Conditions</h3>
                <p>{{ $hub['terms_conditions'] ?? 'Official terms of service, certification standards, and purchase agreements.' }}</p>
            </a>
        </div>
    </main>
</div>
@endsection
