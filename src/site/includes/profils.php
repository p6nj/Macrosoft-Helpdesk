<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class ErreurBD extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Erreur : ' . $message, $code, $previous);
    }
}

class ConnexionImpossible extends ErreurBD
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Connexion impossible : ' . $message, $code, $previous);
    }
}

class RequêteIllégale extends ErreurBD
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
            throw new ConnexionImpossible(
                $code == 1044 ? 'Base inexistante ou connexion refusée.' : (
                    $code == 1045 ? 'Identifiants invalides.' : (
                        'Erreur mysqli #' . $e->getCode() . ' : ' . $e->getMessage() . '.'
                    )
                ), $code
            );
        }
        $this->mdp = $mdp;
        $this->id = $id;
    }

    public function __unserialize(array $data): void
    {
        $this->__construct($data['l'], $data['p']);
    }

    public function __serialize(): array
    {
        $this->close();
        return [
            'l' => $this->id,
            'p' => $this->mdp
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

    // la date et l'IP sont à récupérer dans la fonction
    private function echecConnexion(string $id, string $mdp)
    {
        $this->query("insert into Log_connection_echec  values ($date,$id,$mdp,$ip)");
    }
}

interface ProfilInteraction
{
    function getTickets(): array;
}

final class Utilisateur extends Client implements ProfilInteraction
{
    public function getTickets(): array
    {
        return $this->query('select * from VueTicketsUtilisateur');
    }

    public function ajoutTicket(int $lib, int $niv_urgence, string $desc, string $cible)
    {
        $this->query("insert into Ticket values ($lib,$niv_urgence,'Ouvert',$desc,CURRENT_DATE," . $_SERVER['REMOTE_ADDR'] . ",$niv_urgence,getLogin()," . $cible == '' ? $cible : $this->getLogin() . ',null)');
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
        return $this->query('select * from VueTicketsUtilisateur');
    }

    public function inscription(string $id, string $mdp, string $verif)
    {
        if ($mdp == $verif) {
            $this->query("insert into Utilisateur values ($id,$mdp,null)");
        }
        // sinon faux donc a voir
    }
}

final class Technicien extends Client
{
    public function getTicketsAttribuées(): array  // a renommer getTicketsAttribuées
    {
        return $this->query('select * from VueTicketsTechnicien');
    }

    public function getTicketsNonAttribués(): array
    {
        return $this->query('select * from VueTicketsNonTraites ');
    }

    public function assigneTicket(int $id)
    {
        $this->query("UPDATE  VueTicketsNonTraites SET technicien=getLogin() and etat='En cours de traitement' WHERE idT=$id");
    }

    public function fermeTicket(int $id)
    {
        $this->query("UPDATE VueTicketsTechnicien SET etat='Fermé' WHERE idT=$id");
    }
}

final class AdminSys extends Client
{
    public function getTicketValidés(): array
    {
        return $this->query('select * from VueLogTicketsValides  ');
    }

    public function getConnexionsEchouées(): array
    {
        return $this->query('select * from Log_connection_echec   ');
    }
}

final class AdminWeb extends Client
{
    public function getTickets(): array
    {
        return $this->query('select * from VueTicketsOuverts    ');
    }

    public function getLibellés(): array
    {
        return $this->query('select * from VueLibellesNonArchives    ');
    }

    public function ajoutLibellé(string $titre, ?int $groupe)
    {
        $this->query("insert into VueLibellesNonArchives  values ($titre,$groupe,FALSE)");
    }

    public function ajoutTechnicien(string $id, string $mdp)
    {
        $this->query("insert into Utilisateur  values ($id,$mdp,'Technicien')");
    }

    public function midifieTicket(int $id, int $niveau, int $lib, string $technicien)
    {
        $this->query("UPDATE VueTicketsTechnicien SET etat='En cours de traitement' and niv_urgence=$niveau and libelle=$lib and technicien=$technicien  WHERE idT=$id");
    }  // Trouver un moyen pour recuperer l'id du Ticket

    public function modifieLibellé(int $id, string $titre, ?int $groupe, bool $archive)
    {
        $this->query("UPDATE VueLibellesNonArchives  SET intitule=$titre and lib_sup=$groupe and archive=$archive WHERE idL=$id");
    }  // Trouver un moyen pour recuperer l'id du Libellé
}

/*
 * // admettons qu'un admin web et sys peuvent voir tous les tickets
 * class Admin extends Client
 * {
 *     public function getAllTickets(): array
 *     {
 *         return $this->query('select * from VueTicketsTechnicien');
 *     }
 * }
 *
 * final class AdminSys extends Admin
 * {
 *     public function getLogTicketsValides(): array
 *     {
 *         return $this->query('select * from VueLogTicketsValides');
 *     }
 *
 *     public function getLogConnectionEchec(): array
 *     {
 *         return $this->query('select * from Log_connection_echec');
 *     }
 * }
 */
