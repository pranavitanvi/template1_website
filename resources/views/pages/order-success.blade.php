@extends('layouts.app')

@section('title', 'Order Confirmed | Aura Fine Jewellery')
@section('meta_description', 'Your Aura jewellery order has been confirmed successfully.')
@section('main_style', 'margin-top: 130px; margin-bottom: 5rem;')

@push('styles')
<style>
    .success-wrap {
        max-width: 700px;
        margin: 2rem auto 4rem;
        padding: 0 1.5rem;
        text-align: center;
    }
    .check-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #eaf4e6;
        color: #5a8a4e;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    .order-box {
        background: #fff;
        border-radius: 16px;
        padding: 2.5rem;
        border: 1px solid #f0ece4;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        text-align: left;
        margin-bottom: 2rem;
    }
    .order-meta {
        display: flex;
        justify-content: space-between;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f0ece4;
        margin-bottom: 1.5rem;
    }
    .meta-col .label {
        font-size: 0.75rem;
        color: #aaa;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.4rem;
    }
    .meta-col .val {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1814;
        font-family: 'Cinzel', serif;
    }
    .item-row {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin-bottom: 1rem;
    }
    .item-thumb {
        width: 60px;
        height: 70px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #f0ece4;
    }
    .total-row {
        display: flex;
        justify-content: space-between;
        padding-top: 1.5rem;
        border-top: 1px solid #f0ece4;
        font-family: 'Cinzel', serif;
        font-size: 1.2rem;
        font-weight: 600;
        color: #c0a062;
    }
    .action-btns {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="success-wrap">
    <div class="check-icon">
        <i class="ph-fill ph-check-circle" style="font-size: 3rem;"></i>
    </div>
    <h1 style="font-family: 'Cinzel', serif; font-size: 2.2rem; margin-bottom: 0.5rem;">Order Placed Successfully</h1>
    <p style="color: #7a7068; font-size: 1rem; margin-bottom: 2.5rem;">Thank you for shopping with AURA. Your beautifully crafted pieces will soon be on their way.</p>

    <div class="order-box" id="order-box">
        <p style="text-align: center; color: #888;">Loading order details...</p>
    </div>

    <div class="action-btns">
        <a href="{{ route('home') }}" class="btn btn-secondary">Return Home</a>
        <a href="{{ route('shop') }}" class="btn btn-primary">Continue Shopping</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function fmt(n) { return '&#8377;' + Math.round(n).toLocaleString('en-IN'); }
    
    document.addEventListener("DOMContentLoaded", () => {
        const params = new URLSearchParams(window.location.search);
        const orderId = params.get('order') || '{{ $orderId ?? "" }}';
        
        const orders = JSON.parse(localStorage.getItem('aura_orders') || '[]');
        const order = orders.find(o => o.orderId === orderId) || orders[orders.length - 1];

        if (!order) {
            document.getElementById('order-box').innerHTML = '<p style="text-align:center; color:#888;">Order confirmation generated. Check your email for full delivery dispatch details.</p>';
            return;
        }

        const dateStr = new Date(order.timestamp || Date.now()).toLocaleDateString('en-IN', { day: 'numeric', month: 'long', year: 'numeric' });

        let itemsHtml = '';
        (order.items || []).forEach(item => {
            itemsHtml += `
            <div class="item-row">
                <img src="${item.image}" alt="${item.name}" class="item-thumb" onerror="this.onerror=null; this.src='{{ asset('assets/images/placeholders/default.jpg') }}';">
                <div style="flex:1;">
                    <div style="font-family:'Cinzel',serif; font-size:0.95rem; font-weight:600; color:#1a1814;">${item.name}</div>
                    <div style="font-size:0.8rem; color:#888;">Qty: ${item.quantity}</div>
                </div>
                <div style="font-weight:600; color:#1a1814; font-size:0.95rem;">${fmt(item.price * item.quantity)}</div>
            </div>`;
        });

        document.getElementById('order-box').innerHTML = `
            <div class="order-meta">
                <div class="meta-col">
                    <div class="label">Order Number</div>
                    <div class="val">${order.orderId}</div>
                </div>
                <div class="meta-col" style="text-align:right;">
                    <div class="label">Order Date</div>
                    <div class="val">${dateStr}</div>
                </div>
            </div>
            <div style="margin-bottom: 1.5rem;">
                ${itemsHtml}
            </div>
            <div class="total-row">
                <span>Total Paid</span>
                <span>${fmt(order.total || 0)}</span>
            </div>
        `;
    });
</script>
@endpush
