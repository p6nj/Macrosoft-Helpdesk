<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
// GET vars : 'message'
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
                <button onclick="window.location.href='connexion.php';">Connexion</button>
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
    <?php include_once('includes/footer.html'); ?>
</body>

</html>