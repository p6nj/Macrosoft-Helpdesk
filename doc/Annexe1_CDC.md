# SAE 3.01 - Cahier des Charges

## Sommaire

* [**Chapitre 1**](#part1) **:** _Introduction_
* [**Chapitre 2**](#part2) **:** _Énoncé_
* [**Chapitre 3**](#part3) **:** _Pré-requis_

---

## <a id="part1"></a>Introduction

Ce document est une reformulation du cahier des charges fourni par le client. Il décrit les attendus du projet, et exprime les
besoins du client. Ce document a été rédigé dans le but de présenter efficacement les objectifs du système, tant pour les intervenants
au projet que pour le client.

Le projet joue un rôle central dans l'évaluation et la validation du projet, en veillant à ce que toutes les conditions et objectifs
énoncés dans ce document soient pleinement satisfaits. Il constitue ainsi une étape critique pour certifier la conformité du projet
aux attentes du client.

Ce cahier des charges commencera par une description détaillée du contexte de développement et des objectifs que le projet doit
atteindre. Les contraintes imposées au projet seront données ensuite, autour desquelles le projet devra se concentrer. Enfin, il
détaillera les connaissances, ressources matérielles, ressources logicielles et compétences nécessaires à la réalisation du projet.

---

## <a id="part2"></a>Énoncé

## _Objectifs du projet_

Le projet vise à mettre en place une application web de ticketing interne à l'IUT de Vélizy, et plus précisément à la formation de BUT
Informatique. Cette plateforme a pour but de recueillir les demandes de dépannage des différents utilisateurs (étudiants et professeurs)
dans les salles machines.

### Objets du système

Étant une plateforme de ticketing, les demandes de dépannage seront représentées sous la forme de tickets. Chaque ticket possède un 
niveau d'urgence, d'abord estimé par le demandeur, dont les informations seront également renseignées dans le ticket, puis évalué
par un administrateur web. Il est aussi possible pour le demandeur de créer un ticket pour une autre personne, appelée personne
concernée, dont les informations seront renseignées dans le ticket.

Un ticket peut posséder 4 niveaux d'urgence : 4(faible), 3(moyen), 2(important) et 1(urgent).
Un ticket possède également un état/statut, qui peut avoir 3 valeurs : Ouvert, En cours de traitement et Fermé. Les tickets fermés devront
être stockés dans un historique.

Afin de déterminer la nature des tickets, un système de libellés devra être mis en place. Un libellé est une étiquette attribuée au ticket
qui détaille la nature du problème énoncé par ce dernier. Les libellés sont stockés dans une base de données, au même titre que les tickets
et les utilisateurs. Un libellé peut également regrouper plusieurs autres sous-libellés qui présentent des versions plus précises du problème énoncé.

Enfin, il y a également des journaux d'activités, ou logs, qui seront enregistrés dans des fichiers en dehors de la base de données, stockés dans
le système. La raison pour laquelle on les stocke ailleurs est pour éviter toute destruction des logs en cas de problème avec la BD. Il existe deux
types de journaux d'activités :
- Journal d'activité de création de ticket : Un log de création de ticket est enregistré à chaque ouverture de ticket par un utilisateur.
Ce journal contiendra la date de création, l'adresse IP et le login de l'utilisateur qui a formulé le ticket, ainsi que le niveau d'urgence de ce dernier.
- Journal d'activité d'échec de connexion : Un log de connexion infructueuse est enregistré à chaque connexion échouée.
Ce journal contiendra la date de connexion, le login et le mot de passe tenté, ainsi que l'adresse IP qui a voulu se connecter.

La plateforme web sera divisés en plusieurs pages :
- Page d'accueil : Change de contenu selon le type d'utilisateur, mais contient toujours le logo du site web, ainsi que son nom.
- Page de connexion : Permets à un utilisateur non connecté d'entrer un login et un mot de passe pour se connecter.
Si l'utilisateur a oublié son mot de passe, il pourra se diriger sur la page de récupération de mot de passe.
- Page d'inscription : Permets à un utilisateur non inscrit de créer un login et un mot de passe. Le formulaire devra être confirmé avec un captcha.
- Page de récupération de mot de passe : Il n'y aura pas de méthode de récupération de mot de passe, cette page sera donc affichée
comme étant en construction.
- Page de profil : Permets de consulter les informations de son profil et de changer son mot de passe.

### Acteurs du système

#### 1. Visiteur

Le visiteur est un utilisateur non connecté/non inscrit. Il peut donc s'agir d'une personne extérieure au système, mais également
de n'importe quel autre type d'utilisateur ne s'étant simplement pas encore connecté à la plateforme. Il est ainsi vital que l'affichage
de la page d'accueil dans le format Visiteur soit efficace et ergonomique, car tout type d'utilisateur sera amené à la visiter.

La page d'accueil en vue Visiteur devra afficher un texte explicatif de la plateforme, accompagné par une vidéo de démonstration
de l'utilisation de la plateforme. Il aura également une visualisation des 10 derniers tickets faits sur la plateforme. Ainsi,
si un utilisateur veut se connecter pour émettre une demande de dépannage pour un problème récent, il pourra vérifier si ce problème
n'a pas déjà été énoncé.

Enfin, la page possèdera en évidence un bouton de connexion et d'inscription, permettant un accès direct et facile pour les utilisateurs
voulant se connecter.

#### 2. Utilisateur connecté

L'utilisateur connecté devra pouvoir ouvrir un nouveau ticket, en précisant son libellé et le niveau d'urgence qu'il estime. Par défaut,
la personne concernée est l'utilisateur lui-même, mais il pourra renseigner le login d'un autre utilisateur dans le cas où cette demande
le concerne.

La page montrera en évidence un tableau de bord. Il s'agit de la liste des tickets publiés par l'utilisateur, ou ceux formulés par d'autre,
mais qui l'ont en personne concernée, ainsi que leur état.

Enfin, à la place des boutons d'inscription et de connexion, un bouton de profil permettra de se déconnecter et de se rendre sur la
page de profil, qui permet de voir son login et de changer son mot de passe.

#### 3. Administrateur web

Il n'y a qu'un seul administrateur web enregistré dans la base de données. Pour se connecter au compte, il faudra un login gestion, et
un mot de passe qui sera à définir et à donner au client. Il possède une vue sur tous les tickets en état ouvert, et pourra cliquer sur
lesdits tickets pour les affecter aux différents comptes techniciens, ou les modifier.

L'administrateur web peut en effet modifier l'état et le niveau d'urgence du ticket. Il peut notamment passer l'état d'un ticket à "Fermé", ce
qui le clôture immédiatement.

C'est à lui que revient la charge de gérer tout le système de libellés. Il peut les créer, les supprimer/archiver, modifier leur
intitulé, et également définir pour chacun d'entre eux un libellé supérieur, permettant ainsi de créer le système de sous-libellés
mentionné plus haut.

L'administrateur web peut également créer des comptes techniciens, en renseignant leur login et leur mot de passe.

#### 4. Technicien

Il existe par défaut deux comptes techniciens, tec1 et tec2, qui possèdent le mot de passe tec.

Le technicien peut prendre en charge un ticket, afin de traiter le problème qu'il énonce. Généralement, c'est l'administrateur web
qui leur affecte directement les tickets, mais il leur est également possible, par le biais de la même vue que l'admin web, de
s'auto-affecter les tickets en état ouvert. 

Il possèdera donc une vue de tous les tickets qui lui sont attribués, et pourra les fermer une fois le problème réglé.

#### 5. Administrateur système

Pour se connecter au compte d'administrateur système, il faudra un login admin, et un mot de passe qui sera à définir et à donner
au client. Il accède par l'intermédiaire de la plateforme web aux journaux d'activités de l'application web, bien qu'il pourra
également y accéder en lignes de commandes.

## _Contraintes et Exigences_

L'application web devra être développée en PHP & MYSQL et être installée sur un serveur porté par un RPi4, disponible en connexion ssh
depuis les postes des salles machines. À terme, il devra également être disponible depuis l'extérieur via un tunnel ssh.

Une carte SD devra être configurée pour installer le système, le serveur web et le serveur SGBD Mysql. Le login de base de la
carte SD sera pisae et son mot de passe !pisae!, jusqu'à ce que l'adresse IP de la carte ne soit communiquée à l'équipe de
développement.

Un github du projet devra être partagé avec les professeurs souhaitant le consulter, et devra contenir tous les éléments du projet,
de sa documentation à son code php.

---

## <a id="part3"></a>Pré-requis

Le projet demande des compétences en PHP & MYSQL, en Analyse des besoins et en Conception. Des connaissances en HTML et en
installation système et réseau sont également nécessaires à la bonne réalisation du projet.

Il est nécessaire de savoir travailler avec Git, ainsi que d'avoir des compétences en communication dans le cadre de la documentation, 
de la charte graphique et du logo à réaliser. 

En termes de ressources matérielles, le projet devra faire l'objet d'un Raspberry PI RPi4 et d'une carte SD à paramétrer. Pour les
ressources logicielles, nous allons utiliser Github pour tenir un registre du projet, et les IDE Jetbrains ainsi que VSCode pour
écrire les fichiers markdown et coder les fichiers php, css, html et autres.

Enfin, l'utilisation du logiciel Excalidraw permettra de mettre en forme des diagrammes et des figures pour concevoir et illustrer
les choix de conceptions.