<?php
// Include required files and start session
include "./fonctions/post.php";
include "./fonctions/autoload.php";
session_start();

// Authentication check
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit();
}

// Récupération des informations du gérant
$gerant = new gerant($_SESSION["id"]);
$managerName = $gerant->presence();
if (is_null($managerName)) {
    header("Location: index.php");
    exit();
}

// Fonction pour récupérer les détails du gérant (à remplacer par une vraie fonction)
function getGerantInfo($id) {
    // Cette fonction devrait normalement récupérer les informations du gérant depuis la base de données
    // Pour l'exemple, nous utilisons des données statiques
    return [
        'prenom' => explode(' ', $GLOBALS['managerName'])[0] ?? 'Prénom',
        'nom' => explode(' ', $GLOBALS['managerName'])[1] ?? 'Nom',
        'email' => 'gerant@sunuotto.sn',
        'telephone' => '771234567',
        'adresse' => 'Dakar, Sénégal',
        'date_creation' => date("d/m/Y", strtotime("-1 year")),
        'role' => 'Administrateur',
        'derniere_connexion' => date("d/m/Y H:i")
    ];
}

// Function to render the notification badge
function renderNotificationBadge($pendingCount)
{
    if ($pendingCount > 0) {
        return "<span class='position-absolute top-25 start-75 translate-middle badge rounded-pill bg-danger'>" .
            ($pendingCount > 3 ? "3+" : $pendingCount) .
            "</span>";
    }
    return "";
}

// Récupération des infos du gérant et des réservations
$gerantData = getGerantInfo($_SESSION["id"]);
$reserv = new reservation();
$pendingReservations = array_filter(
    $reserv->table(),
    function ($row) {
        return $row["status_reser"] == "suivie";
    }
);
$pendingCount = count($pendingReservations);

// Récupération des activités récentes (simulées pour l'exemple)
function getRecentActivities() {
    return [
        [
            'type' => 'reservation_confirmee',
            'icon' => 'check-circle',
            'bg_color' => 'primary',
            'title' => 'Réservation confirmée #123',
            'date' => 'Aujourd\'hui, ' . date("H:i")
        ],
        [
            'type' => 'nouveau_client',
            'icon' => 'person-plus',
            'bg_color' => 'warning',
            'title' => 'Nouveau client enregistré',
            'date' => 'Hier, 14:25'
        ],
        [
            'type' => 'vehicule_ajoute',
            'icon' => 'car-front',
            'bg_color' => 'success',
            'title' => 'Véhicule ajouté au parc',
            'date' => '18/04/2025, 09:15'
        ],
        [
            'type' => 'reservation_annulee',
            'icon' => 'x-circle',
            'bg_color' => 'danger',
            'title' => 'Réservation annulée #118',
            'date' => '15/04/2025, 16:40'
        ]
    ];
}

$recentActivities = getRecentActivities();

// Équipe des gérants (simulée pour l'exemple)
function getTeamMembers() {
    return [
        [
            'nom' => 'Amadou Diop',
            'email' => 'amadou.diop@sunuotto.sn',
            'role' => 'Administrateur',
            'bg_color' => 'primary',
            'badge_color' => 'danger'
        ],
        [
            'nom' => 'Fatou Sow',
            'email' => 'fatou.sow@sunuotto.sn',
            'role' => 'Modérateur',
            'bg_color' => 'info',
            'badge_color' => 'info'
        ],
        [
            'nom' => 'Omar Ndiaye',
            'email' => 'omar.ndiaye@sunuotto.sn',
            'role' => 'Modérateur',
            'bg_color' => 'info',
            'badge_color' => 'info'
        ]
    ];
}

$teamMembers = getTeamMembers();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Gérant - Suñu Otto</title>
    <link href="./bootstrap/css/bootstrap.min.css?v=<?= time() ?>" rel="stylesheet">
    <link href="./bootstrap/css/baseAdmin.css?v=<?= time() ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet">
    <link rel="stylesheet" href="./bootstrap/icons/font/bootstrap-icons.css">
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
            position: relative;
        }
        
        .edit-profile-btn {
            position: absolute;
            top: 60px;
            right: 10px;
        }
        
        .nav-pills .nav-link {
            color: #4169E1;
            border-radius: 4px;
            margin-right: 5px;
        }
        
        .nav-pills .nav-link.active {
            background-color: #4169E1;
            color: white;
        }
        
        .tab-content {
            padding: 20px 10px;
        }
        
        .card {
            border: 1px solid rgba(65, 105, 225, 0.2);
            border-radius: 8px;
            transition: transform 0.3s ease;
        }
        
        .card-header.mandm {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            color: #4169E1;
        }
        
        .stats-card {
            border-left: 4px solid #4169E1;
        }
        
        .stats-icon {
            font-size: 2rem;
            opacity: 0.7;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-info {
                text-align: center;
                padding-left: 0;
            }
            
            .profile-avatar {
                left: 50%;
                transform: translateX(-50%);
            }
            
            .edit-profile-btn {
                position: static;
                margin-top: 15px;
                display: block;
                width: 100%;
            }
        }

        .user-avatar {
            min-width: 50px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
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
                    <a href="./profileGerant.php" class="nav-link active">
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
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell fs-5"></i>
                            <?= renderNotificationBadge($pendingCount) ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">Centre de notifications</h6>
                            <?php
                            $recentPendingReservations = array_slice(
                                $pendingReservations,
                                0,
                                3
                            );
                            if (empty($recentPendingReservations)) {
                                echo "<div class='dropdown-item text-center text-muted'>Aucune notification</div>";
                            } else {
                                foreach ($recentPendingReservations as $row) {
                                    $reserveId = htmlspecialchars(
                                        $row["id_reser"]
                                    );
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
                <h1 class="h3 mb-0 text-gray-800">Mon Profil</h1>
                <p class="text-muted">Gérez vos informations personnelles et les paramètres de votre compte.</p>
            </div>

            <section class="profile-section" id="profile">
                <div class="profile-header">
                    <div class="profile-banner"></div>
                    <div class="profile-avatar"><i class="bi bi-person"></i></div>
                </div>
                <div class="profile-info">
                    <h2><?= $gerantData['prenom'] ?? '' ?> <?= $gerantData['nom'] ?? '' ?></h2>
                    <p><i class="bi bi-envelope me-2"></i><?= $gerantData['email'] ?></p>
                    <span class="badge bg-primary">Rôle: <?= $gerantData['role'] ?></span>
                </div>
                <div class="tab-content">
                <div class="tab-pane fade show active" id="info">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header mandm">
                    <h4 class="mb-0">Informations personnelles</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Nom:</strong></div>
                        <div class="col-sm-9"><?= $gerantData['nom'] ?? 'Non renseigné' ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Prénom:</strong></div>
                        <div class="col-sm-9"><?= $gerantData['prenom'] ?? 'Non renseigné' ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Email:</strong></div>
                        <div class="col-sm-9"><?= $gerantData['email'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    
                </div>
            </section>
        </div>

        <footer class="sticky-footer">
            <div class="container-fluid">
                <div class="copyright text-center">
                    <span>© <script>document.write(new Date().getFullYear())</script> - Suñu Otto | Développé par Ibrahima et Khady</span>
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