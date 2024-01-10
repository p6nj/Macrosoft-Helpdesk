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
