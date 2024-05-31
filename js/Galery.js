const items = document.querySelectorAll('.gallery-item');
let currentIndex = 0;

function updateCarousel() {
    items.forEach((item, index) => {
        item.classList.remove('left', 'center', 'right', 'hidden');
        if (index === currentIndex) {
            item.classList.add('center');
        } else if (index === (currentIndex + 1) % items.length) {
            item.classList.add('right');
        } else if (index === (currentIndex - 1 + items.length) % items.length) {
            item.classList.add('left');
        } else {
            item.classList.add('hidden');
        }
    });
}

function showNextItem() {
    currentIndex = (currentIndex + 1) % items.length;
    updateCarousel();
}

setInterval(showNextItem, 3000);
updateCarousel();


