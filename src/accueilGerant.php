<?php
include "./fonctions/post.php";
include "./fonctions/autoload.php";
session_start();

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

function renderStatsCard($title, $value, $iconClass) {
    return "
    <div class='col-xl-3 col-md-6 mb-4'>
        <div class='card stats-card h-100'>
            <div class='card-body'>
                <div class='row align-items-center'>
                    <div class='col'>
                        <div class='text-xs font-weight-bold text-uppercase mb-1'>$title</div>
                        <div class='h5 mb-0 font-weight-bold'>$value</div>
                    </div>
                    <div class='col-auto'>
                        <i class='bi $iconClass stats-icon'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>";
}

$reserv = new reservation();
$allReservations = $reserv->table();
$recentReservations = array_slice($allReservations, 0, 5);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Gérant</title>
    <link href="./bootstrap/css/bootstrap.min.css?v=<?= time() ?>" rel="stylesheet">
    <link href="./bootstrap/css/baseAdmin.css?v=<?= time() ?>" rel="stylesheet">
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
                    <a href="./accueilGerant.php" class="nav-link active">
                        <i class="bi bi-info-circle"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./traitement.gerant.php?envoie=listeReservation" class="nav-link">
                        <i class="bi bi-list-check"></i>
                        <span>Réservations</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./traitement.gerant.php?envoie=listeClient" class="nav-link">
                        <i class="bi bi-person-lines-fill"></i>
                        <span>Clients</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./traitement.gerant.php?envoie=listeVoiture" class="nav-link">
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
            <div class="dashboard-header">
                <h1 class="h3 mb-0 text-gray-800">Tableau de bord</h1>
                <p class="text-muted">Bienvenue, <?= $managerName ?>! Voici un aperçu de votre activité.</p>
            </div>

            <div class="row">
                <?= renderStatsCard(
                    "Réservations",
                    count($reserv->table()),
                    "bi-calendar-check text-primary"
                ) ?>
                <?= renderStatsCard(
                    "Véhicules disponibles",
                    array_sum(
                        array_column(
                            (new voiture(null, null, null, null, null, null, null))->listeVoiture(),
                            "qt_disponible"
                        )
                    ),
                    "bi-car-front text-warning"
                ) ?>
                <?= renderStatsCard(
                    "Client(s) non bloqué(s)",
                    count(
                        array_filter((new client())->tabClient(), function ($row) {
                            return $row["status"] == "actif";
                        })
                    ),
                    "bi-people text-danger"
                ) ?>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header mandm">
                            Réservations récentes
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Client</th>
                                            <th>Véhicule</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($recentReservations)): ?>
                                            <tr>
                                                <td colspan="5" class="text-center">Aucune réservation trouvée</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($recentReservations as $reservation): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($reservation["id_reser"]) ?></td>
                                                    <td><?= htmlspecialchars($reservation["nom_client"] ?? "N/A") ?></td>
                                                    <td><?= htmlspecialchars($reservation["code"]) ?></td>
                                                    <td><?= htmlspecialchars($reservation["debut_reser"]) ?></td>
                                                    <td>
                                                        <?php
                                                        $statusClass = "bg-secondary";
                                                        switch ($reservation["status_reser"]) {
                                                            case "suivie":
                                                                $statusClass = "bg-warning";
                                                                break;
                                                            case "confirmer":
                                                                $statusClass = "bg-success";
                                                                break;
                                                            case "en cours":
                                                                $statusClass = "bg-info";
                                                                break;
                                                            case "terminer":
                                                                $statusClass = "bg-secondary";
                                                                break;
                                                            case "interrompue":
                                                                $statusClass = "bg-dark";
                                                                break;
                                                            case "annuler":
                                                                $statusClass = "bg-danger";
                                                                break;
                                                        }
                                                        ?>
                                                        <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($reservation["status_reser"]) ?></span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end mt-3">
                                <a href="./traitement.gerant.php?envoie=listeReservation" class="btn btn-sm btn-secondary">Voir toutes les réservations</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header mandm">
                            Actions rapides
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="./formulaires/formAjoutVoiture.php" class="btn btn-primary mb-2">
                                    <i class="bi bi-plus-circle me-2"></i>Ajouter un véhicule
                                </a>
                                <a href="./traitement.gerant.php?envoie=listeReservation" class="btn btn-warning mb-2">
                                    <i class="bi bi-list-check me-2"></i>Gérer les réservations
                                </a>
                                <a href="./traitement.gerant.php?envoie=listeClient" class="btn btn-info mb-2">
                                    <i class="bi bi-person-lines-fill me-2"></i>Consulter les clients
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
    <div class="card shadow mb-4">
        <div class="card-header mandm">
            Statistiques de location
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <?php
                $confirmedReservations = array_filter(
                    $reserv->table(),
                    function ($row) {
                        return $row["status_reser"] == "confirmer";
                    }
                );
                $pendingReservations = array_filter(
                    $reserv->table(),
                    function ($row) {
                        return $row["status_reser"] == "suivie";
                    }
                );
                $cancelledReservations = array_filter(
                    $reserv->table(),
                    function ($row) {
                        return $row["status_reser"] == "annuler";
                    }
                );
                $inProgressReservations = array_filter(
                    $reserv->table(),
                    function ($row) {
                        return $row["status_reser"] == "en cours";
                    }
                );
                $completedReservations = array_filter(
                    $reserv->table(),
                    function ($row) {
                        return $row["status_reser"] == "terminer";
                    }
                );
                $interruptedReservations = array_filter(
                    $reserv->table(),
                    function ($row) {
                        return $row["status_reser"] == "interrompue";
                    }
                );
                ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Réservations confirmées
                    <span class="badge bg-success rounded-pill"><?= count($confirmedReservations) ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Réservations en attente
                    <span class="badge bg-warning rounded-pill"><?= count($pendingReservations) ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Réservations en cours
                    <span class="badge bg-info rounded-pill"><?= count($inProgressReservations) ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Réservations terminées
                    <span class="badge bg-secondary rounded-pill"><?= count($completedReservations) ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Réservations interrompues
                    <span class="badge bg-dark rounded-pill"><?= count($interruptedReservations) ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Réservations annulées
                    <span class="badge bg-danger rounded-pill"><?= count($cancelledReservations) ?></span>
                </li>
            </ul>
        </div>
    </div>
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
            const sidebar = document.querySelector('.sidebar');

            if (sidebarToggler) {
                sidebarToggler.addEventListener('click', function() {
                    sidebar.classList.toggle('expanded');
                });
            }
        });
    </script>
</body>
</html>