<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/css/baseForm.css">
    <link rel="stylesheet" href="../bootstrap/icons/font/bootstrap-icons.css">
    <title>Connexion</title>
</head>
<body>
    <div class="container">
        <div class="page-links">
            <a href="../"><i class="bi bi-house"></i></a>
            <a href="./connexionClient.php">Connexion</a>
            <a href="./inscription.php">Inscription</a>
        </div>
        <h3>Connexion</h3>
        <form method="post" action="../traitement.cacher.php" class="form-container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id">Email</label>
                        <input type="email" id="id" name="id" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label for="passe">Mot de passe</label>
                        <input type="password" id="passe" name="passe" class="form-control" required />
                    </div>
                    <div class="text-center">
                        <button type="submit" name="envoie" class="btn btn-primary btn-block" value="connectionClient">Se connecter</button>
                    </div>
                    <div class="error-message">
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
    </div>

    <script src="../bootstrap/js/jquery.slim.min.js"></script>
    <script src="../bootstrap/js/popper.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>