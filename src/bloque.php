<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès Bloqué</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url('./media/autres/picture.gif'); /* Placeholder background image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        /* Navbar */
        .navbar {
            background-color: rgba(0, 0, 0, 0.7);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .navbar-brand {
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
            text-decoration: none;
            margin-left: 20px;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Masthead */
        .masthead {
            background-color: rgba(0, 0, 0, 0.5); /* Black with 50% opacity */
            backdrop-filter: blur(5px);
            padding: 40px;
            min-height: 100vh;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding-top: 80px; /* Add space for the fixed navbar */
        }

        h1 {
            margin-bottom: 20px;
            font-size: 2.5rem;
        }

        p {
            margin-bottom: 15px;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        a {
            color: #0d6efd;
        }

        .btn-primary {
            background-color: #0d6efd;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="./">Suñu Otto</a>
        </div>
    </nav>

    <div class="masthead">
        <h1>Vous avez été bloqué</h1>
                <p>Votre accès à ce site a été temporairement ou définitivement bloqué.</p>
                <p>Si vous pensez qu'il s'agit d'une erreur, veuillez contacter l'administrateur à l'adresse suivante : <a href="mailto:admin@example.com">admin@example.com</a>.</p>
                <p>Vous pouvez également retourner à la page d'accueil.</p>
        <a href="./" class="btn btn-primary">Retour à l'accueil</a>
    </div>
</body>
</html>
