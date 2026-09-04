@extends('layouts.app')

@section('main_style', 'margin-top: 110px;')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/editorial_pages.css') }}">
<style>
    .aura-cc-page {
        background-color: #FAF8F4;
        color: #1a1814;
        padding-bottom: 80px;
    }
    .cc-hero {
        text-align: center;
        padding: 50px 20px 30px;
    }
    .cc-hero h1 {
        font-family: 'Cinzel', serif;
        font-size: 2.8rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #1a1814;
    }
    .cc-hero p {
        color: #666;
        font-size: 1.05rem;
    }
    .cc-container {
        max-width: 1300px;
        width: 90%;
        margin: 0 auto;
        display: flex;
        gap: 50px;
        align-items: flex-start;
    }
    .cc-sidebar {
        flex: 0 0 260px;
        position: sticky;
        top: 140px;
    }
    .cc-nav {
        list-style: none;
        padding: 0;
        margin: 0;
        border-left: 1.5px solid rgba(156, 127, 76, 0.25);
    }
    .cc-nav-link {
        display: block;
        padding: 0.9rem 0 0.9rem 1.4rem;
        text-decoration: none;
        color: #555;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        transition: all 0.3s ease;
        position: relative;
    }
    .cc-nav-link:hover { color: #9c7f4c; }
    .cc-nav-link.active {
        color: #9c7f4c;
        font-weight: 600;
    }
    .cc-nav-link.active::before {
        content: '';
        position: absolute;
        left: -1.5px;
        top: 0;
        bottom: 0;
        width: 3px;
        background-color: #9c7f4c;
    }
    .cc-content {
        flex: 1;
        background: #fff;
        padding: 3.5rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        min-height: 550px;
        border-radius: 6px;
    }
    .cc-content h2 {
        font-family: 'Cinzel', serif;
        font-size: 2rem;
        font-weight: 500;
        color: #1a1814;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(156, 127, 76, 0.2);
    }
    .ship-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.8rem;
    }
    .ship-card {
        padding: 2rem;
        border: 1px solid #f0f0f0;
        background: #fafafa;
        border-radius: 6px;
        transition: transform 0.3s ease, border-color 0.3s ease;
    }
    .ship-card:hover {
        transform: translateY(-4px);
        border-color: rgba(156, 127, 76, 0.35);
    }
    .ship-card i {
        font-size: 2.2rem;
        color: #9c7f4c;
        margin-bottom: 1rem;
        display: block;
    }
    .ship-card h3 {
        font-family: 'Cinzel', serif;
        font-size: 1.15rem;
        margin-bottom: 0.8rem;
        color: #1a1814;
    }
    .ship-card p {
        font-size: 0.92rem;
        line-height: 1.6;
        color: #555;
    }
    .ret-steps {
        display: flex;
        flex-direction: column;
        gap: 1.8rem;
    }
    .ret-step {
        display: flex;
        gap: 1.5rem;
        align-items: flex-start;
    }
    .ret-step-num {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #faf8f4;
        border: 1.5px solid #c0a062;
        color: #c0a062;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        flex-shrink: 0;
        font-size: 1rem;
    }
    .faq-container { display: flex; flex-direction: column; gap: 1.5rem; }
    .faq-item { border-bottom: 1px solid #f0ece4; padding-bottom: 1.2rem; }
    .faq-question { font-family: 'Cinzel', serif; font-size: 1.15rem; font-weight: 500; cursor: pointer; display: flex; justify-content: space-between; align-items: center; color: #1a1814; }
    .faq-answer { max-height: 0; overflow: hidden; transition: max-height 0.35s ease; color: #555; line-height: 1.7; font-size: 0.95rem; }
    .faq-item.active .faq-answer { max-height: 300px; margin-top: 1rem; }
    .faq-item.active .faq-question i { transform: rotate(180deg); }
    @media (max-width: 900px) {
        .cc-container { flex-direction: column; }
        .cc-sidebar { flex: 1 1 auto; width: 100%; position: static; }
        .cc-nav { border-left: none; display: flex; flex-wrap: wrap; gap: 10px; border-bottom: 1px solid #eee; padding-bottom: 1rem; }
        .cc-nav-link { padding: 0.5rem 1rem; border: 1px solid #ddd; border-radius: 20px; }
        .cc-nav-link.active::before { display: none; }
        .cc-nav-link.active { background: #c0a062; color: #fff; border-color: #c0a062; }
        .ship-grid { grid-template-columns: 1fr; }
        .cc-content { padding: 2rem 1.5rem; }
    }
</style>
@stack('cc_styles')
@endpush

@section('content')
<main class="aura-cc-page">
    <div class="cc-hero">
        @if(!empty($careData['hero']['eyebrow']))
            <div style="font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase; color: #c0a062; margin-bottom: 0.5rem; font-weight: 600;">
                {{ $careData['hero']['eyebrow'] }}
            </div>
        @endif
        <h1>{{ $careData['hero']['title'] ?? 'Customer Care' }}</h1>
        <p>{{ $careData['hero']['subtitle'] ?? 'We are here to assist you with every detail.' }}</p>
    </div>
    
    <div class="cc-container">
        @include('partials.customer-care-sidebar')
        <section class="cc-content">
            @yield('care_content')
        </section>
    </div>
</main>
@endsection
