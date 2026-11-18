<?php
class utilisateur
{
    protected $id;
    protected $passe;
    protected $role;

    function __construct(...$param)
    {
        if(count($param) == 0)
        {
            $this->id = null;
            $this->passe = null;
            $this->role = null;
        }
        else if(count($param) == 1)
        {
            include "./fonctions/connexion.php";

            $requete = "SELECT * FROM utilisateur WHERE id = '$param[0]'";
            $demande = $connexion->prepare($requete);
            $demande->execute();
            $table = $demande->fetchAll();
            if(count($table) > 0)
            {
                $this->id = $table[0]['id'];
                $this->passe = $table[0]['passe'];
                $this->role = $table[0]['role'];
            }
            else
            {
                $this->id = null;
                $this->passe = null;
                $this->role = null;
            }

            $connexion ==  NULL;
        }
        else if(count($param) == 2)
        {
            $this->id = $param[0];
            $this->passe = $param[1];
            $this->role = null;
        }
    }

    function getter($i)
    {
        switch($i)
        {
            case 0:
                return $this->id;
                break;
            case 1:
                return $this->passe;
                break;
            case 2:
                return $this->role;
                break;
        }
    }

    function setter($valeur ,$i)
    {
        switch($i)
        {
            case 0:
                 $this->id = $valeur;
                break;
            case 1:
                $this->passe = $valeur;
                break;
            case 2:
                $this->role = $valeur;
                break;
        }
    }

    function affiche()
    {
        echo "<p>Utilisateur: $this->id</p><br>\n";
    }

    function inserer()
    {
        include "./fonctions/connexion.php";

        $requete = "INSERT INTO utilisateur (id, passe, role) VALUES(?,?,?)";
        $traitement = $connexion->prepare($requete);
        $traitement->bindParam(1, $this->id);
        $traitement->bindParam(2, $this->passe);
        $traitement->bindParam(3, $this->role);
        $traitement->execute();

        $connexion = NULL;
    }

    function suprimer()
    {
        include "./fonctions/connexion.php";

        $requete = $connexion->prepare("DELETE FROM utilisateur WHERE id = '$this->id'");
        $requete->execute();

        $connexion = NULL;
    }
}

?>
