/**
 * BookCafe - Main JavaScript
 * Handles navigation, animations, and interactive features
 */

document.addEventListener('DOMContentLoaded', () => {
    // Initialize all modules
    Navigation.init();
    ScrollEffects.init();
    Newsletter.init();
    Animations.init();
});

/**
 * Navigation Module
 * Handles mobile menu toggle and scroll behavior
 */
const Navigation = {
    header: null,
    navToggle: null,
    navMenu: null,
    
    init() {
        this.header = document.querySelector('.site-header');
        this.navToggle = document.querySelector('.nav-toggle');
        this.navMenu = document.querySelector('.nav-menu');
        
        if (this.navToggle && this.navMenu) {
            this.bindEvents();
        }
        
        this.handleScroll();
    },
    
    bindEvents() {
        // Mobile menu toggle
        this.navToggle.addEventListener('click', () => this.toggleMenu());
        
        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.nav-container') && this.navMenu.classList.contains('active')) {
                this.closeMenu();
            }
        });
        
        // Close menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.navMenu.classList.contains('active')) {
                this.closeMenu();
            }
        });
        
        // Close menu when clicking a link
        this.navMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => this.closeMenu());
        });
        
        // Scroll behavior
        window.addEventListener('scroll', () => this.handleScroll(), { passive: true });
    },
    
    toggleMenu() {
        const isOpen = this.navMenu.classList.toggle('active');
        this.navToggle.setAttribute('aria-expanded', isOpen);
        document.body.style.overflow = isOpen ? 'hidden' : '';
    },
    
    closeMenu() {
        this.navMenu.classList.remove('active');
        this.navToggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    },
    
    handleScroll() {
        if (!this.header) return;
        
        if (window.scrollY > 50) {
            this.header.classList.add('scrolled');
        } else {
            this.header.classList.remove('scrolled');
        }
    }
};

/**
 * Scroll Effects Module
 * Handles smooth scrolling and reveal animations
 */
const ScrollEffects = {
    init() {
        this.initSmoothScroll();
        this.initScrollReveal();
    },
    
    initSmoothScroll() {
        // Handle anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                const targetId = anchor.getAttribute('href');
                if (targetId === '#') return;
                
                const target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();
                    const headerHeight = document.querySelector('.site-header')?.offsetHeight || 0;
                    const targetPosition = target.getBoundingClientRect().top + window.scrollY - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    },
    
    initScrollReveal() {
        // Create intersection observer for reveal animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        // Observe elements that should reveal on scroll
        document.querySelectorAll('.section-header, .about-content, .about-image, .info-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    }
};

// Add revealed class styles
document.head.insertAdjacentHTML('beforeend', `
    <style>
        .revealed {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
    </style>
`);

/**
 * Newsletter Module
 * Handles newsletter form submission
 */
const Newsletter = {
    init() {
        const forms = document.querySelectorAll('.newsletter-form');
        forms.forEach(form => this.bindForm(form));
    },
    
    bindForm(form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const emailInput = form.querySelector('input[type="email"]');
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            if (!emailInput.value) return;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'Subscribing...';
            
            try {
                const formData = new FormData();
                formData.append('email', emailInput.value);
                
                const response = await fetch('subscribe.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.showMessage(form, 'success', result.message);
                    emailInput.value = '';
                } else {
                    this.showMessage(form, 'error', result.message);
                }
            } catch (error) {
                this.showMessage(form, 'error', 'Something went wrong. Please try again.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    },
    
    showMessage(form, type, message) {
        // Remove existing message
        const existingMsg = form.parentNode.querySelector('.form-message');
        if (existingMsg) existingMsg.remove();
        
        // Create new message
        const msgEl = document.createElement('div');
        msgEl.className = `form-message form-message-${type}`;
        msgEl.textContent = message;
        msgEl.style.cssText = `
            margin-top: 0.75rem;
            padding: 0.75rem;
            border-radius: 4px;
            font-size: 0.875rem;
            ${type === 'success' 
                ? 'background: rgba(122, 139, 110, 0.2); color: #5a6b4e;' 
                : 'background: rgba(166, 93, 63, 0.2); color: #8b4f35;'
            }
        `;
        
        form.after(msgEl);
        
        // Auto-remove after 5 seconds
        setTimeout(() => msgEl.remove(), 5000);
    }
};

/**
 * Animations Module
 * Handles page load animations and micro-interactions
 */
const Animations = {
    init() {
        this.animateHero();
        this.initHoverEffects();
    },
    
    animateHero() {
        const hero = document.querySelector('.hero');
        if (!hero) return;
        
        const elements = [
            '.hero-badge',
            '.hero-title',
            '.hero-description',
            '.hero-actions'
        ];
        
        elements.forEach((selector, index) => {
            const el = hero.querySelector(selector);
            if (el) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, 100 + (index * 150));
            }
        });
    },
    
    initHoverEffects() {
        // Add subtle scale effect to cards on hover
        const cards = document.querySelectorAll('.menu-card, .book-card, .event-card, .info-card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transition = 'all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)';
            });
        });
    }
};

/**
 * Utility Functions
 */
const Utils = {
    // Debounce function for performance
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    // Throttle function for scroll events
    throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
};

// Export for potential module use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { Navigation, ScrollEffects, Newsletter, Animations, Utils };
}

