<?php
class offre
{
    private $id_offr;
    private $code;
    private $tarif_reduit;
    private $debut_offr;
    private $fin_offr;

    public function __construct($id_offr, $code, $debut_offr, $fin_offr, $tarif_reduit)
    {
        $this->id_offr = $id_offr;
        $this->code = $code;
        $this->debut_offr = $debut_offr;
        $this->fin_offr = $fin_offr;
        $this->tarif_reduit = $tarif_reduit;
    }

    function verifieOffre()
    {
        include "./fonctions/connexion.php";
        $currentDate = date("Y-m-d");

        $req = "
        SELECT *
        FROM offre
        WHERE code = ?
        AND debut_offr <= ?
        AND fin_offr >= ?
        ORDER BY tarif_reduit ASC
        LIMIT 1
        ";

        $req = $connexion->prepare($req);
        $req->bindParam(1, $this->code);
        $req->bindParam(2, $currentDate);
        $req->bindParam(3, $currentDate);
        $req->execute();
        $tableau = $req->fetchAll();

        if (count($tableau) == 0) {
            return null;
        } else {
            return $tableau[0];
        }
    }

    public function supprimerOffre()
    {
        include "./fonctions/connexion.php";
        $req = "DELETE FROM offre WHERE code = ?";
        $rep = $connexion->prepare($req);
        if ($rep->execute([$this->code])) {
            header("Location: traitement.cacher.php");
        } else {
            echo "Erreur lors de la suppression.";
        }
    }

    public function ajoutOffre()
    {
        if(!is_null($this->verifieOffre())){
                $this->supprimerOffre();
        }
        include "./fonctions/connexion.php";
        $req = "insert into offre(code, debut_offr, fin_offr, tarif_reduit) VALUES (?, ?, ?, ?)";
        $rep = $connexion->prepare($req);
        $rep->bindParam(1, $this->code);
        $rep->bindParam(2, $this->debut_offr);
        $rep->bindParam(3, $this->fin_offr);
        $rep->bindParam(4, $this->tarif_reduit);
        $rep->execute();
    }

    function recupCode($code) {
        include "./fonctions/connexion.php";
        $req = "select * from offre where code=?";
        $rep = $connexion->prepare($req);
        $rep->bindParam(1, $code);
        $rep->execute();
        return $rep->fetchAll();
    }

    public function listeOffre() {
        include "./fonctions/connexion.php";
        $req = "select * from offre";
        $rep = $connexion->prepare($req);
        $rep->execute();
        return $rep->fetchAll();
    }

    public function listeOffreVoiture() {
        include "./fonctions/connexion.php";
        $req = "select v.* from voiture v NATURAL JOIN offre";
        $rep = $connexion->prepare($req);
        $rep->execute();
        return $rep->fetchAll();
    }

    public function modifierOffre()
    {
        include "./fonctions/connexion.php";
        $req = "UPDATE offre SET debut_offr = ?, fin_offr = ?, tarif_reduit = ? WHERE code = ?";
        $rep = $connexion->prepare($req);
        $rep->bindParam(1, $this->debut_offr);
        $rep->bindParam(2, $this->fin_offr);
        $rep->bindParam(3, $this->tarif_reduit);
        $rep->bindParam(4, $this->code);
        $rep->execute();
    }

}
?>
