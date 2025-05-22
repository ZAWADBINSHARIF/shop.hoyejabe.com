import './bootstrap';



document.addEventListener('alpine:init', () => {
    Alpine.store('cartSlider', {
        slideOverOpen: false
    });
});