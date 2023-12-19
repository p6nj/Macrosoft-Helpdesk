<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" type="text/css" href="common.css">
</head>

<?php
// GET vars : 'erreur', 'message', 'déco'
require_once 'includes/profils.php';
require_once 'includes/misc.php';
debug();
?>

<body>
    <header>
        <nav>
            <div class="left">
                <img id="logo" src="img/logo.svg" alt="Logo Macrosoft Helpdesk">
                <h1>HelpDesk</h1>
            </div>
            <div class="right">
                <button onclick="window.location.href='accueil.php';">Accueil</button>
                <button onclick="window.location.href='inscription.php';">Inscription</button>
            </div>
        </nav>
    </header>
    <main id="header-top-margin">
        <div class="shadow-form">
            <h1 class="center-text">Connexion</h1>
            <div class="form-division">
                <div class="left">
                    <form method="post">
                        <label for="username">Nom d'utilisateur :</label><br>
                        <input name="username" id="username" type="text">
                        <br>
                        <br>
                        <label for="password">Mot de passe :</label><br>
                        <input name="password" id="password" type="password">
                        <br>
                        <a href="maintenance.php?message=La page de réinitialisation de mot de passe est en cours de construction.">Mot de passe oublié</a>
                        <br>
                        <br>
                        <input type="submit" value="Se connecter">
                    </form>
                </div>
                <div class="right-captcha">
                    <p>Captcha</p>
                </div>
            </div>
            <div class="error">
                <?php
                    try {
                        session_start();  // la déserialisation du client est sujet à une erreur de reconnexion à la base
                        if (isset($_GET['déco']))  // la page précédente a demandé la déconnexion
                            session_destroy() && session_start();
                        if (!isset($_SESSION['client']) || !$_SESSION['client'] instanceof Client)  // une instance de visiteur est nécessaire
                            $_SESSION['client'] = new Visiteur();
                        else if ($_SESSION['client'] instanceof Compte)  // l'utilisateur est déjà connecté
                            redirect($_SESSION['client'] instanceof Utilisateur ? 'utilisateur.php' : 'accueil.php');
                        if (isset($_POST['username']) && isset($_POST['password'])) {  // résultat du formulaire
                            $_SESSION['client'] = $_SESSION['client']->connecte(htmlspecialchars($_POST['username']), htmlspecialchars($_POST['password']));  // la connexion est demandée depuis le visiteur
                            redirect($_SESSION['client'] instanceof Utilisateur ? 'utilisateur.php' : 'accueil.php');
                        }
                    } catch (ErreurBD $e) {  // seules nos erreurs 'maison' sont capturées, les autres représentent des bugs et doivent interrompre le chargement de la page
                        echo $e->getMessage();
                    }
                    if (isset($_GET['erreur']))
                        echo $_GET['erreur'];
                ?>
            </div>
            <div class="message">
                <?php
                    if (isset($_GET['message']))
                        echo $_GET['message'];
                ?>
            </div>
        </div>
    </main>
    <footer class="special-footer">
        <p>&copy; 2023 Macrosoft Helpdesk</p>
    </footer>
</body>

</html>