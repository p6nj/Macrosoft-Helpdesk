<?php
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
            $this->con = mysqli_connect(Client::bd_hôte, $id, $mdp, Client::bd_nom);
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
     */
    public function ajoutTicket(int $lib, int $niv_urgence, string $desc, string $cible)
    {
        try {
            $this->insert("into Ticket (lib, niv_urgence, etat, description, date, IP, og_niv_urgence, demandeur, cible) values ($lib,$niv_urgence,'Ouvert','$desc',CURRENT_DATE,'" . $_SERVER['REMOTE_ADDR'] . "',$niv_urgence,'" . $this->getLogin() . "','" . ($cible != '' ? $cible : $this->getLogin()) . "')");
        } catch (mysqli_sql_exception $e) {
            $code = $e->getCode();
            throw new RequêteIllégale("impossible de créer ce ticket : " . ($code == 1452 ? 'cible introuvable' : 'erreur inconnue'), 1, $e);
        }
    }
}

/**
 * Abstraction du role visiteur
 */
final class Visiteur extends Client
{
    public function __construct()
    {
        parent::__construct('visiteur', file_get_contents('includes/mdp_visiteur'));
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
        try {
            $temp = new Utilisateur($id, $mdp);
        } catch (ConnexionImpossible $e) {
            if ($e->getCode() == 2)
                $this->echecConnexion($id, $mdp);
            throw $e;
        }
        $role = $temp->select('CURRENT_ROLE() as role')[0]['role'];
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
        try {
            $this->insert("into Utilisateur(login, mdp) values ('$id','$mdp')");
            (new Système())->créeUtilisateur($id, $mdp);
        } catch (mysqli_sql_exception $e) {
            $code = $e->getCode();
            throw new RequêteIllégale("impossible d'ajouter l'utilisateur '$id' : " . ($code == 1396 ? 'cet identifiant est déjà pris' : 'raison inconnue'), 2, $e);
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
}
/**
 * Classe Système, représentation du role absolu, héritant de la classe abstraction Compte.
 *
 */
final class Système extends Client
{
    public function __construct()
    {
        parent::__construct('sys', file_get_contents('includes/mdp_sys'));
    }

    /**
     * Cette méthode attribue les droits UTILISATEUR à un Utilisateur de la base de données.
     * @param string $id id de l'utilisateur
     * @param string $mdp mot de passe de l'utilisateur
     * @return void
     */
    public function créeUtilisateur(string $id, string $mdp)
    {
        $this->create("user `$id` identified by '$mdp'");
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
        $this->create("user `$id` identified by '$mdp'");
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
        try {
            $this->update("VueTicketsNonTraites SET technicien='" . $this->getLogin() . "', etat='En cours de traitement' WHERE idT=$id");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("Impossible de s'assigner le ticket $id", 3, $e);
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
            $this->insert("into Utilisateur(login, mdp, role) values ('$id','$mdp','Technicien')");
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
