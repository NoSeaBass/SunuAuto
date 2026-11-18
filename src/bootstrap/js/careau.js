document.addEventListener('DOMContentLoaded', function() {
    const mainImage = document.getElementById('mainImage');
    const thumbnails = document.querySelectorAll('.gallery-thumbnail');
    const prevButton = document.getElementById('prevButton');
    const nextButton = document.getElementById('nextButton');
    
    let currentImageIndex = 0;
    const images = Array.from(thumbnails).map(thumb => thumb.src);
    mainImage.src = images[currentImageIndex];

    // Update main image when thumbnail is clicked
    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('click', () => {
            mainImage.src = thumbnail.src;
            currentImageIndex = index;
        });
    });

    // Previous button functionality
    prevButton.addEventListener('click', () => {
        currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
        mainImage.src = images[currentImageIndex];
    });

    // Next button functionality
    nextButton.addEventListener('click', () => {
        currentImageIndex = (currentImageIndex + 1) % images.length;
        mainImage.src = images[currentImageIndex];
    });

    setInterval(function (){
        currentImageIndex = (currentImageIndex + 1) % images.length;
        mainImage.src = images[currentImageIndex];
    }, 5000);
});