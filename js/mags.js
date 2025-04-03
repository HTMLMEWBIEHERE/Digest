// Magazine Carousel
document.addEventListener("DOMContentLoaded", function () {
    const track = document.querySelector(".magazine-track");
    const prevBtn = document.querySelector(".prev-magazine-btn");
    const nextBtn = document.querySelector(".next-magazine-btn");
    const cards = document.querySelectorAll(".magazine-card");

    if (cards.length === 0) return; // Prevent errors if no cards exist

    let index = 0;
    const visibleCards = 3; // Number of visible cards
    const cardWidth = cards[0].getBoundingClientRect().width; // Accurate width calculation
    const gap = parseFloat(window.getComputedStyle(track).gap || 30); // Read CSS gap (default 30px)
    const totalCards = cards.length;
    const maxIndex = totalCards - visibleCards;

    function updateCarousel() {
        const offset = -index * (cardWidth + gap);
        track.style.transform = `translateX(${offset}px)`;
    }

    nextBtn.addEventListener("click", () => {
        if (index < maxIndex) {
            index++;
        } else {
            index = 0; // Loop back to start
        }
        updateCarousel();
    });

    prevBtn.addEventListener("click", () => {
        if (index > 0) {
            index--;
        } else {
            index = maxIndex; // Loop to end
        }
        updateCarousel();
    });

    // Ensure the carousel starts at the correct position
    updateCarousel();
});
