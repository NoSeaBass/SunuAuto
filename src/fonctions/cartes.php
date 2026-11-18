<?php
function imageExists($url)
{
    return @getimagesize($url); 
}

function carte($voiture, $id)
{
    include "./fonctions/connexion.php";

    $code = htmlspecialchars($voiture["code"]);
    $marque = htmlspecialchars($voiture["marque"]);
    $modele = htmlspecialchars($voiture["modele"]);
    $description = htmlspecialchars($voiture["description"]);
    $tarif = number_format($voiture["tarif_reserv"], 0, ",", " ");
    $quantity = htmlspecialchars($voiture["qt_disponible"]);

    $favoris = $connexion->prepare(
        "SELECT 1 FROM favorie WHERE code = ? AND id = ? LIMIT 1"
    );
    $favoris->execute([$code, $id]);
    $isFavorite = $favoris->fetchColumn();

    $action = $isFavorite ? "supprimerFavori" : "ajouterFavori";
    $heartIconClass = $isFavorite ? "bi-heart-fill" : "bi-heart";

    $photos = $connexion->prepare("SELECT url FROM photo WHERE code = ?");
    $photos->execute([$code]);
    $photos = $photos->fetchAll(PDO::FETCH_COLUMN);

    $today = date("Y-m-d");
    $verifiOffre = $connexion->prepare("
    SELECT tarif_reduit
    FROM offre
    WHERE code = ? AND debut_offr <= ? AND fin_offr >= ?
    LIMIT 1
    ");
    $verifiOffre->execute([$code, $today, $today]);
    $offrePrice = $verifiOffre->fetchColumn();
        
        $output = "<div class='col-lg-4 col-md-6'>
        <div class='card car-card h-100 shadow-sm position-relative'>";
        
        $output .= "<div class='favorite-button-container position-absolute top-0 end-0 m-2' style='z-index: 1000;'>
        <a href='./traitement.cacher.php?envoie=$action&code=$code' class='btn btn-light btn-sm favorite-toggle rounded-circle' onclick='event.stopPropagation();'>
        <i class='bi $heartIconClass text-danger'></i>
        </a>
        </div>";
    
    $output .= "<a href='./reservation.php?code=$code' class='text-decoration-none text-dark d-block h-100'>";
    
    $output .= "<div class='position-relative'>";

    if (!empty($photos)) {
        $output .= "<div id='voiture-carousel-$code' class='carousel carousel-fade slide' data-bs-ride='carousel' data-bs-interval='3000'><div class='carousel-inner'>";

        foreach ($photos as $index => $photoUrl) {
            if (imageExists($photoUrl)) {
                $active = $index === 0 ? "active" : "";
                $output .=
                "<div class='carousel-item $active'><img src='" .
                htmlspecialchars($photoUrl) .
                "' class='card-img-top' alt='$marque $modele'></div>";
            }else{
                $output .= "<div class='carousel-item active'><img src='./media/autres/picture.jpg' class='card-img-top' alt='$marque $modele'></div>";
            }
        }


        $output .= "</div></div>"; 
    } else {
        $output .= "<img src='./media/autres/picture.jpg' class='card-img-top' alt='$marque $modele'>";
    }

    $output .= "<div class='card-body'>
    <h5 class='card-title'>$marque $modele</h5>
    <p class='card-text text-muted'>$description</p>
    <span class='badge bg-secondary'>Disponible: $quantity</span>";

    if ($offrePrice) {
        $formattedOffrePrice = number_format($offrePrice, 0, ",", " ");
        $output .= "<div class='text-end'>
        <del class='text-danger'>$tarif FCFA/jour</del>
        <h5 class='text-success'>$formattedOffrePrice FCFA/jour</h5>
        </div>";
    } else {
        $output .= "<div class='text-end'><h5 class='text-success'>$tarif FCFA/jour</h5></div>";
    }

    $output .= "</div></div></a></div></div>";

    return $output;
}
