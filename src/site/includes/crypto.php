<?php

// Fonction principale de l'algorithme RC4
function rc4($key, $str)
{
    // Initialisation du tableau S
    $s = array();
    for ($i = 0; $i < 256; $i++) {
        $s[$i] = $i;
    }

    // Mélange du tableau S en utilisant la clé
    $j = 0;
    for ($i = 0; $i < 256; $i++) {
        // ord($key[$i % strlen($key)]) convertit le caractère courant de la clé en son code ASCII
        $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
        $temp = $s[$i];
        $s[$i] = $s[$j];
        $s[$j] = $temp;
    }

    // Génération du flux de clé pseudo-aléatoire et chiffrement
    $i = $j = 0;
    $encrypted = '';
    for ($y = 0; $y < strlen($str); $y++) {
        $i = ($i + 1) % 256;
        $j = ($j + $s[$i]) % 256;
        $temp = $s[$i];
        $s[$i] = $s[$j];
        $s[$j] = $temp;
        // chr($s[($s[$i] + $s[$j]) % 256]) est un caractère généré par le flux de clé
        $encrypted .= $str[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
    }

    return $encrypted;
}

// Fonction pour chiffrer un message en utilisant RC4
function encrypt($key, $plaintext) {
    // Encode le résultat chiffré en base64 pour le rendre lisible
    return base64_encode(rc4($key, $plaintext));
}

// Fonction pour déchiffrer un message chiffré en RC4
function decrypt($key, $ciphertext) {
    // Décode d'abord le texte de base64, puis le déchiffre
    return rc4($key, base64_decode($ciphertext));
}