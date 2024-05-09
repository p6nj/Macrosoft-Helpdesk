<?php

include 'profils.php';

// Tests unitaires pour la méthode `inscription` de la classe `Visiteur`
function testInscription() {
    $visiteur = new Visiteur();

    // C1: Inscrire un utilisateur déjà présent
    try {
        $visiteur->inscription('MDufaud', 'dgtsgfs');
    } catch (RequêteIllégale $e) {
        echo 'Test C1 (MDufaud existant) Passed: ', $e->getMessage(), PHP_EOL;
    }

    // C1: Login vide
    try {
        $visiteur->inscription('', 'dgtsgfs');
    } catch (RequêteIllégale $e) {
        echo 'Test C1 (Login vide) Passed: ', $e->getMessage(), PHP_EOL;
    }

    // C2: Inscrire un nouvel utilisateur avec succès
    try {
        $visiteur->inscription('MHoffer', 'fsdfsdf');
        echo 'Test C2 (MHoffer) Passed: Compte créé', PHP_EOL;
    } catch (RequêteIllégale $e) {
        echo 'Test C2 Failed: ', $e->getMessage(), PHP_EOL;
    }
}

// Tests unitaires pour la méthode `ajoutTicket` de la classe `Utilisateur`
function testAjoutTicket() {
    $utilisateur = new Utilisateur('user1', 'mdp_correct');

    // C1: Libellé vide
    try {
        $utilisateur->ajoutTicket('', 1, 'Problème X', 'user1');
    } catch (RequêteIllégale $e) {
        echo 'Test C1 (Libellé vide) Passed: ', $e->getMessage(), PHP_EOL;
    }

    // C2: Libellé inexistant
    try {
        $utilisateur->ajoutTicket(999, 2, 'Problème Y', 'user2');
    } catch (RequêteIllégale $e) {
        echo 'Test C2 (Libellé inexistant) Passed: ', $e->getMessage(), PHP_EOL;
    }

    // C3: Niveau d'urgence incorrect
    try {
        $utilisateur->ajoutTicket(1, 5, 'Problème Z', 'user3');
    } catch (RequêteIllégale $e) {
        echo 'Test C3 (Niveau urgence incorrect) Passed: ', $e->getMessage(), PHP_EOL;
    }

    // C4: Cible introuvable
    try {
        $utilisateur->ajoutTicket(2, 1, 'Autre problème', 'user999');
    } catch (RequêteIllégale $e) {
        echo 'Test C4 (Cible introuvable) Passed: ', $e->getMessage(), PHP_EOL;
    }

    // C5: Ticket ajouté avec succès
    try {
        $utilisateur->ajoutTicket(1, 2, 'Problème résolu', 'user1');
        echo 'Test C5 Passed: Ticket ajouté', PHP_EOL;
    } catch (RequêteIllégale $e) {
        echo 'Test C5 Failed: ', $e->getMessage(), PHP_EOL;
    }
}

// Tests unitaires pour la méthode `connecte` de la classe `Visiteur`
function testConnecte() {
    $visiteur = new Visiteur();

    // C1: Utilisateur non trouvé
    try {
        $visiteur->connecte('user999', 'mdp');
    } catch (ConnexionImpossible $e) {
        echo 'Test C1 (Utilisateur non trouvé) Passed: ', $e->getMessage(), PHP_EOL;
    }

    // C2: Mot de passe invalide
    try {
        $visiteur->connecte('user1', 'wrong_mdp');
    } catch (ConnexionImpossible $e) {
        echo 'Test C2 (Mot de passe invalide) Passed: ', $e->getMessage(), PHP_EOL;
    }

    // C3: Rôle non existant
    try {
        $visiteur->connecte('user2', 'mdp');
    } catch (ConnexionImpossible $e) {
        echo 'Test C3 (Rôle non existant) Passed: ', $e->getMessage(), PHP_EOL;
    }

    // C4: Rôle non reconnu
    try {
        $visiteur->connecte('user3', 'mdp');
    } catch (ConnexionImpossible $e) {
        echo 'Test C4 (Rôle non reconnu) Passed: ', $e->getMessage(), PHP_EOL;
    }

    // C5: Connexion réussie, retour d'un objet Client correspondant
    try {
        $client = $visiteur->connecte('user1', 'mdp_correct');
        echo 'Test C5 Passed: Connexion réussie, Client ', get_class($client), PHP_EOL;
    } catch (ConnexionImpossible $e) {
        echo 'Test C5 Failed: ', $e->getMessage(), PHP_EOL;
    }
}

// Tests unitaires pour la méthode `assigneTicket` de la classe `Technicien`
function testAssigneTicket() {
    $technicien = new Technicien('tech1', 'mdp_tech');

    // C1: Utilisateur n'existe pas
    try {
        $technicien->assigneTicket(999);
    } catch (RequêteIllégale $e) {
        echo 'Test C1 (Utilisateur n\'existe pas) Passed: ', $e->getMessage(), PHP_EOL;
    }

    // C2: Rôle invalide
    try {
        $technicien->assigneTicket('user2');
    } catch (RequêteIllégale $e) {
        echo 'Test C2 (Rôle invalide) Passed: ', $e->getMessage(), PHP_EOL;
    }

    // C3: Ticket n'existe pas
    try {
        $technicien->assigneTicket(998);
    } catch (RequêteIllégale $e) {
        echo 'Test C3 (Ticket n\'existe pas) Passed: ', $e->getMessage(), PHP_EOL;
    }
}

testInscription();
testAjoutTicket();
testConnecte();
testAssigneTicket();

?>
