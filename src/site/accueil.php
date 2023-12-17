<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Macrosoft HelpDesk</title>
    <link rel="stylesheet" type="text/css" href="common.css">
</head>

<?php
require_once 'includes/profils.php';
require_once 'includes/misc.php';
debug();
try {
    session_start();  // la déserialisation du client est sujet à une erreur de reconnexion à la base
    if (!isset($_SESSION['client']) || !$_SESSION['client'] instanceof Client)  // une instance de visiteur est nécessaire
        $_SESSION['client'] = new Visiteur();
    else if ($_SESSION['client'] instanceof Compte)  // l'utilisateur est déjà connecté
        redirect($_SESSION['client'] instanceof Utilisateur ? 'utilisateur.html' : 'accueil.html');
} catch (ErreurBD $e) {  // seules nos erreurs 'maison' sont capturées, les autres représentent des bugs et doivent interrompre le chargement de la page
    redirect('connexion.php?erreur=' . $e->getMessage());
}
?>

<body>
    <header>
        <nav>
            <div class="left">
                <img id="logo" src="img/logo.svg" alt="Logo Macrosoft Helpdesk">
                <h1>HelpDesk</h1>
            </div>
            <div class="right">
                <button onclick="window.location.href='inscription.html';">Inscription</button>
                <button onclick="window.location.href='connexion.php';">Connexion</button>
            </div>
        </nav>
    </header>
    <main id="header-top-margin">
        <div class="video-container">
            <h1>Vidéo Démonstration</h1>
            <p>La vidéo suivante sera un tutoriel d'utilisation du site. Pour l'instant, il s'agit d'une vidéo avec un
                lien cassé.</p>
            <video controls>
                <source src="" type="video/mp4">
            </video><br>
            <button>Afficher le script de la vidéo</button>
        </div>
        <div>
            <h1>Derniers tickets</h1>
            <div id="ticket-container">
                <?php foreach ($_SESSION['client']->getTickets() as $ticket): ?>
                <div>
                    <p><?= $ticket['description'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2023 Macrosoft Helpdesk</p>
    </footer>
</body>

</html>
