<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" type="text/css" href="common.css">
</head>

<body>
    <header>
        <nav>
            <div class="left">
                <img id="logo" src="img/logo.svg" alt="Logo Macrosoft Helpdesk">
                <h1>HelpDesk</h1>
            </div>
            <div class="right">
                <button>Accueil</button>
                <button>Inscription</button>
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
                        <br>
                        <input type="submit" value="Se connecter">
                        <br>
                        <?php
                            require_once 'includes/profils.php';
                            require_once 'includes/misc.php';
                            debug();
                            if (isset($_SESSION['client']))
                                redirect($_SESSION['client'] instanceof Utilisateur ? 'utilisateur.html' : 'accueil.html');
                            if (isset($_POST['username']) && isset($_POST['password'])) {
                                session_start();
                                try {
                                    $_SESSION['client'] = (new Visiteur())->connecte($_POST['username'], $_POST['password']);
                                    redirect($_SESSION['client'] instanceof Utilisateur ? 'utilisateur.html' : 'accueil.html');
                                } catch (ErreurBD $e) {
                                    echo $e->getMessage();
                                }
                            }
                        ?>
                    </form>
                </div>
                <div class="right-captcha">
                    <p>Captcha</p>
                </div>
            </div>
        </div>
    </main>
    <footer class="special-footer">
        <p>&copy; 2023 Macrosoft Helpdesk</p>
    </footer>
</body>

</html>
