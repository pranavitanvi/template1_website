@extends('layouts.customer-care')

@php
    $privacy = $careData['privacy_policy'] ?? $careData['privacy'] ?? [];
    $customContent = $privacy['custom_content'] ?? ($privacy['content'] ?? null);
    $sections = !empty($privacy['sections']) ? $privacy['sections'] : [
        [
            'title' => '1. Commitment to Privacy & Discretion',
            'content' => 'At Aura Fine Jewellery, we recognize that acquiring fine jewellery is an intensely personal and cherished experience. We are steadfastly dedicated to safeguarding the confidentiality, privacy, and security of every patron. This Privacy Policy details how we collect, handle, safeguard, and respect your personal information across all digital and in-store touchpoints.'
        ],
        [
            'title' => '2. Information We Collect',
            'content' => "To deliver bespoke craftsmanship and seamless concierge service, we may collect the following categories of information:
• Personal Details: Full legal name, contact telephone number, billing and secure delivery addresses, and email address.
• Transaction & Order Records: Purchased pieces, metal karatage, diamond carat weight, ring sizes, bespoke engraving instructions, and certified invoices.
• Consultation Details: Preferred metal tones, appointment schedules, and bespoke design briefs provided during virtual or in-store styling sessions.
• Technical & Device Identifiers: IP address, device type, browser specifications, and encrypted session cookies to provide optimal browsing speed and security."
        ],
        [
            'title' => '3. Purpose & Use of Collected Data',
            'content' => "Your information is utilized solely for legitimate operational and luxury service objectives:
• Order Processing & Fulfillment: Precision crafting, quality control, BIS hallmarking, and armored transit dispatch.
• Order Notifications: Timely dispatch alerts, tracking credentials, and delivery signature confirmations.
• Bespoke Concierge Care: Assisting with sizing adjustments, valuation certificates, and lifetime cleaning consultations.
• Regulatory & Legal Compliance: Fulfilling statutory jewellery trade regulations, taxation audits, and high-value transaction reporting standards."
        ],
        [
            'title' => '4. Zero Sale of Personal Data',
            'content' => 'We maintain a strict and uncompromising policy: Aura Fine Jewellery will NEVER sell, lease, rent, or trade your personal information to any third-party marketers or advertisers under any circumstances. Your patronage and trust are sacred to our brand heritage.'
        ],
        [
            'title' => '5. Security & Armored Transit Safeguards',
            'content' => "We employ defense-grade 256-bit SSL encryption across our entire digital infrastructure. All payment transactions are processed through tokenized, PCI-DSS compliant payment gateways—no payment card PINs or full credentials are ever stored on our servers.
Physical deliveries are handled exclusively through specialized armored and fully-insured luxury courier partners, requiring verified adult OTP and signature verification upon handover."
        ],
        [
            'title' => '6. Cookies & Digital Experience',
            'content' => 'We utilize essential session cookies to maintain your shopping bag, preserve your wishlist pieces, and ensure secure authentication. You may modify your browser settings to restrict cookies at any time, though some interactive features of our online catalog may be limited.'
        ],
        [
            'title' => '7. Your Rights & Data Autonomy',
            'content' => "You retain full authority over your personal information. At any point, you may:
• Request an itemized extract of your personal and transaction data on record.
• Request correction or updates to your contact credentials.
• Request permanent deletion of non-statutory account data.
• Opt out of promotional previews and seasonal catalog mailers with a single click."
        ],
        [
            'title' => '8. Privacy Concierge & Grievance Officer',
            'content' => "If you have questions, privacy requests, or wish to exercise your data rights, please connect with our dedicated Privacy Concierge:
• Email: care@aurajewellery.com
• Concierge Hotline: +91 98765 43210 (Monday - Saturday, 10:00 AM - 7:00 PM IST)
• Atelier Headquarters: Aura Fine Jewellery Flagship, Mumbai & Pune, Maharashtra, India."
        ]
    ];
@endphp

@section('title', 'Privacy Policy | Aura Fine Jewellery')
@section('meta_description', 'Learn about our rigorous privacy standards, zero data-sale policy, and secure transit protections for fine jewellery patrons.')

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
<h2>{{ $privacy['title'] ?? 'Privacy Policy' }}</h2>

<div class="policy-header-meta">
    <span class="policy-badge"><i class="ph-fill ph-shield-check"></i> {{ $privacy['badge'] ?? 'Certified Privacy Standards' }}</span>
    <span>Effective Date: {{ $privacy['effective_date'] ?? 'September 2026' }}</span>
</div>

<p style="color: #666; font-size: 1.02rem; line-height: 1.8; margin-bottom: 2rem;">
    {{ $privacy['intro'] ?? 'Your trust is the foundation of our craft. We handle your personal and financial information with the same uncompromising precision and discretion that goes into every piece of fine jewellery we create.' }}
</p>

<div class="policy-highlight-card">
    <i class="ph ph-lock-key"></i>
    <div>
        <strong style="font-family: 'Cinzel', serif; color: #1a1814; font-size: 1.05rem; display: block; margin-bottom: 4px;">{{ $privacy['guarantee_title'] ?? 'Patron Privacy Guarantee' }}</strong>
        <span style="font-size: 0.92rem; color: #5c4b31; line-height: 1.6;">
            {{ $privacy['guarantee_desc'] ?? 'We strictly do not share, sell, or monetize patron information. Every interaction, appointment, and acquisition is protected by end-to-end 256-bit encryption and strict non-disclosure protocols.' }}
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
