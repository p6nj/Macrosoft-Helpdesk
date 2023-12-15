<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

class ProfilException extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Erreur de profil : ' . $message, $code, $previous);
    }
}

abstract class Client
{
    protected const db = 'MacrosoftDB', dbh = 'localhost';

    private readonly mysqli $con;
    private readonly string $p, $login;

    public static function cree(string $id, string $mdp): Client
    {
        $con = mysqli_connect(Client::dbh, $id, $mdp, Client::db);
        mysqli_set_charset($con, 'utf8');
        $role = $con->query('select CURRENT_ROLE() as role')->fetch_assoc()['role'];
        switch ($role) {
            case 'UTILISATEUR':
                return new Utilisateur($id, $mdp);
            case 'TECHNICIEN':
                return new Technicien($id, $mdp);
                // ...
            default:
                throw new ProfilException("Profil inconnu : $role");
        }
    }

    public function __construct(string $id, string $mdp)
    {
        try {
            $this->con = mysqli_connect(Client::dbh, $id, $mdp, Client::db);
        } catch (mysqli_sql_exception $e) {
            $code = $e->getCode();
            if ($code == 1044)
                throw new ProfilException('Base inexistante ou connexion refusée.');
            else if ($code == 1045)
                throw new ProfilException('Identifiants invalides.');
            else
                throw new ProfilException('Erreur mysqli #' . $e->getCode() . ' : ' . $e->getMessage() . '.');
        }
        $this->p = $mdp;
        $this->login = $id;
    }

    public function __unserialize(array $data): void
    {
        $this->__construct($data['l'], $data['p']);
    }

    public function __serialize(): array
    {
        $this->close();
        return [
            'l' => $this->login,
            'p' => $this->p
        ];
    }

    protected function query(string $q): array
    {
        return $this->con->query($q)->fetch_all(MYSQLI_ASSOC);
    }

    private function close()
    {
        $this->con->close();
    }
    protected function getLogin():string{
        return $this -> login;
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

        $this->query("insert into Ticket values ($lib,$niv_urgence,'Ouvert',$desc,CURRENT_DATE,".$_SERVER['REMOTE_ADDR'].",$niv_urgence,getLogin(),".$cible==""?$cible:$this->getLogin().",null)");
    }
}



final class Visiteur extends Client
{
    public function __construct(string $id, string $mdp)
    {
        try {
            $this->con = mysqli_connect(Client::dbh, 'visiteur', include('mdp_visiteur'), Client::db);
        } catch (mysqli_sql_exception $e) {
            $code = $e->getCode();
            if ($code == 1044)
                throw new ProfilException('Base inexistante ou connexion refusée.');
            else if ($code == 1045)
                throw new ProfilException('Identifiants invalides.');
            else
                throw new ProfilException('Erreur mysqli #' . $e->getCode() . ' : ' . $e->getMessage() . '.');
        }
        $this->p = $mdp;
        $this->login = $id;
    }

     public function getTickets():array
    {
        return $this->query('select * from VueTicketsUtilisateur');
    }
    public function echecConnexion(int $date,string $login, string $mdp, string $ip )
    {
        $this->query("insert into Log_connection_echec  values ($date,$login,$mdp,$ip)");
    }

    public function inscription(string $login,string $mdp, string $verif)
    {
        if ($mdp == $verif){
            $this ->query("insert into Utilisateur values ($login,$mdp,null)");
        }
        // sinon faux donc a voir
    }
}



final class Technicien extends Client
{
    public function getTicketsAttribuées(): array  //a renommer getTicketsAttribuées
    {
        return $this->query('select * from VueTicketsTechnicien');
    }

    public function getTicketsNonAttribués():array
    {
        return $this->query('select * from VueTicketsNonTraites ');
    }

    public function assigneTicket(int $id)
    {
        $this ->query("UPDATE  VueTicketsNonTraites SET technicien=getLogin() and etat='En cours de traitement' WHERE idT=$id");
    }

    public function fermeTicket(int $id)
    {
        $this ->query("UPDATE VueTicketsTechnicien SET etat='Fermé' WHERE idT=$id");
    }

}
final class AdminSys extends Client
{
    public function getTicketValidés():array
    {
        return $this->query('select * from VueLogTicketsValides  ');
    }

    public function getConnexionsEchouées():array
    {
        return $this->query('select * from Log_connection_echec   ');
    }
}

final class AdminWeb extends Client
{
    public function getTickets():array
    {
        return $this->query('select * from VueTicketsOuverts    ');
    }

    public function getLibellés():array
    {
        return $this->query('select * from VueLibellesNonArchives    ');
    }

    public function ajoutLibellé(string $titre, ?int $groupe)
    {
        $this ->query("insert into VueLibellesNonArchives  values ($titre,$groupe,FALSE)");
    }

    public function ajoutTechnicien(string $id, string $mdp)
    {
        $this ->query("insert into Utilisateur  values ($id,$mdp,'Technicien')");
    }

    public function midifieTicket(int $id,int $niveau, int $lib, string $technicien)
    {
        $this ->query("UPDATE VueTicketsTechnicien SET etat='En cours de traitement' and niv_urgence=$niveau and libelle=$lib and technicien=$technicien  WHERE idT=$id");
    }//Trouver un moyen pour recuperer l'id du Ticket

    public function modifieLibellé(int $id,string $titre, ?int $groupe, bool $archive)
    {
        $this ->query("UPDATE VueLibellesNonArchives  SET intitule=$titre and lib_sup=$groupe and archive=$archive WHERE idL=$id");
    } //Trouver un moyen pour recuperer l'id du Libellé


}

/*
// admettons qu'un admin web et sys peuvent voir tous les tickets
class Admin extends Client
{
    public function getAllTickets(): array
    {
        return $this->query('select * from VueTicketsTechnicien');
    }
}

final class AdminSys extends Admin
{
    public function getLogTicketsValides(): array
    {
        return $this->query('select * from VueLogTicketsValides');
    }

    public function getLogConnectionEchec(): array
    {
        return $this->query('select * from Log_connection_echec');
    }
}
*/
