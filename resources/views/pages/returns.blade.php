@extends('layouts.customer-care')

@php
    $returns = $careData['returns'] ?? [];
    $steps = !empty($returns['steps']) ? $returns['steps'] : [
        [
            'step' => 1,
            'title' => 'Initiate Request',
            'description' => 'Contact our concierge team at care@aurajewellery.com or call us within 14 days of delivery to request a return authorization.'
        ],
        [
            'step' => 2,
            'title' => 'Secure Packaging',
            'description' => 'Place the unworn item back in its original presentation box, along with all certificates of authenticity, packaging, and invoices.'
        ],
        [
            'step' => 3,
            'title' => 'Complimentary Pickup',
            'description' => 'We arrange an armored, fully-insured courier pickup from your address at zero additional cost to you.'
        ]
    ];
@endphp

@section('title', 'Returns & Exchanges | Aura Fine Jewellery')
@section('meta_description', 'Experience our complimentary hassle-free 14-day return and exchange policy.')

@section('care_content')
<h2>{{ $returns['title'] ?? 'Returns & Exchanges' }}</h2>
<p style="color: #666; margin-bottom: 2.5rem; line-height: 1.7;">
    {{ $returns['intro'] ?? 'We want you to adore your jewellery. If for any reason you are not completely enchanted with your purchase, we offer a complimentary 14-day return and exchange window.' }}
</p>

<div class="ret-steps">
    @foreach($steps as $s)
        <div class="ret-step">
            <div class="ret-step-num">{{ $s['step'] ?? $loop->iteration }}</div>
            <div class="ret-step-content">
                <h3 style="font-family: 'Cinzel', serif; font-size: 1.15rem; margin-bottom: 0.4rem;">{{ $s['title'] ?? '' }}</h3>
                <p style="color: #666; font-size: 0.95rem; line-height: 1.6;">{!! nl2br(e($s['description'] ?? '')) !!}</p>
            </div>
        </div>
    @endforeach
</div>

@if(!empty($returns['note']))
    <p style="margin-top: 3rem; font-size: 0.85rem; color: #888; border-top: 1px solid #f0ece4; padding-top: 1.5rem;">
        {{ $returns['note'] }}
    </p>
@endif
@endsection
