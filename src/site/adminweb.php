<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
debug();
foreach ($_POST as $k => $v) $_POST[$k] = htmlspecialchars($_POST[$k]);
$newtechopen = false;
$popuperror = '';
try {
  session_start(); // la déserialisation du client est sujet à une erreur de reconnexion à la base
  if (!isset($_SESSION['client']) || !$_SESSION['client'] instanceof AdminWeb) {
    // l'utilisateur n'est pas connecté
    redirect('accueil.php');
  } else {
    if (isset($_POST['id']) && isset($_POST['mdp']) && isset($_POST['confmdp'])) {
      // ajout d'un technicien
      if ($_POST['mdp'] == $_POST['confmdp']) {
        $_SESSION['client']->ajoutTechnicien($_POST['id'], $_POST['mdp']);
        $_SESSION['message'] = 'Technicien ajouté avec succès.';
      } else {
        $popuperror = 'Le mot de passe et la confirmation ne correspondent pas.';
        $newtechopen = true;
      }
    } else if (isset($_POST['idT']) && isset($_POST['niveau']) && isset($_POST['libelle']) && isset($_POST['tech'])) {
      // modification d'un ticket
      $_SESSION['client']->modifieTicket($_POST['idT'], $_POST['niveau'], $_POST['libelle'], $_POST['tech']);
      $_SESSION['message'] = 'Ticket modifié avec succès.';
    } else if (isset($_POST['idL']) && isset($_POST['titre']) && isset($_POST['sup'])) {
      // modification d'un libellé
      $_SESSION['client']->modifieLibellé((int) $_POST['idL'], $_POST['titre'], $_POST['sup'] ?: null, isset($_POST['archive']));
      $_SESSION['message'] = 'Libellé modifié avec succès.';
    } else if (isset($_POST['titre']) && isset($_POST['sup'])) {
      // ajout d'un libellé
      $_SESSION['client']->ajoutLibellé($_POST['titre'], $_POST['sup'] ? $_POST['sup'] : null);
      $_SESSION['message'] = 'Libellé ajouté avec succès.';
    }
  }
} catch (ErreurBD $e) {
  $_SESSION['erreur'] = $e->getMessage();
}
?>

<style>
  libellé nom {
    /* don't trigger children */
    pointer-events: none
  }
</style>

<script>
  function modticket(event) {
    let target = event.target.parentElement; // le tr
    let dialog = document.getElementById('ticket-dialog');
    let form = document.forms['ticket'];
    form.elements['idT'].value = target.attributes['tik'].value;
    form.elements['libelle'].value = target.attributes['lib'].value;
    form.elements['niveau'].value = target.attributes['niv'].value;
    form.elements['tech'].value = target.children[2].innerText;
    dialog.showModal();
  }

  function modlib(event) {
    let target = event.target;
    event.cancelBubble = true; // don't trigger parents either
    let dialog = document.getElementById('lib-dialog');
    let form = document.forms['libelle'];
    form.elements['idL'].value = target.id;
    form.elements['titre'].value = target.children[0].innerText;
    form.elements['sup'].value = target.parentElement.parentElement.id;
    form.elements['archive'].checked = false;
    dialog.showModal();
  }

  <?php if ($newtechopen) : ?> window.onload = () => {
      const techdialog = document.getElementById('new-tech');
      const techform = document.forms['tech'];
      techform.elements['id'].value = "<?= $_POST['id'] ?>";
      techform.elements['mdp'].value = "<?= $_POST['mdp'] ?>";
      techform.elements['confmdp'].value = "<?= $_POST['confmdp'] ?>";
      techdialog.showModal();
    }
  <?php endif; ?>
</script>

<body>
  <header>
    <nav>
      <div class="left">
        <img id="logo" src="img/logo.svg" alt="Logo Macrosoft Helpdesk">
        <h1>HelpDesk</h1>
      </div>
      <div class="far-right">
        <button onclick="document.querySelector('dialog#new-lib').showModal()">
          Créer&nbsp;un&nbsp;libellé
        </button>
        <button onclick="document.querySelector('dialog#new-tech').showModal()">
          Créer&nbsp;un&nbsp;technicien
        </button>
        <button title="<?= $_SESSION['client']->getProfil()['login']; ?>" onclick="document.querySelector(' dialog#profil').showModal()">
          Profil
        </button>
        <button onclick="window.location.href='connexion.php?déco=1&message=Vous avez été déconnecté.';">
          Deconnexion
        </button>
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
      <?php
      function affiche_lib(array $lib, bool $inf = false)
      { ?>
        <libellé class="<?= $inf ? 'inf ' : '' ?>clickable" onclick="modlib(event)" id="<?= $lib['idL'] ?>">
          <nom><?= $lib['intitule'] ?></nom>
          <?php if (sizeof($libellés = $lib['inf'])) : ?>
            <div class="ticket-container">
              <?php foreach ($libellés as $inf) {
                affiche_lib($inf, true);
              } ?>
            </div>
          <?php endif; ?>
        </libellé>
      <?php } ?>
      <h1>Libellés</h1>
      <div class="ticket-container">
        <?php if (!sizeof($libellés = $_SESSION['client']->getLibellés())) : ?>
          <div class="message">Aucun libellé à afficher.</div>
        <?php else : ?>
        <?php foreach ($libellés as $v) {
            affiche_lib($v);
          }
        endif; ?>
      </div>
    </div>
    <div>
      <h1>Tickets</h1>
      <?php if (!sizeof($tickets = $_SESSION['client']->getTickets())) : ?>
        <div class="message">Aucun ticket à afficher.</div>
      <?php else : ?>
        <table>
          <thead>
            <tr>
              <th>Niveau d'urgence</th>
              <th>Etat</th>
              <th>Technicien</th>
              <th>Libellé</th>
              <th>Description</th>
              <th>Demandeur</th>
              <th>Cible</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($tickets as $ticket) : ?>
              <tr onclick="modticket(event)" class="clickable" niv="<?= $ticket['niv_urgence'] ?>" lib="<?= $ticket['idL'] ?>" tik="<?= $ticket['idT'] ?>">
                <td><?= niv_urgence_str($ticket['niv_urgence']) ?></td>
                <td><?= $ticket['etat'] ?></td>
                <td><?= $ticket['technicien'] ?></td>
                <td><?= $ticket['libelle'] ?></td>
                <td><?= $ticket['description'] ?></td>
                <td><?= $ticket['demandeur'] ?></td>
                <td><?= $ticket['cible'] ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

    <?php function affiche_lib2(array $lib, int $niveau = 0)
    { ?>
      <option value=<?= $lib['idL'] ?>>
        <?= str_repeat('&emsp;', $niveau) . $lib['intitule'] ?>
      </option>
    <?php foreach ($lib['inf'] as $inf) {
        affiche_lib2($inf, $niveau + 1);
      }
    } ?>

    <dialog id="ticket-dialog">
      <h2>Modifier le ticket</h2>
      <form method="post" id="ticket">
        <input type="hidden" name="idT">
        <label for="niveau">Niveau d'urgence :</label>
        <br>
        <select name="niveau" id="niveau">
          <?php for ($i = 1; $i < 5; $i++) : ?>
            <option value="<?= $i ?>"><?= niv_urgence_str($i) ?></option>
          <?php endfor; ?>
        </select>
        <br><br>
        <label for="libelle">Libellé :</label>
        <br>
        <select name="libelle" id="libelle">
          <?php foreach ($_SESSION['client']->getLibellés() as $v) {
            affiche_lib2($v);
          } ?>
        </select>
        <br><br>
        <label for="tech">Technicien assigné :</label>
        <br>
        <select name="tech" id="tech">
          <option value="">Aucun</option>
          <?php foreach ($_SESSION['client']->getTechniciens() as $tech) : ?>
            <option value="<?= $tech['login'] ?>"><?= $tech['login'] ?></option>
          <?php endforeach; ?>
        </select>
        <br><br>
        <button type="submit">Enregistrer</button>
        <input type="button" onclick="event.target.parentElement.parentElement.close()" value="Annuler">
      </form>
    </dialog>

    <dialog id="lib-dialog">
      <h2>Modifier le libellé</h2>
      <form method="post" id="libelle">
        <input type="hidden" name="idL">
        <label for="titre">Titre :</label>
        <br>
        <input type="text" name="titre" id="titre">
        <br><br>
        <label for="sup">Libellé supérieur :</label>
        <br>
        <select name="sup" id="sup">
          <option value="">Aucun</option>
          <?php foreach ($_SESSION['client']->getLibellés() as $v) {
            affiche_lib2($v);
          } ?>
        </select>
        <br><br>
        <label for="archive">Archivé </label>
        <input type="checkbox" name="archive" id="archive">
        <br><br>
        <button type="submit">Enregistrer</button>
        <input type="button" onclick="event.target.parentElement.parentElement.close()" value="Annuler">
      </form>
    </dialog>

    <dialog id="new-lib">
      <h2>Créer un nouveau libellé</h2>
      <p>Merci de renseigner les informations ci-desssous.</p>
      <form method="post">
        <label for="titre">Titre :</label>
        <br>
        <input type="text" name="titre" id="titre">
        <br><br>
        <label for="sup">Libellé supérieur :</label>
        <br>
        <select name="sup" id="sup">
          <option value="">Aucun</option>
          <?php foreach ($_SESSION['client']->getLibellés() as $v) {
            affiche_lib2($v);
          } ?>
        </select>
        <br><br>
        <button type="submit">Enregistrer</button>
        <input type="button" onclick="event.target.parentElement.parentElement.close()" value="Annuler">
      </form>
    </dialog>

    <dialog id="new-tech">
      <h2>Créer un nouveau technicien</h2>
      <p>Renseignez les informations ci-dessous pour créer un compte technicien.</p>
      <form method="post" id="tech">
        <label for="id">Identifiant :</label>
        <br>
        <input type="text" name="id" id="id">
        <br><br>
        <label for="mdp">Mot de passe :</label>
        <br>
        <input type="password" name="mdp" id="mdp">
        <br><br>
        <label for="confmdp">Confirmation du mot de passe :</label>
        <br>
        <input type="password" name="confmdp" id="confmdp">
        <br><br>
        <button type="submit">Enregistrer</button>
        <input type="button" onclick="event.target.parentElement.parentElement.close()" value="Annuler">
      </form>
      <div class="error"><?= $popuperror ?></div>
    </dialog>

  </main>
  <?php include_once('includes/footer.html'); ?>
</body>

</html>