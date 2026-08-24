/**
 * ==============================================================================
 * Quetta Tech Solutions - Frontend JavaScript Interactions
 * ==============================================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Auto-dismiss alerts after 6 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) {
                bsAlert.close();
            }
        }, 6000);
    });

    // 2. Animated Stats Counter
    const statCounters = document.querySelectorAll('.stat-number[data-count]');
    if ('IntersectionObserver' in window && statCounters.length > 0) {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const target = parseInt(el.getAttribute('data-count'), 10);
                    const duration = 1500;
                    const stepTime = Math.abs(Math.floor(duration / target)) || 20;
                    let current = 0;
                    
                    const timer = setInterval(() => {
                        current += Math.ceil(target / (duration / stepTime));
                        if (current >= target) {
                            el.textContent = target.toLocaleString() + (el.dataset.suffix || '');
                            clearInterval(timer);
                        } else {
                            el.textContent = current.toLocaleString() + (el.dataset.suffix || '');
                        }
                    }, stepTime);
                    
                    obs.unobserve(el);
                }
            });
        }, { threshold: 0.5 });

        statCounters.forEach(counter => observer.observe(counter));
    }

    // 3. Client-side Form Validation for Contact Page
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', (event) => {
            if (!contactForm.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            contactForm.classList.add('was-validated');
        });
    }

    // 4. Smooth Anchor Scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId && targetId !== '#') {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });
});
