@extends('layouts.app')

@section('title', 'My Account | Aura Fine Jewellery')
@section('meta_description', 'Manage your personal Aura fine jewellery account, saved addresses, orders, and rewards.')
@section('main_style', 'margin-top: 110px; min-height: 70vh; background-color: var(--bg-secondary); padding: 3rem 1rem;')

@section('content')
<div class="container" style="max-width: 900px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: gap; gap: 1rem;">
        <div>
            <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.2em; color: #c0a062; font-weight: 600;">MY ACCOUNT</span>
            <h1 style="font-family: 'Cinzel', serif; font-size: 2.2rem; color: #1a1a1a; margin-top: 0.2rem; margin-bottom: 0;">Hello, {{ $customer['name'] ?? 'Valued Customer' }}</h1>
            <p style="color: var(--text-secondary); margin: 0; font-size: 0.95rem;">Customer ID: <span style="font-family: monospace; font-weight: 600; color: #1a1a1a;">{{ $customer['customer_id'] ?? 'C-00000' }}</span></p>
        </div>
        <form method="POST" action="{{ route('logout') }}" onsubmit="localStorage.removeItem('jewellery_cart'); localStorage.removeItem('jewellery_wishlist'); localStorage.removeItem('aura_cart_session_id'); localStorage.removeItem('aura_user');">
            @csrf
            <button type="submit" class="btn" style="padding: 0.65rem 1.4rem; font-size: 0.85rem; border: 1px solid #ddd; background: #fff; border-radius: 6px; cursor: pointer; color: #666; font-weight: 600;">
                Sign Out
            </button>
        </form>
    </div>

    <!-- Overview Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: #fff; padding: 1.8rem; border-radius: 10px; border: 1px solid #eee; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
            <div style="font-size: 0.8rem; text-transform: uppercase; color: #888; font-weight: 600; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Loyalty Rewards</div>
            <div style="font-size: 2rem; font-family: 'Cinzel', serif; color: #c0a062; font-weight: 700;">{{ $customer['loyalty_points'] ?? 0 }} pts</div>
            <div style="font-size: 0.85rem; color: #999; margin-top: 0.3rem;">Earn points on all fine jewellery purchases</div>
        </div>

        <div style="background: #fff; padding: 1.8rem; border-radius: 10px; border: 1px solid #eee; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
            <div style="font-size: 0.8rem; text-transform: uppercase; color: #888; font-weight: 600; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Registered Contact</div>
            <div style="font-size: 1.1rem; font-weight: 600; color: #1a1a1a;">{{ $customer['phone'] ?? '-' }}</div>
            <div style="font-size: 0.9rem; color: #666; margin-top: 0.2rem;">{{ $customer['email'] ?? 'No email provided' }}</div>
        </div>

        <div style="background: #fff; padding: 1.8rem; border-radius: 10px; border: 1px solid #eee; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
            <div style="font-size: 0.8rem; text-transform: uppercase; color: #888; font-weight: 600; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Default City</div>
            <div style="font-size: 1.1rem; font-weight: 600; color: #1a1a1a;">{{ $customer['city'] ?? 'Mumbai' }}</div>
            <div style="font-size: 0.9rem; color: #666; margin-top: 0.2rem;">PIN: {{ $customer['pin_code'] ?? '-' }}</div>
        </div>
    </div>

    <!-- Quick Navigation Tabs / Links -->
    <div style="background: #fff; padding: 2rem; border-radius: 10px; border: 1px solid #eee; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
        <h3 style="font-family: 'Cinzel', serif; font-size: 1.3rem; margin-bottom: 1rem; border-bottom: 1px solid #f0ece4; padding-bottom: 0.8rem;">Exclusive Member Privileges</h3>
        <p style="color: #666; font-size: 0.95rem; line-height: 1.6;">
            As an enrolled Aura Member, your transactions, custom commissions, and saving schemes are backed directly by 
            our certified ERP inventory. Book a private consultation or browse the collection to add to your wishlist.
        </p>
        <div style="display: flex; gap: 1rem; margin-top: 1.5rem; flex-wrap: wrap;">
            <a href="{{ route('shop') }}" class="btn btn-primary" style="padding: 0.85rem 1.8rem; font-family: 'Cinzel', serif; letter-spacing: 0.08em; background: #c0a062; border: 1px solid #c0a062; color: #fff; border-radius: 6px; text-decoration: none; font-size: 0.9rem;">
                BROWSE COLLECTION
            </a>
            <a href="{{ route('contact') }}" class="btn" style="padding: 0.85rem 1.8rem; font-family: 'Cinzel', serif; letter-spacing: 0.08em; background: #fff; border: 1px solid #c0a062; color: #c0a062; border-radius: 6px; text-decoration: none; font-size: 0.9rem;">
                BOOK CONSULTATION
            </a>
            <a href="{{ route('wishlist') }}" class="btn" style="padding: 0.85rem 1.8rem; font-family: 'Cinzel', serif; letter-spacing: 0.08em; background: #fff; border: 1px solid #ddd; color: #444; border-radius: 6px; text-decoration: none; font-size: 0.9rem;">
                MY WISHLIST
            </a>
        </div>
    </div>
</div>
@endsection
