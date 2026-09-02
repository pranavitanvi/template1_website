@extends('layouts.app')

@php
    $hero = $contactPage['hero'] ?? [];
    $info = $contactPage['contact_info'] ?? [];
    $form = $contactPage['form_section'] ?? [];
    $consultation = $contactPage['consultation_section'] ?? [];
    $seo = $contactPage['seo'] ?? [];
@endphp

@section('title', !empty($seo['meta_title']) ? $seo['meta_title'] : 'Contact Us | Aura Fine Jewellery')
@section('meta_description', !empty($seo['meta_description']) ? $seo['meta_description'] : 'Get in touch with the Aura concierge team for styling advice, custom creations, or virtual consultations.')
@section('main_style', 'margin-top: 0;')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/editorial_pages.css') }}">
<style>
    .aura-contact-page {
        background-color: #FAF8F4;
        color: #1a1814;
        padding-bottom: 80px;
    }
    .cont-container {
        max-width: 1400px;
        width: 90%;
        margin: 0 auto;
    }
    .cont-serif { font-family: 'Cinzel', serif; }
    .cont-sans { font-family: 'Montserrat', sans-serif; }
    .cont-gold { color: #c0a062; }
    
    .cont-hero-new {
        position: relative;
        min-height: 520px;
        background-image: url('{{ !empty($hero['background_image_url']) ? $hero['background_image_url'] : asset("assets/images/hero/hero_main.jpg") }}');
        background-size: cover;
        background-position: center top;
        margin-top: 110px;
    }
    .cont-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, rgba(237, 232, 222, 0.98) 0%, rgba(237, 232, 222, 0.9) 45%, rgba(237, 232, 222, 0.3) 100%);
        z-index: 1;
    }
    .cont-hero-content-new {
        position: relative;
        z-index: 2;
        width: 100%;
        height: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px 5%;
        color: #1a1814;
    }
    .cont-hero-inner {
        max-width: 550px;
        text-align: center;
        display: flex;
        flex-direction: column;
    }
    .cont-hero-text-wrap h1 {
        font-family: 'Cinzel', serif;
        font-size: 3rem;
        margin: 0.5rem 0 1rem;
        letter-spacing: 0.05em;
    }
    .cont-hero-icons {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(156, 127, 76, 0.25);
    }
    .cont-icon-box {
        text-align: center;
    }
    .cont-icon-box i {
        font-size: 1.5rem;
        color: #9c7f4c;
        margin-bottom: 0.3rem;
        display: block;
    }
    .cont-icon-box h4 {
        font-size: 0.75rem;
        letter-spacing: 0.1em;
        margin-bottom: 0.2rem;
    }
    .cont-icon-box span {
        font-size: 0.75rem;
        color: #666;
    }
    .cont-grid {
        display: flex;
        justify-content: center;
        padding: 60px 0;
    }
    .cont-form-wrap {
        background: #fff;
        padding: 4rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        width: 100%;
        max-width: 750px;
        border-radius: 8px;
        text-align: center;
    }
    .cont-form-wrap h3 {
        font-size: 2.2rem;
        margin-bottom: 0.5rem;
    }
    .cont-form-group {
        margin-bottom: 1.8rem;
    }
    .cont-input, .cont-textarea {
        width: 100%;
        background: transparent;
        border: none;
        border-bottom: 1px solid #ddd;
        padding: 0.8rem 0;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.95rem;
        color: #1a1814;
        transition: border-color 0.3s ease;
    }
    .cont-input:focus, .cont-textarea:focus {
        outline: none;
        border-bottom-color: #c0a062;
    }
    .cont-btn {
        background: #1a1814;
        color: #fff;
        border: none;
        padding: 1.1rem 2rem;
        font-family: 'Cinzel', serif;
        font-size: 0.9rem;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        cursor: pointer;
        transition: background 0.3s ease;
        width: 100%;
        border-radius: 4px;
    }
    .cont-btn:hover {
        background: #c0a062;
    }
    .cont-appointment {
        background: #fff;
        padding: 50px 30px;
        text-align: center;
        margin-bottom: 40px;
        border-radius: 8px;
    }
    .cont-alert {
        padding: 1rem;
        border-radius: 4px;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
        display: none;
    }
    .cont-alert-success {
        background-color: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
    }
    .cont-alert-danger {
        background-color: #ffebee;
        color: #c62828;
        border: 1px solid #ffcdd2;
    }
</style>
@endpush

@section('content')
<div class="aura-contact-page">
    <section class="cont-hero-new">
        <div class="cont-hero-overlay"></div>
        <div class="cont-hero-content-new">
            <div class="cont-hero-inner">
                <div class="cont-hero-text-wrap">
                    <div style="font-size: 0.8rem; letter-spacing: 0.2em; text-transform: uppercase; color: #9c7f4c; margin-bottom: 0.5rem;">
                        {{ $hero['eyebrow'] ?? "WE'RE HERE FOR YOU" }}
                    </div>
                    <h1>{{ $hero['title'] ?? "LET'S CONNECT" }}</h1>
                    <p style="color: #666; font-size: 1rem; line-height: 1.6;">
                        {{ $hero['subtitle'] ?? 'Have a question, need styling advice, or want to create something special?' }}
                    </p>
                </div>
                
                <div class="cont-hero-icons">
                    <div class="cont-icon-box">
                        <i class="ph ph-phone-call"></i>
                        <h4>CALL US</h4>
                        <span>{{ $info['phone'] ?? '+91 98765 43210' }}</span>
                    </div>
                    <div class="cont-icon-box">
                        <i class="ph ph-envelope-simple"></i>
                        <h4>EMAIL US</h4>
                        <span>{{ $info['email'] ?? 'care@aura.com' }}</span>
                    </div>
                    <div class="cont-icon-box">
                        <i class="ph ph-clock"></i>
                        <h4>TIMINGS</h4>
                        <span>{{ $info['timings'] ?? '10 AM - 7 PM' }}</span>
                    </div>
                    <div class="cont-icon-box">
                        <i class="ph ph-map-pin"></i>
                        <h4>VISIT US</h4>
                        <span>{{ $info['address'] ?? 'Mumbai & Pune' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="cont-container">
        <div class="cont-grid">
            <div class="cont-form-wrap">
                <h3 class="cont-serif">{{ $form['title'] ?? 'Send an Inquiry' }}</h3>
                <p style="color: #666; margin-bottom: 2.5rem;">
                    {{ $form['subtitle'] ?? 'Fill out the form below and a member of our concierge team will get back to you shortly.' }}
                </p>

                <!-- Feedback alerts -->
                <div id="contactSuccessMsg" class="cont-alert cont-alert-success"></div>
                <div id="contactErrorMsg" class="cont-alert cont-alert-danger"></div>

                <form id="storeContactForm" method="POST">
                    @csrf
                    <div class="cont-form-group">
                        <input type="text" name="name" class="cont-input" placeholder="Full Name *" required>
                    </div>
                    <div class="cont-form-group">
                        <input type="email" name="email" class="cont-input" placeholder="Email Address *" required>
                    </div>
                    <div class="cont-form-group">
                        <input type="tel" name="phone" class="cont-input" placeholder="Phone Number (Optional)">
                    </div>
                    <div class="cont-form-group">
                        <input type="text" name="subject" class="cont-input" placeholder="Subject (Optional)">
                    </div>
                    <div class="cont-form-group">
                        <textarea name="message" class="cont-textarea" rows="4" placeholder="Your Message *" required></textarea>
                    </div>
                    <button type="submit" id="contactSubmitBtn" class="cont-btn">Send Message</button>
                </form>
            </div>
        </div>

        <section class="cont-appointment">
            <h2 style="font-family: 'Cinzel', serif; font-size: 2rem; margin-bottom: 1rem;">
                {{ $consultation['title'] ?? 'Book a Personal Consultation' }}
            </h2>
            <p style="color: #666; max-width: 600px; margin: 0 auto 2rem; line-height: 1.8;">
                {{ $consultation['subtitle'] ?? 'Meet with our master gemologists and stylists in-store or over video call to select or custom craft your jewellery.' }}
            </p>
            <a href="{{ !empty($consultation['button']['url']) ? $consultation['button']['url'] : route('stores') }}" class="btn btn-primary" style="display: inline-block;">
                {{ $consultation['button']['text'] ?? 'Explore Stores' }}
            </a>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('storeContactForm');
    if (!form) return;

    var btn = document.getElementById('contactSubmitBtn');
    var successMsg = document.getElementById('contactSuccessMsg');
    var errorMsg = document.getElementById('contactErrorMsg');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        btn.disabled = true;
        btn.innerText = 'SENDING...';
        successMsg.style.display = 'none';
        errorMsg.style.display = 'none';

        var formData = new FormData(form);

        fetch("{{ route('contact.submit') }}", {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(function(res) {
            return res.json().then(function(data) {
                return { status: res.status, data: data };
            });
        })
        .then(function(result) {
            btn.disabled = false;
            btn.innerText = 'SEND MESSAGE';

            if (result.status === 200 && result.data.success) {
                successMsg.innerText = result.data.message || 'Thank you! Your inquiry has been submitted successfully.';
                successMsg.style.display = 'block';
                form.reset();
            } else {
                var errText = result.data.message || 'There was an issue submitting your message. Please check the fields and try again.';
                if (result.data.errors) {
                    var firstErrKey = Object.keys(result.data.errors)[0];
                    if (firstErrKey && result.data.errors[firstErrKey][0]) {
                        errText = result.data.errors[firstErrKey][0];
                    }
                }
                errorMsg.innerText = errText;
                errorMsg.style.display = 'block';
            }
        })
        .catch(function(err) {
            btn.disabled = false;
            btn.innerText = 'SEND MESSAGE';
            errorMsg.innerText = 'Network error. Please try again in a few moments.';
            errorMsg.style.display = 'block';
        });
    });
});
</script>
@endpush
