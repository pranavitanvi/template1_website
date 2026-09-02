@extends('layouts.customer-care')

@section('title', 'Returns & Exchanges | Aura Fine Jewellery')
@section('meta_description', 'Experience our complimentary hassle-free 14-day return and exchange policy.')

@section('care_content')
<h2>Returns & Exchanges</h2>
<p style="color: #666; margin-bottom: 2.5rem; line-height: 1.7;">
    We want you to adore your jewellery. If for any reason you are not completely enchanted with your purchase, we offer a complimentary 14-day return and exchange window.
</p>

<div class="ret-steps">
    <div class="ret-step">
        <div class="ret-step-num">1</div>
        <div class="ret-step-content">
            <h3 style="font-family: 'Cinzel', serif; font-size: 1.15rem; margin-bottom: 0.4rem;">Initiate Request</h3>
            <p style="color: #666; font-size: 0.95rem; line-height: 1.6;">Contact our concierge team at <a href="mailto:care@aurajewellery.com" style="color: #c0a062;">care@aurajewellery.com</a> or call us within 14 days of delivery to request a return authorization.</p>
        </div>
    </div>
    <div class="ret-step">
        <div class="ret-step-num">2</div>
        <div class="ret-step-content">
            <h3 style="font-family: 'Cinzel', serif; font-size: 1.15rem; margin-bottom: 0.4rem;">Secure Packaging</h3>
            <p style="color: #666; font-size: 0.95rem; line-height: 1.6;">Place the unworn item back in its original presentation box, along with all certificates of authenticity, packaging, and invoices.</p>
        </div>
    </div>
    <div class="ret-step">
        <div class="ret-step-num">3</div>
        <div class="ret-step-content">
            <h3 style="font-family: 'Cinzel', serif; font-size: 1.15rem; margin-bottom: 0.4rem;">Complimentary Pickup</h3>
            <p style="color: #666; font-size: 0.95rem; line-height: 1.6;">We arrange an armored, fully-insured courier pickup from your address at zero additional cost to you.</p>
        </div>
    </div>
</div>

<p style="margin-top: 3rem; font-size: 0.85rem; color: #888; border-top: 1px solid #f0ece4; padding-top: 1.5rem;">
    *Please note: Custom-crafted, bespoke orders or personalized engraved pieces are final sale and cannot be returned or refunded.
</p>
@endsection
