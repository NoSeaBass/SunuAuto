<?php

function carousel($photos) {
    $total = count($photos);
    if ($total <= 0) {
        return '
        <div class="alert alert-warning text-center">Photo non disponible</div>
        <img src="./media/autres/picture.jpg" alt="vide"/>
        ';
    }
    
    $output = "
    <div id='myCarousel' class='carousel slide carousel-fade' data-bs-ride='carousel' data-bs-interval='8000'>
        <!-- Indicators -->
        <div class='carousel-indicators'>
            <button type='button' data-bs-target='#myCarousel' data-bs-slide-to='0' class='active' aria-current='true' aria-label='Slide 1'></button>";
    
    for ($i = 1; $i < $total; $i++) {
        $output .= "<button type='button' data-bs-target='#myCarousel' data-bs-slide-to='$i' aria-label='Slide " . ($i + 1) . "'></button>";
    }
    
    $output .= "
        </div>
        <!-- Carousel Inner -->
        <div class='carousel-inner'>";
    
    foreach ($photos as $i => $photo) {
        if(imageExists($photo['url'])){
            $isActive = ($i === 0) ? "active" : "";
            $output .= "
                <div class='carousel-item $isActive'>
                    <img src='" . htmlspecialchars($photo['url']) . "' class='d-block w-100' style='height: 750px; object-fit: cover;' alt='slide " . ($i + 1) . "'>
                </div>";
        }
        else{
            $isActive = ($i === 0) ? "active" : "";
            $output .= "
                <div class='carousel-item $isActive'>
                    <img src='./media/autres/picture.jpg' class='d-block w-100' style='height: 750px; object-fit: cover;' alt='slide " . ($i + 1) . "'>
                </div>";
        }
    }
    
    $output .= "
        </div>
        <!-- Controls -->
        <button class='carousel-control-prev' type='button' data-bs-target='#myCarousel' data-bs-slide='prev'>
            <span class='carousel-control-prev-icon' aria-hidden='true'></span>
            <span class='visually-hidden'>Previous</span>
        </button>
        <button class='carousel-control-next' type='button' data-bs-target='#myCarousel' data-bs-slide='next'>
            <span class='carousel-control-next-icon' aria-hidden='true'></span>
            <span class='visually-hidden'>Next</span>
        </button>
    </div>
    <!-- Thumbnails Section -->
    <div class='row mt-3'>";
    
    foreach ($photos as $index => $photo) {
        if(imageExists($photo['url'])){
            $output .= "
            <div class='col-auto'>
                <img src='" . htmlspecialchars($photo['url']) . "' class='img-thumbnail' style='cursor: pointer; height: 60px; width: 60px;' alt='Thumbnail " . ($index + 1) . "' onclick='changeSlide($index)'>
            </div>";
        }
    }
    
    $output .= "
    </div>
    <script>
    function changeSlide(index) {
        var carousel = document.querySelector('#myCarousel');
        var carouselInstance = new bootstrap.Carousel(carousel);
        carouselInstance.to(index);
    }
    </script>";
    
    return $output;
}
?>