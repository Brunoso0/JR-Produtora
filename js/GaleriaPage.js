document.addEventListener("DOMContentLoaded", function(event) {
    const categories = document.querySelectorAll('.category');
    const dates = document.querySelectorAll('.date');
    const images = document.querySelectorAll('.gallery img');

    function filterImages(categoryFilter, dateFilter) {
        images.forEach(image => {
            const matchesCategory = categoryFilter === 'all' || image.getAttribute('data-category') === categoryFilter;
            const matchesDate = dateFilter === 'all' || image.getAttribute('data-date') === dateFilter;
            image.style.display = (matchesCategory && matchesDate) ? 'block' : 'none';
        });
    }

    categories.forEach(category => {
        category.addEventListener('click', function(event) {
            event.preventDefault();
            const filter = this.getAttribute('data-filter');
            filterImages(filter, 'all');
        });
    });

    dates.forEach(date => {
        date.addEventListener('click', function(event) {
            event.preventDefault();
            const dateFilter = this.getAttribute('data-filter');
            const categoryFilter = this.closest('.dropdown').querySelector('.category').getAttribute('data-filter');
            filterImages(categoryFilter, dateFilter);
        });
    });

    document.querySelector('.navbar .category[data-filter="all"]').click();
});



let slideIndex = 0;
showSlides();

function showSlides() {
    let slides = document.getElementsByClassName("slide");
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.opacity = "0";  
        slides[i].style.display = "none"; 
    }
    slideIndex++;
    if (slideIndex > slides.length) {slideIndex = 1}    
    slides[slideIndex-1].style.display = "block";  
    slides[slideIndex-1].style.opacity = "1";  
    setTimeout(showSlides, 5000); // Change image every 5 seconds
}

function plusSlides(n) {
    slideIndex += n;
    if (slideIndex > document.getElementsByClassName("slide").length) {slideIndex = 1}
    if (slideIndex < 1) {slideIndex = document.getElementsByClassName("slide").length}
    showSpecificSlide(slideIndex);
}

function showSpecificSlide(index) {
    let slides = document.getElementsByClassName("slide");
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.opacity = "0";  
        slides[i].style.display = "none"; 
    }
    slides[index-1].style.display = "block";  
    slides[index-1].style.opacity = "1";
}