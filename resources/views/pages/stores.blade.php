@extends('layouts.app')

@section('title', 'Boutique Locator | Aura Fine Jewellery')
@section('meta_description', 'Visit an Aura boutique in Mumbai, Delhi, or Bangalore to experience our fine jewellery collections in person.')
@section('main_style', 'margin-top: 0;')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/editorial_pages.css') }}">
<style>
    .stores-map {
        height: 480px;
        background-image: url('{{ asset("assets/images/hero/hero_banner.png") }}');
        background-size: cover;
        background-position: center;
        position: relative;
        margin-top: 110px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .stores-map::after {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(42, 36, 34, 0.65);
    }
    .stores-map-text {
        position: relative;
        z-index: 10;
        color: #fff;
        text-align: center;
    }
    .stores-map-text h1 {
        font-family: 'Cinzel', serif;
        font-size: clamp(2.5rem, 5vw, 3.5rem);
        color: #C2A878;
        margin-bottom: 1rem;
    }
    .stores-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        padding: 5rem 2rem 6rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    .store-grid-item {
        background: #fff;
        padding: 3rem 2rem;
        text-align: center;
        border-top: 4px solid #C2A878;
        border-radius: 4px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }
    .store-grid-item:hover {
        transform: translateY(-8px);
    }
    .store-grid-item i {
        font-size: 2.5rem;
        color: #C2A878;
        margin-bottom: 1rem;
        display: inline-block;
    }
    .store-grid-item h3 {
        font-family: 'Cinzel', serif;
        font-size: 1.2rem;
        margin-bottom: 0.8rem;
    }
    @media (max-width: 900px) {
        .stores-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div>
    <div class="stores-map">
        <div class="stores-map-text">
            <h1>Global Boutiques</h1>
            <p style="font-size: 1.2rem; max-width: 600px; margin: 0 auto; color: #d1cbc7;">Experience the luxury and artistry of Aura in person.</p>
        </div>
    </div>
    <div class="stores-grid">
        <div class="store-grid-item">
            <i class="ph ph-storefront"></i>
            <h3>AURA Flagship - Mumbai</h3>
            <p style="color: #666; margin-bottom: 1.5rem; line-height: 1.6;">Ground Floor, Palladium Mall, Lower Parel<br>Mumbai, Maharashtra 400013</p>
            <p style="font-weight: 600; color: #1a1814;"><i class="ph ph-phone" style="font-size: 1.1rem; margin-right: 5px;"></i> +91 22 1234 5678</p>
            <a href="{{ route('contact') }}" class="btn btn-primary" style="margin-top: 1.5rem; width: 100%; display: block; text-align: center;">Book Visit</a>
        </div>
        <div class="store-grid-item">
            <i class="ph ph-storefront"></i>
            <h3>AURA Boutique - Delhi</h3>
            <p style="color: #666; margin-bottom: 1.5rem; line-height: 1.6;">DLF Emporio, Vasant Kunj<br>New Delhi, Delhi 110070</p>
            <p style="font-weight: 600; color: #1a1814;"><i class="ph ph-phone" style="font-size: 1.1rem; margin-right: 5px;"></i> +91 11 9876 5432</p>
            <a href="{{ route('contact') }}" class="btn btn-primary" style="margin-top: 1.5rem; width: 100%; display: block; text-align: center;">Book Visit</a>
        </div>
        <div class="store-grid-item">
            <i class="ph ph-storefront"></i>
            <h3>AURA Salon - Bangalore</h3>
            <p style="color: #666; margin-bottom: 1.5rem; line-height: 1.6;">UB City, Vittal Mallya Road<br>Bengaluru, Karnataka 560001</p>
            <p style="font-weight: 600; color: #1a1814;"><i class="ph ph-phone" style="font-size: 1.1rem; margin-right: 5px;"></i> +91 80 4567 8901</p>
            <a href="{{ route('contact') }}" class="btn btn-primary" style="margin-top: 1.5rem; width: 100%; display: block; text-align: center;">Book Visit</a>
        </div>
    </div>
</div>
@endsection
