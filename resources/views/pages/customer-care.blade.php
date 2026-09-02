@extends('layouts.app')

@section('title', 'Customer Care | Aura Fine Jewellery')
@section('meta_description', 'Thoughtful service, from your first discovery to every moment after. Explore Aura customer care.')
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
            <h1 class="ed-hero-title" style="font-family: 'Cinzel', serif; font-size: 2.8rem; margin-bottom: 0.8rem;">Customer Care</h1>
            <p class="ed-hero-subtitle" style="color: #666; font-size: 1.1rem;">Thoughtful service, from your first discovery to every moment after.</p>
        </div>
    </div>

    <main class="container" style="padding: 4rem 1.5rem 6rem;">
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
            <a href="{{ route('contact') }}" class="cc-card">
                <i class="ph ph-envelope-open"></i>
                <h3>Contact Us</h3>
                <p>Get in touch with our dedicated concierge team.</p>
            </a>
            <a href="{{ route('shipping') }}" class="cc-card">
                <i class="ph ph-truck"></i>
                <h3>Shipping & Delivery</h3>
                <p>Learn about our complimentary insured shipping and tracking.</p>
            </a>
            <a href="{{ route('returns') }}" class="cc-card">
                <i class="ph ph-arrow-counter-clockwise"></i>
                <h3>Returns & Exchanges</h3>
                <p>Our hassle-free 15-day return policy.</p>
            </a>
            <a href="{{ route('size-guide') }}" class="cc-card">
                <i class="ph ph-ruler"></i>
                <h3>Size Guide</h3>
                <p>Find the perfect fit for your rings, necklaces, and bangles.</p>
            </a>
            <a href="{{ route('jewellery-care') }}" class="cc-card">
                <i class="ph ph-sparkle"></i>
                <h3>Jewellery Care</h3>
                <p>Expert tips to maintain the brilliance and luster of your pieces.</p>
            </a>
            <a href="{{ route('faqs') }}" class="cc-card">
                <i class="ph ph-question"></i>
                <h3>FAQs</h3>
                <p>Answers to your most frequently asked questions.</p>
            </a>
        </div>
    </main>
</div>
@endsection
