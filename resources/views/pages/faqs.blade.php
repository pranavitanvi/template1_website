@extends('layouts.customer-care')

@section('title', 'Frequently Asked Questions | Aura Fine Jewellery')
@section('meta_description', 'Find answers to common questions regarding Aura craftsmanship, diamond certifications, and orders.')

@section('care_content')
<h2>Frequently Asked Questions</h2>

<div class="faq-container">
    <div class="faq-item">
        <div class="faq-question">Do you offer custom and bespoke designs? <i class="ph ph-caret-down"></i></div>
        <div class="faq-answer">
            Yes, we offer custom bespoke design services. You can collaborate directly with our master designers and gemologists to create a one-of-a-kind heirloom. Please visit our <a href="{{ route('contact') }}" style="color: #c0a062;">Contact</a> page to schedule an appointment.
        </div>
    </div>
    <div class="faq-item">
        <div class="faq-question">Are your diamonds ethically sourced & certified? <i class="ph ph-caret-down"></i></div>
        <div class="faq-answer">
            Every diamond in our collection is 100% ethically sourced and complies strictly with the international Kimberley Process. All solitaires above 0.50ct come with accredited IGI or GIA grading certifications.
        </div>
    </div>
    <div class="faq-item">
        <div class="faq-question">Can I upgrade my diamond solitaire later? <i class="ph ph-caret-down"></i></div>
        <div class="faq-answer">
            Yes, we offer an exclusive lifetime diamond upgrade program. You can trade in your Aura solitaire piece for a larger diamond or higher grade by paying the differential amount.
        </div>
    </div>
    <div class="faq-item">
        <div class="faq-question">What payment methods do you accept? <i class="ph ph-caret-down"></i></div>
        <div class="faq-answer">
            We accept all major domestic and international Credit/Debit cards (Visa, Mastercard, RuPay, Amex), UPI (Google Pay, PhonePe, Paytm), Net Banking, and Cash on Delivery for qualifying order values.
        </div>
    </div>
    <div class="faq-item">
        <div class="faq-question">How can I track my shipment? <i class="ph ph-caret-down"></i></div>
        <div class="faq-answer">
            As soon as your handcrafted piece is dispatched from our atelier, an SMS and email notification with an encrypted tracking number and carrier link will be sent to you.
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.faq-question').forEach(q => {
            q.addEventListener('click', () => {
                const item = q.parentElement;
                item.classList.toggle('active');
            });
        });
    });
</script>
@endpush
@endsection
