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
    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/profils.php';
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

function printlog(array $array, string $name)
{
    header('Content-Type: text/csv');
    header("Content-Disposition: attachment; filename=\"$name.csv\"");
    $keys = array_keys($array[0]);
    print $keys[0];
    foreach (array_slice($keys, 1) as $field) print ',' . $field;
    print '
';
    foreach ($array as $line) {
        print array_shift($line);
        foreach ($line as $field) print ',' . $field;
        print '
';
    }
}

function log_table(string $name, string $linkname)
{
?><div class="logtitle">
        <h1><?= $name ?></h1>
        <button onclick="window.location.href='logs/<?= $linkname ?>.php'">
            <svg xmlns="http://www.w3.org/2000/svg" xmln │ s:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 10 10" width="24px" height="24px">
                <path d="M 5 0 L 5 8 L 2 5 M 5 8 L 8 5 M 0 8 L 0 10 L 10 10 L 10 8" />
            </svg>
        </button>
    </div><?php
        }
