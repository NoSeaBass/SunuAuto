<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../bootstrap/css/baseForm.css">
    <link rel="stylesheet" href="../bootstrap/icons/font/bootstrap-icons.css">
    <title>Inscription</title>
</head>
<body>
    <div class="container">
        <div class="page-links">
            <a href="../"><i class="bi bi-house"></i></a>
            <a href="./connexionClient.php">Connexion</a>
            <a href="./inscription.php">Inscription</a>
        </div>
        <h3>Inscription</h3>
        <form method="post" action="../traitement.cacher.php">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <label for="passe">Mot de passe</label>
                        <input type="password" id="passe" name="passe" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" id="name" name="nom" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" class="form-control" required />
                    </div>

                        <div class="form-group">
                                <label for="telephone">Téléphone</label>
                                <input type="tel" id="telephone" name="telephone" class="form-control" placeholder="+221" pattern="^(77|76|75|78|70)[0-9]{7}$" required />
                        </div>

                    <div class="form-group">
                        <label for="profession">Profession</label>
                        <input type="text" id="profession" name="profession" class="form-control" required />
                    </div>

                    <div class="form-group">
                        <label>Sexe</label>
                        <div class="gender-group">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sexe" id="M" value="M" required>
                                <label class="form-check-label" for="M">Masculin</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sexe" id="F" value="F" required>
                                <label class="form-check-label" for="F">Féminin</label>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" name="envoie" class="btn btn-primary" value="Inscription">S'inscrire</button>
                    </div>

                    <?php if (isset($_GET["error"])) {
                        if ($_GET["error"] == "exist") {
                            echo "<div class='error-message text-center'>Compte existe déjà</div>";
                        } elseif ($_GET["error"] == "noninscrit") {
                            echo "<div class='error-message text-center'>Vous n'êtes pas inscrit/connecté</div>";
                        }
                    } ?>

                </div>
            </div>
        </form>
    </div>

    <script src="../bootstrap/js/jquery.slim.min.js"></script>
    <script src="../bootstrap/js/popper.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
