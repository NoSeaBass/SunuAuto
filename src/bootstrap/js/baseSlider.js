document.addEventListener('DOMContentLoaded', function() {
        // Get slider elements
        const slider = document.querySelector('.card-slider');
        const slides = document.querySelectorAll('.card-slide');
        const prevBtn = document.querySelector('.prev-btn');
        const nextBtn = document.querySelector('.next-btn');
        
        // Initialize variables
        let currentIndex = 0;
        const totalSlides = slides.length;
        
        // Function to update slider position and classes
        function updateSlider() {
            // Remove all positioning classes from slides
            slides.forEach(slide => {
                slide.classList.remove('active', 'prev-1', 'next-1', 'prev-2', 'next-2');
            });
            
            // Calculate indices for surrounding slides (with wrapping)
            const prevIndex1 = (currentIndex - 1 + totalSlides) % totalSlides;
            const nextIndex1 = (currentIndex + 1) % totalSlides;
            const prevIndex2 = (currentIndex - 2 + totalSlides) % totalSlides;
            const nextIndex2 = (currentIndex + 2) % totalSlides;
            
            // Add appropriate classes
            slides[currentIndex].classList.add('active');
            slides[prevIndex1].classList.add('prev-1');
            slides[nextIndex1].classList.add('next-1');
            slides[prevIndex2].classList.add('prev-2');
            slides[nextIndex2].classList.add('next-2');
        }
        
        // Initialize the slider
        updateSlider();
        
        // Add event listeners for navigation buttons
        prevBtn.addEventListener('click', function() {
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            updateSlider();
        });
        
        nextBtn.addEventListener('click', function() {
            currentIndex = (currentIndex + 1) % totalSlides;
            updateSlider();
        });
        
        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                updateSlider();
            } else if (e.key === 'ArrowRight') {
                currentIndex = (currentIndex + 1) % totalSlides;
                updateSlider();
            }
        });
        
        // Optional: Add swipe support for mobile devices
        let touchStartX = 0;
        let touchEndX = 0;
        
        const sliderContainer = document.querySelector('.card-slider-container');
        
        sliderContainer.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, false);
        
        sliderContainer.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, false);
        
        function handleSwipe() {
            if (touchEndX < touchStartX - 50) {
                // Swipe left - go to next
                currentIndex = (currentIndex + 1) % totalSlides;
                updateSlider();
            } else if (touchEndX > touchStartX + 50) {
                // Swipe right - go to previous
                currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                updateSlider();
            }
        }
        
        // Optional: Auto-rotation (uncomment to enable)
        let autoRotateInterval = setInterval(function() {
            currentIndex = (currentIndex + 1) % totalSlides;
            updateSlider();
        }, 2750);
        
        sliderContainer.addEventListener('mouseenter', function() {
            clearInterval(autoRotateInterval);
        });
        
        sliderContainer.addEventListener('mouseleave', function() {
            autoRotateInterval = setInterval(function() {
                currentIndex = (currentIndex + 1) % totalSlides;
                updateSlider();
            }, 5000);
        });
    });