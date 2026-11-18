<?php
session_start();
if(isset($_SESSION["id"])){
        header("Location: ../accueilGerant.php");
        exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/css/baseForm.css"> <!-- Link to your custom CSS -->
    <title>Connection</title>
</head>
<body>

    <section class="container">
        <form method="post" action="../traitement.cacher.php" class="form-container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <h3>Connection</h3>

                    <div class="form-group">
                        <label for="id">Identifiant</label>
                        <input type="text" id="id" name="id" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <label for="passe">Mot de passe</label>
                        <input type="password" id="passe" name="passe" class="form-control" required />
                    </div>

                    <button type="submit" name="envoie" class="btn btn-primary btn-block" value="connectionGerant">Se connecter</button>

                    <div class="error-message text-center">
                        <?php
                        if(isset($_GET['error'])) {
                            if($_GET['error'] == "exist") {
                                echo "<p>Identifiant ou mot de passe incorrect</p>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </form>
    </section>

</body>
</html>
