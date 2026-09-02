@extends('layouts.customer-care')

@section('title', 'Shipping & Delivery | Aura Fine Jewellery')
@section('meta_description', 'Learn about complimentary insured shipping, dispatch timelines, and tamper-evident packaging at Aura.')

@section('care_content')
<h2>Shipping & Delivery</h2>
<div class="ship-grid">
    <div class="ship-card">
        <i class="ph ph-truck"></i>
        <h3>Complimentary Shipping</h3>
        <p>We offer complimentary fully-insured shipping on all orders within India. Your piece is in safe hands from our atelier to your doorstep.</p>
    </div>
    <div class="ship-card">
        <i class="ph ph-clock"></i>
        <h3>Delivery Timelines</h3>
        <p>In-stock items are dispatched within 2 business days. Made-to-order masterpieces typically require 15-20 business days for meticulous crafting before dispatch.</p>
    </div>
    <div class="ship-card">
        <i class="ph ph-package"></i>
        <h3>Secure Packaging</h3>
        <p>Your jewellery arrives in our signature Aura presentation box, securely enclosed in a tamper-evident outer box to ensure complete safety during transit.</p>
    </div>
    <div class="ship-card">
        <i class="ph ph-map-pin"></i>
        <h3>Order Tracking</h3>
        <p>Upon dispatch, you will receive a tracking link via email and SMS. All shipments require an adult signature upon delivery for absolute security.</p>
    </div>
</div>
@endsection
