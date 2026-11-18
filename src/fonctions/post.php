<?php
function recuperation($valeur_a_recuperer)
{
    if(isset($_POST[$valeur_a_recuperer]))
        return $_POST[$valeur_a_recuperer];
    return NULL;
}
?>
