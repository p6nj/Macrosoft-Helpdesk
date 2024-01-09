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
try {
    session_start(); // la déserialisation du client est sujet à une erreur de reconnexion à la base
    if (!isset($_SESSION['client']) || !$_SESSION['client'] instanceof AdminSys) {
        // l'utilisateur n'est pas connecté
        redirect('accueil.php');
    }
} catch (ErreurBD $e) {
    $_SESSION['erreur'] = $e->getMessage();
    redirect('adminsys.php');
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
                <button onclick="window.location.href='connexion.php?déco=1&message=Vous avez été déconnecté.';">Deconnexion</button>
                <button title="<?= $_SESSION['client']->getProfil()['login']; ?>" onclick="document.querySelector(' dialog#profil').showModal()">
                    Profil
                </button>
                <dialog id="profil">
                    <h2>Profil</h2>
                    <?php $profil = $_SESSION['client']->getProfil(); ?>
                    Login :
                    <?= $profil['login'] ?><br>
                    Mot de passe : <hidden id='mdp'>
                        <?= $profil['mdp'] ?>
                    </hidden>
                    <button onclick="document.getElementById('mdp').style.display='block'; document.getElementById('affiche-mdp').style.display='none'" id="affiche-mdp">Afficher le mot de passe</button>
                    <br>
                    <button onclick="event.target.parentElement.close(); document.getElementById('mdp').style.display='none'; document.getElementById('affiche-mdp').style.display='block';">Fermer</button>
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
        <div>
            <h1>Connexions Echouées</h1>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>IP</th>
                        <th>Login</th>
                        <th>Mot de passe</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['client']->getConnexionsEchouées() as $ligne) : ?>
                        <tr>
                            <td><?= $ligne['date'] ?></td>
                            <td><?= $ligne['IP'] ?></td>
                            <td><?= $ligne['login_tente'] ?></td>
                            <td><?= $ligne['mdp_tente'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div>
            <h1>Tickets Validés</h1>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>IP</th>
                        <th>Login</th>
                        <th>Urgence</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>26 December 2023</td>
                        <td>191.145.125.5</td>
                        <td>alice</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>25 December 2023</td>
                        <td>191.145.125.5</td>
                        <td>tommy</td>
                        <td>4</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </main>
    <footer>
        <p>&copy; 2023 Macrosoft Helpdesk</p>
    </footer>
</body>

</html>