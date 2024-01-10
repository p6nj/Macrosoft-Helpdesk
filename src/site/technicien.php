<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Macrosoft HelpDesk</title>
    <link rel="stylesheet" type="text/css" href="common.css">
</head>

<script>
    function confirm(at, tid) {
        let dialog = document.getElementById('confirm');
        let form = document.forms['options'];
        if (at) {
            [...dialog.getElementsByClassName('at')].forEach(e => {
                e.style.display = 'inline';
            });
            [...dialog.getElementsByClassName('na')].forEach(e => {
                e.style.display = 'none';
            });
        } else {
            [...dialog.getElementsByClassName('na')].forEach(e => {
                e.style.display = 'inline';
            });
            [...dialog.getElementsByClassName('at')].forEach(e => {
                e.style.display = 'none';
            });
        }
        form.elements['idT'].value = tid;
        dialog.showModal();
    }
</script>

<?php
require_once 'includes/profils.php';
require_once 'includes/misc.php';
try {
    session_start(); // la déserialisation du client est sujet à une erreur de reconnexion à la base
    if (!isset($_SESSION['client']) || !$_SESSION['client'] instanceof Technicien) {
        // l'utilisateur n'est pas connecté
        redirect('accueil.php');
    } else if (isset($_POST['idT'])) {
        if (isset($_POST['at'])) {
            $_SESSION['client']->fermeTicket((int) $_POST['idT']);
        } else if (isset($_POST['na'])) {
            $_SESSION['client']->assigneTicket((int) $_POST['idT']);
        }
    }
} catch (ErreurBD $e) {
    $_SESSION['erreur'] = $e->getMessage();
    redirect('technicien.php');
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
        <?php
        if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        }
        ?>
        </div>
        <div class="error">
            <?php
            if (isset($_SESSION['erreur'])) {
                echo $_SESSION['erreur'];
                unset($_SESSION['erreur']);
            }
            ?>
        </div>
        <div>
            <h1>Tickets Attribués</h1>
            <div id="ticket-container">
                <?php foreach ($_SESSION['client']->getTicketsAttribués() as $ticket) : ?>
                    <ticket onclick="confirm(true, <?= $ticket['idT'] ?>)" class="clickable">
                        <lib><?= $ticket['libelle'] ?></lib>
                        <niv><?= niv_urgence_str($ticket['niv_urgence']) ?></niv>
                        <p><?= $ticket['description'] ?></p>
                        <cible><?= $ticket['cible'] ?></cible>
                        <demandeur><?= $ticket['demandeur'] ?></demandeur>
                    </ticket>
                <?php endforeach; ?>
            </div>
        </div>
        <div>
            <h1>Tickets Non Attribués</h1>
            <div id="ticket-container">
                <?php foreach ($_SESSION['client']->getTicketsNonAttribués() as $ticket) : ?>
                    <ticket onclick="confirm(false, <?= $ticket['idT'] ?>)" class="clickable">
                        <lib><?= $ticket['libelle'] ?></lib>
                        <niv><?= niv_urgence_str($ticket['niv_urgence']) ?></niv>
                        <p><?= $ticket['description'] ?></p>
                        <cible><?= $ticket['cible'] ?></cible>
                        <demandeur><?= $ticket['demandeur'] ?></demandeur>
                    </ticket>
                <?php endforeach; ?>
            </div>
        </div>
        <dialog id="confirm">
            <h2>Confirmation</h2>
            <p class="na">Voulez-vous prendre en charge ce ticket ?</p>
            <p class="at">Voulez-vous fermer ce ticket ?</p>
            <br><br>
            <form method="post" id="options">
                <input type="hidden" name="idT">
                <button type="submit" name="na" class="na">Confirmer</button>
                <button type="submit" name="at" class="at">Confirmer</button>
                <input type="button" onclick="document.getElementById('confirm').close()" value="Annuler">
            </form>
        </dialog>
    </main>
    <footer>
        <p>&copy; 2023 Macrosoft Helpdesk</p>
    </footer>
</body>

</html>