<style>
.footer {
    background-color: #2a2422;
    color: #fff;
    padding: 5rem 0 2rem 0;
    font-family: 'Montserrat', sans-serif;
}
.footer-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 3rem;
}
.footer h4 {
    font-family: 'Cinzel', serif !important;
    color: #C2A878 !important;
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
    color: #C2A878;
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

<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <!-- COLUMN 1: Contact -->
            <div class="footer-col">
                <h4>CONTACT</h4>
                <p style="color: #d1cbc7; font-size: 0.95rem; line-height: 1.8; margin-bottom: 1.5rem;">
                    <strong>{{ $cmsHeader['store_name'] ?? 'AURA Flagship Store' }}</strong><br>
                    {{ $cmsContact['contact_info']['address'] ?? 'Palladium Mall, Lower Parel, Mumbai, Maharashtra 400013' }}
                </p>
                <p style="color: #d1cbc7; font-size: 0.95rem; line-height: 1.8;">
                    <strong>Phone:</strong> {{ $cmsContact['contact_info']['phone'] ?? '+91 98765 43210' }}<br>
                    <strong>Email:</strong> {{ $cmsContact['contact_info']['email'] ?? 'care@aura.com' }}
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
                    <li><a href="{{ route('faqs') }}">FAQs</a></li>
                </ul>
            </div>

            <!-- COLUMN 4: ABOUT AURA & SUBSCRIBE -->
            <div class="footer-col">
                <h4>ABOUT AURA</h4>
                <ul class="footer-links">
                    <li><a href="{{ route('about') }}">Our Story</a></li>
                    <li><a href="{{ route('craftsmanship') }}">Craftsmanship</a></li>
                    <li><a href="{{ route('stores') }}">Store Locator</a></li>
                </ul>
                
                <div style="margin-top: 2rem;">
                    <h4 style="font-size: 0.9rem !important; margin-bottom: 1rem !important;">SUBSCRIBE</h4>
                    <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Thank you for subscribing!');" style="display: flex;">
                        <input type="email" placeholder="Email Address" required style="padding: 0.8rem; border: none; background: #fff; width: 100%; outline: none; font-family: inherit; font-size: 0.9rem;">
                        <button type="submit" style="padding: 0.8rem 1.2rem; background: #C2A878; border: none; color: #fff; cursor: pointer; font-family: inherit; font-weight: 500;">JOIN</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div>
                <a href="#" style="color: #999; text-decoration: none; margin-right: 1.5rem;">Privacy Policy</a>
                <a href="#" style="color: #999; text-decoration: none; margin-right: 1.5rem;">Terms & Conditions</a>
                <span>&copy; {{ date('Y') }} {{ $cmsHeader['store_name'] ?? 'Aura Fine Jewellery' }}. All rights reserved.</span>
            </div>
            <div style="display: flex; gap: 1.5rem; font-size: 1.2rem; color: #C2A878;">
                <a href="#" style="color: inherit;" aria-label="Instagram"><i class="ph-fill ph-instagram-logo"></i></a>
                <a href="#" style="color: inherit;" aria-label="Facebook"><i class="ph-fill ph-facebook-logo"></i></a>
                <a href="#" style="color: inherit;" aria-label="Pinterest"><i class="ph-fill ph-pinterest-logo"></i></a>
                <a href="#" style="color: inherit;" aria-label="YouTube"><i class="ph-fill ph-youtube-logo"></i></a>
            </div>
        </div>
    </div>
</footer>
