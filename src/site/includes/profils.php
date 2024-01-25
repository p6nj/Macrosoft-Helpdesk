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
                    throw new ConnexionImpossible('Base inexistante ou connexion refusée.', 1, $e);
                case 1045:
                    throw new ConnexionImpossible('Identifiants invalides.', 2, $e);
                    // ...
                default:
                    throw $e;
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
    protected function insert(string $q): void
    {
        $this->con->query('insert ' . $q);
    }

    /**
     * Opération SQL UPDATE sur la base
     * 
     * @param string $q query (suite de la requête)
     * @return void
     */
    protected function update(string $q): void
    {
        $this->con->query('update ' . $q);
    }

    /**
     * Opération SQL GRANT sur la base
     * 
     * @param string $q query (suite de la requête)
     * @return void
     */
    protected function grant(string $q): void
    {
        $this->con->query('grant ' . $q);
    }

    /**
     * Opération SQL SET sur la base
     * 
     * @param string $q query (suite de la requête)
     * @return void
     */
    protected function set(string $q): void
    {
        $this->con->query('set ' . $q);
    }

    /**
     * Opération SQL CREATE sur la base
     * 
     * @param string $q query (suite de la requête)
     * @return void
     */
    protected function create(string $q): void
    {
        $this->con->query('create ' . $q);
    }

    /**
     * Ferme la connexion
     * @return void
     */
    private function close(): void
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
 * Client connecté avec un rôle "utilisateur"
 */
final class Utilisateur extends AccesseurLibellé
{
    /**
     * Permet l'accès aux tickets créés par l'utilisateur
     * @return array liste de dictionnaires de champs
     */
    public function getTickets(): array
    {
        return $this->select('* from VueTicketsUtilisateur');
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
    public function ajoutTicket(int $lib, int $niv_urgence, string $desc, string $cible): void
    {
        try {
            $this->insert("into Ticket (lib, niv_urgence, etat, description, date, IP, og_niv_urgence, demandeur, cible) values ($lib,$niv_urgence,'Ouvert','$desc',CURRENT_DATE,'" . $_SERVER['REMOTE_ADDR'] . "',$niv_urgence,'" . $this->getLogin() . "','" . ($cible != '' ? $cible : $this->getLogin()) . "')");
        } catch (mysqli_sql_exception $e) {
            $code = $e->getCode();
            throw new RequêteIllégale("impossible de créer ce ticket : " . ($code == 1452 ? 'cible introuvable' : 'erreur inconnue'), 1, $e);
        }
    }
}

final class Visiteur extends Client
{
    public function __construct()
    {
        parent::__construct('visiteur', file_get_contents('includes/mdp_visiteur'));
    }

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

    public function getTickets(): array
    {
        return $this->select('* from VueDerniersTicketsOuverts');
    }

    // la vérification du mdp se fera plus en amont
    public function inscription(string $id, string $mdp)
    {
        try {
            (new Système())->créeUtilisateur($id, $mdp);
            $this->insert("into Utilisateur(login, mdp) values ('$id','$mdp')");
        } catch (mysqli_sql_exception $e) {
            $code = $e->getCode();
            throw new RequêteIllégale("impossible d'ajouter l'utilisateur '$id' : " . ($code == 1396 ? 'cet identifiant est déjà pris' : 'raison inconnue'), 2, $e);
        }
    }

    private function echecConnexion(string $id, string $mdp)
    {
        $this->insert("into Log_connexion_echec (date, login_tente, mdp_tente, IP) values (CURRENT_DATE,'$id','$mdp','" . $_SERVER['REMOTE_ADDR'] . "')");
    }
}

final class Système extends Client
{
    public function __construct()
    {
        parent::__construct('sys', file_get_contents('includes/mdp_sys'));
    }

    public function créeUtilisateur(string $id, string $mdp)
    {
        $this->create("user `$id` identified by '$mdp'");
        $this->grant("UTILISATEUR to `$id`");
        $this->set("default role UTILISATEUR for `$id`");
    }

    public function créeTechnicien(string $id, string $mdp)
    {
        $this->create("user `$id` identified by '$mdp'");
        $this->grant("TECHNICIEN to `$id`");
        $this->set("default role TECHNICIEN for `$id`");
    }
}

final class Technicien extends Compte
{
    public function getTicketsAttribués(): array
    {
        return $this->select('* from VueTicketsTechnicien');
    }

    public function getTicketsNonAttribués(): array
    {
        return $this->select('* from VueTicketsNonTraites ');
    }

    public function assigneTicket(int $id)
    {
        try {
            $this->update("VueTicketsNonTraites SET technicien='" . $this->getLogin() . "', etat='En cours de traitement' WHERE idT=$id");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("Impossible de s'assigner le ticket $id", 3, $e);
        }
    }

    public function fermeTicket(int $id)
    {
        try {
            $this->update("VueTicketsTechnicien SET etat='Fermé' WHERE idT=$id");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("Impossible de fermer le ticket $id", 4, $e);
        }
    }
}

final class AdminSys extends Compte
{
    public function getTicketValidés(): array
    {
        return $this->select('* from VueLogTicketsValides');
    }

    public function getConnexionsEchouées(): array
    {
        return $this->select('* from Log_connexion_echec');
    }
}

final class AdminWeb extends AccesseurLibellé
{
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

    public function getTickets(): array
    {
        return $this->select('* from VueTicketsOuverts');
    }

    public function modifieLibellé(int $id, string $titre, ?int $groupe, bool $archive)
    {
        try {
            $this->update("VueLibellesNonArchives SET intitule='$titre', lib_sup=" . ($groupe ?: 'null') . ", archive=" . ($archive ? 'true' : 'false') . " WHERE idL=$id");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("impossible de modifier le libellé $id", 6, $e);
        }
    }

    public function ajoutLibellé(string $titre, ?int $groupe)
    {
        try {
            $this->insert("into VueLibellesNonArchives(intitule, lib_sup) values ('$titre'," . ($groupe ?: 'null') . ")");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("impossible d'ajouter le libellé '$titre'", 7, $e);
        }
    }

    public function ajoutTechnicien(string $id, string $mdp)
    {
        try {
            $this->insert("into Utilisateur(login, mdp, role) values ('$id','$mdp','Technicien')");
            (new Système())->créeTechnicien($id, $mdp);
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale($e->getCode() == 1062 ? "le technicien '$id' existe déjà" : "impossible de créer le technicien '$id'", 8, $e);
        }
    }

    public function getTechniciens(): array
    {
        return $this->select('login from VueTechniciens');
    }
}
