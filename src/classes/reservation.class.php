<?php
include "voiture.class.php";

class reservation {
    private $code;
    private $id;
    private $tarif;
    private $id_reser;
    private $status_reser;
    private $debut_reser;
    private $fin_reserv;

    function __construct(...$args) {
        if (count($args) == 0) {
            $this->code = NULL;
        }
        else if (count($args) == 1) {
            include "./fonctions/connexion.php";
            $request = $connexion->prepare("
            SELECT *
            FROM reservation
            WHERE id_reser = ?;
            ");
            $request->bindParam(1, $args[0]);
            $request->execute();
            $t = $request->fetch(PDO::FETCH_ASSOC);
            if ($t) {
                $this->code = $t["code"];
                $this->id = $t["id"];
                $this->tarif = $t["tarif"];
                $this->id_reser = $t["id_reser"];
                $this->status_reser = $t["status_reser"];
                $this->debut_reser = $t["debut_reser"];
                $this->fin_reserv = $t["fin_reserv"];
            }
        }
        else if (count($args) == 2) {
            include "./fonctions/connexion.php";
            $request = $connexion->prepare("
            SELECT *
            FROM reservation
            WHERE (code = ?) AND (id = ?);
            ");
            $request->bindParam(1, $args[0]);
            $request->bindParam(2, $args[1]);
            $request->execute();
            $t = $request->fetch(PDO::FETCH_ASSOC);
            if ($t) {
                $this->code = $t["code"];
                $this->id = $t["id"];
                $this->tarif = $t["tarif"];
                $this->id_reser = $t["id_reser"];
                $this->status_reser = $t["status_reser"];
                $this->debut_reser = $t["debut_reser"];
                $this->fin_reserv = $t["fin_reserv"];
            }
        }
        else if (count($args) == 5) {
            $this->code = $args[0];
            $this->id = $args[1];
            $this->tarif = $args[2]; 
            $this->status_reser = "suivie";
            $this->debut_reser = $args[3];
            $this->fin_reserv = $args[4];
        }
    }

private function addToHistorique($status) {
        include "./fonctions/connexion.php";
        
        // Get car details
        $request = $connexion->prepare("
        SELECT marque, modele FROM voiture WHERE code = ?
        ");
        $request->bindParam(1, $this->code);
        $request->execute();
        $car = $request->fetch(PDO::FETCH_ASSOC);
        
        if ($car) {
            $request = $connexion->prepare("
            INSERT INTO historique(marque, modele, tarif, debut_reser, fin_reserv, email, status)
            VALUES(?, ?, ?, ?, ?, ?, ?);
            ");
            $request->bindParam(1, $car["marque"]);
            $request->bindParam(2, $car["modele"]);
            $request->bindParam(3, $this->tarif);
            $request->bindParam(4, $this->debut_reser);
            $request->bindParam(5, $this->fin_reserv);
            $request->bindParam(6, $this->id);
            $request->bindParam(7, $status);
            $request->execute();
        }
    }

    function ajout() {
        $voiture = new voiture(null, null, null, null, null, null, null);
        $tableau = $voiture->recupCode($this->code);
        foreach ($tableau as $row) {
            if ($row["qt_disponible"] > 0) {
                include "./fonctions/connexion.php";
                $request = $connexion->prepare("
                INSERT INTO reservation(code, id, debut_reser, fin_reserv, tarif)
                VALUES(?, ?, ?, ?, ?);
                ");
                $request->bindParam(1, $this->code);
                $request->bindParam(2, $this->id);
                $request->bindParam(3, $this->debut_reser);
                $request->bindParam(4, $this->fin_reserv);
                $request->bindParam(5, $this->tarif);
                $request->execute();
    
                $id_reser = $connexion->lastInsertId();
                $this->id_reser = $id_reser;
                
                $this->addToHistorique("suivie");
                
                return $id_reser; 
            } else {
                return 0; 
            }
        }
    }

    function annuler() {
        if (!is_null($this->id_reser)) {
            include "./fonctions/connexion.php";

            $request = $connexion->prepare("
            SELECT COUNT(*) FROM reservation WHERE id_reser=? AND status_reser=?;
            ");
            $status = "confirmer";
            $request->bindParam(1, $this->id_reser);
            $request->bindParam(2, $status);
            $request->execute();
            $count = $request->fetchColumn();

            $request = $connexion->prepare("
            UPDATE reservation
            SET status_reser = 'annuler'
            WHERE id_reser = ?;
            ");
            $request->bindParam(1, $this->id_reser);
            $request->execute();
            
            $this->addToHistorique("annuler");

            if ($count > 0) {
                $request = $connexion->prepare("
                UPDATE voiture
                SET qt_disponible = qt_disponible + 1
                WHERE code = ?;
                ");
                $request->bindParam(1, $this->code);
                $request->execute();
            }
            
            return 1; 
        }
        return 0;
    }

    function confirmer() {
        if (!is_null($this->id_reser)) {
            $voiture = new voiture(null, null, null, null, null, null, null);
            $tableau = $voiture->recupCode($this->code);
            foreach ($tableau as $row) {
                if ($row["qt_disponible"] > 0) {
                    include "./fonctions/connexion.php";

                    $request = $connexion->prepare("
                    UPDATE reservation
                    SET status_reser = 'confirmer'
                    WHERE id_reser = ?;
                    ");
                    $request->bindParam(1, $this->id_reser);
                    $request->execute();
                    
                    $this->addToHistorique("confirmer");

                    $request = $connexion->prepare("
                    UPDATE voiture
                    SET qt_disponible = qt_disponible - 1
                    WHERE code = ?;
                    ");
                    $request->bindParam(1, $this->code);
                    $request->execute();
                    return 1;
                }
                $this->annuler();
                return 0;
            }
        }
        return 0;
    }

    function terminer() {
        if (!is_null($this->id_reser)) {
            include "./fonctions/connexion.php";
            
            $request = $connexion->prepare("
            UPDATE reservation
            SET status_reser = 'terminer'
            WHERE id_reser = ?;
            ");
            $request->bindParam(1, $this->id_reser);
            $request->execute();
            
            $this->addToHistorique("terminer");
    
            $request = $connexion->prepare("
            UPDATE voiture
            SET qt_disponible = qt_disponible + 1
            WHERE code = ?;
            ");
            $request->bindParam(1, $this->code);
            $request->execute();
    
            return 1; 
        }
        return 0;
    }
    
    function demarrer() {
        if (!is_null($this->id_reser)) {
            include "./fonctions/connexion.php";
            
            $request = $connexion->prepare("
            UPDATE reservation
            SET status_reser = 'en cours'
            WHERE id_reser = ?;
            ");
            $request->bindParam(1, $this->id_reser);
            $request->execute();
            
            $this->addToHistorique("en cours");
            
            return 1; 
        }
        return 0; 
    }
    
    function interrompre() {
        if (!is_null($this->id_reser)) {
            include "./fonctions/connexion.php";
            
            $request = $connexion->prepare("
            UPDATE reservation
            SET status_reser = 'interrompue'
            WHERE id_reser = ?;
            ");
            $request->bindParam(1, $this->id_reser);
            $request->execute();
            
            $this->addToHistorique("interrompue");
            
            $request = $connexion->prepare("
            UPDATE voiture
            SET qt_disponible = qt_disponible + 1
            WHERE code = ?;
            ");
            $request->bindParam(1, $this->code);
            $request->execute();
            
            return 1; 
        }
        return 0; 
    }

    function table() {
        include "./fonctions/connexion.php";
        $request = $connexion->prepare("
            SELECT *
            FROM reservation
            WHERE id_reser NOT IN (
                SELECT id_reser
                FROM reservation
                WHERE fin_reserv <= NOW()
            )
            ORDER BY debut_reser DESC
        ");
        $request->execute();
        return $request->fetchAll();
    }

    function tableLocation() {
        include "./fonctions/connexion.php";
        $request = $connexion->prepare("
        SELECT *
        FROM location;
        ");
        $request->execute();
        return $request->fetchAll();
    }

    function historique($clientEmail) {
        include "./fonctions/connexion.php";
        $request = $connexion->prepare("
        SELECT 
            r.*,
            v.marque,
            v.modele,
            v.description,
            DATEDIFF(r.fin_reserv, r.debut_reser) AS nb_jours,
            o.tarif_reduit AS tarif_reduit,
            o.debut_offr AS debut_offr,
            o.fin_offr AS fin_offr,
            CASE 
                WHEN (o.id_offr IS NOT NULL AND o.debut_offr <= r.debut_reser AND o.fin_offr >= r.debut_reser)
                THEN 1
                ELSE 0
            END AS has_offer
        FROM reservation r
        JOIN voiture v ON r.code = v.code
        LEFT JOIN offre o ON r.code = o.code 
            AND o.debut_offr <= r.debut_reser 
            AND o.fin_offr >= r.debut_reser
        WHERE r.id = ?
        ORDER BY r.debut_reser DESC;
        ");
        $request->bindParam(1, $clientEmail);
        $request->execute();
        return $request->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function getHistorique() {
        include "./fonctions/connexion.php";
        $request = $connexion->prepare("
        SELECT *
        FROM historique
        ORDER BY id_histo DESC;
        ");
        $request->execute();
        return $request->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function getHistoriqueByEmail($email) {
        include "./fonctions/connexion.php";
        $request = $connexion->prepare("
        SELECT *
        FROM historique
        WHERE email = ?
        ORDER BY id_histo DESC;
        ");
        $request->bindParam(1, $email);
        $request->execute();
        return $request->fetchAll(PDO::FETCH_ASSOC);
    }

    function details()
    {
        include "./fonctions/connexion.php";
        $request = $connexion->prepare("
        SELECT *
        FROM reservation
        LEFT JOIN voiture ON reservation.code = voiture.code
        LEFT JOIN client ON reservation.id = client.email
        LEFT JOIN offre ON reservation.code = offre.code
        AND debut_offr <= CURDATE() AND fin_offr >= CURDATE()
        WHERE id_reser=?;
        ");
        $request->bindParam(1, $this->id_reser);
        $request->execute();
        return $request->fetch(PDO::FETCH_ASSOC);
    }
}
?>