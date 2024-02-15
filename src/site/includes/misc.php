<?php
function redirect(string $url)
{
    header('Location: ' . $url);
    exit(0);
}

function debug()
{
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

function niv_urgence_str(int $niv)
{
    switch ($niv) {
        case 1:
            return 'Moindre';
        case 2:
            return 'Important';
        case 3:
            return 'Très important';
        case 4:
            return 'Urgent';
        default:
            'Non spécifié';
    }
}

function log_prepare()
{
    require_once 'includes/header.php';
    try {
        session_start(); // la déserialisation du client est sujet à une erreur de reconnexion à la base
        if (!isset($_SESSION['client']) || !$_SESSION['client'] instanceof AdminSys) {
            // l'utilisateur n'est pas connecté
            redirect('accueil.php');
        }
    } catch (ErreurBD $e) {
        $_SESSION['erreur'] = $e->getMessage();
    }
}
