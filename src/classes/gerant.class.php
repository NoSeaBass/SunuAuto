<?php
class gerant extends utilisateur{
    private $email;
    private $nom;
    private $prenom;


    function __construct(...$param)
    {
        if(count($param) == 0)
        {
            $this->id = null;
            $this->email = null;
            $this->nom = null;
            $this->prenom = null;
            $this->role = null;
        }
        else if(count($param) == 1)
        {
            include "./fonctions/connexion.php";

            $requete = "SELECT * FROM gerant WHERE id = '$param[0]'";
            $demande = $connexion->prepare($requete);
            $demande->execute();
            $table = $demande->fetchAll();
            if(count($table) > 0)
            {
                $this->id = $table[0]['id'];
                $this->email = $table[0]['email'];
                $this->nom = $table[0]['nom'];
                $this->prenom = $table[0]['prenom'];
            }
            else
            {
                $this->id = null;
                $this->email = null;
                $this->nom = null;
                $this->prenom = null;
            }

            $connexion ==  NULL;
        }
        else if(count($param) == 5)
        {
            $this->id = $param[0];
            $this->passe = $param[1];
            $this->email = $param[2];
            $this->nom = $param[3];
            $this->prenom = $param[4];
            $this->role = 'gerant';
        }
    }

    function presence()
    {
        if (!is_null($this->id)) {
            return $this->prenom . " " . $this->nom;
        } else {
            return null;
        }
    }

    function inserer(){
        parent::inserer();
        include "./fonctions/connexion.php";
        $traitement = $connexion->prepare("INSERT INTO gerant(id, email, nom, prenom) VALUES(?,?,?,?)");
        $traitement->bindParam(1,$this->id);
        $traitement->bindParam(2,$this->email);
        $traitement->bindParam(3,$this->nom);
        $traitement->bindParam(4,$this->prenom);
        $traitement->execute();
        $connexion = NULL;
    }

    function suprimer()
    {
        include "./fonctions/connexion.php";

        $requete = $connexion->prepare("DELETE FROM gerant WHERE id = '$this->id'");
        $requete->execute();

        $connexion = NULL;
        parent::suprimer();
    }
}
?>
