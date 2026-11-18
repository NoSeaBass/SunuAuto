<?php
class voiture
{
    private $code;
    private $marque;
    private $modele;
    private $description;
    private $tarif_reserv;
    private $qt_initial;
    private $qt_disponible;

    function __construct($code, $marque, $modele, $description, $tarif_reserv, $qt_initial, $qt_disponible)
    {
        $this->code = $code;
        $this->marque = $marque;
        $this->modele = $modele;
        $this->description = $description;
        $this->tarif_reserv = $tarif_reserv;
        $this->qt_initial = $qt_initial;
        $this->qt_disponible = $qt_disponible;
    }

    function ajoutVoiture() {
        $req = "insert into voiture (code, marque, modele, description, tarif_reserv, qt_initial, qt_disponible) values(?,?,?,?,?,?,?)";
        include "./fonctions/connexion.php";
        $rep = $connexion->prepare($req);
        $rep->bindParam(1, $this->code);
        $rep->bindParam(2, $this->marque);
        $rep->bindParam(3, $this->modele);
        $rep->bindParam(4, $this->description);
        $rep->bindParam(5, $this->tarif_reserv);
        $rep->bindParam(6, $this->qt_initial);
        $rep->bindParam(7, $this->qt_disponible);

        $res = $rep->execute();
    }

    function listeVoiture() {
        include "./fonctions/connexion.php";
        $req = "select * from voiture";
        $rep = $connexion->prepare($req);
        $res = $rep->execute();
        return $rep->fetchAll();
    }

    function listeVoitureDispo() {
        include "./fonctions/connexion.php";
        $req = $connexion->prepare("SELECT * FROM voiture WHERE qt_disponible > 0");
        $req->execute();
        $voitures = $req->fetchAll();
        return $voitures;
    }
    

    function photovoiture($code,$url) {
        include "./fonctions/connexion.php";
        $req = "insert into photo(code, url) values(?,?)";
        $rep = $connexion->prepare($req);
        $rep->bindParam(1, $code);
        $rep->bindParam(2, $url);
        $res = $rep->execute();
    }

    function recupPhotos() {
        include "./fonctions/connexion.php";
        $req = "select * from photo";
        $rep = $connexion->prepare($req);
        $rep->execute();
        return $rep->fetchAll();
    }

    function recupPhotoCode($code)
    {
        include "./fonctions/connexion.php";
        $req = "select * from photo where code=?";
        $rep = $connexion->prepare($req);
        $rep->bindParam(1, $code);
        $rep->execute();
        return $rep->fetchAll();
    }
    
    function recupCode($code) {
        include "./fonctions/connexion.php";
        $req = "select * from voiture where code=?";
        $rep = $connexion->prepare($req);
        $rep->bindParam(1, $code);
        $rep->execute();
        return $rep->fetchAll();
    }
    
    function modifVoiture() {
        include "./fonctions/connexion.php";
        $req = "update voiture set modele=?, marque=?, description=?,
        tarif_reserv=?, qt_initial=?, qt_disponible=? where code=?";
        $rep = $connexion->prepare($req);
        $rep->bindParam(1, $this->modele);
        $rep->bindParam(2, $this->marque);
        $rep->bindParam(3, $this->description);
        $rep->bindParam(4, $this->tarif_reserv);
        $rep->bindParam(5, $this->qt_initial);
        $rep->bindParam(6, $this->qt_disponible);
        $rep->bindParam(7, $this->code);
        $res = $rep->execute();
    }

    function supVoiture() {
        include "./fonctions/connexion.php";

        $checkReq = "SELECT COUNT(*) FROM reservation WHERE code = ? AND status_reser IN ('confirmer', 'en cours');";
        $checkRep = $connexion->prepare($checkReq);
        $checkRep->execute([$this->code]);
        $reservationCount = $checkRep->fetchColumn();

        if ($reservationCount > 0) {
            return 1;
        }

        $deleteCarAndRelatedReq = "
        DELETE FROM reservation WHERE code = ?;
        DELETE FROM photo WHERE code = ?;
        DELETE FROM offre WHERE code = ?;
        DELETE FROM voiture WHERE code = ?;
        ";
        $deleteCarAndRelatedRep = $connexion->prepare($deleteCarAndRelatedReq);
        $deleteCarAndRelatedRep->execute([$this->code, $this->code, $this->code, $this->code]);

        $connexion = NULL;

        return 0;
    }

    function rechercheVoitures($marque = null, $modele = null, $prix_max = null) {
        include "./fonctions/connexion.php";
        
        $sql = "SELECT * FROM voiture WHERE 1=1";
        $params = [];
        
        if (!empty($marque)) {
            $sql .= " AND marque LIKE ?";
            $params[] = "%$marque%";
        }
        
        if (!empty($modele)) {
            $sql .= " AND modele LIKE ?";
            $params[] = "%$modele%";
        }
        
        if (!empty($prix_max) && is_numeric($prix_max)) {
            $sql .= " AND tarif_reserv <= ?";
            $params[] = $prix_max;
        }
        
        $sql .= " AND qt_disponible > 0";
        
        $rep = $connexion->prepare($sql);
        $rep->execute($params);
        return $rep->fetchAll();
    }
}
?>

