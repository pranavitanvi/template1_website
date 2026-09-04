@extends('layouts.customer-care')

@php
    $faqs = $careData['faqs'] ?? [];
    $faqItems = !empty($faqs['items']) ? $faqs['items'] : [
        [
            'question' => 'Do you offer custom and bespoke designs?',
            'answer' => 'Yes, we offer custom bespoke design services. You can collaborate directly with our master designers and gemologists to create a one-of-a-kind heirloom. Please visit our <a href="' . route('contact') . '" style="color: #c0a062;">Contact</a> page to schedule an appointment.'
        ],
        [
            'question' => 'Are your diamonds ethically sourced & certified?',
            'answer' => 'Every diamond in our collection is 100% ethically sourced and complies strictly with the international Kimberley Process. All solitaires above 0.50ct come with accredited IGI or GIA grading certifications.'
        ],
        [
            'question' => 'Can I upgrade my diamond solitaire later?',
            'answer' => 'Yes, we offer an exclusive lifetime diamond upgrade program. You can trade in your Aura solitaire piece for a larger diamond or higher grade by paying the differential amount.'
        ],
        [
            'question' => 'What payment methods do you accept?',
            'answer' => 'We accept all major domestic and international Credit/Debit cards (Visa, Mastercard, RuPay, Amex), UPI (Google Pay, PhonePe, Paytm), Net Banking, and Cash on Delivery for qualifying order values.'
        ],
        [
            'question' => 'How can I track my shipment?',
            'answer' => 'As soon as your handcrafted piece is dispatched from our atelier, an SMS and email notification with an encrypted tracking number and carrier link will be sent to you.'
        ]
    ];
@endphp

@section('title', 'Frequently Asked Questions | Aura Fine Jewellery')
@section('meta_description', 'Find answers to common questions regarding Aura craftsmanship, diamond certifications, and orders.')

@section('care_content')
<h2>{{ $faqs['title'] ?? 'Frequently Asked Questions' }}</h2>

<div class="faq-container">
    @foreach($faqItems as $item)
        <div class="faq-item">
            <div class="faq-question">{{ $item['question'] ?? '' }} <i class="ph ph-caret-down"></i></div>
            <div class="faq-answer">
                {!! $item['answer'] ?? '' !!}
            </div>
        </div>
    @endforeach
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
