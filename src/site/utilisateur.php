<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Macrosoft HelpDesk</title>
    <link rel="stylesheet" type="text/css" href="common.css">
</head>

<?php
// GET vars : 'message', 'erreur'
require_once 'includes/profils.php';
require_once 'includes/misc.php';
debug();
try {
    session_start();  // la déserialisation du client est sujet à une erreur de reconnexion à la base
    if (!isset($_SESSION['client']) || !$_SESSION['client'] instanceof Utilisateur)  // l'utilisateur n'est pas connecté
        redirect('accueil.php');
    else if (isset($_POST['libellé']) && isset($_POST['niveau']) && isset($_POST['description']) && isset($_POST['cible']))
        $_SESSION['client']->ajoutTicket(
            (int) $_POST['libellé'], (int) $_POST['niveau'], htmlspecialchars($_POST['description']), htmlspecialchars($_POST['cible'])
        );
} catch (ErreurBD $e) {
    redirect('utilisateur.php?erreur=' . $e->getMessage());
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
                <button onclick="document.querySelector(' dialog#add-ticket').showModal()">Créer&nbsp;un&nbsp;ticket&nbsp;+</button>
                <button onclick="window.location.href='connexion.php?déco=1&message=Vous avez été déconnecté.';">Deconnexion</button>
                <button id="pfp" title="<?= $_SESSION['client']->getProfil()[0]['login']; ?>" onclick="document.querySelector(' dialog#profil').showModal()">
                    <img src="https://i.pinimg.com/474x/8f/e6/66/8fe66626ec212bb54e13fa94e84c105c.jpg" alt="photo de profil">
                </button>
                <dialog id="profil">
                    <h2>Profil</h2>
                    <?php $profil = $_SESSION['client']->getProfil(); ?>
                    Login : <?=$profil['login']?><br>
                    Mot de passe : <hidden id='mdp'><?=$profil['mdp']?></hidden>
                    <button onclick="document.getElementById('mdp').style.display='block'">Afficher le mot de passe</button>
                    <br>
                    <button onclick="document.querySelector(' dialog#profil').close()">Fermer</button>
                </dialog>
            </div>
        </nav>
    </header>
    <main id="header-top-margin">
        <div class="message">
            <?php
                if (isset($_GET['message']))
                    echo $_GET['message'];
            ?>
        </div>
        <div class="error">
            <?php
                if (isset($_GET['erreur']))
                    echo $_GET['erreur'];
            ?>
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
        <dialog id="add-ticket">
            <h2>Ajouter un ticket</h2>
            <p>Une fois créé, ce ticket sera assigné à un technicien compétent qui vous assistera dans les plus brefs délais.</p>
            <form method="post">
                <label for="libellé">Libellé :</label>
                <br>
                <select name="libellé" id="libellé">
                    <option value="1265168445">Problème de trucmuche</option>
                    <option value="8498436251">Problème de machin</option>
                </select>
                <br>
                <label for="niveau">Niveau d'urgence :</label>
                <br>
                <select name="niveau" id="niveau">
                    <option value="1">Moindre</option>
                    <option value="2">Important</option>
                    <option value="3">Très important</option>
                    <option value="4">Urgent</option>
                </select>
                <br>
                <label for="description">Description du problème :</label>
                <br>
                <input type="text" name="description" id="">
                <br>
                <label for="cible">Login de l'utilisateur cible :</label>
                <br>
                <input type="text" name="cible" id="">
                <br>
                <button onclick="document.querySelector(' dialog#add-ticket').close()">Annuler</button>
                <input autofocus type="submit" value="Enregistrer">
            </form>
        </dialog>
    </main>
    <footer>
        <p>&copy; 2023 Macrosoft Helpdesk</p>
    </footer>
</body>

</html>
