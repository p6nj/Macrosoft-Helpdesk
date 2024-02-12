CREATE DATABASE MacrosoftDB;
set session max_sp_recursion_depth = 10;
set global max_sp_recursion_depth = 10;
USE MacrosoftDB;
DROP TRIGGER IF EXISTS VerifierRoleTechnicienInsert;
DROP TRIGGER IF EXISTS VerifierRoleTechnicienUpdate;
-- Création de la table Utilisateur
CREATE TABLE Utilisateur (
    login VARCHAR(255) PRIMARY KEY,
    mdp VARCHAR(255) NOT NULL,
    role VARCHAR(20) CHECK (role IN ('Admin sys', 'Admin web', 'Technicien'))
);
-- Création de la table Libelle
CREATE TABLE Libelle (
    idL INT AUTO_INCREMENT PRIMARY KEY,
    intitule VARCHAR(255) NOT NULL,
    archive BOOLEAN NOT NULL DEFAULT FALSE,
    lib_sup INT,
    FOREIGN KEY (lib_sup) REFERENCES Libelle(idL)
);
-- Création de la table Ticket
CREATE TABLE Ticket (
    idT INT AUTO_INCREMENT PRIMARY KEY,
    lib INT NOT NULL,
    niv_urgence INT NOT NULL CHECK (niv_urgence IN (1, 2, 3, 4)),
    etat VARCHAR(22) NOT NULL CHECK (
        etat IN ('Ouvert', 'En cours de traitement', 'Fermé')
    ),
    description TEXT,
    date DATE NOT NULL,
    IP VARCHAR(15) NOT NULL CHECK (IP LIKE '%.%.%.%'),
    og_niv_urgence INT NOT NULL CHECK (og_niv_urgence IN (1, 2, 3, 4)),
    demandeur VARCHAR(255) NOT NULL,
    cible VARCHAR(255) NOT NULL,
    technicien VARCHAR(255),
    FOREIGN KEY (lib) REFERENCES Libelle(idL),
    FOREIGN KEY (demandeur) REFERENCES Utilisateur(login),
    FOREIGN KEY (cible) REFERENCES Utilisateur(login),
    FOREIGN KEY (technicien) REFERENCES Utilisateur(login)
);
-- Création de la table Log_connexion_echec
CREATE TABLE Log_connexion_echec (
    idLC INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    login_tente VARCHAR(255) NOT NULL,
    mdp_tente VARCHAR(255) NOT NULL,
    IP VARCHAR(15) NOT NULL CHECK (IP LIKE '%.%.%.%')
);
-- Création de la vue pour afficher les libellés non archivés
CREATE VIEW VueLibellesNonArchives AS
SELECT *
FROM Libelle
WHERE archive = FALSE;
-- Création de la vue pour afficher tous les tickets ouverts
CREATE VIEW VueTickets AS
SELECT idT,
    intitule AS libelle,
    niv_urgence,
    etat,
    description,
    demandeur,
    cible,
    date,
    IP,
    technicien,
    idL
FROM Ticket t
    JOIN Libelle l ON t.lib = l.idL;
-- Création de la vue pour afficher les tickets ouverts
create view VueTicketsOuverts as
select *
from VueTickets
WHERE etat IN ('Ouvert', 'En cours de traitement');
-- Création de la vue pour afficher les tickets fermés
create view VueTicketsFermés as
select *
from VueTickets
WHERE etat='Fermé';
-- Création de la vue pour afficher les 10 derniers tickets ouverts
CREATE VIEW VueDerniersTicketsOuverts AS
SELECT *
FROM VueTicketsOuverts
ORDER BY date DESC
LIMIT 10;
-- Création de la vue pour afficher tous les tickets non traités
CREATE VIEW VueTicketsNonTraites AS
SELECT *
FROM VueTicketsOuverts
WHERE etat = 'Ouvert';
-- Création de la vue du profil de l'utilisateur connecté
CREATE VIEW VueProfilUtilisateur AS
SELECT *
FROM Utilisateur
WHERE login = substring_index(user(), '@', 1);
-- Création de la vue des tickets ouverts concernant l'utilisateur connecté
CREATE VIEW VueTicketsOuvertsUtilisateur AS
SELECT *
FROM VueTicketsOuverts
WHERE demandeur = substring_index(user(), '@', 1)
    OR cible = substring_index(user(), '@', 1);
-- Création de la vue des tickets fermés concernant l'utilisateur connecté
CREATE VIEW VueTicketsFermésUtilisateur AS
SELECT *
FROM VueTicketsFermés
WHERE demandeur = substring_index(user(), '@', 1)
    OR cible = substring_index(user(), '@', 1);
-- Création de la vue des tickets gérés par le technicien
CREATE VIEW VueTicketsTechnicien AS
SELECT *
FROM VueTicketsOuverts
WHERE technicien = substring_index(user(), '@', 1);
-- Création de la vue des journaux d'activité de tickets validés
CREATE VIEW VueLogTicketsValides AS
SELECT idt,
    date,
    IP,
    demandeur AS login,
    og_niv_urgence AS niv_urgence
FROM Ticket t
    JOIN Utilisateur u ON t.demandeur = u.login;
-- Création de la vue des techniciens de la base pour l'admin web --
create view VueTechniciens as
select login
from Utilisateur
where role = 'Technicien';
-- Création des rôles
CREATE ROLE UTILISATEUR;
CREATE ROLE TECHNICIEN;
CREATE ROLE ADMIN_SYS;
CREATE ROLE ADMIN_WEB;
CREATE ROLE VISITEUR;
-- Attribution du droit de voir son profil à tous les rôles
GRANT SELECT ON VueProfilUtilisateur TO UTILISATEUR,
    TECHNICIEN,
    ADMIN_SYS,
    ADMIN_WEB;
-- Attribution des permissions au rôle Utilisateur
GRANT SELECT ON VueTicketsOuvertsUtilisateur TO UTILISATEUR;
GRANT SELECT ON VueTicketsFermésUtilisateur TO UTILISATEUR;
GRANT INSERT ON Ticket TO UTILISATEUR;
GRANT SELECT ON VueLibellesNonArchives to UTILISATEUR;
-- Attribution des permissions au rôle Technicien
GRANT SELECT,
    UPDATE (etat) ON VueTicketsTechnicien TO TECHNICIEN;
GRANT SELECT,
    UPDATE (technicien, etat) ON VueTicketsNonTraites TO TECHNICIEN;
-- Attribution des permissions au rôle Admin système
GRANT SELECT ON VueLogTicketsValides TO ADMIN_SYS;
GRANT SELECT ON Log_connexion_echec TO ADMIN_SYS;
-- Attribution des permissions au rôle Admin web
GRANT SELECT ON VueTicketsOuverts TO ADMIN_WEB;
GRANT SELECT(idT), UPDATE (etat, niv_urgence, lib, technicien) ON Ticket TO ADMIN_WEB;
GRANT SELECT,
    INSERT,
    UPDATE (intitule, lib_sup, archive) ON VueLibellesNonArchives TO ADMIN_WEB;
GRANT INSERT ON Utilisateur TO ADMIN_WEB;
grant select on VueTechniciens to ADMIN_WEB;
-- Attribution des permissions au rôle Visiteur
GRANT SELECT ON VueDerniersTicketsOuverts TO VISITEUR;
-- Attribution des permissions au visiteur (lors de la création de compte et de l'échec de connexion uniquement)
GRANT INSERT ON Log_connexion_echec TO VISITEUR;
GRANT INSERT ON Utilisateur TO VISITEUR;
-- TRIGGERS
DELIMITER $$
CREATE TRIGGER VerifierRoleTechnicienInsert BEFORE
INSERT ON Ticket FOR EACH ROW BEGIN
DECLARE role_utilisateur VARCHAR(20);
SELECT role INTO role_utilisateur
FROM Utilisateur
WHERE login = NEW.technicien;
IF role_utilisateur != 'Technicien' THEN SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Seuls les utilisateurs avec le rôle Technicien peuvent être assignés comme technicien pour un ticket.';
END IF;
END $$
CREATE TRIGGER VerifierRoleTechnicienUpdate BEFORE
UPDATE ON Ticket FOR EACH ROW BEGIN
DECLARE role_utilisateur VARCHAR(20);
SELECT role INTO role_utilisateur
FROM Utilisateur
WHERE login = NEW.technicien;
IF role_utilisateur != 'Technicien' THEN SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Seuls les utilisateurs avec le rôle Technicien peuvent être assignés comme technicien pour un ticket.';
END IF;
END $$
CREATE TRIGGER VerifierLibelleInsert
BEFORE INSERT ON Libelle
FOR EACH ROW
BEGIN
    DECLARE lib_sup_exist INT;
    
    IF NEW.lib_sup IS NOT NULL THEN
        SELECT COUNT(*) INTO lib_sup_exist
        FROM Libelle
        WHERE idL = NEW.lib_sup;
        
        IF lib_sup_exist = 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Le libellé supérieur spécifié nexiste pas.';
        ELSEIF NEW.intitule = (SELECT intitule FROM Libelle WHERE idL = NEW.lib_sup) THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Le libellé ne peut pas avoir la même valeur que son libellé supérieur.';
        END IF;
    END IF;
    
END $$
CREATE TRIGGER VerifierLibelleUpdate
BEFORE UPDATE ON Libelle
FOR EACH ROW
BEGIN
    DECLARE lib_sup_exist INT;
    
    IF NEW.lib_sup IS NOT NULL THEN
        SELECT COUNT(*) INTO lib_sup_exist
        FROM Libelle
        WHERE idL = NEW.lib_sup;
        
        IF lib_sup_exist = 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Le libellé supérieur spécifié nexiste pas.';
        ELSEIF NEW.intitule = (SELECT intitule FROM Libelle WHERE idL = NEW.lib_sup) THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Le libellé ne peut pas avoir la même valeur que son libellé supérieur.';
        END IF;
    END IF;
    
END $$
CREATE PROCEDURE VerifierLibelleSup(IN libelle_id INT, IN parent_id INT)
BEGIN
    DECLARE parent_libelle VARCHAR(255);
    
    -- Récupérer le libellé supérieur actuel
    SELECT intitule INTO parent_libelle FROM Libelle WHERE idL = parent_id;
    
    -- Vérifier si le libellé actuel est égal au libellé supérieur
    IF parent_libelle IS NOT NULL AND EXISTS (SELECT 1 FROM Libelle WHERE idL = libelle_id AND intitule = parent_libelle) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Le libellé ne peut pas avoir la même valeur que son libellé supérieur.';
    END IF;
    
    -- Récupérer le libellé supérieur du libellé supérieur actuel (récursivement)
    SELECT lib_sup INTO parent_id FROM Libelle WHERE idL = parent_id;
    
    -- Appeler récursivement la procédure si un libellé supérieur existe
    IF parent_id IS NOT NULL THEN
        CALL VerifierLibelleSup(libelle_id, parent_id);
    END IF;
END $$
CREATE TRIGGER VerifierLibelleSupInsert
BEFORE INSERT ON Libelle
FOR EACH ROW
BEGIN
    DECLARE parent_id INT;
    
    -- Récupérer l'ID du libellé supérieur
    SET parent_id = NEW.lib_sup;
    
    -- Appeler la procédure récursive pour vérifier les libellés supérieurs
    CALL VerifierLibelleSup(NEW.idL, parent_id);
END $$
CREATE TRIGGER VerifierLibelleSupUpdate
BEFORE UPDATE ON Libelle
FOR EACH ROW
BEGIN
    DECLARE parent_id INT;
    
    -- Récupérer l'ID du libellé supérieur
    SET parent_id = NEW.lib_sup;
    
    -- Appeler la procédure récursive pour vérifier les libellés supérieurs
    CALL VerifierLibelleSup(NEW.idL, parent_id);
END $$
DELIMITER ;
