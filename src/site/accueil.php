<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
debug();
try {
    session_start();  // la déserialisation du client est sujet à une erreur de reconnexion à la base
    if (!isset($_SESSION['client']) || !$_SESSION['client'] instanceof Client)  // une instance de visiteur est nécessaire
    {
        // echo $_SESSION['client'];
        $_SESSION['client'] = new Visiteur();
    } else if ($_SESSION['client'] instanceof Compte)  // l'utilisateur est déjà connecté
        redirect(
            $_SESSION['client'] instanceof Utilisateur ? 'utilisateur.php' : (
                $_SESSION['client'] instanceof Technicien ? 'technicien.php' : (
                    $_SESSION['client'] instanceof AdminSys ? 'adminsys.php' : (
                        $_SESSION['client'] instanceof AdminWeb ? 'adminweb.php' : 'accueil.php'
                    )
                )
            )
        );
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
                <button onclick="window.location.href='inscription.php';">Inscription</button>
                <button onclick="window.location.href='connexion.php';">Connexion</button>
            </div>
        </nav>
    </header>
    <main id="header-top-margin">
        <div class="video-container">
            <h1>Vidéo Démonstration</h1>
            <p>La vidéo suivante est un tutoriel d'utilisation du site.</p>
            <video controls>
                <source src="img/presentation.mp4" type="video/mp4">
            </video><br>
        </div>
        <div>
            <h1>Derniers tickets</h1>
            <div id="ticket-container">
                <?php if (!sizeof($tickets = $_SESSION['client']->getTickets())) : ?>
                    <div class="message">Aucun ticket à afficher.</div>
                <?php else : ?>
                    <?php foreach ($tickets as $ticket) : ?>
                        <ticket class="disabled">
                            <p><?= $ticket['description'] ?></p>
                        </ticket>
                <?php endforeach;
                endif; ?>
            </div>
        </div>
    </main>
    <?php include_once('includes/footer.html'); ?>
</body>

</html>
