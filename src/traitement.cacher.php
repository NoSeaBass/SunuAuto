<?php
include "./fonctions/post.php";
include "./fonctions/autoload.php";

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function redirect($url) {
    header("Location: $url");
    exit();
}

if (isset($_GET["envoie"]) && $_GET["envoie"] == "deconnexion") {
    if (isset($_GET["pour"]) && $_GET["pour"] == 'client') {
        $_SESSION['email'] = null;
        $_SESSION['status'] = null;
        redirect("./index.php");
    }
    if (isset($_GET["pour"]) && $_GET["pour"] == 'gerant') {
        $_SESSION['id'] = null;
        $_SESSION['acces'] = 1;
        redirect("./index.php");
    }
}

if (isset($_SESSION["email"])) {
    $test = new client($_SESSION["email"]);
    if ($test->status() == "bloque") {
        redirect("./bloque.php");
    }
}

$info = recuperation("envoie");
$info2 = recuperation("ajouter");
$info3 = recuperation("modif");
$aujourdhui = new DateTime();

if (isset($_GET["envoie"])) {
    // Add to favorites
    if ($_GET["envoie"] == "ajouterFavori") {
        if (!isset($_SESSION['email'])) {
            redirect("./formulaires/connexionClient.php?error=noninscrit");
        }

        $code = $_GET["code"];
        $client = new client($_SESSION['email']); 
        try {
            $client->ajoutFav($code); 
            redirect("./accueilClient.php"); 
        } catch (PDOException $e) {
            error_log("Favori Error: " . $e->getMessage());
            redirect("./accueilClient.php?error=database_error");
        }
    }

    if ($_GET["envoie"] == "supprimerFavori") {
        if (!isset($_SESSION['email'])) {
            redirect("./formulaires/connexionClient.php?error=noninscrit");
        }

        $code = $_GET["code"];
        $client = new client($_SESSION['email']); 
        try {
            $client->supprimerFavori($code);
            redirect("./accueilClient.php"); 
        } catch (PDOException $e) {
            error_log("Favori Error: " . $e->getMessage());
            redirect("./accueilClient.php?error=database_error");
        }
    }
}

if ($info == "reservation") {
    $code = recuperation("code");
    $id = recuperation("id");
    $tarif = recuperation("tarif");
    $debut = recuperation("depart");
    $fin = recuperation("arrive");

    if (empty($debut) || empty($fin)) {
        redirect("./reservation.php?code=$code&error=empty_dates");
    }

    if (!isset($_SESSION["email"]) || is_null($_SESSION["email"])) {
        redirect("./formulaires/inscription.php?error=noninscrit");
    }

    $date_debut = new DateTime($debut);
    $date_fin = new DateTime($fin);
    $interval = $date_debut->diff($date_fin);
    $nb_jours = $interval->days;

    try {
        $reserv = new reservation($code, $id, $tarif, $debut, $fin);
        $id_reser = $reserv->ajout();
        redirect("./payement.php?id_reser=" . $id_reser);
    } catch (PDOException $e) {
        error_log("Reservation Error: " . $e->getMessage());
        redirect("./reservation.php?code=$code&error=database_error");
    }
}

if ($info2 == "ajouter") {
    if (!isset($_SESSION["id"])) {
        redirect("index.php");
    }
    
    $code = recuperation("code");
    $marque = recuperation("marque");
    $modele = recuperation("modele");
    
    if ($marque === 'autre' && isset($_POST['autre-marque']) && !empty($_POST['autre-marque'])) {
        $marque = $_POST['autre-marque'];
    }
    
    if ($modele === 'autre' && isset($_POST['autre-modele']) && !empty($_POST['autre-modele'])) {
        $modele = $_POST['autre-modele'];
    }
    
    $description = recuperation("description");
    $tarif_reserv = recuperation("tarif_reserv");
    $qt_initial = recuperation("qt_initial");
    $qt_disponible = recuperation("qt_disponible");
    $voiture = new voiture($code, $marque, $modele, $description, $tarif_reserv, $qt_initial, $qt_disponible);

    try {
        $voiture->ajoutVoiture();
        
        if (isset($_FILES['photos'])) {
            $n = count($_FILES['photos']['name']);
            
            for ($i = 0; $i < $n; $i++) {
                if ($_FILES['photos']['error'][$i] == 0) {
                    $filename = $_FILES["photos"]["name"][$i];
                    $tmpname = $_FILES["photos"]["tmp_name"][$i];
                    $size = $_FILES["photos"]["size"][$i];
                    
                    $extv = array('jpg', 'jpeg', 'png');
                    $fext = strtolower(substr(strrchr($filename, '.'), 1));
                    
                    if (in_array($fext, $extv)) {
                        $dest = "./media/voitures/" . $filename;
                        if (move_uploaded_file($tmpname, $dest)) {
                            $voiture->photovoiture($code, $dest);
                        }
                    }
                }
            }
        }
        
        redirect("./traitement.gerant.php?envoie=listeVoiture");
    } catch (PDOException $e) {
        error_log("Add Car Error: " . $e->getMessage());
        redirect("./formulaires/formAjoutVoiture.php?error=exist");
    }
}

if ($info == "Inscription") {
    $nom = recuperation("nom");
    $prenom = recuperation("prenom");
    $id = recuperation("email");
    $telephone = recuperation("telephone");
    $passe = recuperation("passe");
    $sexe = recuperation("sexe");
    $profession = recuperation("profession");

    $utilisateur = new client($id, $passe, $nom, $prenom, $sexe, $profession, $telephone);
    try {
        $utilisateur->inserer();
        $_SESSION['email'] = $id;
        $_SESSION['status'] = "actif";
        redirect("./profileClient.php");
    } catch (PDOException $e) {
        error_log("Client Registration Error: " . $e->getMessage());
        redirect("./formulaires/inscription.php?error=exist");
    }
}

if ($info == "InscriptionGerant") {
    if (!isset($_SESSION["id"])) {
        redirect("index.php");
    }
    
    $nom = recuperation("nom");
    $prenom = recuperation("prenom");
    $id = recuperation("id");
    $passe = recuperation("passe");
    $email = recuperation("email");

    $utilisateur = new gerant($id, $passe, $email, $nom, $prenom);
    try {
        $utilisateur->inserer();
        redirect("./accueilGerant.php");
    } catch (PDOException $e) {
        error_log("Manager Registration Error: " . $e->getMessage());
        redirect("./formulaires/formAjoutGerant.php?error=exist");
    }
}

if ($info == "connectionClient") {
    $id = recuperation("id");
    $passe = recuperation("passe");

    $utilisateur = new utilisateur($id);
    if ($utilisateur->getter(1) != null && $utilisateur->getter(1) == $passe && $utilisateur->getter(2) == "client") {
        $utilisateur = new client($id);
        $_SESSION['email'] = $id;
        redirect("./profileClient.php");
    } else {
        redirect("./formulaires/connexionClient.php?error=exist");
    }
}

if ($info == "connectionGerant") {
    $id = recuperation("id");
    $passe = recuperation("passe");

    $utilisateur = new utilisateur($id);
    if ($utilisateur->getter(1) != null && $utilisateur->getter(1) == $passe && $utilisateur->getter(2) == "gerant") {
        $_SESSION['id'] = $id;
        $_SESSION['acces'] = 1;
        redirect("./profileGerant.php");
    } else {
        redirect("./formulaires/connextionGerant.php?error=exist");
    }
}

if ($info3 == "modifier") {
    if (!isset($_SESSION["id"])) {
        redirect("index.php");
    }
    
    $code = recuperation("code");
    $marque = recuperation("marque");
    $modele = recuperation("modele");
    $description = recuperation("description");
    $qt_initial = recuperation("qt_initial");
    $qt_disponible = recuperation("qt_disponible");
    $tarif_reserv = recuperation("tarif_reserv");
    
    try {
        $voiture = new voiture($code, $marque, $modele, $description, $tarif_reserv, $qt_initial, $qt_disponible);
        $voiture->modifVoiture();
        redirect("./traitement.gerant.php?envoie=listeVoiture");
    } catch (PDOException $e) {
        error_log("Car Modification Error: " . $e->getMessage());
        redirect("./modifierVoiture.php?code=$code");
    }
}

if (isset($_GET["envoie"]) && $_GET["envoie"] == "supprimerVoiture") {
    if (!isset($_SESSION["id"])) {
        redirect("index.php");
    }
    
    $code = $_GET["code"];
    $message = "&message=error"; 

    try {
        $voiture = new voiture($code, null, null, null, null, null, null);
        $error = $voiture->supVoiture();

        $message = "";
        if ($error == 1) {
            $message = "&message=location"; 
        }

        redirect("traitement.gerant.php?envoie=listeVoiture" . $message);
    } catch (PDOException $e) {
        error_log("Car Deletion Error: " . $e->getMessage());
        redirect("traitement.gerant.php?envoie=listeVoiture" . $message);
    } catch (Exception $e) {
        error_log("Unexpected Error: " . $e->getMessage());
        redirect("traitement.gerant.php?envoie=listeVoiture" . $message);
    }
}

if ($info2 == "ajouterOffre") {
    if (!isset($_SESSION["id"])) {
        redirect("index.php");
    }
    
    $code = recuperation("code");
    $prix = recuperation("prix");
    $debut = recuperation("debut");
    $fin = recuperation("fin");
    
    $offre = new offre(null, $code, $debut, $fin, $prix);
    try {
        $offre->ajoutOffre();
        redirect("./traitement.gerant.php?envoie=listeVoiture");
    } catch (PDOException $e) {
        error_log("Add Offer Error: " . $e->getMessage());
        redirect("./traitement.gerant.php?envoie=listeVoiture&errorOffre=errorOffre");
    }
}

if ($info3 == "modifierOffre") {
    if (!isset($_SESSION["id"])) {
        redirect("index.php");
    }

    $debut_offr = recuperation("debut_offr");
    $fin_offr = recuperation("fin_offr");
    $tarif_reduit = recuperation("tarif_reduit");
    $code = recuperation("code");

    try {
        $offre = new offre(null, $code, $debut_offr, $fin_offr, $tarif_reduit);
        $offre->modifierOffre();
        redirect("./traitement.gerant.php?envoie=listeVoiture");
    } catch (PDOException $e) {
        error_log("Offer Modification Error: " . $e->getMessage());
        redirect("./modifierOffre.php?code=$code&error=nope");
    }
}

if (isset($_GET["envoie"]) && $_GET["envoie"] == "supprimerOffre") {
    if (!isset($_SESSION["id"])) {
        redirect("index.php");
    }
    
    $code = $_GET["code"];
    try {
        $offre = new offre(null, $code, null, null, null);
        $offre->supprimerOffre();
        redirect("traitement.gerant.php?envoie=listeVoiture");
    } catch (PDOException $e) {
        error_log("Offer Deletion Error: " . $e->getMessage());
        redirect("traitement.gerant.php?envoie=listeVoiture");
    } catch (Exception $e) {
        error_log("Unexpected Error: " . $e->getMessage());
        redirect("traitement.gerant.php?envoie=listeVoiture");
    }
}

if (isset($_GET["envoie"]) && $_GET["envoie"] == "listeReservation" && isset($_GET["action"]) && isset($_GET["id"])) {
    if (!isset($_SESSION["id"])) {
        redirect("index.php");
    }
    
    $id_reser = $_GET["id"];
    $action = $_GET["action"];
    
    $reservation = new reservation($id_reser);
    
    switch ($action) {
        case "active":
            if ($reservation->confirmer()) {
                redirect("./traitement.gerant.php?envoie=listeReservation&success=confirmed");
            } else {
                redirect("./traitement.gerant.php?envoie=listeReservation&error=confirm_failed");
            }
            
        case "desactive":
            if ($reservation->annuler()) {
                redirect("./traitement.gerant.php?envoie=listeReservation&success=canceled");
            } else {
                redirect("./traitement.gerant.php?envoie=listeReservation&error=cancel_failed");
            }
            
        case "demarrer":
            if ($reservation->demarrer()) {
                redirect("./traitement.gerant.php?envoie=listeReservation&success=started");
            } else {
                redirect("./traitement.gerant.php?envoie=listeReservation&error=start_failed");
            }
            
        case "terminer":
            if ($reservation->terminer()) {
                redirect("./traitement.gerant.php?envoie=listeReservation&success=completed");
            } else {
                redirect("./traitement.gerant.php?envoie=listeReservation&error=complete_failed");
            }
            
        case "interrompre":
            if ($reservation->interrompre()) {
                redirect("./traitement.gerant.php?envoie=listeReservation&success=interrupted");
            } else {
                redirect("./traitement.gerant.php?envoie=listeReservation&error=interrupt_failed");
            }
            
        default:
            redirect("./traitement.gerant.php?envoie=listeReservation&error=invalid_action");
    }
}
?>