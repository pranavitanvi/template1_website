@extends('layouts.app')

@php
    $brandName = $cmsHeader['brand_name'] ?? ($cmsHeader['store_name'] ?? 'Aura');
    $brandFull = $cmsHeader['brand_name'] ?? ($cmsHeader['store_name'] ?? 'Aura Fine Jewellery');
@endphp

@section('title', 'Sign In | ' . $brandFull)
@section('meta_description', 'Sign in to access your personal ' . $brandName . ' fine jewellery account, saved orders, and wishlist.')
@section('main_style', 'margin-top: 110px; min-height: 70vh; display: flex; align-items: center; justify-content: center; background-color: var(--bg-secondary); padding: 4rem 1rem;')

@section('content')
<div style="background: var(--white); padding: 3rem 2.5rem; width: 100%; max-width: 480px; box-shadow: 0 10px 40px rgba(0,0,0,0.06); text-align: center; border-radius: 12px; border: 1px solid #f0ece4;">
    <div style="margin-bottom: 0.5rem; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.2em; color: #c0a062; font-weight: 600;">CUSTOMER PORTAL</div>
    <h2 style="margin-bottom: 0.5rem; font-family: 'Cinzel', serif; font-size: 2rem; color: #1a1a1a;">Welcome Back</h2>
    <p style="margin-bottom: 2rem; color: var(--text-secondary); font-size: 0.95rem;">Sign in to access your bespoke orders, wishlist, and rewards.</p>
    
    <div id="loginAlert" style="display: none; padding: 0.85rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem; text-align: left;"></div>

    @if(request('redirect'))
        <div style="background: #faf6ee; color: #855b14; border: 1px solid #eedec3; padding: 0.85rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem; text-align: left; display: flex; align-items: center; gap: 10px;">
            <i class="ph ph-lock-key" style="font-size: 1.25rem; flex-shrink: 0; color: #c0a062;"></i>
            <span>Please sign in to add pieces to your shopping bag.</span>
        </div>
    @endif

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
        <input type="hidden" name="redirect" value="{{ request('redirect') }}">
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

        <!-- Security Captcha Verification -->
        <div class="form-group" style="margin-bottom: 1.2rem;">
            <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #666; margin-bottom: 6px; font-weight: 600;">
                Security Captcha <span style="color: red;">*</span>
            </label>
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <div style="border-radius: 6px; overflow: hidden; border: 1px solid #ddd; background: #faf7f2; display: flex; align-items: center; justify-content: center; height: 46px; flex-shrink: 0; box-shadow: inset 0 1px 3px rgba(0,0,0,0.03);">
                    <img id="loginCaptchaImg" src="{{ route('captcha.generate') }}" alt="Security Captcha" style="display: block; height: 44px; width: 160px; user-select: none;">
                </div>
                <button type="button" onclick="refreshLoginCaptcha()" title="Refresh Captcha"
                        style="width: 44px; height: 44px; flex-shrink: 0; border: 1px solid #ddd; border-radius: 6px; background: #faf8f5; color: #555; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; transition: all 0.2s;"
                        onmouseover="this.style.borderColor='#c0a062'; this.style.color='#c0a062';"
                        onmouseout="this.style.borderColor='#ddd'; this.style.color='#555';">
                    <i class="ph ph-arrows-clockwise" id="loginRefreshIcon"></i>
                </button>
                <input type="text" name="captcha" id="loginCaptcha" class="input-field" placeholder="ENTER CAPTCHA *" required maxlength="6" autocomplete="off"
                       style="flex: 1; min-width: 150px; height: 46px; padding: 0.85rem 1rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; outline: none; letter-spacing: 0.15em; font-weight: 600; text-transform: uppercase; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='#c0a062'" onblur="this.style.borderColor='#ddd'">
            </div>
        </div>

        <button type="submit" id="loginSubmitBtn" class="btn btn-primary" 
                style="width: 100%; margin-top: 0.5rem; margin-bottom: 1.5rem; padding: 1rem; font-family: 'Cinzel', serif; letter-spacing: 0.12em; background: #c0a062; border: 1px solid #c0a062; color: #fff; border-radius: 6px; cursor: pointer; font-size: 0.95rem; transition: background 0.3s;">
            LOG IN
        </button>

        <div class="text-center" style="font-size: 0.9rem; color: #666;">
            New to {{ $brandName }}? <a href="{{ route('register') }}{{ request('redirect') ? '?redirect=' . urlencode(request('redirect')) : '' }}" style="font-weight: 600; text-decoration: none; color: #c0a062;">Create an Account</a>
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

function refreshLoginCaptcha() {
    var img = document.getElementById('loginCaptchaImg');
    var icon = document.getElementById('loginRefreshIcon');
    if (icon) {
        icon.style.transform = 'rotate(360deg)';
        icon.style.transition = 'transform 0.5s ease';
    }
    if (img) {
        img.src = "{{ route('captcha.generate') }}?t=" + Date.now();
    }
    var input = document.getElementById('loginCaptcha');
    if (input) input.value = '';
    setTimeout(function() {
        if (icon) {
            icon.style.transform = 'none';
            icon.style.transition = 'none';
        }
    }, 550);
}

function handleCustomerLogin(e) {
    e.preventDefault();
    var form = document.getElementById('loginForm');
    var btn = document.getElementById('loginSubmitBtn');
    var alertBox = document.getElementById('loginAlert');

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
        btn.innerText = 'LOG IN';

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
            // Refresh captcha on failure
            refreshLoginCaptcha();
            var errMsg = resObj.data.message || 'Invalid credentials. Please try again.';
            if (resObj.data.errors && resObj.data.errors.captcha) {
                errMsg = resObj.data.errors.captcha[0];
            }
            alertBox.style.display = 'block';
            alertBox.style.background = '#fdf2f2';
            alertBox.style.color = '#b91c1c';
            alertBox.style.border = '1px solid #fecaca';
            alertBox.innerText = errMsg;
        }
    })
    .catch(function(err) {
        refreshLoginCaptcha();
        btn.disabled = false;
        btn.innerText = 'LOG IN';
        alertBox.style.display = 'block';
        alertBox.style.background = '#fdf2f2';
        alertBox.style.color = '#b91c1c';
        alertBox.style.border = '1px solid #fecaca';
        alertBox.innerText = 'Unable to connect to authentication server. Please try again.';
    });
}
</script>
@endpush
