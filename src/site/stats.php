<?php
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
?>

<body>
    <header>
        <nav>
            <div class="left">
                <img id="logo" src="img/logo.svg" alt="Logo Macrosoft Helpdesk">
                <h1>HelpDesk</h1>
            </div>
            <div class="far-right">
            	<button onclick="window.location.href='adminsys.php';">Retour</button>
                <button title="<?= $_SESSION['client']->getProfil()['login']; ?>" onclick="document.querySelector(' dialog#profil').showModal()">
                    Profil
                </button>
                <button onclick="window.location.href='connexion.php?déco=1&message=Vous avez été déconnecté.';">Deconnexion</button>
                <dialog id="profil">
                    <h2>Profil</h2>
                    <?php $profil = $_SESSION['client']->getProfil(); ?>
                    Login :
                    <?= $profil['login'] ?><br>
                    <button onclick="event.target.parentElement.close()">Fermer</button>
                </dialog>
            </div>
        </nav>
    </header>

    <main id="header-top-margin">
        <div class="error">
            <?php
            if (isset($_SESSION['erreur'])) {
                echo $_SESSION['erreur'];
                unset($_SESSION['erreur']);
            }
            ?>
        </div>
        <iframe src="http://192.168.1.168:3838/R/" frameborder="0"></iframe>

    </main>
    <?php include_once('includes/footer.html'); ?>
</body>

</html>
