<?php
require_once 'includes/header.php';
try {
    session_start(); // la déserialisation du client est sujet à une erreur de reconnexion à la base
    if (!isset($_SESSION['client']) || !$_SESSION['client'] instanceof Utilisateur) {
        // l'utilisateur n'est pas connecté
        redirect('accueil.php');
    } else if (isset($_POST['libellé']) && isset($_POST['niveau']) && isset($_POST['description']) && isset($_POST['cible'])) {
        $_SESSION['client']->ajoutTicket(
            (int) $_POST['libellé'],
            (int) $_POST['niveau'],
            htmlspecialchars($_POST['description']),
            htmlspecialchars($_POST['cible'])
        );
        $_SESSION['message'] = 'Ticket ajouté avec succès.';
    }
} catch (ErreurBD $e) {
    $_SESSION['erreur'] = $e->getMessage();
    redirect('utilisateur.php');
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
                <button title="<?= $_SESSION['client']->getProfil()['login']; ?>" onclick="document.querySelector(' dialog#profil').showModal()">
                    Profil
                </button>
                <dialog id="profil">
                    <h2>Profil</h2>
                    <?php $profil = $_SESSION['client']->getProfil(); ?>
                    Login : <?= $profil['login'] ?><br>
                    Mot de passe : <hidden id='mdp'><?= $profil['mdp'] ?></hidden>
                    <button onclick="document.getElementById('mdp').style.display='block'; document.getElementById('affiche-mdp').style.display='none'" id='affiche-mdp'>Afficher le mot de
                        passe</button>
                    <br>
                    <button onclick="event.target.parentElement.close(); document.getElementById('mdp').style.display='none'; document.getElementById('affiche-mdp').style.display='block';">Fermer</button>
                </dialog>
            </div>
        </nav>
    </header>
    <main id="header-top-margin">
        <div class="message">
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
            <h1>Derniers tickets</h1>
            <div id="ticket-container">
                <?php foreach ($_SESSION['client']->getTickets() as $ticket) : ?>
                    <ticket>
                        <p><?= $ticket['description'] ?></p>
                    </ticket>
                <?php endforeach; ?>
            </div>
        </div>
        <dialog id="add-ticket">
            <h2>Ajouter un ticket</h2>
            <p>Une fois créé, ce ticket sera assigné à un technicien compétent qui vous assistera dans les plus brefs
                délais.</p>
            <form method="post">
                <label for="libellé">Libellé :</label>
                <br>
                <select name="libellé" id="libellé">
                    <?php function affiche_lib(array $lib, int $niveau = 0)
                    { ?>
                        <option value=<?= $lib['idL'] ?>>
                            <?= str_repeat('&emsp;', $niveau) . $lib['intitule'] ?>
                        </option>
                    <?php foreach ($lib['inf'] as $inf) {
                            affiche_lib($inf, $niveau + 1);
                        }
                    }
                    foreach ($_SESSION['client']->getLibellés() as $v) {
                        affiche_lib($v);
                    } ?>
                </select>
                <br>
                <br>
                <label for="niveau">Niveau d'urgence :</label>
                <br>
                <select name="niveau" id="niveau">
                    <?php for ($i = 1; $i < 5; $i++) : ?>
                        <option value="<?= $i ?>"><?= niv_urgence_str($i) ?></option>
                    <?php endfor; ?>
                </select>
                <br>
                <br>
                <label for="description">Description du problème :</label>
                <br>
                <input type="text" name="description" id="">
                <br>
                <br>
                <label for="cible">Login de l'utilisateur cible (facultatif) :</label>
                <br>
                <input type="text" name="cible" id="">
                <br>
                <br>
                <button autofocus type="submit">Enregistrer</button>
                <input type="button" onclick="event.target.parentElement.parentElement.close()" value="Annuler">
            </form>
        </dialog>
    </main>
    <?php include_once('includes/footer.html'); ?>
</body>

</html>