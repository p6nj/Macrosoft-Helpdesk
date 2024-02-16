<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
debug();
try {
    session_start(); // la déserialisation du client est sujet à une erreur de reconnexion à la base
    if (!isset($_SESSION['client']) || !$_SESSION['client'] instanceof AdminSys) {
        // l'utilisateur n'est pas connecté
        redirect('accueil.php');
    } else if (isset($_SESSION['dl'])) {
        switch ($_SESSION['dl']) {
            case 'connexions':
                redirect('logs/connexions.php');
            case 'validations':
                redirect('logs/validations.php');
            case 'etats':
                redirect('logs/etats.php');
            default:
        }
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
                <button onclick="window.location.href='stats.php';">Stats</button>
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
        <div>
            <h1>Connexions Echouées</h1>
            <?php if (!sizeof($connexions = $_SESSION['client']->getConnexionsEchouées())) : ?>
                <div class="message">Aucune information à afficher.</div>
            <?php else : ?>
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
                        <?php foreach ($connexions as $ligne) : ?>
                            <tr>
                                <td><?= $ligne['date'] ?></td>
                                <td><?= $ligne['IP'] ?></td>
                                <td><?= $ligne['login_tente'] ?></td>
                                <td><?= $ligne['mdp_tente'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div>
            <h1>Tickets Validés</h1>
            <?php if (!sizeof($tickets = $_SESSION['client']->getTicketValidés())) : ?>
                <div class="message">Aucune information à afficher.</div>
            <?php else : ?>
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
                        <?php foreach ($tickets as $ligne) : ?>
                            <tr>
                                <td><?= $ligne['date'] ?></td>
                                <td><?= $ligne['IP'] ?></td>
                                <td><?= $ligne['login'] ?></td>
                                <td><?= $ligne['niv_urgence'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div>
            <h1>État des tickets</h1>
            <?php if (!sizeof($tickets = $_SESSION['client']->getTicketsEtat())) : ?>
                <div class="message">Aucune information à afficher.</div>
            <?php else : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Etat</th>
                            <th>Libellé</th>
                            <th>Urgence</th>
                            <th>Date</th>
                            <th>Technicien</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $ligne) : ?>
                            <tr>
                                <td><?= $ligne['description'] ?></td>
                                <td><?= $ligne['etat'] ?></td>
                                <td><?= $ligne['libelle'] ?></td>
                                <td><?= $ligne['niv_urgence'] ?></td>
                                <td><?= $ligne['date'] ?></td>
                                <td><?= $ligne['technicien'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </main>
    <?php include_once('includes/footer.html'); ?>
</body>

</html>