<?php
include "./fonctions/post.php";
include "./fonctions/autoload.php";
session_start();
$todaysDate = date('Y-m-d');

if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit();
}

$gerant = new gerant($_SESSION["id"]);
$managerName = $gerant->presence();
if (is_null($managerName)) {
    header("Location: index.php");
    exit();
}

function renderNotificationBadge($pendingCount) {
    if ($pendingCount > 0) {
        return "<span class='position-absolute top-25 start-75 translate-middle badge rounded-pill bg-danger'>" .
            ($pendingCount > 3 ? "3+" : $pendingCount) .
            "</span>";
    }
    return "";
}

$reserv = new reservation();
$allReservations = $reserv->table();
$recentReservations = array_slice($allReservations, 0, 5);

if (isset($_GET["envoie"]) && $_GET["envoie"] == "listeClient" && isset($_GET["id"])) {
    $utilisateur = new client($_GET["id"]);
    if (isset($_GET["action"])) {
        if ($_GET["action"] == "active") {
            $utilisateur->activer();
        } elseif ($_GET["action"] == "desactive") {
            $utilisateur->desactiver();
        } else {
                if($utilisateur->suprimer() == 1){
                        header("Location: traitement.gerant.php?envoie=listeClient&errorClient=true");
                        exit();
                };
        }
        header("Location: traitement.gerant.php?envoie=listeClient");
        exit();
    }
}

if(isset($_GET["errorClient"]) && $_GET["errorClient"]=='true'){
        $messageClient = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        Le client à au moins une réservation autorisée
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
}

if ($_GET["envoie"] == "listeReservation" && isset($_GET["id"])) {
    $id = $_GET["id"];
    $reservation = new reservation($id);
    if ($_GET["action"] == "active") {
        $reservation->confirmer();
    } elseif ($_GET["action"] == "desactive") {
        $reservation->annuler();
    }
}

 function afficherMessages() {
        if (isset($_GET['success'])) {
            $type = $_GET['success'];
            $message = "";
            
            switch ($type) {
                case "confirmed":
                    $message = "La réservation a été acceptée avec succès.";
                    break;
                case "canceled":
                    $message = "La réservation a été refusée avec succès.";
                    break;
                case "started":
                    $message = "La réservation a démarré avec succès.";
                    break;
                case "completed":
                    $message = "La réservation a été terminée avec succès.";
                    break;
                case "interrupted":
                    $message = "La réservation a été interrompue avec succès.";
                    break;
            }
            
            if ($message) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    ' . $message . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
        }
        
        if (isset($_GET['error'])) {
            $type = $_GET['error'];
            $message = "";
            
            switch ($type) {
                case "confirm_failed":
                    $message = "Erreur lors de la confirmation de la réservation.";
                    break;
                case "cancel_failed":
                    $message = "Erreur lors de l'annulation de la réservation.";
                    break;
                case "start_failed":
                    $message = "Erreur lors du démarrage de la réservation.";
                    break;
                case "complete_failed":
                    $message = "Erreur lors de la fin de la réservation.";
                    break;
                case "interrupt_failed":
                    $message = "Erreur lors de l'interruption de la réservation.";
                    break;
                case "invalid_action":
                    $message = "Action non valide.";
                    break;
            }
            
            if ($message) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ' . $message . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
        }
    }

    if(isset($_GET["message"])){
        if($_GET["message"] == "location"){
                $mes = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Une réservation est déjà en cours
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Gérant</title>
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./bootstrap/css/baseAdmin.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet">
    <link rel="stylesheet" href="./bootstrap/icons/font/bootstrap-icons.css">
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-car-front fs-4 me-2"></i>
            <span class="fs-4 fw-bold">Suñu Otto</span>
        </div>
        <div class="sidebar-menu">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="./index.php" class="nav-link">
                        <i class="bi bi-house-door"></i>
                        <span>Accueil</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./accueilGerant.php" class="nav-link">
                        <i class="bi bi-info-circle"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./traitement.gerant.php?envoie=listeReservation" class="nav-link<?= $_GET["envoie"] == "listeReservation" ? ' active' : '' ?>">
                        <i class="bi bi-list-check"></i>
                        <span>Réservations</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./traitement.gerant.php?envoie=listeClient" class="nav-link<?= $_GET["envoie"] == "listeClient" ? ' active' : '' ?>">
                        <i class="bi bi-person-lines-fill"></i>
                        <span>Clients</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./traitement.gerant.php?envoie=listeVoiture" class="nav-link<?= $_GET["envoie"] == "listeVoiture" ? ' active' : '' ?>">
                        <i class="bi bi-car-front"></i>
                        <span>Véhicules</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./formulaires/formAjoutGerant.php" class="nav-link">
                        <i class="bi bi-person-plus"></i>
                        <span>Ajouter gérant</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./formulaires/formAjoutVoiture.php" class="nav-link">
                        <i class="bi bi-plus-circle"></i>
                        <span>Ajouter véhicule</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./profileGerant.php" class="nav-link">
                        <i class="bi bi-person-circle"></i>
                        <span>Mon Profil</span>
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a href="./traitement.cacher.php?envoie=deconnexion&pour=gerant" class="nav-link text-danger">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Déconnexion</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <nav class="navbar navbar-expand-lg sticky-top">
            <div class="container-fluid">
                <button class="btn btn-link sidebar-toggler d-md-none" type="button">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <?php
                        $pendingReservations = array_filter(
                            $reserv->table(),
                            function ($row) {
                                return $row["status_reser"] == "suivie";
                            }
                        );
                        $pendingCount = count($pendingReservations);
                        ?>
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell fs-5"></i>
                            <?= renderNotificationBadge($pendingCount) ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">Centre de notifications</h6>
                            <?php
                            $recentPendingReservations = array_slice($pendingReservations, 0, 3);
                            if (empty($recentPendingReservations)) {
                                echo "<div class='dropdown-item text-center text-muted'>Aucune notification</div>";
                            } else {
                                foreach ($recentPendingReservations as $row) {
                                    $reserveId = htmlspecialchars($row["id_reser"]);
                                    $code = htmlspecialchars($row["code"]);
                                    echo "<a class='dropdown-item d-flex align-items-center' href='./traitement.gerant.php?envoie=listeReservation'>
                                            <div class='me-3'><div class='bg-primary text-white rounded-circle p-2'></div></div>
                                            <div><div class='small text-muted'>Réservation: $reserveId</div><div class='small'>Véhicule: $code</div><span class='badge bg-warning'>suivie</span></div>
                                          </a>";
                                }
                            }
                            ?>
                            <a class="dropdown-item text-center small text-muted" href="./traitement.gerant.php?envoie=listeReservation">Voir toutes les notifications</a>
                        </div>
                    </li>
                    <div class="text-center text-muted d-flex align-items-center">
                        <span class="me-2 d-none d-lg-inline small"><?= $managerName ?></span>
                        <a href="./profileGerant.php"><i class="bi bi-person-circle fs-5"></i></a>
                    </div>
                </ul>
            </div>
        </nav>

        <div class="content">
            <?php
            if ($_GET["envoie"] == "listeClient") {
                $utilisateur = new client();
                $table = $utilisateur->tabClient();
                if(count($table) <= 0):
                    echo "<div class='container-fluid'>";
                    echo "<div class='page-header d-flex justify-content-between align-items-center'>";
                    echo "<h1 class='h3 mb-0 text-gray-800'>Pas de client</h1>";
                    echo"</div>";                    
                    echo"</div>";                    
                else:
                ?>
                <div class="container-fluid">
                        <?php if(isset($messageClient) && !empty($messageClient)){echo $messageClient;}?>
                    <div class="page-header d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-0 text-gray-800">Liste des clients</h1>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Gestion des clients</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Sexe</th>
                                            <th>Profession</th>
                                            <th>Statut</th>
                                            <th>Activer/Bloquer</th>
                                            <th>Supprimer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($table as $row): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row["email"]) ?></td>
                                                <td><?= htmlspecialchars($row["prenom"]) ?></td>
                                                <td><?= htmlspecialchars($row["nom"]) ?></td>
                                                <td><?= htmlspecialchars($row["sexe"]) ?></td>
                                                <td><?= htmlspecialchars($row["profession"]) ?></td>
                                                <td>
                                                    <span class="badge <?= $row["status"] == "actif" ? 'bg-success' : 'bg-secondary' ?> text-white">
                                                        <?= $row["status"] == "actif" ? 'Actif' : 'Inactif' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($row["status"] == "actif"): ?>
                                                        <button class="btn btn-sm btn-secondary" onclick="window.location.replace('traitement.gerant.php?envoie=listeClient&id=<?= htmlspecialchars($row["email"]) ?>&action=desactive');">
                                                            <i class="bi bi-person-x"></i> Bloquer
                                                        </button>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-success" onclick="window.location.replace('traitement.gerant.php?envoie=listeClient&id=<?= htmlspecialchars($row["email"]) ?>&action=active');">
                                                            <i class="bi bi-person-check"></i> Activer
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger" onclick="window.location.replace('traitement.gerant.php?envoie=listeClient&id=<?= htmlspecialchars($row["email"]) ?>&action=supprime');">
                                                        <i class="bi bi-trash"></i> Supprimer
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif;  ?>
            <?php
            }

            if ($_GET["envoie"] == "listeVoiture") {
                $voiture = new voiture(null, null, null, null, null, null, null);
                $tableau = $voiture->listeVoiture();
                if (count($tableau) <= 0) :
                    echo "<div class='container-fluid'>";
                    echo "<div class='page-header d-flex justify-content-between align-items-center'>";
                    echo "<h1 class='h3 mb-0 text-gray-800'>Pas de voiture</h1>";
                    echo"</div>";                    
                    echo"</div>";           
                else:        
                ?>
                <div class="container-fluid">
                        <?php
                        if(isset($mes)){
                                echo $mes;
                        }
                        ?>
                    <div class="page-header d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-0 text-gray-800">Liste des véhicules</h1>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Gestion du parc automobile</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Marque</th>
                                            <th>Modèle</th>
                                            <th>Description</th>
                                            <th>Tarif</th>
                                            <th>Qté initiale</th>
                                            <th>Qté disponible</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tableau as $row): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row["code"]) ?></td>
                                                <td><?= htmlspecialchars($row["marque"]) ?></td>
                                                <td><?= htmlspecialchars($row["modele"]) ?></td>
                                                <td><?= htmlspecialchars($row["description"]) ?></td>
                                                <td><?= htmlspecialchars($row["tarif_reserv"]) ?> FCFA/jour</td>
                                                <td><?= htmlspecialchars($row["qt_initial"]) ?></td>
                                                <td>
                                                    <span class="badge <?= $row["qt_disponible"] > 0 ? 'bg-success' : 'bg-danger' ?> text-white">
                                                        <?= $row["qt_disponible"] > 0 ? htmlspecialchars($row["qt_disponible"]) : '0' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-warning" onclick="window.location.replace('modifierVoiture.php?code=<?= htmlspecialchars($row["code"]) ?>&action=modifier');">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" onclick="confirmSuppression('<?= htmlspecialchars($row["code"]) ?>');">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                        <?php if($row["tarif_reserv"]>10000):?>
                                                        <button class="btn btn-sm btn-success" onclick="window.location.replace('./formulaires/formAjoutOffre.php?code=<?= htmlspecialchars($row['code']) ?>')">
                                                            <i class="bi bi-tag"></i>
                                                        </button>
                                                        <?php endif;?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $offre = new offre(null, null, null, null, null);
                $table = $offre->listeOffre();
                if (count($table) <= 0) :
                    echo "<div class='container-fluid'>";
                    echo "<div class='page-header d-flex justify-content-between align-items-center'>";
                    echo "<h1 class='h3 mb-0 text-gray-800'>Pas d'offre</h1>";
                    echo "</div>";
                    echo "</div>";
                else:
                ?>
                <div class="container-fluid">
                    <div class="page-header d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-0 text-gray-800">Liste des offres</h1>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Gestion des offres du parc automobile</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Date debut</th>
                                            <th>Date fin</th>
                                            <th>tarif_reduit</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($table as $row): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row["code"]) ?></td>
                                                <td><?= htmlspecialchars($row["debut_offr"]) ?></td>
                                                <td><?= htmlspecialchars($row["fin_offr"]) ?></td>
                                                <td><?= htmlspecialchars($row["tarif_reduit"]) ?></td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-warning" onclick="window.location.replace('modifierOffre.php?code=<?= htmlspecialchars($row["code"]) ?>&action=modifier');">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" onclick="confirmSuppressionO('<?= htmlspecialchars($row["code"]) ?>');">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif;?>
                <?php endif;?>
            <?php
            }

            if ($_GET["envoie"] == "listeReservation") {
                $reserv = new reservation();
                $table = $reserv->table();
                if (count($table) <= 0) :
                    echo "<div class='container-fluid'>";      
                    echo "<div class='page-header d-flex justify-content-between align-items-center'>";
                    echo "<h1 class='h3 mb-0 text-gray-800'>Pas de reservation</h1>";
                    echo "</div>";
                    echo "</div>";
                else:
                ?>
                <div class="container-fluid">
                    <?php afficherMessages(); ?>
                    <div class="page-header d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-0 text-gray-800">Liste des réservations</h1>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold">Gestion des réservations</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Numéro</th>
                                            <th>Véhicule</th>
                                            <th>Client</th>
                                            <th>Depart</th>
                                            <th>Arrive</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($table as $row): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row["id_reser"]) ?></td>
                                                <td><?= htmlspecialchars($row["code"]) ?></td>
                                                <td><?= htmlspecialchars($row["id"]) ?></td>
                                                <td><?= htmlspecialchars($row["debut_reser"]) ?></td>
                                                <td><?= htmlspecialchars($row["fin_reserv"]) ?></td>
                                                <td>
                                                    <?php 
                                                    $badgeClass = 'bg-warning text-dark';
                                                    $status = 'En attente';
                                                    
                                                    switch($row["status_reser"]) {
                                                        case "confirmer":
                                                            $badgeClass = 'bg-success text-white';
                                                            $status = 'Acceptée';
                                                            break;
                                                        case "annuler":
                                                            $badgeClass = 'bg-danger text-white';
                                                            $status = 'Refusée';
                                                            break;
                                                        case "en cours":
                                                            $badgeClass = 'bg-info text-white';
                                                            $status = 'En cours';
                                                            break;
                                                        case "terminer":
                                                            $badgeClass = 'bg-secondary text-white';
                                                            $status = 'Terminée';
                                                            break;
                                                        case "interrompue":
                                                            $badgeClass = 'bg-dark text-white';
                                                            $status = 'Interrompue';
                                                            break;
                                                        default: // "suivie"
                                                            $badgeClass = 'bg-warning text-dark';
                                                            $status = 'En attente';
                                                    }
                                                    ?>
                                                    <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
                                                </td>
                                                <td>
                                                        <?php 
                                                        $departDate = $row['debut_reser']; 
                                                        $arriveDate = $row['fin_reserv']; 

                                                        $departDate = date('Y-m-d', strtotime($departDate));
                                                        $arriveDate = date('Y-m-d', strtotime($arriveDate));
                                                        ?>
                                                    <div class="btn-group" role="group">
                                                        <?php if ($row["status_reser"] == "suivie" || ($row["status_reser"] == "annuler"  && $todaysDate<$departDate)): ?>
                                                            <button class="btn btn-sm btn-success" onclick="window.location.replace('traitement.cacher.php?envoie=listeReservation&id=<?= htmlspecialchars($row["id_reser"]) ?>&action=active');">
                                                                <i class="bi bi-check-circle"></i> Accepter
                                                            </button>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($row["status_reser"] == "suivie" || $row["status_reser"] == "confirmer"): ?>
                                                            <button class="btn btn-sm btn-danger" onclick="window.location.replace('traitement.cacher.php?envoie=listeReservation&id=<?= htmlspecialchars($row["id_reser"]) ?>&action=desactive');">
                                                                <i class="bi bi-x-circle"></i> Refuser
                                                            </button>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($row["status_reser"] == "confirmer" && $departDate == $todaysDate): ?>
                                                            <button class="btn btn-sm btn-info" onclick="window.location.replace('traitement.cacher.php?envoie=listeReservation&id=<?= htmlspecialchars($row["id_reser"]) ?>&action=demarrer');">
                                                                <i class="bi bi-play-circle"></i> Démarrer
                                                            </button>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($row["status_reser"] == "en cours" && $arriveDate == $todaysDate): ?>
                                                            <button class="btn btn-sm btn-secondary" onclick="window.location.replace('traitement.cacher.php?envoie=listeReservation&id=<?= htmlspecialchars($row["id_reser"]) ?>&action=terminer');">
                                                                <i class="bi bi-flag-fill"></i> Terminer
                                                            </button>
                                                        <?php endif; ?>

                                                        <?php if ($row["status_reser"] == "en cours" && $departDate <= $todaysDate && $arriveDate > $todaysDate): ?>
                                                            <button class="btn btn-sm btn-dark" onclick="window.location.replace('traitement.cacher.php?envoie=listeReservation&id=<?= htmlspecialchars($row["id_reser"]) ?>&action=interrompre');">
                                                                <i class="bi bi-slash-circle"></i> Interrompre
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif?>
            <?php
            }
            ?>
        </div>

        <footer class="sticky-footer">
            <div class="container-fluid">
                <div class="copyright text-center">
                    <span>© <script>document.write(new Date().getFullYear())</script> - Auto Rental | Développé par Ibrahima et Khady</span>
                </div>
            </div>
        </footer>
    </div>

    <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggler = document.querySelector('.sidebar-toggler');
            if (sidebarToggler) {
                sidebarToggler.addEventListener('click', function() {
                    document.body.classList.toggle('sidebar-collapsed');
                });
            }
        });
        
        function confirmSuppression(code) {
            if (confirm("Voulez-vous vraiment supprimer cette voiture ?")) {
                window.location.replace("traitement.cacher.php?envoie=supprimerVoiture&code="+code);
            }
        }
        
        function confirmSuppressionO(code) {
            if (confirm("Voulez-vous vraiment supprimer cette offre ?")) {
                window.location.replace("traitement.cacher.php?envoie=supprimerOffre&code="+code);
            }
        }
    </script>
</body>
</html>