@extends('layouts.customer-care')

@php
    $shipping = $careData['shipping'] ?? [];
    $shippingCards = !empty($shipping['cards']) ? $shipping['cards'] : [
        [
            'icon' => 'ph-truck',
            'title' => 'Complimentary Shipping',
            'description' => 'We offer complimentary fully-insured shipping on all orders within India. Your piece is in safe hands from our atelier to your doorstep.'
        ],
        [
            'icon' => 'ph-clock',
            'title' => 'Delivery Timelines',
            'description' => 'In-stock items are dispatched within 2 business days. Made-to-order masterpieces typically require 15-20 business days for meticulous crafting before dispatch.'
        ],
        [
            'icon' => 'ph-package',
            'title' => 'Secure Packaging',
            'description' => 'Your jewellery arrives in our signature Aura presentation box, securely enclosed in a tamper-evident outer box to ensure complete safety during transit.'
        ],
        [
            'icon' => 'ph-map-pin',
            'title' => 'Order Tracking',
            'description' => 'Upon dispatch, you will receive a tracking link via email and SMS. All shipments require an adult signature upon delivery for absolute security.'
        ]
    ];
@endphp

@section('title', ($careData['seo']['meta_title'] ?? null) ?: 'Shipping & Delivery | Aura Fine Jewellery')
@section('meta_description', ($careData['seo']['meta_description'] ?? null) ?: 'Learn about complimentary insured shipping, dispatch timelines, and tamper-evident packaging at Aura.')

@section('care_content')
<h2>{{ $shipping['title'] ?? 'Shipping & Delivery' }}</h2>
<div class="ship-grid">
    @foreach($shippingCards as $card)
        @php
            $icon = $card['icon'] ?? 'ph-truck';
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
