<?php
// Inclusion of the autoload to automatically load classes
require_once "./fonctions/autoload.php";

// Begin session if not already started
session_start();

// Company information
$entreprise = "Suñu Auto";
$adresse = "123 Rue de la Location, Dakar";
$telephone = "+221 77 123 45 67";
$email = "contact@sunuauto.sn";

// Get parameters from URL
$reservation_id = isset($_GET["id_reser"]) ? $_GET["id_reser"] : null;

// Initialize variables
$code_voiture = "";
$nb_jours = 0;
$date_debut = date("d/m/Y");
$date_fin = date("d/m/Y");
$tarif_journalier = 0;
$client_nom = "Non spécifié";
$client_prenom = "Non spécifié";
$client_email = "Non spécifié";
$client_telephone = "";
$voiture_modele = "Non spécifié";
$voiture_marque = "Non spécifié";
$voiture_description = "Non spécifié";
$voiture_immatriculation = "Non spécifié";
$status_reservation = "Non spécifié";
$has_offer = 0;
$tarif_reduit = 0;
$reduction = 0;

// A partir d'ici c'est Ibrahima qui prend les commandes >:)
$la_reservation = new reservation($reservation_id);
$la_reservation = $la_reservation->details();

if ($la_reservation) {
    // Populate variables with reservation details
    $code_voiture = $la_reservation["code"];
    $client_nom = $la_reservation["nom"];
    $client_prenom = $la_reservation["prenom"];
    $client_email = $la_reservation["email"]; // Added client email
    $client_telephone = $la_reservation["telephone"]; // Added client telephone
    $tarif_journalier = $la_reservation["tarif_reserv"];
    $status_reservation = $la_reservation["status_reser"];
    $date_debut = date("d/m/Y", strtotime($la_reservation["debut_reser"])); // Format date
    $date_fin = date("d/m/Y", strtotime($la_reservation["fin_reserv"])); // Format date
    $voiture_marque = $la_reservation["marque"]; // Added car brand
    $voiture_modele = $la_reservation["modele"]; // Added car model
    $voiture_description = $la_reservation["description"]; // Added car description
    $voiture_immatriculation = $la_reservation["code"]; // Assuming 'code' is the registration number
    $nb_jours =
        (strtotime($la_reservation["fin_reserv"]) -
            strtotime($la_reservation["debut_reser"])) /
        (60 * 60 * 24); // Calculate number of days
    $total_avant_reduction = $nb_jours * $tarif_journalier;
    $total_a_payer = $total_avant_reduction;
    // Check if there is an offer
    if (
        isset($la_reservation["tarif_reduit"]) &&
        $la_reservation["tarif_reduit"] > 0
    ) {
        $has_offer = 1; // There is an offer
        $tarif_reduit = $la_reservation["tarif_reduit"]; // Get the reduced rate
        $total_avant_reduction = $nb_jours * $tarif_journalier;
        $total_a_payer = $nb_jours * $tarif_reduit;
        // Calculate the actual monetary reduction
        $reduction = $total_avant_reduction - $total_a_payer;
        // Calculate reduction percentage for display
        $reduction_percent = (1 - $tarif_reduit / $tarif_journalier) * 100;
    }
} else {
    echo "No reservation found for the given ID.";
}

// Generate a unique receipt number
$numero_recu = "NU-" . date("Ymd") . "-" . rand(1000, 9999);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu - <?php echo $entreprise; ?></title>
    <!-- Bootstrap CSS -->
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .recu {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .logo {
            text-align: center;
           height: 100px;
           width: auto;
            margin-bottom: 20px;
        }
        .header {
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 12px;
        }
        .signature {
            margin-top: 50px;
            border-top: 1px dotted #000;
            width: 200px;
            text-align: center;
        }
        .vehicle-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        .status-confirmer {
            background-color: #d4edda;
            color: #155724;
        }
        .status-en-attente {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-annuler {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-terminer {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        /* Specific print styles to fix width issues */
        @media print {
            body {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .recu {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 15px !important;
                border: none !important;
                box-shadow: none !important;
            }
            .row {
                width: 100% !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            table {
                width: 100% !important;
            }
            /* Fix for table inside container */
            .table-responsive {
                overflow-x: visible !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="recu">
            <div class="header">
                <div class="row">
                    <div class="col-8">
                        <div><img src="./media/autres/logo.png" alt="Suñu Otto" class="logo"></div>
                        <p>
                            <?php echo $adresse; ?><br>
                            Tél: <?php echo $telephone; ?><br>
                            Email: <?php echo $email; ?><br>
                            NINEA: XXXXXXXXX
                        </p>
                    </div>
                    <div class="col-4 text-end">
                        <h4>REÇU</h4>
                        <p>
                            <strong>N°:</strong> <?php echo $numero_recu; ?><br>
                            <strong>Date:</strong> <?php echo date(
                                "d/m/Y"
                            ); ?><br>
                            <?php if (!empty($reservation_id)): ?>
                            <strong>ID Réservation:</strong> <?php echo $reservation_id; ?><br>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-6">
                    <h5>Client</h5>
                    <p>
                        <strong>Nom:</strong> <?php echo $client_nom; ?><br>
                        <strong>Prénom:</strong> <?php echo $client_prenom; ?><br>
                        <strong>Email:</strong> <?php echo $client_email; ?><br>
                        <?php if (!empty($client_telephone)): ?>
                        <strong>Téléphone:</strong> <?php echo $client_telephone; ?>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-6">
                    <h5>Informations de location</h5>
                    <p>
                        <strong>Code:</strong> <?php echo $voiture_immatriculation; ?><br>
                        <strong>Marque:</strong> <?php echo $voiture_marque; ?><br>
                        <strong>Modèle:</strong> <?php echo $voiture_modele; ?><br>
                        <strong>Date de début:</strong> <?php echo $date_debut; ?><br>
                        <strong>Date de fin:</strong> <?php echo $date_fin; ?><br>
                        <strong>Durée:</strong> <?php echo $nb_jours; ?> jour(s)
                    </p>
                </div>
            </div>

            <div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-end">Tarif journalier</th>
                        <th class="text-end">Nombre de jours</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Location<?php echo $voiture_marque .
                            " " .
                            $voiture_modele; ?></td>
                        <td class="text-end"><?php echo number_format(
                            $tarif_journalier,
                            0,
                            ",",
                            " "
                        ); ?> FCFA</td>
                        <td class="text-end"><?php echo $nb_jours; ?></td>
                        <td class="text-end"><?php echo number_format(
                            $total_avant_reduction,
                            0,
                            ",",
                            " "
                        ); ?> FCFA</td>
                    </tr>
                    <?php if ($reduction > 0): ?>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Sous-total:</strong></td>
                        <td class="text-end"><?php echo number_format(
                            $total_avant_reduction,
                            0,
                            ",",
                            " "
                        ); ?> FCFA</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end">
                            <strong>
                                <?php if ($has_offer == 1): ?>
                                    Tarif promotionnel (<?php echo number_format(
                                        $reduction_percent,
                                        0
                                    ); ?>%):
                                <?php else: ?>
                                    Réduction (<?php echo number_format(
                                        $reduction_percent,
                                        0
                                    ); ?>%):
                                <?php endif; ?>
                            </strong>
                        </td>
                        <td class="text-end">-<?php echo number_format(
                            $reduction,
                            0,
                            ",",
                            " "
                        ); ?> FCFA</td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total à payer:</strong></td>
                        <td class="text-end"><strong><?php echo number_format(
                            $total_a_payer,
                            0,
                            ",",
                            " "
                        ); ?> FCFA</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

            <div class="row mt-4">
                <div class="col-8">
                    <div class="mb-4">
                        <h5>Conditions:</h5>
                        <p>Ce reçu confirme le paiement complet de votre location de véhicule pour la période indiquée.</p>
                    </div>
                    <div class="signature">
                        Signature autorisée
                    </div>
                </div>
                <div class="col-4 text-end">
                    <div class="signature">
                        Signature client
                    </div>
                </div>
            </div>

            <div class="footer text-center">
            <p>La réservation de votre véhicule sera effective après le versement d'un acompte<br>
                    Veuillez venir avec ce reçu lors de la récupération de la voiture.
                    Merci d'avoir choisi <?php echo $entreprise; ?> pour votre location de voiture!
                </p>
            </div>

            <div class="mt-4 text-center no-print">
                <button class="btn btn-primary" onclick="window.print()">Imprimer le reçu</button>
                <?php if(isset($_GET['recu']) && $_GET['recu'] == 'true'):?>
                        <a href="historique.php" class="btn btn-secondary">Retour aux réservations</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
