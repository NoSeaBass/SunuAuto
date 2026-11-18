<?php
session_start();
if (isset($_SESSION["email"])) {
    $id = $_SESSION["email"];
} else {
    header("Location: ./formulaires/connexionClient.php");
    exit();
}

include "./fonctions/autoload.php";
$reserv = new reservation();
$historique = $reserv->historique($id);


$reservationsActives = [];
$reservationsHistorique = [];

foreach ($historique as $row) {
    if ($row["status_reser"] == "suivie" || $row["status_reser"] == "en cours" || $row["status_reser"] == "confirmer") {
        $reservationsActives[] = $row;
    } else {
        $reservationsHistorique[] = $row;
    }
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
    <link rel="stylesheet" href="./bootstrap/icons/font/bootstrap-icons.css">
    <title>Gestion des Réservations</title>
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
    
    <section class="section page-section">
        <div class="container">
            <h1 class="mt-5 mb-4">Gestion des Réservations</h1>
            
            <!-- Tableau des réservations actives -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h5 class="m-0 font-weight-bold">Réservations Actives</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($reservationsActives)): ?>
                        <div class="alert alert-info">Vous n'avez pas de réservations actives.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Marque</th>
                                        <th>Modèle</th>
                                        <th>Date de Début</th>
                                        <th>Date de Fin</th>
                                        <th>Statut</th>
                                        <th>Tarif</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservationsActives as $row): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row["marque"]); ?></td>
                                            <td><?php echo htmlspecialchars($row["modele"]); ?></td>
                                            <td><?php echo htmlspecialchars($row["debut_reser"]); ?></td>
                                            <td><?php echo htmlspecialchars($row["fin_reserv"]); ?></td>
                                            <td>
                                                <?php 
                                                $badgeClass = 'bg-warning text-dark';
                                                $status = 'En attente';
                                                
                                                switch($row["status_reser"]) {
                                                    case "confirmer":
                                                        $badgeClass = 'bg-success text-white';
                                                        $status = 'Acceptée';
                                                        break;
                                                    case "en cours":
                                                        $badgeClass = 'bg-info text-white';
                                                        $status = 'En cours';
                                                        break;
                                                    default: // "suivie"
                                                        $badgeClass = 'bg-warning text-dark';
                                                        $status = 'En attente';
                                                }
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
                                            </td>
                                            <td><?php echo htmlspecialchars($row["tarif"]); ?> FCFA</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <?php if ($row["status_reser"] == "suivie"): ?>
                                                        <!-- Bouton pour payer -->
                                                        <a href="./payement.php?id_reser=<?= htmlspecialchars($row["id_reser"]) ?>" class="btn btn-sm btn-success">
                                                            <i class="bi bi-credit-card"></i> Payer
                                                        </a>
                                                    <?php elseif ($row["status_reser"] == "confirmer"): ?>
                                                        <!-- Voir reçu pour réservation confirmée -->
                                                        <a href="./recu.php?id_reser=<?= htmlspecialchars($row["id_reser"]) ?>&recu=true" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-receipt"></i> Reçu
                                                        </a>
                                                    <?php elseif ($row["status_reser"] == "en cours"): ?>
                                                        <!-- Voir détails pour réservation en cours -->
                                                        <a href="./recu.php?id_reser=<?= htmlspecialchars($row["id_reser"]) ?>&recu=true" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-receipt"></i> Reçu
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-secondary text-white">
                    <h5 class="m-0 font-weight-bold">Historique des Réservations</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($reservationsHistorique)): ?>
                        <div class="alert alert-info">Vous n'avez pas d'historique de réservations.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Marque</th>
                                        <th>Modèle</th>
                                        <th>Date de Début</th>
                                        <th>Date de Fin</th>
                                        <th>Statut</th>
                                        <th>Tarif</th>
                                        <th>Reçu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservationsHistorique as $row): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row["marque"]); ?></td>
                                            <td><?php echo htmlspecialchars($row["modele"]); ?></td>
                                            <td><?php echo htmlspecialchars($row["debut_reser"]); ?></td>
                                            <td><?php echo htmlspecialchars($row["fin_reserv"]); ?></td>
                                            <td>
                                                <?php 
                                                $badgeClass = 'bg-danger text-white';
                                                $status = 'Refusée';
                                                
                                                switch($row["status_reser"]) {
                                                    case "annuler":
                                                        $badgeClass = 'bg-danger text-white';
                                                        $status = 'Refusée';
                                                        break;
                                                    case "terminer":
                                                        $badgeClass = 'bg-secondary text-white';
                                                        $status = 'Terminée';
                                                        break;
                                                    case "interrompue":
                                                        $badgeClass = 'bg-dark text-white';
                                                        $status = 'Interrompue';
                                                        break;
                                                    default:
                                                        $badgeClass = 'bg-danger text-white';
                                                        $status = 'Refusée';
                                                }
                                                ?>
                                                <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
                                            </td>
                                            <td><?php echo htmlspecialchars($row["tarif"]); ?> FCFA</td>
                                            <td>
                                                <?php if ($row["status_reser"] == "terminer" || $row["status_reser"] == "interrompue"): ?>
                                                    <a href="./recu.php?id_reser=<?= htmlspecialchars($row["id_reser"]) ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-receipt"></i> Voir
                                                    </a>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>