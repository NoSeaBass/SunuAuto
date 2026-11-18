<?php

session_start();

$id = $_SESSION["email"] ?? null;

require_once "./fonctions/autoload.php";
require_once "./fonctions/cartes.php";
require_once "./fonctions/connexion.php";

$voiture = new offre(null, null, null, null, null);

$voitures = $voiture->listeOffreVoiture();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil Client</title>
    <link href="./bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="./bootstrap/css/base.css" rel="stylesheet">
    <link href="./bootstrap/css/baseCard.css" rel="stylesheet">
    <link rel="stylesheet" href="./bootstrap/icons/font/bootstrap-icons.css">
</head>
<body>

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

<div class="content-container">
    <section class="page-section section voitures-section" id="voitures">
        <div class="container">
            <h2 class="text-center mb-5">Offres</h2>
            <div class="row g-4">
                <?php if (empty($voitures)): ?>
                    <div class="col-12 text-center"><p>Aucune voiture disponible actuellement.</p></div>
                <?php else: ?>
                    <?php foreach ($voitures as $v): ?>
                        <?= carte($v, $id); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
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
</body>
</html>

