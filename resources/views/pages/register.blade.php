@extends('layouts.app')

@php
    $brandName = $cmsHeader['brand_name'] ?? ($cmsHeader['store_name'] ?? 'Aura');
    $brandFull = $cmsHeader['brand_name'] ?? ($cmsHeader['store_name'] ?? 'Aura Fine Jewellery');
@endphp

@section('title', 'Create Account | ' . $brandFull)
@section('meta_description', 'Join ' . $brandName . ' to enjoy personalized curations, order tracking, and exclusive previews.')
@section('main_style', 'margin-top: 110px; min-height: 70vh; display: flex; align-items: center; justify-content: center; background-color: var(--bg-secondary); padding: 4rem 1rem;')

@section('content')
<div style="background: var(--white); padding: 3rem 2.5rem; width: 100%; max-width: 520px; box-shadow: 0 10px 40px rgba(0,0,0,0.06); text-align: center; border-radius: 12px; border: 1px solid #f0ece4;">
    <div style="margin-bottom: 0.5rem; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.2em; color: #c0a062; font-weight: 600;">MEMBERSHIP</div>
    <h2 style="margin-bottom: 0.5rem; font-family: 'Cinzel', serif; font-size: 2rem; color: #1a1a1a;">Create Account</h2>
    <p style="margin-bottom: 2rem; color: var(--text-secondary); font-size: 0.95rem;">Join {{ $brandName }} to unlock personalized curations, order tracking, and loyalty rewards.</p>
    
    <div id="regAlert" style="display: none; padding: 0.85rem 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.9rem; text-align: left;"></div>

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

    <form id="registerForm" method="POST" action="{{ route('register.submit') }}" style="text-align: left;" onsubmit="handleCustomerRegister(event)">
        @csrf
        <input type="hidden" name="redirect" value="{{ request('redirect') }}">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.2rem;">
            <div>
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #666; margin-bottom: 6px; font-weight: 600;">First Name <span style="color: red;">*</span></label>
                <input type="text" name="first_name" id="regFname" class="input-field" placeholder="Priya" required 
                       style="width: 100%; padding: 0.85rem 1rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; outline: none;"
                       onfocus="this.style.borderColor='#c0a062'" onblur="this.style.borderColor='#ddd'">
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #666; margin-bottom: 6px; font-weight: 600;">Last Name</label>
                <input type="text" name="last_name" id="regLname" class="input-field" placeholder="Sharma" 
                       style="width: 100%; padding: 0.85rem 1rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; outline: none;"
                       onfocus="this.style.borderColor='#c0a062'" onblur="this.style.borderColor='#ddd'">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.2rem;">
            <div>
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #666; margin-bottom: 6px; font-weight: 600;">Mobile Number <span style="color: red;">*</span></label>
                <input type="tel" name="phone" id="regPhone" class="input-field" placeholder="10-digit mobile" required maxlength="10"
                       style="width: 100%; padding: 0.85rem 1rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; outline: none;"
                       onfocus="this.style.borderColor='#c0a062'" onblur="this.style.borderColor='#ddd'">
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #666; margin-bottom: 6px; font-weight: 600;">Email Address</label>
                <input type="email" name="email" id="regEmail" class="input-field" placeholder="priya@example.com" 
                       style="width: 100%; padding: 0.85rem 1rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; outline: none;"
                       onfocus="this.style.borderColor='#c0a062'" onblur="this.style.borderColor='#ddd'">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.2rem;">
            <div>
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #666; margin-bottom: 6px; font-weight: 600;">Password <span style="color: red;">*</span></label>
                <div style="position: relative;">
                    <input type="password" name="password" id="regPassword" class="input-field" placeholder="Min 6 characters" required 
                           style="width: 100%; padding: 0.85rem 2.4rem 0.85rem 1rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; outline: none;"
                           onfocus="this.style.borderColor='#c0a062'" onblur="this.style.borderColor='#ddd'">
                    <button type="button" onclick="togglePass('regPassword', this)" 
                            style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #888;">
                        👁️
                    </button>
                </div>
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #666; margin-bottom: 6px; font-weight: 600;">Confirm Password <span style="color: red;">*</span></label>
                <div style="position: relative;">
                    <input type="password" name="password_confirmation" id="regPasswordConfirm" class="input-field" placeholder="Re-enter password" required 
                           style="width: 100%; padding: 0.85rem 2.4rem 0.85rem 1rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; outline: none;"
                           onfocus="this.style.borderColor='#c0a062'" onblur="this.style.borderColor='#ddd'">
                    <button type="button" onclick="togglePass('regPasswordConfirm', this)" 
                            style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #888;">
                        👁️
                    </button>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #666; margin-bottom: 6px; font-weight: 600;">City</label>
                <input type="text" name="city" id="regCity" class="input-field" placeholder="e.g. Mumbai" 
                       style="width: 100%; padding: 0.85rem 1rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; outline: none;"
                       onfocus="this.style.borderColor='#c0a062'" onblur="this.style.borderColor='#ddd'">
            </div>
            <div>
                <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #666; margin-bottom: 6px; font-weight: 600;">Pincode</label>
                <input type="text" name="pin_code" id="regPincode" class="input-field" placeholder="6-digit pincode" maxlength="6"
                       style="width: 100%; padding: 0.85rem 1rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; outline: none;"
                       onfocus="this.style.borderColor='#c0a062'" onblur="this.style.borderColor='#ddd'">
            </div>
        </div>

        <!-- Security Captcha Verification -->
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #666; margin-bottom: 6px; font-weight: 600;">
                Security Captcha <span style="color: red;">*</span>
            </label>
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <div style="border-radius: 6px; overflow: hidden; border: 1px solid #ddd; background: #faf7f2; display: flex; align-items: center; justify-content: center; height: 46px; flex-shrink: 0; box-shadow: inset 0 1px 3px rgba(0,0,0,0.03);">
                    <img id="regCaptchaImg" src="{{ route('captcha.generate') }}" alt="Security Captcha" style="display: block; height: 44px; width: 160px; user-select: none;">
                </div>
                <button type="button" onclick="refreshRegCaptcha()" title="Refresh Captcha"
                        style="width: 44px; height: 44px; flex-shrink: 0; border: 1px solid #ddd; border-radius: 6px; background: #faf8f5; color: #555; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; transition: all 0.2s;"
                        onmouseover="this.style.borderColor='#c0a062'; this.style.color='#c0a062';"
                        onmouseout="this.style.borderColor='#ddd'; this.style.color='#555';">
                    <i class="ph ph-arrows-clockwise" id="regRefreshIcon"></i>
                </button>
                <input type="text" name="captcha" id="regCaptcha" class="input-field" placeholder="Enter Captcha" required maxlength="6" autocomplete="off"
                       style="flex: 1; min-width: 140px; height: 46px; padding: 0 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.88rem; outline: none; letter-spacing: 0.1em; font-weight: 600; text-transform: uppercase; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='#c0a062'" onblur="this.style.borderColor='#ddd'">
            </div>
        </div>

        <button type="submit" id="regSubmitBtn" class="btn btn-primary" 
                style="width: 100%; margin-bottom: 1.5rem; padding: 1rem; font-family: 'Cinzel', serif; letter-spacing: 0.12em; background: #c0a062; border: 1px solid #c0a062; color: #fff; border-radius: 6px; cursor: pointer; font-size: 0.95rem; transition: background 0.3s;">
            CREATE ACCOUNT
        </button>

        <div class="text-center" style="font-size: 0.9rem; color: #666;">
            Already have an account? <a href="{{ route('login') }}" style="font-weight: 600; text-decoration: none; color: #c0a062;">Log In</a>
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

function refreshRegCaptcha() {
    var img = document.getElementById('regCaptchaImg');
    var icon = document.getElementById('regRefreshIcon');
    if (icon) {
        icon.style.transform = 'rotate(360deg)';
        icon.style.transition = 'transform 0.5s ease';
    }
    if (img) {
        img.src = "{{ route('captcha.generate') }}?t=" + Date.now();
    }
    var input = document.getElementById('regCaptcha');
    if (input) input.value = '';
    setTimeout(function() {
        if (icon) {
            icon.style.transform = 'none';
            icon.style.transition = 'none';
        }
    }, 550);
}

function handleCustomerRegister(e) {
    e.preventDefault();
    var form = document.getElementById('registerForm');
    var btn = document.getElementById('regSubmitBtn');
    var alertBox = document.getElementById('regAlert');

    var p1 = document.getElementById('regPassword').value;
    var p2 = document.getElementById('regPasswordConfirm').value;
    if (p1 !== p2) {
        alertBox.style.display = 'block';
        alertBox.style.background = '#fdf2f2';
        alertBox.style.color = '#b91c1c';
        alertBox.style.border = '1px solid #fecaca';
        alertBox.innerText = 'Passwords do not match. Please ensure both passwords match.';
        refreshRegCaptcha();
        return;
    }

    alertBox.style.display = 'none';
    btn.disabled = true;
    btn.innerText = 'CREATING ACCOUNT...';

    var formData = new FormData(form);

    fetch("{{ route('register.submit') }}", {
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
        btn.innerText = 'CREATE ACCOUNT';

        if (resObj.data.success) {
            alertBox.style.display = 'block';
            alertBox.style.background = '#f0fdf4';
            alertBox.style.color = '#15803d';
            alertBox.style.border = '1px solid #bbf7d0';
            alertBox.innerText = resObj.data.message || 'Account created successfully! Welcome to Aura.';

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
            }, 1000);
        } else {
            refreshRegCaptcha();
            var msg = resObj.data.message || 'Registration failed.';
            if (resObj.data.errors) {
                if (resObj.data.errors.captcha) {
                    msg = resObj.data.errors.captcha[0];
                } else {
                    var firstErr = Object.values(resObj.data.errors)[0];
                    if (Array.isArray(firstErr)) msg = firstErr[0];
                }
            }
            alertBox.style.display = 'block';
            alertBox.style.background = '#fdf2f2';
            alertBox.style.color = '#b91c1c';
            alertBox.style.border = '1px solid #fecaca';
            alertBox.innerText = msg;
        }
    })
    .catch(function(err) {
        refreshRegCaptcha();
        btn.disabled = false;
        btn.innerText = 'CREATE ACCOUNT';
        alertBox.style.display = 'block';
        alertBox.style.background = '#fdf2f2';
        alertBox.style.color = '#b91c1c';
        alertBox.style.border = '1px solid #fecaca';
        alertBox.innerText = 'Unable to connect to registration server. Please try again.';
    });
}
</script>
@endpush
