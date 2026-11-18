<?php
include "./fonctions/autoload.php";
include "./fonctions/post.php";
include "./fonctions/cartes.php";
include "./fonctions/carousel.php";
session_start();

$id = $_SESSION["email"] ?? null;

$today = date("Y-m-d");
$currentDate = new DateTime();
$maxReservationDays = 30; 
$maxDate = date("Y-m-d", strtotime($today . " +" . $maxReservationDays . " days"));

$errorMessages = [
    "dates" => 'Les dates sélectionnées sont invalides. La date d\'arrivée doit être postérieure à la date de départ.',
    "unavailable" => 'Ce véhicule n\'est pas disponible aux dates sélectionnées.',
    "login" => "Veuillez vous connecter pour effectuer une réservation.",
    "default" => 'Une erreur s\'est produite. Veuillez réessayer.',
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./bootstrap/css/base.css">
    <link rel="stylesheet" href="./bootstrap/css/baseCard.css">
    <link rel="stylesheet" href="./bootstrap/icons/font/bootstrap-icons.css">
    <title>Réservations - Suñu Otto</title>
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

    <!-- Selected Vehicle Section -->
    <section class="section page-section fade-in">
        <div class="container">
            <div class="alert alert-primary shadow" role="alert">
                <div>
                    <?php if (isset($_GET["code"])) {
                        $code = htmlspecialchars($_GET["code"]);
                        $voiture = new voiture(null, null, null, null, null, null, null);
                        $offre = new offre(null, $code, null, null, null);
                        $table = $voiture->recupCode($code);

                        if (!empty($table)) {
                            $photos = $voiture->recupPhotoCode($code);
                            $verifiOffre = $offre->verifieOffre();

                            // Use null coalescing to simplify logic
                            $tarif = $verifiOffre["tarif_reduit"] ?? $table[0]["tarif_reserv"];

                            echo "<h3 class='mb-3'>Voiture sélectionnée:</h3>";

                            foreach ($table as $row) {
                                // Create client object only if needed
                                $isFavori = $id ? (new client($id))->estFavori($row["code"]) : false;
                                $heartIconClass = $isFavori ? "bi-heart-fill" : "bi-heart";
                                $actionFavori = $isFavori ? "supprimerFavori" : "ajouterFavori";
                                $codeVoiture = htmlspecialchars($row["code"]);
                                ?>
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h4><?= htmlspecialchars($row["marque"]) ?> <?= htmlspecialchars($row["modele"]) ?></h4>
                                        <p><?= htmlspecialchars($row["description"]) ?></p>
                                    </div>
                                    <div class="favorite-button-container">
                                        <a href="./traitement.cacher.php?envoie=<?= $actionFavori ?>&code=<?= $codeVoiture ?>" class="btn btn-outline-danger btn-sm">
                                            <i class="bi <?= $heartIconClass ?>"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-secondary">Code: <?= $codeVoiture ?></span>

                                    <?php if (is_null($verifiOffre)): ?>
                                        <h5 class="mb-0 text-primary"><?= number_format($row["tarif_reserv"], 0, ",", " ") ?> FCFA/jour</h5>
                                    <?php else: ?>
                                        <div class="text-end">
                                            <del class="text-muted"><?= number_format($row["tarif_reserv"], 0, ",", " ") ?> FCFA/jour</del>
                                            <h5 class="mb-0 text-primary"><?= number_format($verifiOffre["tarif_reduit"], 0, ",", " ") ?> FCFA/jour</h5>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Véhicule non trouvé.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-warning'>Aucun véhicule sélectionné.</div>";
                    } ?>
                </div>

                <!-- Vehicle Gallery Section -->
                <section>
                    <div class="row">
                        <div class="container mt-4 col-12 card shadow card-body">
                            <?php echo carousel($photos ?? []); ?>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Reservation Form Section -->
            <div class="container">
                <div class="card p-4 shadow reservation-form">
                    <h4 class="mb-3"><i class="bi bi-calendar-check me-2"></i>Sélectionnez vos dates de réservation</h4>
                    
                    <!-- Information réservation maximum -->
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Les réservations sont limitées à <?= $maxReservationDays ?> jours maximum.
                    </div>
                    
                    <?php if (!$id): ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Veuillez vous <a href="./formulaires/connexionClient.php" class="alert-link">connecter</a> pour effectuer une réservation.
                        </div>
                    <?php else: ?>
                        <form action="./traitement.cacher.php" method="POST" id="reservationForm">
                            <input type="hidden" name="code" value="<?= isset($code) ? htmlspecialchars($code) : "" ?>">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                            <input type="hidden" name="tarif" value="<?= isset($tarif) ? htmlspecialchars($tarif) : "" ?>">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="depart" class="form-label">Date de départ</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar-plus"></i></span>
                                        <input type="date" class="form-control" name="depart" id="depart"
                                               min="<?= $today ?>"
                                               value="<?= $today ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="arrive" class="form-label">Date d'arrivée</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar-minus"></i></span>
                                        <input type="date" class="form-control" name="arrive" id="arrive"
                                               min="<?= date("Y-m-d", strtotime($today . " +1 day")) ?>"
                                               max="<?= $maxDate ?>" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div id="reservation-summary" class="alert alert-info mt-3" style="display: none;">
                                        <p><strong>Résumé de votre réservation:</strong></p>
                                        <p>Durée: <span id="duration"></span> jour(s)</p>
                                        <p>Prix total: <span id="total-price"></span> FCFA</p>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" name="envoie" value="reservation" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>Réserver maintenant
                                    </button>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Error Messages Section -->
            <?php if (isset($_GET["error"])) {
                $errorType = $_GET["error"];
                $errorMessage = $errorMessages[$errorType] ?? $errorMessages["default"];

                echo "
                <div class='container mt-3'>
                    <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                        <i class='bi bi-exclamation-triangle-fill me-2'></i><strong>Erreur !</strong> " . htmlspecialchars($errorMessage) . "
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                </div>
                ";
            } ?>
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

    <!-- JavaScript for reservation calculation -->
    <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const departInput = document.getElementById('depart');
        const arriveInput = document.getElementById('arrive');
        const summaryDiv = document.getElementById('reservation-summary');
        const durationSpan = document.getElementById('duration');
        const totalPriceSpan = document.getElementById('total-price');
        const maxDays = <?= $maxReservationDays ?>;

        // Store tarif value for calculations
        const tarifPerDay = <?= isset($tarif) ? (int) $tarif : 0 ?>;

        function updateSummary() {
            if(departInput.value && arriveInput.value) {
                const departDate = new Date(departInput.value);
                const arriveDate = new Date(arriveInput.value);

                // Calculate difference in days
                const timeDiff = arriveDate - departDate;
                const dayDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

                if(dayDiff > 0) {
                    // Update summary
                    durationSpan.textContent = dayDiff;
                    totalPriceSpan.textContent = new Intl.NumberFormat('fr-FR').format(dayDiff * tarifPerDay);
                    summaryDiv.style.display = 'block';
                } else {
                    summaryDiv.style.display = 'none';
                }
            } else {
                summaryDiv.style.display = 'none';
            }
        }

        // Add event listeners
        if(departInput && arriveInput) {
            departInput.addEventListener('change', function() {
                if(departInput.value) {
                    // Set minimum arrival date (next day)
                    const nextDay = new Date(departInput.value);
                    nextDay.setDate(nextDay.getDate() + 1);
                    arriveInput.min = nextDay.toISOString().split('T')[0];
                    
                    // Set maximum arrival date (departure + max days)
                    const maxDay = new Date(departInput.value);
                    maxDay.setDate(maxDay.getDate() + maxDays);
                    arriveInput.max = maxDay.toISOString().split('T')[0];

                    // Reset arrival date if it's now invalid
                    if(arriveInput.value && new Date(arriveInput.value) <= new Date(departInput.value)) {
                        arriveInput.value = nextDay.toISOString().split('T')[0];
                    }
                    
                    // Reset arrival date if it's beyond maximum
                    if(arriveInput.value && new Date(arriveInput.value) > maxDay) {
                        arriveInput.value = maxDay.toISOString().split('T')[0];
                    }

                    updateSummary();
                }
            });
            
            arriveInput.addEventListener('change', updateSummary);
            
            // Initial update if dates already set
            if(departInput.value) {
                const maxDay = new Date(departInput.value);
                maxDay.setDate(maxDay.getDate() + maxDays);
                arriveInput.max = maxDay.toISOString().split('T')[0];
            }
            
            updateSummary();
        }
    });
    </script>
</body>
</html>