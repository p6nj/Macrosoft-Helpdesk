DROP DATABASE MacrosoftDB;
DROP ROLE UTILISATEUR;
DROP ROLE TECHNICIEN;
DROP ROLE ADMIN_SYS;
DROP ROLE ADMIN_WEB;
DROP ROLE VISITEUR;
CREATE DATABASE MacrosoftDB;
USE MacrosoftDB;

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
    etat VARCHAR(22) NOT NULL CHECK (etat IN ('Ouvert', 'En cours de traitement', 'Fermé')),
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

-- Création de la table Log_connection_echec
CREATE TABLE Log_connection_echec (
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
CREATE VIEW VueTicketsOuverts AS
SELECT idT, intitule AS libelle, niv_urgence, etat, description, demandeur, cible, date, IP, technicien, idL
FROM Ticket t
JOIN Libelle l ON t.lib = l.idL
WHERE t.etat IN ('Ouvert', 'En cours de traitement');

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
WHERE login = USER();

-- Création de la vue des tickets concernant l'utilisateur connecté
CREATE VIEW VueTicketsUtilisateur AS
SELECT *
FROM VueTicketsOuverts
WHERE demandeur = USER() OR cible = USER();

-- Création de la vue des tickets gérés par le technicien
CREATE VIEW VueTicketsTechnicien AS
SELECT *
FROM VueTicketsOuverts
WHERE technicien = USER();

-- Création de la vue des journaux d'activité de tickets validés
CREATE VIEW VueLogTicketsValides AS
SELECT idt, date, IP, demandeur AS login, og_niv_urgence AS niv_urgence
FROM Ticket t
JOIN Utilisateur u ON t.demandeur = u.login;

-- Création des rôles
CREATE ROLE UTILISATEUR;
CREATE ROLE TECHNICIEN;
CREATE ROLE ADMIN_SYS;
CREATE ROLE ADMIN_WEB;
CREATE ROLE VISITEUR;

-- Attribution du droit de voir son profil à tous les rôles
GRANT SELECT ON VueProfilUtilisateur TO UTILISATEUR, TECHNICIEN, ADMIN_SYS, ADMIN_WEB;

-- Attribution des permissions au rôle Utilisateur
GRANT SELECT ON VueTicketsUtilisateur TO UTILISATEUR;
GRANT INSERT ON Ticket TO UTILISATEUR;

-- Attribution des permissions au rôle Technicien
GRANT SELECT, UPDATE (etat) ON VueTicketsTechnicien TO TECHNICIEN;
GRANT SELECT, UPDATE (technicien) ON VueTicketsNonTraites TO TECHNICIEN;

-- Attribution des permissions au rôle Admin système
GRANT SELECT ON VueLogTicketsValides TO ADMIN_SYS;
GRANT SELECT ON Log_connection_echec TO ADMIN_SYS;

-- Attribution des permissions au rôle Admin web
GRANT SELECT, UPDATE (etat, niv_urgence, libelle, technicien) ON VueTicketsOuverts TO ADMIN_WEB;
GRANT SELECT, INSERT, UPDATE (intitule, lib_sup, archive) ON VueLibellesNonArchives TO ADMIN_WEB;
GRANT INSERT ON Utilisateur TO ADMIN_WEB;

-- Attribution des permissions au rôle Visiteur
GRANT SELECT ON VueDerniersTicketsOuverts TO VISITEUR;

-- Attribution des permissions au visiteur (lors de la création de compte et de l'échec de connexion uniquement)
GRANT INSERT ON Log_connection_echec TO VISITEUR;
GRANT INSERT ON Utilisateur TO VISITEUR;
