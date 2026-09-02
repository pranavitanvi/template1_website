@extends('layouts.app')

@section('title', 'Sign In | Aura Fine Jewellery')
@section('meta_description', 'Sign in to access your personal Aura fine jewellery account, saved orders, and wishlist.')
@section('main_style', 'margin-top: 110px; min-height: 70vh; display: flex; align-items: center; justify-content: center; background-color: var(--bg-secondary); padding: 4rem 1rem;')

@section('content')
<div style="background: var(--white); padding: 3rem 2.5rem; width: 100%; max-width: 480px; box-shadow: 0 10px 40px rgba(0,0,0,0.06); text-align: center; border-radius: 12px; border: 1px solid #f0ece4;">
    <div style="margin-bottom: 0.5rem; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.2em; color: #c0a062; font-weight: 600;">CUSTOMER PORTAL</div>
    <h2 style="margin-bottom: 0.5rem; font-family: 'Cinzel', serif; font-size: 2rem; color: #1a1a1a;">Welcome Back</h2>
    <p style="margin-bottom: 2rem; color: var(--text-secondary); font-size: 0.95rem;">Sign in to access your bespoke orders, wishlist, and rewards.</p>
    
    <div id="loginAlert" style="display: none; padding: 0.85rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem; text-align: left;"></div>

    @if(session('error'))
        <div style="background: #fdf2f2; color: #b91c1c; border: 1px solid #fecaca; padding: 0.85rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem; text-align: left;">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div style="background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; padding: 0.85rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem; text-align: left;">
            {{ session('success') }}
        </div>
    @endif

    <form id="loginForm" method="POST" action="{{ route('login.submit') }}" style="text-align: left;" onsubmit="handleCustomerLogin(event)">
        @csrf
        <div class="form-group" style="margin-bottom: 1.2rem;">
            <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #666; margin-bottom: 6px; font-weight: 600;">Email or Mobile Number</label>
            <input type="text" name="login" id="loginIdentifier" class="input-field" placeholder="e.g. priya@example.com or 9876543210" required 
                   style="width: 100%; padding: 0.85rem 1rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; outline: none; transition: border-color 0.2s;"
                   onfocus="this.style.borderColor='#c0a062'" onblur="this.style.borderColor='#ddd'">
        </div>
        
        <div class="form-group" style="margin-bottom: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <label style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #666; font-weight: 600;">Password</label>
                <a href="{{ route('contact') }}" style="font-size: 0.8rem; color: #888; text-decoration: none;">Forgot Password?</a>
            </div>
            <div style="position: relative;">
                <input type="password" name="password" id="loginPassword" class="input-field" placeholder="Enter your account password" required 
                       style="width: 100%; padding: 0.85rem 2.8rem 0.85rem 1rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; outline: none; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='#c0a062'" onblur="this.style.borderColor='#ddd'">
                <button type="button" onclick="togglePass('loginPassword', this)" 
                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #888; padding: 5px;">
                    👁️
                </button>
            </div>
        </div>

        <button type="submit" id="loginSubmitBtn" class="btn btn-primary" 
                style="width: 100%; margin-top: 1rem; margin-bottom: 1.5rem; padding: 1rem; font-family: 'Cinzel', serif; letter-spacing: 0.12em; background: #c0a062; border: 1px solid #c0a062; color: #fff; border-radius: 6px; cursor: pointer; font-size: 0.95rem; transition: background 0.3s;">
            SIGN IN
        </button>

        <div class="text-center" style="font-size: 0.9rem; color: #666;">
            New to Aura? <a href="{{ route('register') }}" style="font-weight: 600; text-decoration: none; color: #c0a062;">Create an Account</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function togglePass(id, btn) {
    var input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerText = '🙈';
    } else {
        input.type = 'password';
        btn.innerText = '👁️';
    }
}

function handleCustomerLogin(e) {
    e.preventDefault();
    var form = document.getElementById('loginForm');
    var btn = document.getElementById('loginSubmitBtn');
    var alertBox = document.getElementById('loginAlert');
    var loginVal = document.getElementById('loginIdentifier').value.trim();
    var passwordVal = document.getElementById('loginPassword').value;

    alertBox.style.display = 'none';
    btn.disabled = true;
    btn.innerText = 'AUTHENTICATING...';

    var formData = new FormData(form);

    fetch("{{ route('login.submit') }}", {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(function(res) {
        return res.json().then(function(data) {
            return { status: res.status, data: data };
        });
    })
    .then(function(resObj) {
        btn.disabled = false;
        btn.innerText = 'SIGN IN';

        if (resObj.data.success) {
            alertBox.style.display = 'block';
            alertBox.style.background = '#f0fdf4';
            alertBox.style.color = '#15803d';
            alertBox.style.border = '1px solid #bbf7d0';
            alertBox.innerText = resObj.data.message || 'Login successful! Redirecting...';

            if (resObj.data.customer) {
                localStorage.setItem("aura_user", JSON.stringify({
                    id: resObj.data.customer.id,
                    name: resObj.data.customer.name,
                    email: resObj.data.customer.email,
                    phone: resObj.data.customer.phone,
                    loggedIn: true
                }));
            }

            setTimeout(function() {
                var urlParams = new URLSearchParams(window.location.search);
                var redirect = urlParams.get("redirect") || resObj.data.redirect || "{{ route('home') }}";
                window.location.href = redirect;
            }, 800);
        } else {
            alertBox.style.display = 'block';
            alertBox.style.background = '#fdf2f2';
            alertBox.style.color = '#b91c1c';
            alertBox.style.border = '1px solid #fecaca';
            alertBox.innerText = resObj.data.message || 'Invalid credentials. Please try again.';
        }
    })
    .catch(function(err) {
        btn.disabled = false;
        btn.innerText = 'SIGN IN';
        alertBox.style.display = 'block';
        alertBox.style.background = '#fdf2f2';
        alertBox.style.color = '#b91c1c';
        alertBox.style.border = '1px solid #fecaca';
        alertBox.innerText = 'Unable to connect to authentication server. Please try again.';
    });
}
</script>
@endpush
