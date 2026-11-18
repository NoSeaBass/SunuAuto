<?php
include "./fonctions/autoload.php";
include "./fonctions/cartes.php";

session_start();

$idCourant = isset($_SESSION["email"]) ? $_SESSION["email"] : null;

$voitureList = new voiture(null, null, null, null, 0, 0, 0);
$voitureList = $voitureList->listeVoiture();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css?v=<?= time() ?>">
    <link rel="stylesheet" href="./bootstrap/css/base.css?v=<?= time() ?>">
    <link rel="stylesheet" href="./bootstrap/css/index.css?v=<?= time() ?>">
    <link rel="stylesheet" href="./bootstrap/css/baseCard.css?v=<?= time() ?>">
    <link rel="stylesheet" href="./bootstrap/css/baseSlider.css?v=<?= time() ?>">
    <link rel="stylesheet" href="./bootstrap/icons/font/bootstrap-icons.css?v=<?= time() ?>">
    <title>Suñu Otto - Location de Voitures Premium au Sénégal</title>
    <meta name="description" content="Location de voitures à Dakar et partout au Sénégal. Service premium, véhicules récents et tarifs compétitifs.">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand text-light" href="#">
                <img src="./media/autres/logo.png" alt="Suñu Otto" class="sunuauto">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#about">À propos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#engagement">Engagements</a></li>
                    <li class="nav-item"><a class="nav-link" href="./accueilClient.php">Nos voitures</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>

                    <li class="nav-item">
                        <div class="vr bg-light my-2 mx-2 d-none d-lg-block"></div>
                    </li>

                    <?php if (is_null($idCourant)): ?>
                        <li class="nav-item"><a class="nav-link" href="./formulaires/connexionClient.php">Connexion</a></li>
                        <li class="nav-item"><a class="nav-link" href="./formulaires/inscription.php">Inscription</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="./profileClient.php"><i class="bi bi-person-circle"></i></a></li>
                        <li class="nav-item"><a class="nav-link" href="./traitement.cacher.php?envoie=deconnexion&pour=client">Déconnexion</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <header class="masthead">
    <div class="container h-100">
        <div class="row h-100">
            <div class="col-12 d-flex align-items-center justify-content-center">
                <div class="logo-container text-center">
                    <img src="./media/autres/logo.png" alt="Suñu Otto" class="masthead-logo">
                    <h1 class="display-4 fw-bold mt-3">Votre agence de location premium</h1>
                    <a href="#about" class="btn btn-primary btn-lg mt-3 rounded-pill">Découvrir</a>
                </div>
            </div>
        </div>
    </div>
</header>

<?php if (count($voitureList) > 0): ?>
<?php $i=0; ?>
<div class="slider-overlap-container">
    <div class="card-slider-container">
        <div class="card-slider">
            <?php foreach ($voitureList as $voiture): ?>
                <?php $i++; ?>
                <?php
                $cardHTML = carte($voiture, $idCourant);
                $cardHTML = str_replace('<div class=\'col-lg-4 col-md-6\'>', '<div class="card-slide">', $cardHTML);
                $cardHTML = str_replace("z-10", "z-20", $cardHTML);
                $cardHTML = str_replace("favorite-button-container position-absolute top-0 start-0 m-2", "favorite-button-container position-absolute top-0 start-0 m-3", $cardHTML);
                echo $cardHTML;
                if($i == 20){break;}
                ?>
            <?php endforeach; ?>
        </div>

        <button class="slider-nav prev-btn">
            <i class="bi bi-chevron-left"></i>
        </button>
        <button class="slider-nav next-btn">
            <i class="bi bi-chevron-right"></i>
        </button>
    </div>
</div>
<?php endif;?>

    <section class="page-section" id="about">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">À propos</h2>
                <h3 class="section-subheading text-muted">Votre partenaire de confiance depuis 2023</h3>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <p class="lead">Suñu Otto est votre partenaire de confiance pour la location de voitures à Dakar. Nous nous engageons à offrir une expérience de location simple, pratique et abordable, que vous ayez besoin d'un véhicule pour une courte escapade en ville ou pour un séjour prolongé.</p>
                    <p class="lead"><strong>Notre flotte diversifiée</strong></p>
                    <p>Nous disposons d'une large gamme de véhicules pour répondre à tous vos besoins, des citadines économiques idéales pour les déplacements urbains aux SUV spacieux parfaits pour les aventures en famille. Tous nos véhicules sont régulièrement entretenus pour garantir votre sécurité et votre confort.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section bg-light" id="services">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Nos Services</h2>
                <h3 class="section-subheading text-muted">Des solutions adaptées à vos besoins</h3>
            </div>
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="service-icon">
                        <i class="bi bi-car-front"></i>
                    </div>
                    <h4 class="my-3">Location Flexible</h4>
                    <p class="text-muted">Location de voitures à court et long terme adaptée à vos besoins. Que ce soit pour un jour ou plusieurs mois, nous avons la solution idéale pour vous.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="service-icon">
                        <i class="bi bi-tools"></i>
                    </div>
                    <h4 class="my-3">Assistance 24/7</h4>
                    <p class="text-muted">Notre service d'assistance routière est disponible 24h/24 et 7j/7 pour vous assurer une tranquillité d'esprit totale pendant toute la durée de votre location.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="service-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h4 class="my-3">Livraison & Ramassage</h4>
                    <p class="text-muted">Nous proposons des options de livraison et de ramassage pour plus de commodité. Notre équipe peut vous livrer le véhicule directement à l'aéroport ou à votre hôtel.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section" id="engagement">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Notre engagement</h2>
                <h3 class="section-subheading text-muted">La satisfaction client au cœur de nos préoccupations</h3>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <p class="lead">Chez Suñu Otto, nous accordons une importance primordiale à la satisfaction de nos clients. Notre équipe dévouée est là pour vous accompagner à chaque étape, de la réservation à la restitution du véhicule. Nous sommes fiers de notre service client personnalisé et de notre engagement envers la qualité.</p>
                    <p>Nos engagements incluent :</p>
                    <ul class="engagement-list text-start lead mb-5">
                        <li>Des tarifs compétitifs et transparents sans frais cachés</li>
                        <li>Des véhicules propres, bien entretenus et révisés régulièrement</li>
                        <li>Un processus de réservation simple et efficace</li>
                        <li>Une assurance complète pour une protection optimale</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section bg-light" id="contact">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Contactez-nous</h2>
                <h3 class="section-subheading text-muted">Nous sommes à votre écoute pour toute question</h3>
            </div>
            <div class="row align-items-stretch mb-5">
                <div class="col-md-6">
                    <div class="bg-white p-4 h-100 shadow-sm rounded contact-info-card">
                        <h4 class="mb-4">Nos coordonnées</h4>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="bi bi-geo-alt-fill text-primary fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <p class="mb-0"><strong>Adresse</strong></p>
                                <p class="mb-0">123 Rue de la Location, Dakar, Sénégal</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="bi bi-telephone-fill text-primary fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <p class="mb-0"><strong>Téléphone</strong></p>
                                <p class="mb-0">+221 77 123 45 67</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <i class="bi bi-envelope-fill text-primary fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <p class="mb-0"><strong>Email</strong></p>
                                <p class="mb-0">contact@sunuotto.sn</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="bi bi-clock-fill text-primary fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <p class="mb-0"><strong>Heures d'ouverture</strong></p>
                                <p class="mb-0">Lundi - Vendredi : 8h - 18h</p>
                                <p class="mb-0">Samedi : 9h - 15h</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <form id="contactForm" class="bg-white p-4 h-100 shadow-sm rounded contact-form">
                        <div class="form-group mb-3">
                            <input class="form-control" id="name" type="text" placeholder="Votre nom *" required>
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" id="email" type="email" placeholder="Votre email *" required>
                        </div>
                        <div class="form-group mb-3">
                            <input class="form-control" id="phone" type="tel" placeholder="Votre téléphone *" required>
                        </div>
                        <div class="form-group mb-3">
                            <textarea class="form-control" id="message" placeholder="Votre message *" style="height: 10rem;" required></textarea>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary btn-lg rounded-pill text-uppercase" id="submitButton" type="submit">Envoyer le message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section" id="team">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">L'équipe derrière ce projet</h2>
                <h3 class="section-subheading text-muted">Des professionnels passionnés à votre service</h3>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="team-member">
                        <img class="mx-auto team-avatar" src="./media/autres/K.jpeg" alt="Khady Faye">
                        <h4>Khady Faye</h4>
                        <p class="text-muted">Experte en développement web</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="team-member">
                        <img class="mx-auto team-avatar" src="./media/autres/ImMe.png" alt="Papa Ibrahima Diop">
                        <h4>Papa Ibrahima Diop</h4>
                        <p class="text-muted">Spécialiste UI/UX</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <p class="large text-muted">Notre équipe combine expertise technique et passion pour le service client afin de vous offrir la meilleure expérience de location de voitures à Dakar.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="py-5 bg-light partners-section">
        <div class="container">
            <div class="text-center mb-4">
                <h3>Nos partenaires</h3>
            </div>
            <div class="row align-items-center">
                <div class="col-md-3 col-sm-6 my-3 text-center partner-item">
                    <a href="#!" class="text-decoration-none">
                        <i class="bi bi-building fs-1 text-primary"></i>
                        <p class="mt-2 mb-0">Aéroport de Dakar</p>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 my-3 text-center partner-item">
                    <a href="#!" class="text-decoration-none">
                        <i class="bi bi-house fs-1 text-primary"></i>
                        <p class="mt-2 mb-0">Hôtels Terrou-Bi</p>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 my-3 text-center partner-item">
                    <a href="#!" class="text-decoration-none">
                        <i class="bi bi-shield-check fs-1 text-primary"></i>
                        <p class="mt-2 mb-0">Assurance NSIA</p>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6 my-3 text-center partner-item">
                    <a href="#!" class="text-decoration-none">
                        <i class="bi bi-tools fs-1 text-primary"></i>
                        <p class="mt-2 mb-0">Garage Moderne Auto</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

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

    <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="./bootstrap/js/baseSlider.js"></script>
    <script src="./bootstrap/js/index.js"></script>
</body>
</html>

