<?php
// TODO: rajouter du contexte aux erreurs de requête illégale
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class ErreurBD extends Exception {}

final class ConnexionImpossible extends ErreurBD
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Connexion impossible : ' . $message, $code, $previous);
    }
}

final class RequêteIllégale extends ErreurBD
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Requête illégale : ' . $message, $code, $previous);
    }
}

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

    protected function select(string $q): array
    {
        return $this->con->query('select ' . $q)->fetch_all(MYSQLI_ASSOC);
    }

    protected function insert(string $q)
    {
        $this->con->query('insert ' . $q);
    }

    protected function update(string $q)
    {
        $this->con->query('update ' . $q);
    }

    protected function grant(string $q)
    {
        $this->con->query('grant ' . $q);
    }

    protected function set(string $q)
    {
        $this->con->query('set ' . $q);
    }

    protected function create(string $q)
    {
        $this->con->query('create ' . $q);
    }

    private function close()
    {
        $this->con->close();
    }

    protected function getLogin(): string
    {
        return $this->id;
    }
}

abstract class Compte extends Client
{
    public function getProfil(): array
    {
        return $this->select('* from VueProfilUtilisateur')[0];
    }
}

final class Utilisateur extends Compte
{
    public function getTickets(): array
    {
        return $this->select('* from VueTicketsUtilisateur');
    }

    public function ajoutTicket(int $lib, int $niv_urgence, string $desc, string $cible)
    {
        try {
            $this->insert("into Ticket (lib, niv_urgence, etat, description, date, IP, og_niv_urgence, demandeur, cible) values ($lib,$niv_urgence,'Ouvert','$desc',CURRENT_DATE,'" . $_SERVER['REMOTE_ADDR'] . "',$niv_urgence,'" . $this->getLogin() . "','" . ($cible != '' ? $cible : $this->getLogin()) . "')");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("Impossible d'ajouter ce ticket : " . $e->getMessage(), 1, $e);
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
                // ...
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
            throw new RequêteIllégale("Impossible d'ajouter l'utilisateur $id", 2, $e);
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
}

final class Technicien extends Compte
{
    public function getTicketsAttribuées(): array
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
        return $this->select('* from Log_connection_echec');
    }
}

final class AdminWeb extends Compte
{
    public function modifieTicket(int $id, int $niveau, int $libellé, string $technicien)
    {
        try {
            $this->update("VueTicketsTechnicien SET etat='En cours de traitement', niv_urgence=$niveau, libelle=$libellé, technicien='$technicien' WHERE idT=$id");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("Impossible de modifier le ticket $id", 5, $e);
        }
    }

    public function getTickets(): array
    {
        return $this->select('* from VueTicketsOuverts');
    }

    public function getLibellés(): array
    {
        return $this->select('* from VueLibellesNonArchives');
    }

    public function modifieLibellé(int $id, string $titre, ?int $groupe, bool $archive)
    {
        try {
            $this->update("VueLibellesNonArchives SET intitule='$titre', lib_sup=$groupe, archive=$archive WHERE idL=$id");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("Impossible de modifier le libellé $id", 6, $e);
        }
    }

    public function ajoutLibellé(string $titre, ?int $groupe)
    {
        try {
            $this->insert("into VueLibellesNonArchives(intitule, lib_sup) values ('$titre',$groupe)");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("Impossible d'ajouter le libellé '$titre'", 7, $e);
        }
    }

    public function ajoutTechnicien(string $id, string $mdp)
    {
        try {
            $this->insert("into Utilisateur(login, mdp, role) values ($id,$mdp,'Technicien')");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("Impossible de créer le technicien '$id'", 8, $e);
        }
    }
}
