<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/crypto.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/**
 * Erreur générique liée à la base de données
 */
class ErreurBD extends Exception
{
}

/**
 * Erreurs de connexion
 */
final class ConnexionImpossible extends ErreurBD
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Connexion impossible : ' . $message, $code, $previous);
    }
}

/**
 * Erreurs liées aux requêtes sur la base de données
 */
final class RequêteIllégale extends ErreurBD
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Demande rejetée, ' . $message, $code, $previous);
    }
}

/**
 * Abstraction de tous les visiteurs du site ; contient ses identifiants, un accès à la base et des méthodes de transaction sur cette base
 */
abstract class Client
{
    private const bd_nom = 'MacrosoftDB', bd_hôte = 'localhost';

    private readonly mysqli $con;
    private readonly string $mdp, $id;

    public function __construct(string $id, string $mdp)
    {
        try {
            $mdpencr = encrypt(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/key"), $mdp);
            $this->con = mysqli_connect(Client::bd_hôte, $id, $mdpencr, Client::bd_nom);
        } catch (mysqli_sql_exception $e) {
            $code = $e->getCode();
            switch ($code) {
                case 1044:
                    throw new ConnexionImpossible('Base inexistante ou connexion refusée.', $code, $e);
                case 1045:
                    throw new ConnexionImpossible('Identifiants invalides.', $code, $e);
                    // ...
                default:
                    throw new ConnexionImpossible('Raison inconnue.', $code, $e);
            }
        }
        $this->mdp = $mdp;
        $this->id = $id;
    }

    public function __unserialize(array $data): void
    {
        $this->__construct($data['id'], $data['mdp']);
    }

    public function __serialize(): array
    {
        $this->close();
        return [
            'id' => $this->id,
            'mdp' => $this->mdp
        ];
    }

    /**
     * Opération SQL SELECT sur la base
     * 
     * @param string $q query (suite de la requête)
     * @return array résultat de la requête
     */
    protected function select(string $q): array
    {
        return $this->con->query('select ' . $q)->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Opération SQL INSERT sur la base
     * 
     * @param string $q query (suite de la requête)
     * @return void
     */
    protected function insert(string $q)
    {
        $this->con->query('insert ' . $q);
    }

    /**
     * Opération SQL UPDATE sur la base
     * 
     * @param string $q query (suite de la requête)
     * @return void
     */
    protected function update(string $q)
    {
        $this->con->query('update ' . $q);
    }

    /**
     * Opération SQL GRANT sur la base
     * 
     * @param string $q query (suite de la requête)
     * @return void
     */
    protected function grant(string $q)
    {
        $this->con->query('grant ' . $q);
    }

    /**
     * Opération SQL SET sur la base
     * 
     * @param string $q query (suite de la requête)
     * @return void
     */
    protected function set(string $q)
    {
        $this->con->query('set ' . $q);
    }

    /**
     * Opération SQL CREATE sur la base
     * 
     * @param string $q query (suite de la requête)
     * @return void
     */
    protected function create(string $q)
    {
        $this->con->query('create ' . $q);
    }

    /**
     * Ferme la connexion
     * @return void
     */
    private function close()
    {
        $this->con->close();
    }

    /**
     * Permet l'accès au login de l'utilisateur
     * @return string login
     */
    protected function getLogin(): string
    {
        return $this->id;
    }
    /**
     * Cette méthode permet de verifier si un utilisateur existe déjà'
     * 
     * @param string $lib Id de l'utilisateur
     * @return bool
     */
    public function utilisateurExiste(string $id): bool
    {
        $result = $this->select("COUNT(*) as count from Utilisateur where login = '$id'");
        return $result[0]['count'] > 0;
    }
}

/**
 * Abstraction d'un client connecté
 */
abstract class Compte extends Client
{
    /**
     * Permet l'accès à toutes les informations du profil
     * @return array dictionnaire
     */
    public function getProfil(): array
    {
        return $this->select('* from VueProfilUtilisateur')[0];
    }
}

/**
 * Classe regroupant les fonctions d'accès aux libellés
 */
abstract class AccesseurLibellé extends Compte
{
    /**
     * Permet l'accès aux libellés disponibles de manière récursive
     * @returns array arbre de libellés
     */
    public function getLibellés(): array
    {
        return
            array_map(function (array $i): array {
                $i['inf'] = $this->getLibellésInf($i['idL']);
                return $i;
            }, $this->select('idL, intitule from VueLibellesNonArchives where lib_sup is null or lib_sup not in (select idL from VueLibellesNonArchives)'));
    }

    /**
     * Récupère les libellés inférieurs au libellé d'ID idL
     * @param int idL ID du libellé
     * @returns array arbre de libellés
     */
    private function getLibellésInf(int $idL): array
    {
        return array_map(function (array $i): array {
            $i['inf'] = $this->getLibellésInf($i['idL']);
            return $i;
        }, $this->select("idL, intitule from VueLibellesNonArchives where lib_sup = $idL"));
    }
}

/**
 * Abstraction du role utilisateur
 */
final class Utilisateur extends AccesseurLibellé
{
    /**
     * Permet l'accès aux tickets ouverts créés par l'utilisateur
     * @return array liste tickets
     */
    public function getTicketsOuverts(): array
    {
        return $this->select('* from VueTicketsOuvertsUtilisateur');
    }

    /**
     * Permet l'accès aux tickets fermés créés par l'utilisateur
     * @return array liste tickets
     */
    public function getTicketsFermés(): array
    {
        return $this->select('* from VueTicketsFermésUtilisateur');
    }

    /**
     * Ajoute un ticket dans la base
     * @param int $lib ID du libellé
     * @param int $niv_urgence niveau d'urgence
     * @param string $desc description du problème
     * @param string $cible personne concernée
     * 
     * @return void
     * @throws RequêteIllégale
     */
    public function ajoutTicket(int $lib, int $niv_urgence, string $desc, string $cible = '')
    {
        if (empty($lib)) {
            throw new RequêteIllégale("Le champ d'intitulé du libellé est vide.", 1);
        } elseif (!($this->libelleExiste($lib))) {
            throw new RequêteIllégale("Le libellé n'existe pas.", 2);
        } elseif ($niv_urgence < 1 || $niv_urgence > 4 || !is_int($niv_urgence)) {
            throw new RequêteIllégale("Le niveau d'urgence est incorrect.", 3);
        } elseif (!empty($cible) && !$this->utilisateurExiste($cible)) {
            throw new RequêteIllégale("Cible introuvable.", 4);
        } elseif (strlen($desc) > 255) {
            throw new RequêteIllégale("Description trop longue.", 5);
        }
        $this->insert("into Ticket (lib, niv_urgence, etat, description, date, IP, og_niv_urgence, demandeur, cible) values ($lib, $niv_urgence, 'Ouvert', '$desc', CURRENT_DATE, '" . $_SERVER['REMOTE_ADDR'] . "', $niv_urgence, '" . $this->getLogin() . "', '" . ($cible != '' ? $cible : $this->getLogin()) . "')");
    }
    /**
     * Cette méthode permet de verifier si l'id du libellé correspondant existe'
     * 
     * @param int $lib Id du Libellé
     * @return bool
     */
    private function libelleExiste(int $lib): bool
    {
        $result = $this->select("COUNT(*) as count from Libelle where idL = $lib");
        return $result[0]['count'] > 0;
    }
}

/**
 * Abstraction du role visiteur
 */
final class Visiteur extends Client
{
    public function __construct()
    {
        parent::__construct('visiteur', file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/includes/mdp_visiteur'));
    }

    /**
     * Connexion de l'utilisateur avec son compte dans la base de données.
     * Elle renvoie un objet Client dont le sous-type corresponds à son rôle dans la base.
     *
     * @param string $id login de l'utilisateur
     * @param string $mdp mot de passe de l'utilisateur
     * @return Client
     * @throws ConnexionImpossible
     * @throws Exception role inexistant
     */
    public function connecte($id, $mdp): Client
    {
        if (!$this->utilisateurExiste($id)) {
            $this->echecConnexion($id, $mdp);
            throw new ConnexionImpossible("Échec de connexion - utilisateur non trouvé.");
        }
        if (!$this->motDePasseValide($id, $mdp)) {
            $this->echecConnexion($id, $mdp);
            throw new ConnexionImpossible("Mot de passe invalide.");
        }
        // À ce stade, l'utilisateur existe et le mot de passe est valide
        $temp = new Utilisateur($id, $mdp);
        if (!$this->UtilisateurArole($id)) {
            throw new ConnexionImpossible("Rôle non existant.");
        }
        $role = $temp->select('CURRENT_ROLE() as role')[0]['role'];
        if (!$this->roleReconnu($role)) {
            throw new ConnexionImpossible("Rôle non reconnu.");
        }
        // Retour d'un objet Client selon le rôle
        switch ($role) {
            case 'UTILISATEUR':
                return $temp;
            case 'TECHNICIEN':
                return new Technicien($id, $mdp);
            case 'ADMIN_SYS':
                return new AdminSys($id, $mdp);
            case 'ADMIN_WEB':
                return new AdminWeb($id, $mdp);
            default:
                throw new Exception("Bug, le role '$role' n'existe pas");
        }
    }

    /**
     * Permet l'accès aux 10 derniers tickets ouverts sur le site.
     *
     * @return array liste de tickets
     */
    public function getTickets(): array
    {
        return $this->select('* from VueDerniersTicketsOuverts');
    }

    /**
     * Inscription d'un nouvel utilisateur sur le site (sans vérification de mdp)
     *
     * @param string $id login de l'utilisateur
     * @param string $mdp mot de passe de l'utilisateur
     * @return void
     * @throws RequêteIllégale
     */
    public function inscription(string $id, string $mdp)
    {
        if (empty($id)) {
            throw new RequêteIllégale("Le champ d'identifiant est vide.", 1);
        } elseif ($this->utilisateurExiste($id)) {
            throw new RequêteIllégale("L'utilisateur '$id' existe déjà.", 2);
        } else {
            $mdpencr = encrypt(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/includes/key'), $mdp);
            $this->insert("into Utilisateur(login, mdp) values ('$id','$mdpencr')");
            (new Système())->créeUtilisateur($id, $mdp);
        }
    }

    /**
     * Insertion d'une ligne dans le log d'échec de connexion de la base de données.
     *
     * @param string $id login tenté
     * @param string $mdp mot de passe tenté
     * @return void
     */
    private function echecConnexion(string $id, string $mdp)
    {
        $this->insert("into Log_connexion_echec (date, login_tente, mdp_tente, IP) values (CURRENT_DATE,'$id','$mdp','" . $_SERVER['REMOTE_ADDR'] . "')");
    }
    /**
     * Cette méthode permet de verifier si l'utilisateur a entrer un mot de passe valide.
     * 
     * @param string $id Id de l'utilisateur
     * @param string $mdp Mdp de l'utilisateur
     * @return bool
     */
    private function motDePasseValide($id, $mdp): bool
    {
        $mdpencr = encrypt(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/key"), $mdp);
        $result = $this->select("COUNT(*) as count from Utilisateur where login = '$id' and mdp = '$mdpencr'");
        return $result[0]['count'] > 0;
    }
    /**
     * Cette méthode permet de verifier si l'utilisateur a un role ou un role null.
     * 
     * @param string $id Id de l'utilisateur
     * @return bool
     */
    private function UtilisateurArole($id): bool
    {
        $result = $this->select("role from Utilisateur where login = '$id'");
        if (count($result) === 0) {
            return false;
        }

        $role = $result[0]['role'];
        // Un utilisateur est considéré comme ayant un "rôle" si celui-ci est non NULL ou explicitement NULL
        // Cela dépend de la logique spécifique de votre application
        return true; // Si le fait d'avoir un rôle NULL est considéré comme valide, retournez true
    }
    /**
     * Cette méthode permet de verifier si le role est reconnu.
     * 
     * @param string $role Role du client actuel
     * @return bool
     */
    private function roleReconnu($role): bool
    {
        $rolesReconnus = ['UTILISATEUR', 'TECHNICIEN', 'ADMIN_SYS', 'ADMIN_WEB'];
        return in_array($role, $rolesReconnus);
    }
}
/**
 * Classe Système, représentation du role absolu, héritant de la classe abstraction Compte.
 *
 */
final class Système extends Client
{
    public function __construct()
    {
        parent::__construct('sys', file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/includes/mdp_sys'));
    }

    /**
     * Cette méthode attribue les droits UTILISATEUR à un Utilisateur de la base de données.
     * @param string $id id de l'utilisateur
     * @param string $mdp mot de passe de l'utilisateur
     * @return void
     */
    public function créeUtilisateur(string $id, string $mdp)
    {
        $mdpencr = encrypt(file_get_contents("includes/key"), $mdp);
        $this->create("user `$id` identified by '$mdpencr'");
        $this->grant("UTILISATEUR to `$id`");
        $this->set("default role UTILISATEUR for `$id`");
    }

    /**
     * Cette méthode attribue les droits TECHNICIEN à un Technicien de la base de données.
     *
     * @param string $id Le login du technicien
     * @param string $mdp Le mot de passe du technicien
     * @return void
     */
    public function créeTechnicien(string $id, string $mdp)
    {
        $mdpencr = encrypt(file_get_contents("includes/key"), $mdp);
        $this->create("user `$id` identified by '$mdpencr'");
        $this->grant("TECHNICIEN to `$id`");
        $this->set("default role TECHNICIEN for `$id`");
    }
}
/**
 * Classe Technicien abstraction du role de Technicien, héritant de la classe abstraction Compte.
 *
 */
final class Technicien extends Compte
{
    /**
     * Cette methode retourne tous les tickets attribuées des techniciens
     * @return array liste des tickets attribués pour le technicien
     */
    public function getTicketsAttribués(): array
    {
        return $this->select('* from VueTicketsTechnicien');
    }
    /**
     * Cette methode retourne tous les tickets non attribuées à des techniciens
     * @return array liste des tickets non attribués à des technicien
     */
    public function getTicketsNonAttribués(): array
    {
        return $this->select('* from VueTicketsNonTraites ');
    }

    /**
     * Cette méthode permets d'assigner un ticket au technicien.
     *
     * @param int $id L'identifiant du ticket à assigner au technicien
     * @return void
     * @throws RequêteIllégale Renvoi d'un objet requête illégale en cas d'échec.
     */
    public function assigneTicket(int $id)
    {
        // Vérifie si l'utilisateur existe
        if (!$this->utilisateurExiste($this->getLogin())) {
            throw new RequêteIllégale("L'utilisateur n'existe pas.");
        }

        // Vérifie le rôle de l'utilisateur
        // Supposons que vous ayez une méthode vérifiant le rôle du technicien. Ajoutez cette méthode selon votre implémentation.
        if (!$this->estTechnicien()) {
            throw new RequêteIllégale("Rôle invalide. Seuls les techniciens peuvent assigner des tickets.");
        }

        // Vérifie si le ticket existe
        if (!$this->ticketExiste($id)) {
            throw new RequêteIllégale("Le ticket n'existe pas.");
        }

        // Assignation du ticket au technicien
        try {
            $this->update("Ticket SET technicien='" . $this->getLogin() . "', etat='En cours de traitement' WHERE idT=$id AND etat='Ouvert'");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("Impossible d'assigner le ticket: " . $e->getMessage());
        }
    }

    /**
     * Cette méthode ferme un ticket dans la base de données.
     *
     * @param int $id L'identifiant du ticket à fermer.
     * @return void
     * @throws RequêteIllégale Renvoi d'un objet requête illégale en cas d'échec.
     */
    public function fermeTicket(int $id)
    {
        try {
            $this->update("VueTicketsTechnicien SET etat='Fermé' WHERE idT=$id");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("Impossible de fermer le ticket $id", 4, $e);
        }
    }
    /**
     * Cette méthode permet de verifier si l'utilisateur est un Technicien.
     * 
     * @return bool
     */
    private function estTechnicien(): bool
    {
        $login = $this->getLogin();
        $result = $this->select("role from Utilisateur where login = '$login'");
        return !empty($result) && $result[0]['role'] === 'Technicien';
    }


    /**
     * Cette méthode permet de verifier l'existence d'un ticket.
     * 
     * @param int $id L'identifiant du Ticket
     * @return bool
     */
    private function ticketExiste(int $id): bool
    {
        $result = $this->select("COUNT(*) as count from Ticket where idT = '$id' AND etat = 'Ouvert'");
        return !empty($result) && $result[0]['count'] > 0;
    }
}
/**
 * Classe AdminSys abstraction du role d'administrateur Système, héritant de la classe abstraction Compte.
 *
 */
final class AdminSys extends Compte
{
    /**
     * Cette méthode renvoie les logs de tickets validés.
     *
     * @return array liste de tickets validés
     */
    public function getTicketValidés(): array
    {
        return $this->select('* from VueLogTicketsValides');
    }

    /**
     * Cette méthode renvoie les logs de connexion échouées.
     *
     * @return array liste des connexions echoués
     */
    public function getConnexionsEchouées(): array
    {
        return $this->select('* from Log_connexion_echec');
    }

    public function getTicketsEtat(): array
    {
        return $this->select('description, etat, libelle, niv_urgence, date, technicien from VueTickets');
    }
}

/**
 * Classe AdminWeb abstraction du role d'administrateur Web, héritant de la classe abstraction accesseurLibellé.
 *
 */
final class AdminWeb extends AccesseurLibellé
{
    /**
     * Cette méthode permet la modification d'un ticket sur la base de données. Il retourne une erreur si la modification a échoué.
     *
     * @param int $id id du ticket
     * @param int $niveau nouveau niveau d'urgence du ticket
     * @param int $libellé nouveau libellé du ticket
     * @param string $technicien nouveau technicien affecté au ticket
     * @return void
     * @throws RequêteIllégale Renvoi d'un objet requête illégale en cas d'échec.
     */
    public function modifieTicket(int $id, int $niveau, int $libellé, string $technicien)
    {
        try {
            if ($technicien)
                $this->update("Ticket SET etat='En cours de traitement', niv_urgence=$niveau, lib=$libellé, technicien='$technicien' WHERE idT=$id");
            else $this->update("Ticket SET etat='Ouvert', niv_urgence=$niveau, lib=$libellé, technicien=null WHERE idT=$id");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("impossible de modifier le ticket $id", 5, $e);
        }
    }

    /**
     * retourne tous les tickets ouverts
     * @return array tous les tickets ouverts
     */
    public function getTickets(): array
    {
        return $this->select('* from VueTicketsOuverts');
    }

    /**
     * Cette méthode fait une requête sur la base de données pour modifier un certain libellé
     *
     * @param int $id L'identifiant du libellé à modifier
     * @param string $titre Le nouveau titre du libellé
     * @param int|null $groupe Le nouveau groupe du libellé
     * @param bool $archive Le nouveau statut d'archive du libellé
     * @return void
     * @throws RequêteIllégale Renvoi d'un objet requête illégale en cas d'échec.
     */
    public function modifieLibellé(int $id, string $titre, ?int $groupe, bool $archive)
    {
        try {
            $this->update("VueLibellesNonArchives SET intitule='$titre', lib_sup=" . ($groupe ?: 'null') . ", archive=" . ($archive ? 'true' : 'false') . " WHERE idL=$id");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("impossible de modifier le libellé $id", 6, $e);
        }
    }

    /**
     * Cette méthode  ajoute un libélle a la base de données
     * @param string $titre titre du libélle
     * @param int|null $groupe  groupe du libéllé
     * @return void
     * @throws RequêteIllégale Renvoi d'un objet requête illégale en cas d'échec.
     */
    public function ajoutLibellé(string $titre, ?int $groupe)
    {
        try {
            $this->insert("into VueLibellesNonArchives(intitule, lib_sup) values ('$titre'," . ($groupe ?: 'null') . ")");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("impossible d'ajouter le libellé '$titre'", 7, $e);
        }
    }

    /**
     * Cette méthode fait une requête sur la base de données pour y ajouter un technicien
     *
     * @param string $id Le login du technicien
     * @param string $mdp Le mot de passe du technicien
     * @return void
     * @throws RequêteIllégale Renvoi d'un objet requête illégale en cas d'échec.
     */
    public function ajoutTechnicien(string $id, string $mdp)
    {
        try {
            $mdpencr = encrypt(file_get_contents("includes/key"), $mdp);
            $this->insert("into Utilisateur(login, mdp, role) values ('$id','$mdpencr','Technicien')");
            (new Système())->créeTechnicien($id, $mdp);
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale($e->getCode() == 1062 ? "le technicien '$id' existe déjà" : "impossible de créer le technicien '$id'", 8, $e);
        }
    }

    /**
     * Cette méthode renvoie la liste des login techniciens dans la base de données.
     *
     * @return array liste des logins des techniciens
     */
    public function getTechniciens(): array
    {
        return $this->select('login from VueTechniciens');
    }
}
