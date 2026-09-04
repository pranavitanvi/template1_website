@extends('layouts.customer-care')

@php
    $care = $careData['jewellery_care'] ?? [];
    $careCards = !empty($care['cards']) ? $care['cards'] : [
        [
            'icon' => 'ph-sparkle',
            'title' => 'Cleaning Diamonds & Gold',
            'description' => 'Clean diamond and solid gold pieces using lukewarm water, mild organic soap, and a soft-bristled brush. Rinse thoroughly and pat dry with a soft microfiber cloth.'
        ],
        [
            'icon' => 'ph-archive',
            'title' => 'Safe Storage',
            'description' => 'Store your jewellery in the original fabric-lined Aura presentation box or separate pouches to prevent harder stones from scratching softer precious metals.'
        ],
        [
            'icon' => 'ph-shield-check',
            'title' => 'Annual Inspection',
            'description' => 'We recommend bringing your solitaire and pave pieces to any Aura boutique annually for complimentary ultrasonic cleaning and prong security checks.'
        ],
        [
            'icon' => 'ph-drop',
            'title' => 'Avoid Harsh Chemicals',
            'description' => 'Never expose gemstones or pearls to chlorine, bleach, household cleansers, or boiling water, which can cause irreparable surface etching and discoloration.'
        ]
    ];
    $careImageUrl = $care['image_url'] ?? asset('assets/images/hero/hero_main.jpg');
@endphp

@section('title', 'Jewellery Care | Aura Fine Jewellery')
@section('meta_description', 'Expert guidance on cleaning, storing, and preserving your Aura gold and diamond pieces.')

@section('care_content')
<h2>{{ $care['title'] ?? 'Jewellery Care' }}</h2>
<div style="display: flex; gap: 2rem; margin-bottom: 3rem; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 280px;">
        <p style="color: #666; line-height: 1.8; margin-bottom: 1.5rem;">
            {{ $care['intro'] ?? 'Fine jewellery is delicate by nature. With proper care, your Aura heirloom pieces will retain their brilliance and fire for generations.' }}
        </p>
        <h3 style="font-family: 'Cinzel', serif; font-size: 1.2rem; margin-bottom: 0.5rem; color: #1a1814;">
            {{ $care['rituals_title'] ?? 'Daily Rituals' }}
        </h3>
        <p style="color: #666; line-height: 1.8;">
            {{ $care['rituals_desc'] ?? 'Remove jewellery during rigorous physical workouts, swimming, or when applying cosmetics, perfumes, and lotions. Chemicals can dull the luster of gold and degrade delicate settings.' }}
        </p>
    </div>
    <div style="flex: 1; min-width: 280px; border-radius: 8px; overflow: hidden; max-height: 220px;">
        <img src="{{ $careImageUrl }}" alt="Aura Fine Jewellery Care" style="width: 100%; height: 100%; object-fit: cover;">
    </div>
</div>

<div class="ship-grid">
    @foreach($careCards as $card)
        @php
            $icon = $card['icon'] ?? 'ph-sparkle';
            if (!str_starts_with($icon, 'ph ')) {
                $icon = 'ph ' . $icon;
            }
        @endphp
        <div class="ship-card">
            <i class="{{ $icon }}"></i>
            <h3>{{ $card['title'] ?? '' }}</h3>
            <p>{{ $card['description'] ?? '' }}</p>
        </div>
    @endforeach
</div>
@endsection
