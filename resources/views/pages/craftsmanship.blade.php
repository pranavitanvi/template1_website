@extends('layouts.app')

@section('title', 'Craftsmanship & Heritage | Aura Fine Jewellery')
@section('meta_description', 'Discover the art of perfection and master craftsmanship behind every handcrafted piece of Aura jewellery.')
@section('main_style', 'margin-top: 0;')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/editorial_pages.css') }}">
<style>
    .craft-hero {
        margin-top: 110px;
        text-align: center;
        padding: 8rem 2rem;
        background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.9)), url('{{ asset("assets/images/hero/herobanner_4.png") }}');
        background-size: cover;
        background-position: center;
        color: #fff;
    }
    .craft-hero h1 { font-family: 'Cinzel', serif; color: #C2A878; font-size: clamp(2.5rem, 5vw, 4rem); margin-bottom: 1rem; }
    .craft-section {
        padding: 6rem 2rem;
        max-width: 1000px;
        margin: 0 auto;
    }
    .craft-step {
        display: flex;
        gap: 4rem;
        margin-bottom: 6rem;
        align-items: center;
    }
    .craft-step-img {
        flex: 1;
        border: 1px solid #C2A878;
        padding: 1rem;
        border-radius: 4px;
    }
    .craft-step-img img { width: 100%; display: block; border-radius: 2px; }
    .craft-step-text { flex: 1; }
    .craft-step-num { font-family: 'Cinzel', serif; font-size: 4rem; color: #C2A878; opacity: 0.4; line-height: 1; margin-bottom: 1rem; }
    .craft-step h3 { font-family: 'Cinzel', serif; font-size: 2rem; color: var(--text-primary); margin-bottom: 1rem; }
    @media (max-width: 768px) {
        .craft-step { flex-direction: column; gap: 2rem; }
        .craft-step:nth-child(even) { flex-direction: column !important; }
    }
</style>
@endpush

@section('content')
<div>
    <div class="craft-hero">
        <h1>The Art of Perfection</h1>
        <p style="font-size: 1.2rem; max-width: 600px; margin: 0 auto; color: #d1cbc7; line-height: 1.8;">Preserving traditional techniques while embracing cutting-edge precision to create masterpieces of enduring quality.</p>
    </div>
    <div class="craft-section">
        <div class="craft-step">
            <div class="craft-step-text">
                <div class="craft-step-num">01</div>
                <h3>Inspiration & Design</h3>
                <p style="color: #555; line-height: 1.8;">The journey begins with a vision. Our designers sketch concepts that celebrate form and light, translating inspiration into intricate technical blueprints.</p>
            </div>
            <div class="craft-step-img">
                <img src="{{ asset('assets/images/hero/everyday.png') }}" alt="Design">
            </div>
        </div>
        <div class="craft-step" style="flex-direction: row-reverse;">
            <div class="craft-step-text">
                <div class="craft-step-num">02</div>
                <h3>Precision Crafting</h3>
                <p style="color: #555; line-height: 1.8;">Master goldsmiths melt and mold pure metals, forging the foundation of the piece. Using a blend of ancient hand-forging techniques and modern laser precision, the structure is born.</p>
            </div>
            <div class="craft-step-img">
                <img src="{{ asset('assets/images/hero/Engagement.png') }}" alt="Crafting">
            </div>
        </div>
    </div>
</div>
@endsection
