// scripts.js
let currentSlide = 0;
let slideInterval;

function showSlide(index) {
    const slides = document.querySelectorAll('.slide');
    if (index >= slides.length) {
        currentSlide = 0;
    } else if (index < 0) {
        currentSlide = slides.length - 1;
    } else {
        currentSlide = index;
    }

    const slideWidth = slides[currentSlide].clientWidth;
    document.querySelector('.slides').style.transform = `translateX(-${currentSlide * slideWidth}px)`;
}

function changeSlide(step) {
    showSlide(currentSlide + step);
}

function startAutoSlide() {
    slideInterval = setInterval(() => {
        changeSlide(1);
    }, 3000); // Muda a cada 3 segundos
}

function stopAutoSlide() {
    clearInterval(slideInterval);
}

// Inicializa o carrossel
document.addEventListener("DOMContentLoaded", () => {
    showSlide(currentSlide);
    startAutoSlide();

    // Para o slide automático ao passar o mouse e retoma ao sair
    const carousel = document.querySelector('.carousel');
    carousel.addEventListener('mouseenter', stopAutoSlide);
    carousel.addEventListener('mouseleave', startAutoSlide);
});
