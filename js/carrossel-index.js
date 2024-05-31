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