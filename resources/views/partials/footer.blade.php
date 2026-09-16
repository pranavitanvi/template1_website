<style>
.footer {
    background-color: var(--footer-bg, #2a2422);
    color: #fff;
    padding: 5rem 0 2rem 0;
    font-family: var(--font-body, 'Montserrat', sans-serif);
}
.footer-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 3rem;
}
.footer h4 {
    font-family: var(--font-heading, 'Cinzel', serif) !important;
    color: var(--accent-gold, #C2A878) !important;
    margin-bottom: 1.5rem !important;
    font-weight: 600 !important;
    font-size: 1.1rem !important;
    letter-spacing: 0.05em !important;
}
.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}
.footer-links li {
    margin-bottom: 0.8rem;
}
.footer-links a {
    color: #d1cbc7;
    text-decoration: none;
    font-size: 0.95rem;
    transition: color 0.3s ease;
}
.footer-links a:hover {
    color: var(--accent-gold, #C2A878);
}
.footer-bottom {
    margin-top: 4rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(194, 168, 120, 0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #999;
    font-size: 0.9rem;
}
@media (max-width: 768px) {
    .footer-grid { grid-template-columns: 1fr; gap: 2rem; }
    .footer-bottom { flex-direction: column; gap: 1rem; text-align: center; }
}
</style>

@php
    $rawFooterName = $cmsFooter['contact_person'] ?? $cmsFooter['jeweller_name'] ?? $cmsFooter['owner_name'] ?? $cmsFooter['store_name'] ?? $cmsHeader['store_name'] ?? null;
    $footerStoreName = (!empty($rawFooterName) && !in_array(strtolower(trim((string)$rawFooterName)), ['test', 'test store', 'default', 'default store'])) 
        ? $rawFooterName 
        : 'Kalbhorer Omkar';

    $footerAddress = $cmsFooter['address'] ?? $cmsContact['contact_info']['address'] ?? 'Mummmmmbai & Pune, India';
    $footerPhone = $cmsFooter['phone'] ?? $cmsContact['contact_info']['phone'] ?? '+91 99876 54889';
    $footerEmail = $cmsFooter['email'] ?? $cmsContact['contact_info']['email'] ?? 'care@aura.com';
    $footerAboutTitle = $cmsFooter['about_title'] ?? 'ABOUT AURA';
    $footerAboutText = $cmsFooter['about_text'] ?? 'Heirloom-inspired fine jewellery handcrafted with certified natural diamonds and hallmarked gold.';
    $newsletterEnabled = !isset($cmsFooter['newsletter_enabled']) || !empty($cmsFooter['newsletter_enabled']);
    $footerCopyright = str_replace('Test', $footerStoreName, $cmsFooter['copyright'] ?? ('&copy; ' . date('Y') . ' ' . $footerStoreName . '. All rights reserved.'));
    $privacyUrl = !empty($cmsFooter['privacy_policy_url']) && $cmsFooter['privacy_policy_url'] !== '#' ? $cmsFooter['privacy_policy_url'] : route('privacy-policy');
    $termsUrl = !empty($cmsFooter['terms_url']) && $cmsFooter['terms_url'] !== '#' ? $cmsFooter['terms_url'] : route('terms-conditions');
    $socialLinks = $cmsFooter['social_links'] ?? ($cmsHeader['social_links'] ?? []);
@endphp

<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <!-- COLUMN 1: Contact -->
            <div class="footer-col">
                <h4>CONTACT</h4>
                <p style="color: #d1cbc7; font-size: 0.95rem; line-height: 1.8; margin-bottom: 1.5rem;">
                    <strong>{{ $footerStoreName }}</strong><br>
                    {!! nl2br(e($footerAddress)) !!}
                </p>
                <p style="color: #d1cbc7; font-size: 0.95rem; line-height: 1.8;">
                    <strong>Phone:</strong> <a href="tel:{{ preg_replace('/[^0-9+]/', '', $footerPhone) }}" style="color: inherit; text-decoration: none;">{{ $footerPhone }}</a><br>
                    <strong>Email:</strong> <a href="mailto:{{ $footerEmail }}" style="color: inherit; text-decoration: none;">{{ $footerEmail }}</a>
                </p>
            </div>

            <!-- COLUMN 2: SHOP -->
            <div class="footer-col">
                <h4>SHOP</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('category', 'womens') }}">Women's Jewellery</a></li>
                    <li><a href="{{ route('category', 'mens') }}">Men's Jewellery</a></li>
                    <li><a href="{{ route('bridal') }}">Bridal</a></li>
                    <li><a href="{{ route('new-arrivals') }}">New Arrivals</a></li>
                    <li><a href="{{ route('collections') }}">Collections</a></li>
                </ul>
            </div>

            <!-- COLUMN 3: CUSTOMER CARE -->
            <div class="footer-col">
                <h4>CUSTOMER CARE</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                    <li><a href="{{ route('shipping') }}">Shipping & Delivery</a></li>
                    <li><a href="{{ route('returns') }}">Returns & Exchanges</a></li>
                    <li><a href="{{ route('size-guide') }}">Size Guide</a></li>
                    <li><a href="{{ route('jewellery-care') }}">Jewellery Care</a></li>
                    <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('terms-conditions') }}">Terms &amp; Conditions</a></li>
                    <li><a href="{{ route('faqs') }}">FAQs</a></li>
                </ul>
            </div>

            <!-- COLUMN 4: ABOUT AURA & SUBSCRIBE -->
            <div class="footer-col">
                <h4>{{ $footerAboutTitle }}</h4>
                @if(!empty($footerAboutText))
                    <p style="color: #d1cbc7; font-size: 0.9rem; line-height: 1.6; margin-bottom: 1rem;">
                        {{ $footerAboutText }}
                    </p>
                @endif
                <ul class="footer-links">
                    <li><a href="{{ route('about') }}">Our Story</a></li>
                    <li><a href="{{ route('craftsmanship') }}">Craftsmanship</a></li>
                    <li><a href="{{ route('stores') }}">Store Locator</a></li>
                </ul>
                
                @if($newsletterEnabled)
                <div style="margin-top: 2rem;">
                    <h4 style="font-size: 0.9rem !important; margin-bottom: 1rem !important;">SUBSCRIBE</h4>
                    <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Thank you for subscribing!');" style="display: flex;">
                        <input type="email" placeholder="Email Address" required style="padding: 0.8rem; border: none; background: #fff; width: 100%; outline: none; font-family: inherit; font-size: 0.9rem;">
                        <button type="submit" style="padding: 0.8rem 1.2rem; background: var(--accent-gold, #C2A878); border: none; color: #fff; cursor: pointer; font-family: inherit; font-weight: 500; border-radius: var(--btn-radius, 4px);">JOIN</button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Footer Bottom Bar -->
        <div class="footer-bottom">
            <div>
                <a href="{{ $privacyUrl }}" style="color: #999; text-decoration: none; margin-right: 1.5rem;">Privacy Policy</a>
                <a href="{{ $termsUrl }}" style="color: #999; text-decoration: none; margin-right: 1.5rem;">Terms &amp; Conditions</a>
                <span>{!! $footerCopyright !!}</span>
            </div>
            <div style="display: flex; gap: 1.5rem; font-size: 1.2rem; color: var(--accent-gold, #C2A878);">
                @if(!empty($socialLinks['instagram']))
                    <a href="{{ $socialLinks['instagram'] }}" target="_blank" rel="noopener noreferrer" style="color: inherit;" aria-label="Instagram"><i class="ph-fill ph-instagram-logo"></i></a>
                @endif
                @if(!empty($socialLinks['facebook']))
                    <a href="{{ $socialLinks['facebook'] }}" target="_blank" rel="noopener noreferrer" style="color: inherit;" aria-label="Facebook"><i class="ph-fill ph-facebook-logo"></i></a>
                @endif
                @if(!empty($socialLinks['pinterest']))
                    <a href="{{ $socialLinks['pinterest'] }}" target="_blank" rel="noopener noreferrer" style="color: inherit;" aria-label="Pinterest"><i class="ph-fill ph-pinterest-logo"></i></a>
                @endif
                @if(!empty($socialLinks['youtube']))
                    <a href="{{ $socialLinks['youtube'] }}" target="_blank" rel="noopener noreferrer" style="color: inherit;" aria-label="YouTube"><i class="ph-fill ph-youtube-logo"></i></a>
                @endif
                @if(!empty($socialLinks['whatsapp']))
                    @php
                        $rawWa = $socialLinks['whatsapp'];
                        $waLink = str_starts_with($rawWa, 'http') ? $rawWa : ('https://wa.me/' . preg_replace('/[^0-9]/', '', $rawWa));
                    @endphp
                    <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" style="color: inherit;" aria-label="WhatsApp"><i class="ph-fill ph-whatsapp-logo"></i></a>
                @endif
                @if(!empty($socialLinks['twitter']))
                    <a href="{{ $socialLinks['twitter'] }}" target="_blank" rel="noopener noreferrer" style="color: inherit;" aria-label="Twitter / X"><i class="ph-fill ph-x-logo"></i></a>
                @endif
            </div>
        </div>
    </div>
</footer>
