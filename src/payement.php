<?php
include "./fonctions/autoload.php";
include "./fonctions/post.php";
include "./fonctions/cartes.php";
include "./fonctions/carousel.php";
session_start();

$id = $_SESSION["email"] ?? null;

$today = date("Y-m-d");
$currentDate = new DateTime();

$reservation_id = isset($_GET["id_reser"]) ? $_GET["id_reser"] : null;

// Fonction pour afficher les QR codes de paiement
function afficherQRCodesPaiement()
{
    $paymentMethods = [
        [
            "name" => "Orange Money",
            "logo" => "./media/autres/orange.webp",
            "code" => "OM" . rand(100000, 999999),
        ],
        [
            "name" => "Wave",
            "logo" => "./media/autres/wave.webp",
            "code" => "WV" . rand(100000, 999999),
        ],
        [
            "name" => "Yas",
            "logo" => "./media/autres/yas.webp",
            "code" => "YS" . rand(100000, 999999),
        ],
    ];

    $output = '<div class="payment-options">';

    foreach ($paymentMethods as $method) {
        $output .= '<div class="qr-payment-option">';
        $output .=
            '<div class="qr-logo"><img src="' .
            $method["logo"] .
            '" alt="' .
            $method["name"] .
            ' logo"></div>';
        $output .=
            '<div class="qr-code"><img src="./media/autres/qr.png"  alt="QR Code"></div>';
        $output .= '<p class="payment-name">' . $method["name"] . "</p>";
        $output .=
            '<p class="payment-code">Code: <strong>' .
            $method["code"] .
            "</strong></p>";
        $output .= "</div>";
    }

    $output .= "</div>";

    return $output;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./bootstrap/css/base.css">
    <link rel="stylesheet" href="./bootstrap/css/baseCard.css">
    <link rel="stylesheet" href="./bootstrap/css/basePayement.css">
    <link rel="stylesheet" href="./bootstrap/icons/font/bootstrap-icons.css">
    <title>Paiement</title>
</head>
<body>
    <div>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="./"><img src="./media/autres/logo.png" alt="Suñu Auto" class="sunuauto" height="40"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="accueilClient.php">Nos Voitures</a></li>
                <li class="nav-item"><a class="nav-link" href="offres.php">Nos offres</a></li>
                <li class="nav-item"><a class="nav-link" href="favoris.php">Vos Favoris</a></li>
                <li class="nav-item"><a class="nav-link" href="historique.php">Vos réservations</a></li>
                <li class="nav-item"><div class="vr my-2 mx-2 d-none d-lg-block"></div></li>
                <?php if ($id === null): ?>
                    <li class="nav-item"><a class="nav-link" href="./formulaires/connexionClient.php">Connexion</a></li>
                    <li class="nav-item"><a class="nav-link" href="./formulaires/inscription.php">Inscription</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link active" href="./profileClient.php"><i class="bi bi-person-circle"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="./traitement.cacher.php?envoie=deconnexion&pour=client">Déconnexion</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

        <!-- Main Content -->
        <section class="section page-section fade-in">
            <div class="container">
                <!-- Receipt Section -->
                <div class="receipt-container">
                    <h3 class="receipt-title">Votre reçu de paiement</h3>
                    <iframe src="./recu.php?id_reser=<?php echo $reservation_id; ?>" frameborder="0" title="Reçu de paiement"></iframe>
                </div>

                <!-- Online Payment Section -->
                <!-- Online Payment Section -->
                <div class="online-payment-section">
                <h3 class="receipt-title">Ou bien payez en ligne</h3>

                <!-- QR Payment Options -->
                <div class="container mb-5">
                        <?php echo afficherQRCodesPaiement(); ?>
                </div>

                <!-- Credit Card Payment Form -->
                <div class="container mb-5">
                        <div class="row justify-content-center">
                        <div class="col-md-6">
                                <div class="card card-payment">
                                <div class="card-body p-4">
                                        <form>
                                        <!-- Card Number Input -->
                                        <div class="form-group mb-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                <div class="w-75">
                                                        <label for="cardNumber">Numéro de carte</label>
                                                        <input type="text" id="cardNumber" class="form-control"
                                                        placeholder="1234 5678 9012 3457" maxlength="19" />
                                                </div>
                                                <img src="https://img.icons8.com/color/48/000000/visa.png" alt="visa" width="64px" />
                                                </div>
                                        </div>

                                        <!-- Cardholder Name Input -->
                                        <div class="form-group mb-3">
                                                <label for="cardholderName">Nom du titulaire</label>
                                                <input type="text" id="cardholderName" class="form-control"
                                                placeholder="Nom du titulaire" />
                                        </div>

                                        <!-- Expiration and CVV -->
                                        <div class="row mb-3">
                                                <div class="col-md-6 mb-2">
                                                <label for="expiration">Date d'expiration</label>
                                                <input type="text" id="expiration" class="form-control"
                                                        placeholder="MM/AAAA" maxlength="7" />
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                <label for="cvv">CVV</label>
                                                <input type="password" id="cvv" class="form-control"
                                                        placeholder="•••" maxlength="3" />
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end justify-content-center">
                                                <button type="button" class="btn btn-primary payment-btn">
                                                        <i class="bi bi-arrow-right"></i>
                                                </button>
                                                </div>
                                        </div>
                                        </form>
                                </div>
                                </div>
                        </div>
                        </div>
                </div>
                </div>
            </div>
        </section>

        <footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <div class="footer-brand mb-4">
                    <img src="./media/autres/logo.png" alt="Suñu Otto" class="footer-logo" height="60">
                </div>
                <p class="mb-4">Suñu Otto est votre partenaire de confiance pour la location de voitures premium à Dakar et partout au Sénégal. Notre engagement : vous offrir une expérience de location simple, pratique et abordable.</p>
                <div class="social-icons">
                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                <h5>Navigation</h5>
                <ul class="footer-links">
                    <li><a href="#about">À propos</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#engagement">Engagements</a></li>
                    <li><a href="./accueilClient.php">Nos voitures</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5>Nos partenaires</h5>
                <ul class="footer-links">
                    <li><a href="#">Aéroport de Dakar</a></li>
                    <li><a href="#">Hôtels Terrou-Bi</a></li>
                    <li><a href="#">Assurance NSIA</a></li>
                    <li><a href="#">Garage Moderne Auto</a></li>
                    <li><a href="https://www.instagram.com/cristiano/">Cristiano Ronaldo</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <h5>Contactez-nous</h5>
                <div class="contact-info">
                    <p><i class="bi bi-geo-alt me-2"></i>123 Rue de la Location, Dakar</p>
                    <p><i class="bi bi-telephone me-2"></i>+221 77 123 45 67</p>
                    <p><i class="bi bi-envelope me-2"></i>contact@sunuotto.sn</p>
                    <p><i class="bi bi-clock me-2"></i>Lun-Ven: 8h-18h, Sam: 9h-15h</p>
                </div>
                <div class="mt-4">
                    <a href="./formulaires/connextionGerant.php" class="btn btn-outline-light">Espace gérants</a>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2025 Suñu Otto - Tous droits réservés.</p>
            <div class="footer-bottom-links">
                <a href="#">Politique de confidentialité</a>
                <span class="mx-2">|</span>
                <a href="#">Conditions d'utilisation</a>
                <span class="mx-2">|</span>
                <a href="#">FAQ</a>
            </div>
        </div>
    </div>
</footer>

    <!-- JavaScript -->
    <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
