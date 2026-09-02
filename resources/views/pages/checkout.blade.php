@extends('layouts.app')

@section('title', 'Secure Checkout | Aura Fine Jewellery')
@section('meta_description', 'Complete your fine jewellery order safely with secure encrypted checkout.')
@section('main_style', 'margin-top: 110px;')

@push('styles')
<style>
    .co-layout {
        max-width: 1180px;
        margin: 0 auto;
        padding: 2rem 1.5rem 5rem;
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 3rem;
        align-items: start;
    }
    .co-steps {
        display: flex;
        align-items: center;
        margin-bottom: 2.5rem;
        font-size: 0.78rem;
        font-weight: 500;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }
    .co-step {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #bbb;
    }
    .co-step.active { color: #c0a062; font-weight: 600; }
    .co-step-num {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 1.5px solid currentColor;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .co-step-divider {
        width: 30px;
        height: 1px;
        background: #ddd;
        margin: 0 8px;
    }
    .co-section {
        background: #fff;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        border: 1px solid #f0ece4;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .co-section-title {
        font-family: 'Cinzel', serif;
        font-size: 1.05rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        color: #1a1814;
        margin-bottom: 1.5rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid #f0ece4;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .co-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .co-form-row.full { grid-template-columns: 1fr; }
    .co-form-row.three { grid-template-columns: 1fr 1fr 1fr; }
    .co-field label {
        display: block;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #888;
        margin-bottom: 6px;
    }
    .co-field input, .co-field select {
        width: 100%;
        padding: 0.85rem 1rem;
        border: 1.5px solid #e8e2d8;
        border-radius: 10px;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.9rem;
        color: #2c2a29;
        background: #faf8f4;
        outline: none;
        transition: all 0.25s ease;
    }
    .co-field input:focus, .co-field select:focus {
        border-color: #c0a062;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(192, 160, 98, 0.15);
    }
    .co-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-size: 0.85rem;
        color: #666;
        margin-top: 0.8rem;
    }
    .co-payment-opts {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }
    .co-pay-opt {
        border: 1.5px solid #e8e2d8;
        border-radius: 10px;
        padding: 1rem 1.2rem;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        background: #faf8f4;
        transition: all 0.2s;
    }
    .co-pay-opt:hover { border-color: #c0a062; }
    .co-pay-opt-label { flex: 1; font-weight: 500; font-size: 0.92rem; }
    .co-card-icon {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 2px 6px;
        background: #fff;
    }
    .co-card-detail {
        background: #faf8f4;
        border: 1.5px solid #e8e2d8;
        border-top: none;
        border-radius: 0 0 10px 10px;
        padding: 1.2rem;
        margin-top: -0.8rem;
        margin-bottom: 0.5rem;
    }
    .co-summary-card {
        background: #fff;
        border-radius: 16px;
        padding: 2rem;
        border: 1px solid #f0ece4;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        position: sticky;
        top: 130px;
    }
    .co-summary-title {
        font-family: 'Cinzel', serif;
        font-size: 1.15rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid #f0ece4;
    }
    .co-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
        color: #666;
    }
    .co-total-row.grand {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1a1814;
        border-top: 1px solid #f0ece4;
        padding-top: 1rem;
        margin-top: 0.5rem;
    }
    .badge-free {
        background: #eaf4e6;
        color: #5a8a4e;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
    }
    .co-trust {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1.2rem;
        border-top: 1px solid #f0ece4;
    }
    .co-trust-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        font-size: 0.68rem;
        color: #888;
        text-align: center;
        font-weight: 600;
        text-transform: uppercase;
    }
    .co-submit-btn {
        background: #2a2422;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 1.1rem 2rem;
        font-family: 'Cinzel', serif;
        font-size: 1rem;
        letter-spacing: 0.08em;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    .co-submit-btn:hover { background: #c0a062; }
    @media (max-width: 900px) {
        .co-layout { grid-template-columns: 1fr; }
        .co-summary-card { position: static; }
    }
</style>
@endpush

@section('content')
<main class="co-layout">
    <!-- LEFT: CHECKOUT FORM -->
    <div class="co-form-side">
        <a href="{{ route('cart') }}" style="color: #c0a062; font-size: 0.85rem; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 1.5rem;">
            <i class="ph ph-arrow-left"></i> Return to Bag
        </a>

        <!-- Steps -->
        <div class="co-steps">
            <div class="co-step active"><div class="co-step-num">1</div> Information</div>
            <div class="co-step-divider"></div>
            <div class="co-step active"><div class="co-step-num">2</div> Shipping</div>
            <div class="co-step-divider"></div>
            <div class="co-step active"><div class="co-step-num">3</div> Payment</div>
        </div>

        <form id="checkout-form" onsubmit="placeOrder(event)">
            <!-- Contact Info -->
            <div class="co-section">
                <div class="co-section-title">
                    <i class="ph ph-user-circle" style="color: #c0a062; font-size: 1.2rem;"></i> Contact Information
                </div>
                <div class="co-form-row full">
                    <div class="co-field">
                        <label>Email Address</label>
                        <input type="email" id="co-email" placeholder="you@example.com" required>
                    </div>
                </div>
                <label class="co-checkbox">
                    <input type="checkbox" id="co-newsletter" checked>
                    Keep me updated with exclusive offers and new collections
                </label>
            </div>

            <!-- Shipping Address -->
            <div class="co-section">
                <div class="co-section-title">
                    <i class="ph ph-map-pin" style="color: #c0a062; font-size: 1.2rem;"></i> Shipping Address
                </div>

                <div class="co-form-row">
                    <div class="co-field">
                        <label>First Name</label>
                        <input type="text" id="co-fname" placeholder="Priya" required>
                    </div>
                    <div class="co-field">
                        <label>Last Name</label>
                        <input type="text" id="co-lname" placeholder="Sharma" required>
                    </div>
                </div>

                <div class="co-form-row full">
                    <div class="co-field">
                        <label>Address</label>
                        <input type="text" id="co-addr" placeholder="Flat / House No., Building, Street" required>
                    </div>
                </div>

                <div class="co-form-row three">
                    <div class="co-field">
                        <label>City</label>
                        <input type="text" id="co-city" placeholder="Mumbai" required>
                    </div>
                    <div class="co-field">
                        <label>State</label>
                        <select id="co-state" required>
                            <option value="">Select State</option>
                            <option selected>Maharashtra</option>
                            <option>Delhi</option>
                            <option>Karnataka</option>
                            <option>Tamil Nadu</option>
                            <option>Gujarat</option>
                            <option>Rajasthan</option>
                            <option>West Bengal</option>
                            <option>Telangana</option>
                            <option>Uttar Pradesh</option>
                        </select>
                    </div>
                    <div class="co-field">
                        <label>PIN Code</label>
                        <input type="text" id="co-pin" placeholder="400001" required maxlength="6">
                    </div>
                </div>

                <div class="co-form-row full" style="margin-top: 1rem;">
                    <div class="co-field">
                        <label>Phone Number</label>
                        <input type="tel" id="co-phone" placeholder="+91 98765 43210" required>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="co-section">
                <div class="co-section-title">
                    <i class="ph ph-credit-card" style="color: #c0a062; font-size: 1.2rem;"></i> Payment Method
                </div>

                <div class="co-payment-opts">
                    <!-- Credit / Debit Card -->
                    <label class="co-pay-opt" onclick="togglePaymentTab('card')">
                        <input type="radio" name="payment" value="card" checked>
                        <span class="co-pay-opt-label">Credit / Debit Card</span>
                        <span class="co-card-icon" style="color:#1a1f71">VISA / MC</span>
                    </label>
                    <div class="co-card-detail" id="card-form">
                        <div class="co-form-row full">
                            <div class="co-field">
                                <label>Card Number</label>
                                <input type="text" placeholder="•••• •••• •••• ••••" maxlength="19">
                            </div>
                        </div>
                        <div class="co-form-row">
                            <div class="co-field">
                                <label>Expiry Date</label>
                                <input type="text" placeholder="MM / YY" maxlength="5">
                            </div>
                            <div class="co-field">
                                <label>CVV</label>
                                <input type="password" placeholder="•••" maxlength="4">
                            </div>
                        </div>
                    </div>

                    <!-- UPI -->
                    <label class="co-pay-opt" onclick="togglePaymentTab('upi')">
                        <input type="radio" name="payment" value="upi">
                        <span class="co-pay-opt-label">UPI / QR (Google Pay, PhonePe, Paytm)</span>
                        <span class="co-card-icon" style="color:#5f259f">UPI</span>
                    </label>
                    <div class="co-card-detail" id="upi-form" style="display:none;">
                        <div class="co-field">
                            <label>UPI ID (VPA)</label>
                            <input type="text" placeholder="mobile@upi">
                        </div>
                    </div>

                    <!-- Cash on Delivery -->
                    <label class="co-pay-opt" onclick="togglePaymentTab('cod')">
                        <input type="radio" name="payment" value="cod">
                        <span class="co-pay-opt-label">Cash on Delivery</span>
                        <span style="font-size:0.72rem; color:#5a8a4e; font-weight:600">Available</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 2rem;">
                <button type="submit" class="co-submit-btn">
                    Place Order &nbsp;&rarr;
                </button>
            </div>
        </form>
    </div>

    <!-- RIGHT: ORDER SUMMARY -->
    <aside class="co-summary">
        <div class="co-summary-card">
            <div class="co-summary-title">Order Summary</div>

            <div id="checkout-items" style="max-height: 280px; overflow-y: auto; margin-bottom: 1.5rem;">
                <!-- Loaded dynamically -->
            </div>

            <div class="co-totals">
                <div class="co-total-row">
                    <span>Subtotal</span>
                    <span id="co-subtotal">&#8377;0</span>
                </div>
                <div class="co-total-row">
                    <span>Shipping</span>
                    <span class="badge-free">FREE</span>
                </div>
                <div class="co-total-row">
                    <span>Tax (GST 3%)</span>
                    <span id="co-tax">&#8377;0</span>
                </div>
                <div class="co-total-row grand">
                    <span>Total</span>
                    <span id="co-total" style="color: #c0a062;">&#8377;0</span>
                </div>
            </div>

            <div class="co-trust">
                <div class="co-trust-item"><i class="ph ph-shield-check" style="font-size: 1.3rem; color: #c0a062;"></i> Secure</div>
                <div class="co-trust-item"><i class="ph ph-truck" style="font-size: 1.3rem; color: #c0a062;"></i> Insured</div>
                <div class="co-trust-item"><i class="ph ph-arrow-counter-clockwise" style="font-size: 1.3rem; color: #c0a062;"></i> 15-Day Return</div>
            </div>
        </div>
    </aside>
</main>
@endsection

@push('scripts')
<script>
    function fmt(n) { return '&#8377;' + Math.round(n).toLocaleString("en-IN"); }

    function togglePaymentTab(type) {
        document.getElementById("card-form").style.display = type === 'card' ? 'block' : 'none';
        document.getElementById("upi-form").style.display = type === 'upi' ? 'block' : 'none';
    }

    function renderOrderSummary() {
        const cart = JSON.parse(localStorage.getItem("jewellery_cart") || "[]");
        const listContainer = document.getElementById("checkout-items");
        if (!listContainer) return;

        if (cart.length === 0) {
            listContainer.innerHTML = '<p style="color:#888; font-size:0.9rem;">Your bag is empty.</p>';
            return;
        }

        let html = "";
        let total = 0;
        cart.forEach(item => {
            total += item.price * item.quantity;
            html += `
            <div style="display:flex; gap:1rem; margin-bottom:1rem; align-items:center;">
                <div style="position:relative;">
                    <img src="${item.image}" alt="${item.name}" style="width:60px; height:60px; object-fit:cover; border-radius:8px; border:1px solid #f0ece4;" onerror="this.onerror=null; this.src='{{ asset('assets/images/placeholders/default.jpg') }}';">
                    <div style="position:absolute; top:-6px; right:-6px; background:#c0a062; color:#fff; width:20px; height:20px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.7rem; font-weight:600;">${item.quantity}</div>
                </div>
                <div style="flex:1;">
                    <div style="font-family:'Cinzel',serif; font-size:0.9rem; font-weight:600; color:#1a1814;">${item.name}</div>
                    <div style="font-size:0.75rem; color:#888; text-transform:uppercase;">${item.category || ""}</div>
                </div>
                <div style="font-weight:600; font-size:0.9rem;">${fmt(item.price * item.quantity)}</div>
            </div>`;
        });
        listContainer.innerHTML = html;

        const tax = Math.round(total * 0.03);
        document.getElementById("co-subtotal").innerHTML = fmt(total);
        document.getElementById("co-tax").innerHTML = fmt(tax);
        document.getElementById("co-total").innerHTML = fmt(total + tax);
    }

    function placeOrder(e) {
        e.preventDefault();
        const cart = JSON.parse(localStorage.getItem("jewellery_cart") || "[]");
        if (cart.length === 0) {
            alert("Your shopping bag is empty!");
            return;
        }

        let total = 0;
        cart.forEach(item => total += item.price * item.quantity);
        const tax = Math.round(total * 0.03);
        const grandTotal = total + tax;

        const orderId = "AURA-" + new Date().toISOString().replace(/\D/g, "").substring(0,8) + "-" + Math.floor(Math.random()*1000).toString().padStart(3,"0");
        
        const order = {
            orderId: orderId,
            timestamp: Date.now(),
            items: cart,
            total: grandTotal,
            subtotal: total,
            tax: tax,
            customer: {
                name: document.getElementById("co-fname").value + " " + document.getElementById("co-lname").value,
                email: document.getElementById("co-email").value,
                phone: document.getElementById("co-phone").value,
                city: document.getElementById("co-city").value
            }
        };

        const orders = JSON.parse(localStorage.getItem("aura_orders") || "[]");
        orders.push(order);
        localStorage.setItem("aura_orders", JSON.stringify(orders));

        // Clear cart
        localStorage.removeItem("jewellery_cart");

        // Processing overlay
        const overlay = document.createElement("div");
        overlay.style.cssText = "position:fixed; inset:0; background:rgba(255,255,255,0.95); z-index:9999; display:flex; flex-direction:column; align-items:center; justify-content:center; backdrop-filter:blur(5px);";
        overlay.innerHTML = `
            <i class="ph-fill ph-check-circle" style="font-size: 5rem; color: #5a8a4e; margin-bottom: 1.5rem;"></i>
            <h2 style="font-family:'Cinzel',serif; font-size:2rem; color:#1a1814; font-weight:500; margin-bottom:0.5rem;">Processing Order...</h2>
            <p style="color: #666;">Thank you for shopping with AURA.</p>
        `;
        document.body.appendChild(overlay);

        setTimeout(() => {
            window.location.href = "{{ route('order-success') }}?order=" + orderId;
        }, 1200);
    }

    document.addEventListener("DOMContentLoaded", renderOrderSummary);
</script>
@endpush
