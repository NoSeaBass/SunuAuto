<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/css/baseForm.css">
    <title>Ajout Gérant</title>
</head>
<body>

    <div class="container">
        <h3>Nouveau gérant</h3>
        <form action="../traitement.cacher.php" method="post">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="id">Identifiant</label>
                        <input type="text" class="form-control" id="id" name="id" required />
                    </div>

                    <div class="form-group">
                        <label for="passe">Mot de passe</label>
                        <input type="password" class="form-control" id="passe" name="passe" required />
                    </div>

                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" class="form-control" id="name" name="nom" required />
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" required />
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required />
                    </div>

                    <div class="text-center"> <!-- Centering the button -->
                        <button type="submit" name="envoie" class="btn btn-primary" value="InscriptionGerant">Inscription Gérant</button>
                    </div>
                    
                    <div>
                    <?php
                    if(isset($_GET['error'])) {
                        if($_GET['error'] == "exist") {
                            echo "<p class='error-message'>Compte existe déjà</p>";
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
