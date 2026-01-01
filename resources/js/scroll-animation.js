/**
 * Scroll Animation Observer
 * Triggers animations when elements enter the viewport
 */
document.addEventListener('DOMContentLoaded', function () {
    // Elements to animate
    const animatedElements = document.querySelectorAll('.fade-up, .fade-left, .fade-right, .scale-up, .animate-on-scroll');

    if (animatedElements.length === 0) return;

    // Intersection Observer configuration
    const observerOptions = {
        root: null, // viewport
        rootMargin: '0px',
        threshold: 0.1 // trigger when 10% visible
    };

    // Observer callback
    const observerCallback = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
                // Unobserve after animation triggered
                observer.unobserve(entry.target);
            }
        });
    };

    // Create observer
    const observer = new IntersectionObserver(observerCallback, observerOptions);

    // Observe all animated elements
    animatedElements.forEach(element => {
        observer.observe(element);
    });
});
