<?php
include "includes/profils.php";

$id = 'utilisateur_de_test';
$mdp = 'mot de passe';
$v = new Visiteur();
$v->inscription($id, $mdp);
assert($v->connecte($id, $mdp) instanceof Utilisateur);
