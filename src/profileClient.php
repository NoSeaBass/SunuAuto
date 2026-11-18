<?php
session_start();
$id = $_SESSION["email"] ?? null;

// Redirection si non connecté
if ($id === null) {
    header("Location: ./formulaires/connexionClient.php");
    exit;
}

require_once "./fonctions/autoload.php";
require_once "./fonctions/cartes.php"; 

$client = new client($id);

$clientData = NULL;

if(!is_null($client->get(0))){
        $clientData= [
                'prenom' => $client->get(1),
                'nom' => $client->get(0),
                'telephone' => $client->get(6),
                'email' => $client->get(5),
                'sexe' => $client->get(2),
                'profession' => $client->get(3),
                'status' => $client->get(4)
            ];
 }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.css?v=<?= time() ?>">
    <link rel="stylesheet" href="./bootstrap/css/base.css?v=<?= time() ?>">
    <link rel="stylesheet" href="./bootstrap/icons/font/bootstrap-icons.css">
    <title>Profil Client - Suñu Otto</title>
    <style>
        
        .profile-section {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .profile-header {
            position: relative;
            margin-bottom: 30px;
        }
        
        .profile-banner {
            height: 140px;
            background: linear-gradient(135deg, #4169E1 0%, #1E3B8C 100%);
            border-radius: 8px 8px 0 0;
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #4169E1;
            color: white;
            position: absolute;
            bottom: -50px;
            left: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2.5rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        
        .profile-info {
            padding-top: 60px;
            padding-left: 10px;
        }
        
        .tab-content {
            padding: 20px 10px;
        }
        
        
        
       
    </style>
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

    <?php if(is_null($clientData)): ?>
    <div class="content-container">
        <div class="container">
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Veuillez vous <a href="./formulaires/connexionClient.php" class="alert-link">connecter</a> pour effectuer une réservation.
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="content-container">
        <div class="container">
            <section class="profile-section" id="profile">
                <div class="profile-header">
                    <div class="profile-banner"></div>
                    <div class="profile-avatar"><i class="bi bi-person"></i></div>
                </div>
                <div class="profile-info">
                    <h2><?= $clientData['prenom'] ?? '' ?> <?= $clientData['nom'] ?? '' ?></h2>
                    <p><i class="bi bi-envelope me-2"></i><?= $id ?></p>
                </div>
                <?php if($clientData['status'] == 'bloque'): ?>
                    <div class="container">
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Votre compte est <a href="./bloque.php" class="alert-link">bloqué</a> par l'administrateur.
                        </div>
                    </div>
                <?php endif; ?>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="info">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="card-header bg-white">
                                        <h4 class="mb-0">Informations personnelles</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-sm-3"><strong>Nom:</strong></div>
                                            <div class="col-sm-9"><?= $clientData['nom'] ?? 'Non renseigné' ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3"><strong>Prénom:</strong></div>
                                            <div class="col-sm-9"><?= $clientData['prenom'] ?? 'Non renseigné' ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3"><strong>Email:</strong></div>
                                            <div class="col-sm-9"><?= $id ?></div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3"><strong>Téléphone:</strong></div>
                                            <div class="col-sm-9"><?= $clientData['telephone'] ?? 'Non renseigné' ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Closing tab-content -->
            </section> <!-- Closing profile-section -->
        </div> <!-- Closing container -->
    </div> <!-- Closing content-container -->
<?php endif; ?>

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
