<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="./bootstrap/css/baseForm.css">
    <title>Modification d'une voiture</title>
</head>
<body>
    <section class="container">
        <h3 class="text-center mb-4">Modification d'une voiture</h3>
        <?php
        include ("./fonctions/autoload.php");
        $code = NULL;
        if(isset($_GET["code"])){
            $code = $_GET["code"];
        }
        
        $voiture = new voiture (null, null, null ,null, null, null, null);
        $tableau = $voiture->recupCode($code);
        foreach ($tableau as $table) {
            $marque = $table["marque"];
            $modele = $table["modele"];
            $description = $table["description"];
            $tarif_reserv = $table["tarif_reserv"];
            $qt_initial = $table["qt_initial"];
            $qt_disponible = $table["qt_disponible"];
            echo "<form action='./traitement.cacher.php' method='post' enctype='multipart/form-data'>
                <div class='row justify-content-center'>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label for='code'>Code</label>
                            <input type='text' id='code' name='code' class='form-control' value='$code' required>
                        </div>
                        <div class='form-group'>
                            <label for='marque'>Marque</label>
                            <input type='text' id='marque' name='marque' class='form-control' value='$marque' required>
                        </div>
                        <div class='form-group'>
                            <label for='modele'>Modèle</label>
                            <input type='text' id='modele' name='modele' class='form-control' value='$modele' required>
                        </div>
                        <div class='form-group'>
                            <label for='description'>Description</label>
                            <textarea id='description' name='description' class='form-control' rows='3' required>$description</textarea>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class='form-group'>
                            <label for='tarif_reserv'>Tarif de réservation</label>
                            <input type='number' id='tarif_reserv' name='tarif_reserv' class='form-control' value='$tarif_reserv' min='10000' required>
                        </div>
                        <div class='form-group'>
                            <label for='qt_initial'>Quantité initiale</label>
                            <input type='number' id='qt_initial' name='qt_initial' class='form-control' value='$qt_initial' min='0' required>
                        </div>
                        <div class='form-group'>
                            <label for='qt_disponible'>Quantité disponible</label>
                            <input type='number' id='qt_disponible' name='qt_disponible' class='form-control' value='$qt_disponible' min='0' required>
                        </div>
                    </div>
                    <div class='col-md-12 text-center mt-3'>
                        <input type='hidden' name='modif' value='modifier'>
                        <button type='submit' name='ajouter' class='btn btn-warning'>Modifier</button>
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