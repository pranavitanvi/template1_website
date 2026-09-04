@extends('layouts.app')

@php
    $hero = $collectionsPage['hero'] ?? [];
    $allCollections = collect($collectionsPage['collections'] ?? []);
    $row1 = $allCollections->slice(0, 3);
    $row2 = $allCollections->slice(3);
    $editorial = $collectionsPage['editorial_feature'] ?? [];
    $seo = $collectionsPage['seo'] ?? [];
@endphp

@section('title', !empty($seo['meta_title']) ? $seo['meta_title'] : 'Our Collections | Aura Fine Jewellery')
@section('meta_description', !empty($seo['meta_description']) ? $seo['meta_description'] : 'Discover distinctive collections crafted to celebrate every occasion, from everyday elegance to bridal edits.')
@section('main_style', 'margin-top: 110px;')

@push('styles')
<style>
    .aura-collections-page {
        background-color: #FAF8F4;
        color: #1a1814;
        padding-bottom: 80px;
    }
    .coll-container {
        max-width: 1400px;
        width: 90%;
        margin: 0 auto;
    }
    .coll-serif { font-family: 'Cinzel', serif; }
    .coll-sans { font-family: 'Montserrat', sans-serif; }
    .coll-gold { color: #c0a062; }
    
    .coll-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        text-decoration: none;
        color: #1a1814;
        border-bottom: 1px solid transparent;
        padding-bottom: 3px;
        transition: all 0.3s ease;
    }
    .coll-link:hover {
        color: #c0a062;
        border-bottom-color: #c0a062;
    }
    .coll-hero {
        padding: 60px 0 40px;
        text-align: center;
    }
    .coll-hero-eyebrow {
        font-size: 0.75rem;
        letter-spacing: 0.25em;
        text-transform: uppercase;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    .coll-hero-title {
        font-size: clamp(2.5rem, 4vw, 3.5rem);
        font-weight: 400;
        letter-spacing: 0.05em;
        margin-bottom: 1.5rem;
        color: #1a1814;
    }
    .coll-hero-desc {
        font-size: 1rem;
        line-height: 1.8;
        color: #555;
        max-width: 650px;
        margin: 0 auto;
    }
    .coll-hero-divider {
        margin: 2rem auto 0;
        width: 60px;
        height: 1px;
        background-color: #c0a062;
    }
    .coll-grid-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 32px;
        margin-bottom: 80px;
    }
    .coll-card {
        display: flex;
        flex-direction: column;
        background: transparent;
        text-decoration: none;
        color: inherit;
    }
    .coll-card-img-wrap {
        width: 100%;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        background: #fff;
        margin-bottom: 1.5rem;
        border-radius: 4px;
    }
    .coll-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s ease;
    }
    .coll-card:hover .coll-card-img {
        transform: scale(1.05);
    }
    .coll-card-title {
        font-size: 1.6rem;
        margin-bottom: 0.5rem;
        font-weight: 400;
        color: #1a1814;
    }
    .coll-card-desc {
        font-size: 0.9rem;
        color: #555;
        line-height: 1.6;
        margin-bottom: 1.2rem;
    }
    .coll-editorial {
        display: flex;
        align-items: center;
        gap: 60px;
        margin-bottom: 80px;
        background: transparent;
    }
    .coll-edi-img-wrap {
        flex: 1;
        aspect-ratio: 4 / 5;
        max-height: 650px;
        overflow: hidden;
        border-radius: 4px;
    }
    .coll-edi-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .coll-edi-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 2rem 5% 2rem 0;
    }
    .coll-edi-eyebrow {
        font-size: 0.75rem;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: #c0a062;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    .coll-edi-title {
        font-size: 2.5rem;
        font-weight: 400;
        color: #1a1814;
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }
    .coll-edi-desc {
        font-size: 1rem;
        line-height: 1.8;
        color: #555;
        margin-bottom: 2rem;
        max-width: 90%;
    }
    @media (max-width: 992px) {
        .coll-grid-row { grid-template-columns: 1fr 1fr; }
        .coll-editorial { flex-direction: column; gap: 40px; }
        .coll-edi-img-wrap { aspect-ratio: 4 / 3; width: 100%; }
        .coll-edi-content { padding: 1rem 0; text-align: center; align-items: center; }
    }
    @media (max-width: 768px) {
        .coll-grid-row { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="aura-collections-page">
    <div class="coll-container">
        <!-- Introduction Hero -->
        <section class="coll-hero">
            <div class="coll-hero-eyebrow coll-sans coll-gold">
                {{ $hero['eyebrow'] ?? 'AURA FINE JEWELLERY' }}
            </div>
            <h1 class="coll-hero-title coll-serif">
                {{ $hero['title'] ?? 'OUR COLLECTIONS' }}
            </h1>
            <p class="coll-hero-desc coll-sans">
                {{ $hero['subtitle'] ?? "Discover distinctive collections crafted to celebrate every occasion, from timeless everyday pieces to statement jewellery designed for life's most memorable moments." }}
            </p>
            <div class="coll-hero-divider"></div>
        </section>

        <!-- ROW 1 (First 3 Collections) -->
        <section class="coll-grid-row">
            @if($row1->count() > 0)
                @foreach($row1 as $card)
                    @php
                        $cSlug = !empty($card['slug']) ? $card['slug'] : \Illuminate\Support\Str::slug($card['name'] ?? '');
                        $cUrl = (!empty($card['link']) && !in_array($card['link'], ['#', '', 'shop.html', '/shop', 'shop']))
                            ? $card['link']
                            : route('collection', $cSlug ?: 'all');
                    @endphp
                    <a href="{{ $cUrl }}" class="coll-card">
                        <div class="coll-card-img-wrap">
                            <img src="{{ $card['image_url'] }}" alt="{{ $card['name'] }}" class="coll-card-img">
                        </div>
                        <div class="coll-card-content">
                            @if(!empty($card['occasion']))
                                <div class="badge-occasion coll-sans" style="font-size: 0.72rem; letter-spacing: 0.15em; text-transform: uppercase; color: #c0a062; margin-bottom: 0.35rem; font-weight: 600;">
                                    {{ $card['occasion'] }}
                                </div>
                            @endif
                            <h2 class="coll-card-title coll-serif">{{ strtoupper($card['name']) }}</h2>
                            <p class="coll-card-desc coll-sans">{{ $card['description'] ?: ($card['occasion'] ? ($card['occasion'] . ' Collection.') : '') }}</p>
                            <span class="coll-link coll-sans">EXPLORE COLLECTION <i class="ph ph-arrow-right"></i></span>
                        </div>
                    </a>
                @endforeach
            @else
                <!-- Fallback Default 3 Cards -->
                <a href="{{ route('collection', 'wedding') }}" class="coll-card">
                    <div class="coll-card-img-wrap">
                        <img src="{{ asset('assets/images/collections/aekta.jpg') }}" alt="Aekta" class="coll-card-img">
                    </div>
                    <div class="coll-card-content">
                        <h2 class="coll-card-title coll-serif">AEKTA</h2>
                        <p class="coll-card-desc coll-sans">The Wedding Collection.</p>
                        <span class="coll-link coll-sans">EXPLORE COLLECTION <i class="ph ph-arrow-right"></i></span>
                    </div>
                </a>
                <a href="{{ route('collection', 'festive') }}" class="coll-card">
                    <div class="coll-card-img-wrap">
                        <img src="{{ asset('assets/images/collections/rajwada.jpg') }}" alt="Noor" class="coll-card-img">
                    </div>
                    <div class="coll-card-content">
                        <h2 class="coll-card-title coll-serif">NOOR</h2>
                        <p class="coll-card-desc coll-sans">Festive Collection.</p>
                        <span class="coll-link coll-sans">EXPLORE COLLECTION <i class="ph ph-arrow-right"></i></span>
                    </div>
                </a>
                <a href="{{ route('collection', 'everyday') }}" class="coll-card">
                    <div class="coll-card-img-wrap">
                        <img src="{{ asset('assets/images/collections/circular_everyday.jpg') }}" alt="Ira" class="coll-card-img">
                    </div>
                    <div class="coll-card-content">
                        <h2 class="coll-card-title coll-serif">IRA</h2>
                        <p class="coll-card-desc coll-sans">Everyday Collection.</p>
                        <span class="coll-link coll-sans">EXPLORE COLLECTION <i class="ph ph-arrow-right"></i></span>
                    </div>
                </a>
            @endif
        </section>

        <!-- Editorial Feature Spotlight -->
        <section class="coll-editorial">
            <div class="coll-edi-img-wrap">
                <img src="{{ !empty($editorial['image_url']) ? $editorial['image_url'] : asset('assets/images/bridal/bridal_main.jpg') }}" alt="{{ $editorial['title'] ?? 'Bridal Collection' }}" class="coll-edi-img">
            </div>
            <div class="coll-edi-content">
                <div class="coll-edi-eyebrow coll-sans">{{ $editorial['eyebrow'] ?? 'FINE JEWELLERY' }}</div>
                <h2 class="coll-edi-title coll-serif">{{ $editorial['title'] ?? 'THE BRIDAL EDIT' }}</h2>
                <p class="coll-edi-desc coll-sans">{{ $editorial['subtitle'] ?? 'Where timeless craftsmanship meets contemporary grace. Designed for women who make elegance a statement, bringing royalty to your special day.' }}</p>
                <div>
                    <a href="{{ !empty($editorial['button']['url']) ? $editorial['button']['url'] : route('bridal') }}" class="coll-link coll-sans">
                        {{ $editorial['button']['text'] ?? 'EXPLORE BRIDAL' }} <i class="ph ph-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- ROW 2 (Remaining Collections) -->
        <section class="coll-grid-row">
            @if($row2->count() > 0)
                @foreach($row2 as $card)
                    @php
                        $cSlug = !empty($card['slug']) ? $card['slug'] : \Illuminate\Support\Str::slug($card['name'] ?? '');
                        $cUrl = (!empty($card['link']) && !in_array($card['link'], ['#', '', 'shop.html', '/shop', 'shop']))
                            ? $card['link']
                            : route('collection', $cSlug ?: 'all');
                    @endphp
                    <a href="{{ $cUrl }}" class="coll-card">
                        <div class="coll-card-img-wrap">
                            <img src="{{ $card['image_url'] }}" alt="{{ $card['name'] }}" class="coll-card-img">
                        </div>
                        <div class="coll-card-content">
                            @if(!empty($card['occasion']))
                                <div class="badge-occasion coll-sans" style="font-size: 0.72rem; letter-spacing: 0.15em; text-transform: uppercase; color: #c0a062; margin-bottom: 0.35rem; font-weight: 600;">
                                    {{ $card['occasion'] }}
                                </div>
                            @endif
                            <h2 class="coll-card-title coll-serif">{{ strtoupper($card['name']) }}</h2>
                            <p class="coll-card-desc coll-sans">{{ $card['description'] ?: ($card['occasion'] ? ($card['occasion'] . ' Collection.') : '') }}</p>
                            <span class="coll-link coll-sans">EXPLORE COLLECTION <i class="ph ph-arrow-right"></i></span>
                        </div>
                    </a>
                @endforeach
            @else
                <!-- Fallback Default Remaining 3 Cards -->
                <a href="{{ route('collection', 'heritage') }}" class="coll-card">
                    <div class="coll-card-img-wrap">
                        <img src="{{ asset('assets/images/collections/circular_heritage.jpg') }}" alt="Viraasat" class="coll-card-img">
                    </div>
                    <div class="coll-card-content">
                        <h2 class="coll-card-title coll-serif">VIRAASAT</h2>
                        <p class="coll-card-desc coll-sans">Heritage Collection.</p>
                        <span class="coll-link coll-sans">EXPLORE COLLECTION <i class="ph ph-arrow-right"></i></span>
                    </div>
                </a>
                <a href="{{ route('collection', 'modern') }}" class="coll-card">
                    <div class="coll-card-img-wrap">
                        <img src="{{ asset('assets/images/collections/circular_modern.jpg') }}" alt="Lustre" class="coll-card-img">
                    </div>
                    <div class="coll-card-content">
                        <h2 class="coll-card-title coll-serif">LUSTRE</h2>
                        <p class="coll-card-desc coll-sans">Modern Collection.</p>
                        <span class="coll-link coll-sans">EXPLORE COLLECTION <i class="ph ph-arrow-right"></i></span>
                    </div>
                </a>
                <a href="{{ route('collection', 'engagement') }}" class="coll-card">
                    <div class="coll-card-img-wrap">
                        <img src="{{ asset('assets/images/collections/circular_engagement.jpg') }}" alt="Meher" class="coll-card-img">
                    </div>
                    <div class="coll-card-content">
                        <h2 class="coll-card-title coll-serif">MEHER</h2>
                        <p class="coll-card-desc coll-sans">Engagement Collection.</p>
                        <span class="coll-link coll-sans">EXPLORE COLLECTION <i class="ph ph-arrow-right"></i></span>
                    </div>
                </a>
            @endif
        </section>
    </div>
</div>
@endsection
