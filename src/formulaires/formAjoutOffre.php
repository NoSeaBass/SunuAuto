<?php
if (isset($_GET["code"])) {
    $code = htmlspecialchars($_GET["code"]); // Escape the code for safety
    include "../fonctions/connexion.php";
    
    // Check if the car code exists
    $req = $connexion->prepare("SELECT * FROM voiture WHERE code=?;");
    $req->execute([$code]);
    $car = $req->fetch();

    if (!$car) {
        die("Le code de la voiture n'existe pas.");
    }

    // Retrieve the tarif_reserv for the car
    $tarif_reserv = $car['tarif_reserv'];
} else {
    die("Code de voiture non spécifié.");
}

$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/css/baseForm.css">
    <title>Offre</title>
</head>
<body>
<section class='container'>
    <h3 class="text-center mb-4">Ajout d'offre</h3>
    <form action="../traitement.cacher.php" method='post'>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="code">Code</label>
                    <input type="text" id="code" name="code" class="form-control" value="<?php echo htmlspecialchars($code); ?>" required readonly>
                </div>
                <div class="form-group">
                    <label for="prix">Nouveau prix </label>
                    <input type="number" id="prix" name="prix" class="form-control" min="10000" max="<?php echo $tarif_reserv; ?>" required>
                    <label for="">(min 10 000 FCFA, max <?php echo number_format($tarif_reserv, 2, ',', ' '); ?> FCFA)</label>
                </div>
                <div class="form-group">
                    <label for="debut">Début de l'offre</label>
                    <input type="date" id="debut" name="debut" class="form-control" value="<?php echo $today; ?>" required min="<?php echo $today; ?>">
                </div>
                <div class="form-group">
                    <label for="fin">Fin de l'offre</label>
                    <input type="date" id="fin" name="fin" class="form-control" required>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" name="ajouter" value="ajouterOffre" class="btn btn-primary">Ajouter</button>
                </div>
                <div>
                <?php
                if (isset($_GET["error"]) && $_GET["error"] == "exist") {
                    echo "<p class='error-message mt-3'>Insertion invalide</p>";
                }
                ?>
                </div>
            </div>
        </div>
    </form>
</section>

<script src="../bootstrap/js/jquery.slim.min.js"></script>
<script src="../bootstrap/js/popper.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>