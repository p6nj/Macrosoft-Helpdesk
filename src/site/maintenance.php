<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Macrosoft HelpDesk</title>
    <link rel="stylesheet" type="text/css" href="common.css">
</head>

<?php
// GET vars : 'message'
require_once 'includes/profils.php';
require_once 'includes/misc.php';
try {
    session_start();
    if (!isset($_SESSION['client']) || !$_SESSION['client'] instanceof Client)  // une instance de visiteur est nécessaire
        $_SESSION['client'] = new Visiteur();
    else if ($_SESSION['client'] instanceof Compte)  // l'utilisateur est déjà connecté
        redirect('accueil.php');
} catch (ErreurBD $e) {
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
            <div class="far-right">
                <button onclick="window.location.href='accueil.php';">Accueil</button>
                <button onclick="window.location.href='connexion.php?déco=1&message=Vous avez été déconnecté.';">Deconnexion</button>
            </div>
        </nav>
    </header>
    <main id="header-top-margin">
        <center class="video-container">
            <img src="img/loading.svg" alt="logo de chargement">
            <div class="message">
                <?php
                if (isset($_GET['message']))
                    echo $_GET['message'];
                ?>
            </div>
        </center>
    </main>
    <footer>
        <p>&copy; 2023 Macrosoft Helpdesk</p>
    </footer>
</body>

</html>