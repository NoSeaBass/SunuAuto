<?php
if (isset($_GET["code"])) {
    $code = htmlspecialchars($_GET["code"]); // Échapper le code
} else {
    // Gérer le cas où le code n'est pas défini
    die("Code non spécifié.");
}

$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="./bootstrap/css/baseForm.css">
    <title>Modification d'une offre</title>
</head>
<body>
<section class='container'>
    <h3 class="text-center mb-4">Modification d'une offre</h3>
    <?php
    include ("./fonctions/autoload.php");
    $offre = new offre(null, null, null, null, null);
    $tableau = $offre->recupCode($code);
    
    foreach ($tableau as $table) {
        $debut_offr = $table["debut_offr"];
        $fin_offr = $table["fin_offr"];
        $tarif_reduit = $table["tarif_reduit"];
        
        echo "<form action='./traitement.cacher.php' method='post' enctype='multipart/form-data'>
            <div class='row justify-content-center'>
                <div class='col-md-6'>
                    <div class='form-group'>
                        <label for='code'>Code</label>
                        <input type='text' id='code' name='code' class='form-control' value='" . htmlspecialchars($code) . "' readonly>
                    </div>
                    <div class='form-group'>
                        <label for='tarif_reduit'>Tarif réduit (min 10 000 FCFA)</label>
                        <input type='number' id='tarif_reduit' name='tarif_reduit' class='form-control' value='$tarif_reduit' min='10000' required>
                    </div>
                    <div class='form-group'>
                        <label for='debut_offr'>Début de l'offre</label>
                        <input type='date' id='debut_offr' name='debut_offr' class='form-control' value='$debut_offr' required min='$today'>
                    </div>
                    <div class='form-group'>
                        <label for='fin_offr'>Fin de l'offre</label>
                        <input type='date' id='fin_offr' name='fin_offr' class='form-control' value='$fin_offr' required>
                    </div>
                    <input type='hidden' name='modif' value='modifierOffre'>
                    <div class='text-center mt-3'>
                        <button type='submit' class='btn btn-warning'>Modifier</button>
                    </div>
                </div>
            </div>
        </form>";
    }
    ?>
</section>

<script src="../bootstrap/js/jquery.slim.min.js"></script>
<script src="../bootstrap/js/popper.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>