INSERT INTO Utilisateur (login, mdp, role) VALUES
('alice', '5d41402abc4b2a76b9719d911017c592', NULL),
('tec1', 'a152e841783914146e4bcd4f39100686', 'Technicien'),
('charlie', '1937e316529d8b6e582d0c4cddb456c3', NULL),
('gestion', '3695c56a2c1d477cb1eaad2ccad6dae5', 'Admin sys'),
('tec2', 'a152e841783914146e4bcd4f39100686', 'Technicien'),
('diana', 'e99a18c428cb38d5f260853678922e03', NULL),
('grace', 'b6d767d2f8ed5d21a44b0e5886680cb9', NULL),
('tec3', 'a152e841783914146e4bcd4f39100686', 'Technicien'),
('isabel', '84bbd073b66596192c110a53f4080cd4', NULL),
('eric', 'd891893f26b9d3d9bdce7b7e77ea8b3d', NULL),
('karen', 'd9355b2e1ad292853bd8e30b30b6a01a', NULL),
('louis', 'ca9d93cc991648095ab9e08f3fc13b1c', NULL),
('marie', '02e74f10e0327ad868d138f2b4fdd6f0', NULL),
('admin', 'de5d59d6517cb6d5a8629a7f4e0bafb3', 'Admin web'),
('jack', '54b6c4b298e6696cbd07d30b96c1ae8b', NULL),
('tec4', '35a3a501b25c7f68110f0c5e7b9ecf85', 'Technicien'),
('queen', 'b0c4a64d7f69f1880b44aafc00d4c990', NULL),
('frank', 'ec83ab13db7c9e6dace9b7c98cc78c66', NULL),
('tec5', 'a152e841783914146e4bcd4f39100686', 'Technicien'),
('tec6', 'a152e841783914146e4bcd4f39100686', 'Technicien');

INSERT INTO Libelle (intitule, archive, lib_sup) VALUES
('Problème réseau', FALSE, NULL),
('Problème matériel', TRUE, NULL),
('Problème logiciel', FALSE, NULL),
('Demande d\'assistance', TRUE, NULL),
('Autre', FALSE, NULL),
('Problème d\'accès', FALSE, 1),
('Problème d\'imprimante', FALSE, 2),
('Problème de système', FALSE, 3),
('Demande de formation', TRUE, 4),
('Problème de messagerie', FALSE, 3),
('Problème de connexion', FALSE, 1),
('Problème de sécurité', TRUE, 2),
('Demande de matériel', FALSE, 4),
('Problème de performance', FALSE, 3),
('Problème de sauvegarde', TRUE, 2),
('Problème de configuration', FALSE, 1),
('Demande de logiciel', TRUE, 4),
('Problème de téléphone', TRUE, 2),
('Problème de serveur', FALSE, 3),
('Demande de dépannage', FALSE, 1);

INSERT INTO Ticket (lib, niv_urgence, etat, description, `date`, IP, og_niv_urgence, demandeur, cible, technicien) VALUES
(1, 3, 'Fermé', 'Impossible de se connecter au réseau', '2023-11-01', '192.168.1.5', 3, 'alice', 'frank', 'tec2'),
(2, 2, 'En cours de traitement', 'L\'imprimante ne fonctionne pas', '2023-11-02', '192.168.1.8', 2, 'queen', 'queen', 'tec1'),
(3, 1, 'En cours de traitement', 'Problème avec le logiciel X', '2023-11-03', '192.168.1.12', 1, 'charlie', 'alice', 'tec3'),
(4, 4, 'Fermé', 'Besoin d\'aide pour une nouvelle fonctionnalité', '2023-11-04', '192.168.1.15', 4, 'diana', 'eric', 'tec4'),
(5, 3, 'En cours de traitement', 'Problème d\'accès à un dossier partagé', '2023-11-05', '192.168.1.20', 3, 'eric', 'charlie', 'tec5'),
(6, 2, 'Fermé', 'L\'imprimante ne répond plus', '2023-11-06', '192.168.1.25', 2, 'frank', 'queen', NULL),
(7, 1, 'En cours de traitement', 'Problème de configuration du système', '2023-11-07', '192.168.1.30', 1, 'grace', 'alice', 'tec5'),
(8, 4, 'Ouvert', 'Demande de formation sur le logiciel Y', '2023-11-08', '192.168.1.35', 4, 'eric', 'eric', NULL),
(9, 3, 'Fermé', 'Problème de messagerie instantanée', '2023-11-09', '192.168.1.40', 3, 'isabel', 'charlie', NULL),
(10, 2, 'Ouvert', 'Problème d\'imprimante partagée', '2023-11-10', '192.168.1.45', 2, 'jack', 'queen', NULL),
(11, 1, 'En cours de traitement', 'Problème de connexion VPN', '2023-11-11', '192.168.1.50', 1, 'admin', 'alice', 'tec6'),
(12, 4, 'Fermé', 'Demande de matériel informatique', '2023-11-12', '192.168.1.55', 4, 'louis', 'queen', NULL),
(13, 3, 'Ouvert', 'Problème de performance du système', '2023-11-13', '192.168.1.60', 3, 'marie', 'alice', NULL),
(14, 2, 'En cours de traitement', 'Problème de sauvegarde des données', '2023-11-14', '192.168.1.65', 2, 'frank', 'charlie', 'tec4'),
(15, 1, 'Fermé', 'Demande de logiciel de dessin', '2023-11-15', '192.168.1.70', 1, 'isabel', 'grace', NULL),
(16, 4, 'Ouvert', 'Problème avec le téléphone de l\'entreprise', '2023-11-16', '192.168.1.75', 4, 'alice', 'alice', NULL),
(17, 3, 'Ouvert', 'Problème de serveur web', '2023-11-17', '192.168.1.80', 3, 'queen', 'diana', NULL),
(18, 2, 'Fermé', 'Demande de dépannage informatique', '2023-11-18', '192.168.1.85', 2, 'charlie', 'charlie', NULL),
(19, 1, 'En cours de traitement', 'Problème de connexion internet', '2023-11-19', '192.168.1.90', 1, 'marie', 'eric', 'tec2'),
(20, 4, 'En cours de traitement', 'Problème d\'installation de logiciel', '2023-11-20', '192.168.1.95', 4, 'diana', 'isabel', 'tec5');

INSERT INTO Log_connection_echec (date, login_tente, mdp_tente, IP) VALUES
('2023-11-01', 'john_doe', '8c32f5a6c9a00a52e6028da4e4b462ed', '192.168.1.5'),
('2023-11-02', 'user123', 'e150a7f07c5f81b13e6ba283b2e9c98b', '192.168.1.8'),
('2023-11-03', 'test_user', 'ebc3ee94d56b98b99c700f9e1f27c6d6', '192.168.1.12'),
('2023-11-04', 'john_smith', '6a91e1a3d84c9a3e79c4c1d7a0db6c8f', '192.168.1.15'),
('2023-11-05', 'admin', 'd033e22ae348aeb5660fc2140aec3585', '192.168.1.20'),
('2023-11-06', 'user1', '35ee0a6b5f0a2ec5c16b05d6f03f3a16', '192.168.1.25'),
('2023-11-07', 'guest', '25f9e794323b453885f5181f1b624d0b', '192.168.1.30'),
('2023-11-08', 'jane_doe', '73e84d81a2f9b79f469c5be4b9b12fcf', '192.168.1.35'),
('2023-11-09', 'user2', '5baa61e4c9b93f3f0682250b6cf8331b', '192.168.1.40'),
('2023-11-10', 'johnny', '7579c90b3ed1ac328f78b2d3f43047e8', '192.168.1.45'),
('2023-11-11', 'admin2', '6fa92c62d0b944e7895b673eaf9ee7b8', '192.168.1.50'),
('2023-11-12', 'test_admin', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', '192.168.1.55'),
('2023-11-13', 'user3', '55fc3c66a5f9d7a9519ed9c6f8a04838', '192.168.1.60'),
('2023-11-14', 'user4', '2afcf51289056cda99a8ae2d06feadc4', '192.168.1.65'),
('2023-11-15', 'user5', '4d7fe90d758b103e9df76cc30bfb95cf', '192.168.1.70'),
('2023-11-16', 'admin3', '64b4ba93f4c92e52aa123d6e27d8c946', '192.168.1.75'),
('2023-11-17', 'test_user2', '1db0b0e06eb99e99281ae4e5e6ad6a0d', '192.168.1.80'),
('2023-11-18', 'guest2', '6ba4415b2e7816a3899e6508f76d1bb2', '192.168.1.85'),
('2023-11-19', 'user6', '5baa61e4c9b93f3f0682250b6cf8331b', '192.168.1.90'),
('2023-11-20', 'admin4', '6fa92c62d0b944e7895b673eaf9ee7b8', '192.168.1.95');

INSERT INTO Utilisateur (login, mdp, role) VALUES ('user_technicien', 'password123', 'Technicien');
INSERT INTO Utilisateur (login, mdp, role) VALUES ('user_non_technicien', 'password123', 'Admin web');
INSERT INTO Utilisateur (login, mdp, role) VALUES ('user_demandeur', 'password123', 'Admin sys');
INSERT INTO Utilisateur (login, mdp, role) VALUES ('user_cible', 'password123', 'Admin web');

INSERT INTO Libelle (intitule) VALUES ('Problème de réseau');
INSERT INTO Ticket (lib, niv_urgence, etat, description, date, IP, og_niv_urgence, demandeur, cible, technicien)
VALUES (1, 1, 'Ouvert', 'Problème de réseau', CURDATE(), '192.168.1.100', 1, 'user_demandeur', 'user_cible', 'user_technicien');
INSERT INTO Ticket (lib, niv_urgence, etat, description, date, IP, og_niv_urgence, demandeur, cible, technicien)
VALUES (1, 2, 'Ouvert', 'Problème serveur', CURDATE(), '192.168.1.101', 2, 'user_demandeur', 'user_cible', 'user_non_technicien');
UPDATE Ticket
SET technicien = 'user_technicien'
WHERE idT = 1;
UPDATE Ticket
SET technicien = 'user_non_technicien'
WHERE idT = 1;

