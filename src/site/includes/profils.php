<!-- TODO: rajouter du contexte aux erreurs de requête illégale -->
<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class ErreurBD extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Erreur : ' . $message, $code, $previous);
    }
}

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
    protected const bd_nom = 'MacrosoftDB', bd_hôte = 'localhost';

    private readonly mysqli $con;
    private readonly string $mdp, $id;

    public function __construct(string $id, string $mdp)
    {
        try {
            $this->con = mysqli_connect(Client::bd_hôte, $id, $mdp, Client::bd_nom);
        } catch (mysqli_sql_exception $e) {
            $code = $e->getCode();
            if ($code == 1045)
                $this->echecConnexion($id, $mdp);
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

    public static function crée(string $id, string $mdp): Client
    {
        $con = mysqli_connect(Client::bd_hôte, $id, $mdp, Client::bd_nom);
        mysqli_set_charset($con, 'utf8');
        $role = $con->query('select CURRENT_ROLE() as role')->fetch_assoc()['role'];
        switch ($role) {
            case 'UTILISATEUR':
                return new Utilisateur($id, $mdp);
            case 'TECHNICIEN':
                return new Technicien($id, $mdp);
                // ...
            default:
                throw new Exception("Bug, le role $role n'existe pas");
        }
    }

    protected function query(string $q): array
    {
        return $this->con->query($q)->fetch_all(MYSQLI_ASSOC);
    }

    private function close()
    {
        $this->con->close();
    }

    protected function getLogin(): string
    {
        return $this->id;
    }

    private function echecConnexion(string $id, string $mdp)
    {
        $this->query("insert into Log_connection_echec values (CURRENT_DATE,$id,$mdp," . $_SERVER['REMOTE_ADDR'] . ')');
    }
}

abstract class Compte extends Client
{
    public function getProfil(): array
    {
        return $this->query('select * from VueProfilUtilisateur');
    }
}

final class Utilisateur extends Compte
{
    public function getTickets(): array
    {
        return $this->query('select * from VueTicketsUtilisateur');
    }

    public function ajoutTicket(int $lib, int $niv_urgence, string $desc, string $cible)
    {
        try {
            $this->query("insert into Ticket values ($lib,$niv_urgence,'Ouvert',$desc,CURRENT_DATE," . $_SERVER['REMOTE_ADDR'] . ",$niv_urgence,getLogin()," . $cible == '' ? $cible : $this->getLogin() . ',null)');
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale(`Impossible d'ajouter ce ticket`, 1, $e);
        }
    }
}

final class Visiteur extends Client
{
    public function __construct()
    {
        parent::__construct('visiteur', include ('mdp_visiteur'));
    }

    public function getTickets(): array
    {
        return $this->query('select * from VueDerniersTicketsOuverts');
    }

    // la vérification du mdp se fera plus en amont
    public function inscription(string $id, string $mdp)
    {
        try {
            $this->query("insert into Utilisateur values ($id,$mdp,null)");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("Impossible d'ajouter l'utilisateur $id", 2, $e);
        }
    }
}

final class Technicien extends Compte
{
    public function getTicketsAttribuées(): array
    {
        return $this->query('select * from VueTicketsTechnicien');
    }

    public function getTicketsNonAttribués(): array
    {
        return $this->query('select * from VueTicketsNonTraites ');
    }

    public function assigneTicket(int $id)
    {
        try {
            $this->query("UPDATE VueTicketsNonTraites SET technicien=getLogin() and etat='En cours de traitement' WHERE idT=$id");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("Impossible de s'assigner le ticket $id", 3, $e);
        }
    }

    public function fermeTicket(int $id)
    {
        try {
            $this->query("UPDATE VueTicketsTechnicien SET etat='Fermé' WHERE idT=$id");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("Impossible de fermer le ticket $id", 4, $e);
        }
    }
}

final class AdminSys extends Compte
{
    public function getTicketValidés(): array
    {
        return $this->query('select * from VueLogTicketsValides');
    }

    public function getConnexionsEchouées(): array
    {
        return $this->query('select * from Log_connection_echec');
    }
}

final class AdminWeb extends Compte
{
    public function midifieTicket(int $id, int $niveau, int $libellé, string $technicien)
    {
        try {
            $this->query("UPDATE VueTicketsTechnicien SET etat='En cours de traitement' and niv_urgence=$niveau and libelle=$libellé and technicien=$technicien WHERE idT=$id");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("Impossible de modifier le ticket $id", 5, $e);
        }
    }

    public function getTickets(): array
    {
        return $this->query('select * from VueTicketsOuverts');
    }

    public function getLibellés(): array
    {
        return $this->query('select * from VueLibellesNonArchives');
    }

    public function modifieLibellé(int $id, string $titre, ?int $groupe, bool $archive)
    {
        try {
            $this->query("UPDATE VueLibellesNonArchives SET intitule=$titre and lib_sup=$groupe and archive=$archive WHERE idL=$id");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("Impossible de modifier le libellé $id", 6, $e);
        }
    }

    public function ajoutLibellé(string $titre, ?int $groupe)
    {
        try {
            $this->query("insert into VueLibellesNonArchives  values ($titre,$groupe,FALSE)");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("Impossible d'ajouter le libellé '$titre'", 7, $e);
        }
    }

    public function ajoutTechnicien(string $id, string $mdp)
    {
        try {
            $this->query("insert into Utilisateur values ($id,$mdp,'Technicien')");
        } catch (mysqli_sql_exception $e) {
            throw new RequêteIllégale("Impossible de créer le technicien '$id'", 8, $e);
        }
    }
}
