<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
// GET vars : 'erreur', 'message'
$confirmation = false;
?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<body>
    <header>
        <nav>
            <div class="left">
                <img id="logo" src="img/logo.svg" alt="Logo Macrosoft Helpdesk">
                <h1>HelpDesk</h1>
            </div>
            <div class="right">
                <button onclick="window.location.href='accueil.php';">Accueil</button>
                <button onclick="window.location.href='connexion.php';">Connexion</button>
            </div>
        </nav>
    </header>
    <main id="header-top-margin">
        <div class="shadow-form">
            <h1 class="center-text">Inscription</h1>
            <div class="form-division">
                <form method="post">
                    <div>
                        <label for="username">Nom d'utilisateur :</label><br>
                        <input name="username" id="username" type="text">
                        <br>
                        <br>
                        <label for="password">Mot de passe :</label><br>
                        <input name="password" id="password" type="password">
                        <br>
                        <br>
                        <label for="cpassword">Confirmation mot de passe :</label><br>
                        <input name="cpassword" id="cpassword" type="password">
                        <br>
                        <br>
                        <div class="g-recaptcha" data-sitekey="6LcGp00pAAAAAH2POS1k28hIIrzgPe78QgBGVsEn"></div>
                        <br>
                        <br>
                        <input type="submit" value="S'inscrire">
                    </div>
                </form>
            </div>
            <div class="error">
                <?php
                try {
                    session_start();  // la déserialisation du client est sujet à une erreur de reconnexion à la base
                    if (!isset($_SESSION['client']) || !$_SESSION['client'] instanceof Client)  // une instance de visiteur est nécessaire
                        $_SESSION['client'] = new Visiteur();
                    else if ($_SESSION['client'] instanceof Compte)  // l'utilisateur est déjà connecté
                        redirect($_SESSION['client'] instanceof Utilisateur ? 'utilisateur.php' : 'accueil.php');
                    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['cpassword'])) {  // résultat du formulaire
                        if ($_POST['username'] != '') {
                            // reCaptcha Verification
                            $recaptchaSecretKey = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/includes/recaptcha.txt');
                            $recaptchaResponse = $_POST['g-recaptcha-response'];

                            $recaptchaVerification = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecretKey&response=$recaptchaResponse");
                            $recaptchaVerification = json_decode($recaptchaVerification);

                            if ($recaptchaVerification->success) {
                                if ($_POST['password'] == $_POST['cpassword']) {
                                    $_SESSION['client']->inscription(htmlspecialchars($_POST['username']), htmlspecialchars($_POST['password']));
                                    $confirmation = true;
                                } else
                                    echo 'Le mot de passe et la confirmation du mot de passe sont différents.';
                            } else
                                echo 'Veuillez compléter le reCAPTCHA.';
                        } else echo 'Le champ `identifiant` ne peut pas être vide.';
                    }
                } catch (ErreurBD $e) {  // seules nos erreurs 'maison' sont capturées, les autres représentent des bugs et doivent interrompre le chargement de la page
                    echo $e->getMessage();
                }
                if (isset($_GET['erreur']))
                    echo $_GET['erreur'];
                ?>
            </div>
            <div class="message">
                <?php if ($confirmation) : ?>
                    Inscription réussie : vous pouvez vous connecter.
                <?php endif; ?>
            </div>
        </div>
    </main>
    <?php include_once('includes/footer.html'); ?>
</body>

</html>