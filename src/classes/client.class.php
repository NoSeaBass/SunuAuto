<?php
class client extends utilisateur
{
    private $telephone;
    private $nom;
    private $prenom;
    private $sexe;
    private $profession;
    public $status;

    function __construct(...$param)
    {
        if (count($param) == 0) {
            $this->id = null;
            $this->passe = null;
            $this->nom = null;
            $this->prenom = null;
            $this->sexe = null;
            $this->profession = null;
            $this->role = null;
            $this->status = null;
            $this->telephone = null;
        } else if (count($param) == 1) {
            include "./fonctions/connexion.php";
            $temp = $param[0];

            $demande = $connexion->prepare("SELECT * FROM client WHERE email = '$temp'");
            $demande->execute();
            $table = $demande->fetchAll();

            if (count($table) > 0) {
                $this->id = $table[0]['email'];
                $this->nom = $table[0]['nom'];
                $this->prenom = $table[0]['prenom'];
                $this->sexe = $table[0]['sexe'];
                $this->profession = $table[0]['profession'];
                $this->role = 'client';
                $this->status = $table[0]['status'];
                $this->telephone = $table[0]['telephone'];
            } else {
                $this->id = null;
                $this->nom = null;
                $this->prenom = null;
                $this->role = null;
                $this->status = null;
                $this->telephone = null;
            }

            $connexion == NULL;
        } else if (count($param) == 7) {
            $this->id = $param[0];
            $this->passe = $param[1];
            $this->nom = $param[2];
            $this->prenom = $param[3];
            $this->sexe = $param[4];
            $this->profession = $param[5];
            $this->telephone = $param[6];
            $this->role = 'client';
            $this->status = 'actif';
        }
    }

    function inserer()
    {
        parent::inserer();
        include "./fonctions/connexion.php";
        $traitement = $connexion->prepare("INSERT INTO client(email, telephone, nom, prenom, sexe, profession, status) VALUES(?,?,?,?,?,?,?)");
        $traitement->bindParam(1, $this->id);
        $traitement->bindParam(2, $this->telephone);
        $traitement->bindParam(3, $this->nom);
        $traitement->bindParam(4, $this->prenom);
        $traitement->bindParam(5, $this->sexe);
        $traitement->bindParam(6, $this->profession);
        $traitement->bindParam(7, $this->status);
        
        $traitement->execute();
        $connexion = NULL;
    }

    function status()
    {
        return $this->status;
    }

    function activer()
    {
        include "./fonctions/connexion.php";
        $traitement = $connexion->prepare("UPDATE client SET status = 'actif' WHERE email = '$this->id'");
        $traitement->execute();
    }

    function desactiver()
    {
        include "./fonctions/connexion.php";
        $traitement = $connexion->prepare("UPDATE client SET status = 'bloque' WHERE email = '$this->id'");
        $traitement->execute();
    }

    function suprimer()
{
    include "./fonctions/connexion.php";

    $checkReservationQuery = $connexion->prepare("SELECT COUNT(*) FROM reservation WHERE id = :email AND status_reser IN ('en cours', 'confirmer')");
    $checkReservationQuery->bindParam(':email', $this->id);
    $checkReservationQuery->execute();
    $reservationCount = $checkReservationQuery->fetchColumn();

    if ($reservationCount > 0) {
        return 1;
    } else {
        $deleteFavoritesQuery = $connexion->prepare("DELETE FROM favorie WHERE id = :email");
        $deleteFavoritesQuery->bindParam(':email', $this->id);
        $deleteFavoritesQuery->execute();

        $deleteReservationsQuery = $connexion->prepare("DELETE FROM reservation WHERE id = :email");
        $deleteReservationsQuery->bindParam(':email', $this->id);
        $deleteReservationsQuery->execute();

        $deleteClientQuery = $connexion->prepare("DELETE FROM client WHERE email = :email");
        $deleteClientQuery->bindParam(':email', $this->id);
        $deleteClientQuery->execute();

        $connexion = NULL;

        parent::suprimer();
    }
}

    function tabClient()
    {
        include "./fonctions/connexion.php";
        $traitement = $connexion->prepare("SELECT * FROM client");
        $traitement->execute();
        return $traitement->fetchAll();
    }

    function ajoutFav($code)
    {
        include "./fonctions/connexion.php";
        $traitement = $connexion->prepare("INSERT INTO favorie(code, id) VALUES(?, ?)");
        $traitement->bindParam(1, $code);
        $traitement->bindParam(2, $this->id);
        $traitement->execute();
        $connexion = NULL;
    }

    function tabfavoris()
    {
        include "./fonctions/connexion.php";
        $traitement = $connexion->prepare("
        SELECT v.* 
        FROM voiture v
        NATURAL JOIN favorie f
        WHERE f.id = ?;
        ");
        $traitement->bindParam(1, $this->id);
        $traitement->execute();
        return $traitement->fetchAll();
    }

    function estFavori($code)
    {
        include "./fonctions/connexion.php";
        $traitement = $connexion->prepare("SELECT COUNT(*) FROM favorie WHERE code = ? AND id = ?");
        $traitement->bindParam(1, $code);
        $traitement->bindParam(2, $this->id);
        $traitement->execute();
        return $traitement->fetchColumn() > 0;
    }

    function supprimerFavori($code)
    {
        include "./fonctions/connexion.php";
        $traitement = $connexion->prepare("DELETE FROM favorie WHERE code = ? AND id = ?");
        $traitement->bindParam(1, $code);
        $traitement->bindParam(2, $this->id);
        $traitement->execute();
        $connexion = NULL;
    }

    function get($i)
    {
        switch ($i) {
            case 0:
                return $this->nom;
            case 1:
                return $this->prenom;
            case 2:
                return $this->sexe;
            case 3:
                return $this->profession;
            case 4:
                return $this->status;
            case 5:
                return $this->id;
            case 6:
                return $this->telephone;
            default:
                return;
        }
    }
}
?>


