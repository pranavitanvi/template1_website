@extends('layouts.customer-care')

@php
    $terms = $careData['terms_conditions'] ?? $careData['terms'] ?? [];
    $customContent = $terms['custom_content'] ?? ($terms['content'] ?? null);
    $sections = !empty($terms['sections']) ? $terms['sections'] : [
        [
            'title' => '1. Acceptance & General Terms',
            'content' => 'By accessing our digital boutique or acquiring pieces from Aura Fine Jewellery, you agree to be bound by these Terms & Conditions. These terms govern all purchases, custom commissions, styling appointments, and digital interactions. We encourage all patrons to review these terms carefully prior to placing an order.'
        ],
        [
            'title' => '2. Certified Authenticity & Hallmark Assurance',
            'content' => "Every creation from Aura Fine Jewellery is handcrafted to exacting standards of gemological excellence:
• BIS Hallmarking: All gold creations carry official Bureau of Indian Standards (BIS) hallmark certification verifying 14K, 18K, or 22K purity.
• Natural Diamond Certification: All solitaire and pavé diamonds are certified by accredited gemological laboratories (GIA, IGI, or SGL) stating exact color, clarity, cut, and carat weight.
• Certified Documentation: Each acquisition is accompanied by an authenticated Certificate of Authenticity and an itemized tax invoice."
        ],
        [
            'title' => '3. Pricing & Precious Metal Market Valuation',
            'content' => "Fine jewellery prices are indexed to international and domestic bullion market rates for gold and platinum, as well as prevailing diamond indices.
• Price Quotations: Once an order is confirmed, the purchase price is locked and will not fluctuate regardless of subsequent metal market changes.
• Transparent Breakdown: Invoices clearly detail the net gold weight, diamond weight, making charges, and applicable statutory taxes (GST)."
        ],
        [
            'title' => '4. Orders & Verification Process',
            'content' => "To protect our patrons against fraudulent transactions, orders may undergo a brief verification protocol:
• Order Confirmation: Orders are confirmed once payment verification is completed by our banking partners.
• Right of Refusal: We reserve the right to decline or cancel any order in the event of pricing errors caused by technical anomalies or unverified payment credentials."
        ],
        [
            'title' => '5. Bespoke & Made-to-Order Pieces',
            'content' => "We pride ourselves on crafting bespoke heirlooms tailored to your personal aesthetic:
• Crafting Timeline: Made-to-order masterpieces typically require 15 to 20 business days of artisanal handcrafting prior to dispatch.
• Design Approval: Custom commissions proceed to casting only after client review and digital CAD / 3D design approval.
• Customized Pieces: Because bespoke creations are personalized with unique engravings or custom dimensions, they are non-returnable once crafted, except in cases of manufacturing variance."
        ],
        [
            'title' => '6. Insured Armored Shipping & Delivery',
            'content' => "We provide complimentary, fully-insured armored transit on all orders:
• Transit Insurance: Full transit risk remains with Aura Fine Jewellery until the package is handed over and an adult signature/OTP is provided.
• Inspection: Patrons are requested to inspect the outer tamper-evident packaging before accepting delivery."
        ],
        [
            'title' => '7. 14-Day Complimentary Return & Exchange Policy',
            'content' => "In keeping with our commitment to absolute patron delight:
• Standard catalog pieces in unworn, pristine condition with all original packaging, tags, and authenticity certificates are eligible for return or exchange within 14 days of delivery.
• Please review our Returns & Exchanges policy page for step-by-step instructions on arranging armored return pickup."
        ],
        [
            'title' => '8. Intellectual Property & Proprietary Designs',
            'content' => 'All jewellery designs, CAD renders, high-resolution photography, brand marks, and editorial narratives displayed on this platform are the exclusive intellectual property of Aura Fine Jewellery. Unauthorized reproduction, imitation, or distribution is strictly prohibited under copyright and trademark statutes.'
        ],
        [
            'title' => '9. Governing Law & Concierge Resolution',
            'content' => "These Terms shall be governed by and construed in accordance with the laws of India. Any legal dispute or controversy arising out of these terms shall be subject to the exclusive jurisdiction of the competent courts in Mumbai & Pune, Maharashtra.
For assistance with terms, orders, or inquiries, please contact our concierge team at care@aurajewellery.com or +91 98765 43210."
        ]
    ];
@endphp

@section('title', 'Terms & Conditions | Aura Fine Jewellery')
@section('meta_description', 'Read the official terms and conditions, certification standards, and purchase agreements of Aura Fine Jewellery.')

@push('cc_styles')
<style>
    .policy-header-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 2rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid #f0ece4;
        font-size: 0.85rem;
        color: #777;
    }
    .policy-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #faf4ea;
        color: #9c7f4c;
        border: 1px solid rgba(156, 127, 76, 0.3);
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.78rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .policy-section-block {
        margin-bottom: 2rem;
        padding-bottom: 1.75rem;
        border-bottom: 1px solid #f9f7f4;
    }
    .policy-section-block:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .policy-section-title {
        font-family: 'Cinzel', serif;
        font-size: 1.15rem;
        color: #1a1814;
        margin-bottom: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.03em;
    }
    .policy-section-body {
        color: #555;
        font-size: 0.95rem;
        line-height: 1.75;
        white-space: pre-line;
    }
    .policy-highlight-card {
        background: linear-gradient(135deg, #faf7f2 0%, #f4ede2 100%);
        border: 1px solid rgba(194, 168, 120, 0.35);
        border-radius: 8px;
        padding: 1.5rem;
        margin: 2rem 0;
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }
    .policy-highlight-card i {
        font-size: 1.8rem;
        color: var(--accent-gold, #c0a062);
        flex-shrink: 0;
        margin-top: 2px;
    }
</style>
@endpush

@section('care_content')
<h2>{{ $terms['title'] ?? 'Terms & Conditions' }}</h2>

<div class="policy-header-meta">
    <span class="policy-badge"><i class="ph-fill ph-file-text"></i> {{ $terms['badge'] ?? 'Official Terms of Service' }}</span>
    <span>Effective Date: {{ $terms['effective_date'] ?? 'September 2026' }}</span>
</div>

<p style="color: #666; font-size: 1.02rem; line-height: 1.8; margin-bottom: 2rem;">
    {{ $terms['intro'] ?? 'Welcome to Aura Fine Jewellery. These terms define our commitment to quality, genuine BIS hallmark certification, and our mutual agreements regarding acquisitions and bespoke commissions.' }}
</p>

<div class="policy-highlight-card">
    <i class="ph ph-certificate"></i>
    <div>
        <strong style="font-family: 'Cinzel', serif; color: #1a1814; font-size: 1.05rem; display: block; margin-bottom: 4px;">{{ $terms['guarantee_title'] ?? 'Authenticity & Purity Guarantee' }}</strong>
        <span style="font-size: 0.92rem; color: #5c4b31; line-height: 1.6;">
            {{ $terms['guarantee_desc'] ?? '100% BIS Hallmarked gold and lab-certified natural diamonds. Every piece is backed by our lifetime cleaning and valuation service.' }}
        </span>
    </div>
</div>

@if(!empty($customContent))
    <div class="policy-section-body" style="margin-top: 1.5rem;">
        {!! nl2br(e($customContent)) !!}
    </div>
@else
    @foreach($sections as $sec)
        <div class="policy-section-block">
            <h3 class="policy-section-title">{{ $sec['title'] ?? '' }}</h3>
            <div class="policy-section-body">{!! nl2br(e($sec['content'] ?? '')) !!}</div>
        </div>
    @endforeach
@endif

@endsection
