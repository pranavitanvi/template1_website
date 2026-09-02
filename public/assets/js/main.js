// Main Javascript for UI interactions

document.addEventListener('DOMContentLoaded', () => {
  initMobileMenu();
  initHeaderScroll();
  updateCartCount();
  updateWishlistCount();
  initScrollAnimations();
  initHeroSlider();
});

function initScrollAnimations() {
  const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.15
  };

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        // Optional: unobserve if we only want it to animate once
        // observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  const elements = document.querySelectorAll('.scroll-reveal, .zoom-in, .product-card, .category-card');
  elements.forEach(el => {
    if(el.classList.contains('product-card') || el.classList.contains('category-card')) {
        el.classList.add('scroll-reveal'); // dynamically add class to cards
    }
    observer.observe(el);
  });
}

function initMobileMenu() {
  const menuBtn = document.querySelector('.mobile-menu-btn');
  const navLinks = document.querySelector('.nav-links');
  
  if (menuBtn && navLinks) {
    menuBtn.addEventListener('click', () => {
      navLinks.classList.toggle('active');
    });
  }
}

function initHeaderScroll() {
  const header = document.querySelector('.header');
  let lastScrollY = window.scrollY;

  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      header.style.boxShadow = '0 4px 10px rgba(0,0,0,0.08)';
    } else {
      header.style.boxShadow = '0 2px 10px rgba(0,0,0,0.05)';
    }
    
    // Optional: Hide header on scroll down, show on scroll up
    /*
    if (window.scrollY > lastScrollY && window.scrollY > 100) {
      header.style.transform = 'translateY(-100%)';
    } else {
      header.style.transform = 'translateY(0)';
    }
    lastScrollY = window.scrollY;
    */
  });
}

function updateCartCount() {
  const cart = JSON.parse(localStorage.getItem('jewellery_cart')) || [];
  const countElements = document.querySelectorAll('.cart-count');
  const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
  
  countElements.forEach(el => {
    el.textContent = totalItems;
    el.style.display = totalItems > 0 ? 'flex' : 'none';
  });
}

function updateWishlistCount() {
  const wishlist = JSON.parse(localStorage.getItem('jewellery_wishlist')) || [];
  const countElements = document.querySelectorAll('.wishlist-count');
  const totalItems = wishlist.length;
  
  countElements.forEach(el => {
    el.textContent = totalItems;
    el.style.display = totalItems > 0 ? 'flex' : 'none';
  });
}

// Global utility for formatting currency
function formatPrice(price) {
  return '\u20b9' + Math.round(price).toLocaleString('en-IN');
}

function initHeroSlider() {
  const slider = document.querySelector('.hero-slider');
  const slides = document.querySelectorAll('.hero-slide');
  if (!slider || slides.length === 0) return;
  
  const dotsContainer = document.createElement('div');
  dotsContainer.className = 'hero-slider-dots';
  
  slides.forEach((_, index) => {
      const dot = document.createElement('div');
      dot.className = `slider-dot ${index === 0 ? 'active' : ''}`;
      dot.addEventListener('click', () => goToSlide(index));
      dotsContainer.appendChild(dot);
  });
  slider.appendChild(dotsContainer);
  
  let currentSlide = 0;
  const dots = document.querySelectorAll('.slider-dot');
  let slideInterval;
  
  function goToSlide(index) {
      slides[currentSlide].classList.remove('active');
      dots[currentSlide].classList.remove('active');
      currentSlide = index;
      slides[currentSlide].classList.add('active');
      dots[currentSlide].classList.add('active');
      resetInterval();
  }
  
  function resetInterval() {
      clearInterval(slideInterval);
      slideInterval = setInterval(() => {
          let nextSlide = (currentSlide + 1) % slides.length;
          goToSlide(nextSlide);
      }, 5000);
  }
  
  resetInterval();
}
