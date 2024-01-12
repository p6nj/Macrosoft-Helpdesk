# SAE 3.01 - Spécifications

## Sommaire

* [**Chapitre 1**](#part1) **:** _Introduction_
* [**Chapitre 2**](#part2) **:** _Charte graphique_
* [**Chapitre 3**](#part3) **:** _Maquettes Web_
* [**Chapitre 4**](#part4) **:** _Cas d'utilisations_
* [**Chapitre 5**](#part5) **:** _Intégration_

---

## <a id="part1"></a>Introduction
Ce dossier de spécifications est un document détaillant le comportement que devra avoir le système. Cela comporte l'affichage
que doivent avoir les pages web du projet, ainsi que les différents cas d'utilisations du système. Il ne détaillera pas l'implémentation
réelle des contraintes listées ici, pour cela, référez-vous au **Dossier de Conception** _(Annexe 4)_.

---

## <a id="part2"></a>Charte graphique

Notre projet est une plateforme de ticketing destiné à aider des techniciens à résoudre des problèmes techniques rencontrés
par des utilisateurs via une interface unique, mais polymorphe. Cette charte tente donc de proposer une solution pour relier
à la fois les valeurs et intentions en lien avec les utilisateurs et les techniciens, les deux principaux types d’usagers.
Vous verrez que pour la première version du logo, une charte plus ancienne a été utilisée (qui sera résumée dans la section
correspondante) ; elle est maintenant obsolète.

Plusieurs hypothèses peuvent être posées sur les caractéristiques de l’utilisateur. Comme il utilise notre produit comme une
solution d’aide technique, ses connaissances dans le domaine de son problème peuvent être peu ou pas développées. Le but du code
graphique est donc de le rassurer par le choix des couleurs, des formes et de la typographie.

Les couleurs sont toutes froides en rapport avec la couleur du logo (#0077c2) sauf quand : il s’agit de faire contraste sur une
couleur froide sans autre solution ; une urgence ou un élément important nécessite l’attention immédiate de l’utilisateur
(destruction de données, erreurs…). Dans ce dernier cas, la couleur utilisée doit être de préférence un rouge intense (plus ou
moins clair pour faciliter la lecture).

Ensuite, les bords arrondis sont moins hostiles et ainsi le site comme le logo doit éviter les bords raides. Tous les angles
extérieurs indépendants (sans angles adjacents) du site doivent être arrondis à part si cette transformation nuit à l’accessibilité
ou l’ergonomie. Pour ces deux derniers principes, la couleur des éléments sera appliquée de préférence sur l’arrière-plan que
sur le texte ou l’avant-plan, et les éléments interactifs seront toujours suffisamment gros.

La police de caractère utilisée est Calibri. Cette police de Microsoft a été une de leurs préférées dans l’histoire de leurs
produits, souvent police par défaut dans leurs outils de traitement de texte puisqu’elle est très neutre, proche d’Arial. Elle
est aussi très homogène et facile à lire et a été décrite comme ayant un caractère doux et chaleureux par son créateur Lucas
de Groot (https://en.wikipedia.org/wiki/Calibri#cite_note-Lucasfonts-4). Elle est donc parfaite pour une utilisation en logo
et sur toute la page.

Mettons-nous maintenant à la place du second principal utilisateur de notre projet : le technicien. Nous avons été tenté de
privilégier une interface plus simple voir technique puisqu’il s’agit d’un public plus expérimenté, or il y a de bonnes raisons
de rejeter cette proposition.

Le technicien est amené à utiliser cette interface quotidiennement. Il faut donc prendre soin de l’ergonomie du site pour
éviter toute frustration et fatigue au long terme. Ainsi, les animations seront uniquement fonctionnelles (pour souligner une
latence contrôlée par exemple). Cela permettra également de limiter le temps de chargement du site. Ensuite, un site agréable
à utiliser est un site élégant, mais aussi neutre, avec peu de couleurs, beaucoup d’espace et une police neutre (Calibri est
encore parfaite pour cet effet).

Enfin, entre techniciens et utilisateurs la différence de compétences peut conduire à des conflits : les techniciens régulièrement
exposés aux mêmes problèmes parfois très simples peuvent être amenés à résumer l’intelligence et l’expérience d’un utilisateur
à ses compétences informatiques et ainsi stéréotyper les utilisateurs. C’est un problème régulier des domaines informatiques
auquel nous sommes nous-même constamment confrontés (quand des élèves se moquent de professeurs ayant du mal à utiliser leur matériel
informatique par exemple). 

Pour palier à ce problème, il est nécessaire de rétablir une égalité entre technicien et utilisateur
en proposant aux deux profils le même style graphique, même si l’interface diffère fonctionnellement. Ce n’est dans notre cas
même pas un problème puisque les deux styles sont compatibles.

### Version 1
Voici la première version du logo de Macrosoft™ Helpdesk :

![](img/logo2.png)

Il s’agit d’un panneau de danger rectangulaire blanc encadré de rouge contenant un marteau noir à 110° vers la droite. Il a
été réalisé sur l’outil de diagramme collaboratif Excalidraw. Pour ce logo, une ancienne version de la charte graphique a été
utilisée. Comme vous le voyez, la couleur dominante de ce logo est le rouge ; l’idée était d’utiliser des couleurs chaudes
pour à la fois rassurer les utilisateurs et mettre en avant la compétence des techniciens. Le panneau triangulaire faisait
allusion à la complexité technique et la délégation des incidents des utilisateurs aux techniciens. Le marteau évoquait le
matériel et les moyens solides dont dispose les techniciens pour résoudre ces problèmes. Un logo sobre, mais frappant.

### Version 2
Voici la nouvelle version :

<img src="img/logo.svg" width="400" height="400" alt="Logo Macrosoft" />

Un cercle bleu englobe un H minuscule blanc dont les deux pieds dépassent du cercle. En dessous est écrit de la même couleur
le nom de notre entreprise et au-dessus se devine un faible halo bleu. Avec la nouvelle charte, tous les aspects du logo ont
changé. 

Les couleurs chaudes ont été remplacées par les couleurs froides qui sont plus relaxantes en pratique. La rondeur de
la charte y passe par la forme extérieure du logo, le texte écrit en Calibri et l’arc du H minuscule. Ce dernier est le H de
Helpdesk, le produit de Macrosoft™ ciblé par ce logo. Le nom de notre entreprise est posé en dessous pour montrer la relation
d’appartenance. Le bleu et la sobriété du logo représente bien les valeurs de la charte en soulignant l’honnêteté et la patience
des techniciens ainsi que l’innocence des clients ayant besoin d’aide.

Ce logo comporte aussi deux autres éléments importants. L’arche du H avec la hauteur de la partie inférieure de la lettre laisse
deviner une porte, l’interface de notre site qui relie les deux profils d’utilisateur sans obstacle, préjugé ou égo. Enfin,
le halo au-dessus du cercle évoque une ampoule ou une chandelle, une lumière : le savoir neutre et efficace proposé par des
techniciens charitables, mais aussi la lueur qui éclaire des problèmes de solutions fiables, mais imparfaites, ce qui souligne
l’humilité des techniciens qui sont tout aussi humains que les utilisateurs qu’il assiste.


### Verdict
Après comparaison des deux logos, le second a été choisi. Le premier logo est rouge et blanc, or le rouge a l’effet inverse
de celui attendu. Ce logo est stressant et non rassurant, et c’est aussi parce qu’il est à l’image d’un panneau de danger du
code de la route. Dû à cette ressemblance, la forme du logo pose d’ailleurs de potentiels problèmes légaux et pourrait être
confondue avec un vrai panneau du code de la route. D’autre part, les formes du logo ont en grande partie des bords raides et peu
confortables pour les yeux, une agressivité qu’on veut absolument éviter dans ce contexte. La couleur n’est pas mise en avant
et ne sert que de bord. Enfin, rien dans le logo ne donne ou ne suggère le nom de l’entreprise ou du produit.

Le second logo est donc largement plus travaillé et conforme aux valeurs du produit et de l’entreprise. Il règle les principaux
problèmes du premier logo et est ainsi parfait pour notre utilisation.

---

## <a id="part3"></a>Maquettes web

Les deux pages statiques de ce livrable sont celles de la page d'accueil (vue Visiteur) et celle de l'inscription à la plateforme.

### Maquette de la page d'accueil (vue Visiteur)

La page d'accueil en vue Visiteur doit contenir :
- Le logo du site web et son nom
- Un bouton d'inscription
- Un bouton de connexion
- Un texte explicatif qui décrit le but de la plateforme
- Une vidéo explicative qui montre comment utiliser la plateforme
- Un affichage des 10 derniers tickets formulés (uniquement leurs libellés)

![](img/maqAccueilVisiteur.png)

### Maquette de la page d'inscription

La page d'inscription doit contenir :
- Le logo du site web et son nom
- Un bouton de retour à l'accueil
- Un bouton de connexion
- Un formulaire d'inscription avec nom d'utilisateur, mot de passe et confirmation du mot de passe
- Un captcha à valider afin de pouvoir s'inscrire

![](img/maqInscription.png)

---

## <a id="part4"></a>Cas d'utilisations

#### Cas d'utilisation 1 : Gestion des tickets
**Nom :** Gérer les tickets.
**Contexte d'utilisation :** La plateforme doit permettre de gérer et afficher des demandes de dépannage d'utilisateurs.
**Portée :** Entreprise boîte noire
**Niveau :** Stratégique 
**Acteur principal :** Utilisateur
**Intervenants :** Administrateur Web, Technicien
**Garantie en cas de succès :** Un ticket sera ouvert puis fermé.
**Déclencheur :** Évènement extérieur poussant à visiter la plateforme. (signaler un problème, voir les problèmes actuels, gérer la plateforme...)
**Scénario nominal :**
1. L'utilisateur créé un ticket.
2. L'administrateur web attribue le ticket à un technicien.
3. Le technicien traite la demande formulée par le ticket.
4. Le ticket est clôturé.

**Extensions :**
1. a. Ticket bidant :
    1. L'administrateur web clos le ticket directement.

    b. Les informations du ticket sont incorrectes :
    1. L'administrateur web modifie les informations erronnées du ticket.
    2. Aller à **2.** dans le scénario nominal.
    
    c. Le niveau d'urgence attribué au ticket n'est pas pertinent :
    1. L'administrateur web définit un niveau d'urgence au ticket.
    2. Aller à **2.** dans le scénario nominal.

2. a. L'administrateur web n'affecte pas le ticket :
    1. Un technicien s'auto-attribue le ticket.
    2. Aller à **3.** dans le scénario nominal.

3. a. Impossible de traiter la demande :
    1. Le ticket est clôturé sans résolution de problème.
    2. L'administrateur web consulte les journaux d'activités de tickets fermés pour comprendre l'impossibilité de traiter la demande.
***
#### Cas d'utilisation 2 : Affectation d'un ticket
**Nom :** Affecter un ticket
**Contexte d'utilisation :** L'administrateur web affecte un ticket à un technicien afin que sa demande soit traitée.
**Portée :** Système boîte blanche
**Niveau :** Utilisateur
**Acteur principal :** Administrateur Web
**Intervenants :** Technicien
**Précondition :** Un ticket n'est pas affecté.
**Garanties de succès :** Le ticket sera affecté à un technicien.
**Déclencheur :** Un ticket doit être attribué à un technicien pour être pris en charge.
**Scénario nominal :**
1. L'administrateur web choisit un technicien
2. Il lui affecte le ticket

**Extensions :**
1. a. Aucun technicien n'est enregistré :
    1. L'administrateur web crée un compte technicien.
    2. Aller à **2.** dans le scénario nominal.

   b. L'administrateur web n'est pas connecté :
    1. Un technicien s'affecte le ticket.
***
#### Cas d'utilisation 3 : Auto-attribution d'un ticket
**Nom :** Auto-attribution d'un ticket
**Contexte d'utilisation :** Un technicien s'attribue un ticket non affecté par l'administrateur web.
**Portée :** Système boîte blanche
**Niveau :** Utilisateur
**Acteur principal :** Technicien
**Précondition :** Un ticket n'est pas affecté.
**Garanties de succès :** Le ticket sera affecté à un technicien.
**Déclencheur :** Un ticket doit être attribué à un technicien pour être pris en charge.
**Scénario nominal :**
1. Le technicien s'affecte un ticket non attribué.
***
#### Cas d'utilisation 4 : Modification d'un ticket
**Nom :** Modifier un ticket
**Contexte d'utilisation :** Un ticket possède des informations erronnées ou obsolètes et doit être modifié par l'administrateur web.
**Portée :** Système boîte noire
**Niveau :** Utilisateur
**Acteur principal :** Administrateur Web
**Précondition :** Un ticket est ouvert.
**Garantie minimale :** Les informations initiales du ticket ne seront pas perdues en cas d'échec.
**Garanties de succès :** Le ticket sera modifié.
**Déclencheur :** Un ticket doit être modifié.
**Scénario nominal :**
1. L'administrateur web corrige les informations erronnées du ticket.

**Exceptions :**
1. a. Le ticket ne peut pas être modifié :
    1. Le ticket n'est pas modifié.
***
#### Cas d'utilisation 5 : Ouverture d'un ticket
**Nom :** Créer un ticket
**Contexte d'utilisation :** Un utilisateur connecté fait une demande de dépannage sous la forme d'un ticket auquel il attribue un libellé pour déterminer la nature de son problème et un niveau d'urgence qu'il estime.
**Portée :** Organisation boîte blanche
**Niveau :** Utilisateur
**Acteur principal :** Utilisateur
**Intervenants :** Administrateur Web
**Précondition :** L'utilisateur est connecté.
**Garanties de succès :** Un ticket sera ouvert.
**Déclencheur :** L'utilisateur fait une demande de dépannage.
**Scénario nominal :**
1. L'utilisateur choisit un libellé à attribuer au ticket.
2. L'utilisateur estime un niveau d'urgence parmi ceux existant.
3. Le ticket est créé suivant les informations données.
4. Un journal d'activité de création du ticket est enregistré.

**Extensions :**
1. a. Aucun libellé n'existe
    1. L'utilisateur choisit l'option 'Autre' et détaille lui-même un libellé.
    2. Aller à **2.** dans le scénario nominal.
   
    b. Aucun libellé ne correspond au problème formulé par l'utilisateur.
    1. L'utilisateur choisit l'option 'Autre' et détaille lui-même un libellé.
    2. Aller à **2.** dans le scénario nominal.


4. a. Le journal d'activité n'est pas enregistré :
    1. Un message en informe l'administrateur système.

**Exceptions :**
3. a. Le ticket n'a pas pu être créé :
    1. Un message d'erreur en informe l'utilisateur.
***
#### Cas d'utilisation 6 : Enregistrement d'un journal d'activité de ticket fermé
**Nom :** Enregistrer journal d'activité de création de ticket
**Contexte d'utilisation :** L'action d'ouverture d'un ticket est enregistrée dans un journal d'activité.
**Portée :** Sous-système
**Niveau :** Sous-fonction
**Acteur principal :** Système
**Précondition :** Il n'y a pas eu de problème lors de la création du ticket.
**Garanties de succès :** Un journal d'activité de création de ticket est enregistré.
**Déclencheur :** Un ticket a été créé.
**Scénario nominal :**
1. Un journal d'activité est créé.
2. La date de création, l’adresse IP du créateur, le login d’utilisateur qui a formulé le ticket et son niveau d’urgence attribué sont attribués au journal.
3. Le journal d'activité est enregistré.

**Extensions :**
2. a. Les informations sont incomplètes ou erronnées :
    1. Le journal mets une valeur ERREUR sur les informations erronnées.
    2. Aller à **3.** dans le scénario nominal.

**Exceptions :**
3. a. Le journal n'est pas enregistré :
    1. Un message en informe l'administrateur système.
***
#### Cas d'utilisation 7 : Fermeture d'un ticket
**Nom :** Fermer un ticket
**Contexte d'utilisation :** Un technicien finit de traiter la demande formulée par le ticket et le ferme. 
**Portée :** Sous-système
**Niveau :** Utilisateur
**Acteur principal :** Technicien
**Intervenants :** Administrateur Web
**Précondition :** Un ticket n'a plus de raisons d'être ouvert.
**Garantie minimale :** Le ticket ne sera plus affiché.
**Garanties de succès :** Le ticket sera fermé.
**Déclencheur :** Un ticket est clôturé.
**Scénario nominal :**
1. Un technicien ferme le ticket.
2. L'état du ticket est passé à 'fermé'.
3. Le ticket est enregistré dans un historique.

**Extensions :**
1. a. Pour une raison extérieure, l'administrateur web doit fermer le ticket :
    1. L'administrateur web ferme le ticket.
    2. Aller à **2.** dans le scénario nominal.


3. a. Le ticket n'est pas enregistré dans l'historique :
    1. Un message en informe l'administrateur système.
***
#### Cas d'utilisation 8 : Enregistrement d'un ticket fermé
**Nom :** Enregistrer le ticket fermé dans l'historique
**Contexte d'utilisation :** Un ticket fermé doit être enregistré par le système dans un historique à des fins de statistiques.
**Portée :** Sous-système
**Niveau :** Sous-fonction
**Acteur principal :** Système
**Précondition :** Un ticket a été clôturé.
**Garanties de succès :** Le ticket sera enregistré dans un historique.
**Déclencheur :** Un ticket est clôturé.
**Scénario nominal :**
1. Il est enregistré dans un historique.

**Exceptions :**
1. a. Le ticket n'a pas pu être enregistré :
    1. Un message en informe l'administrateur système.
***
#### Cas d'utilisation 9 : Consulter le journal d'activité de tickets ouverts
**Nom :** Consulter les jounaux d'activité de création de tickets
**Contexte d'utilisation :**  L'administrateur système consulte le journal d'activité de création de tickets.
**Portée :** Système boîte noire
**Niveau :** Utilisateur 
**Acteur principal :** Administrateur système
**Garantie minimale :** Les journaux d'activités de création de tickets ne sont pas divulgués.
**Garantie en cas succès :** Les journaux d'activités de création de tickets sont affichés.
**Déclencheur :** L'administrateur système souhaite consulter un journal d'activité de création de tickets.
**Scénario nominal :**
1. Les journaux d'activité de création de tickets sont affichés sur la page de l'administrateur système.

**Extensions :**
1. a. Aucun journal d'activité de création de ticket n'est enregistré :
    1. Un tableau contenant un message qui en informe l'administrateur système est affiché.
***
#### Cas d'utilisation 10 : Consultation des tickets
**Nom :** Consulter les tickets
**Contexte d'utilisation :** Un utilisateur souhaite consulter des tickets ouverts.
**Portée :** Système boîte noire
**Niveau :** Stratégique 
**Acteur principal :** Utilisateur
**Intervenants :** Visiteur, Administrateur web, Technicien
**Garantie en cas de succès :** Afficher les tickets visibles par l'utilisateur.
**Déclencheur :** Évènement extérieur poussant à visiter la plateforme.
**Scénario nominal :**
1. Un utilisateur souhaite consulter les tickets.
2. Un tableau de bord affiche les tickets de l'utilisateur.

**Extensions :**
1. a. L'utilisateur n'est pas connecté :
    1. Les 10 derniers tickets sont affichés.

    b. L'utilisateur est connecté sur un compte technicien ou administrateur web :
    1. Tous les tickets ouverts sont affichés.
2. a. Aucun ticket disponible pour l'utilisateur n'existe :
    1. Un tableau contenant un message qui en informe l'utilisateur est affiché.

**Exceptions :**
2. a. Impossible de récupérer les tickets :
    1. Un tableau contenant un message qui en informe l'utilisateur est affiché.
***
#### Cas d'utilisation 11 : 10 derniers tickets
**Nom :** Visualiser 10 derniers tickets
**Contexte d'utilisation :** Le visiteur a besoin de visualiser les 10 derniers tickets formulés encore non résolus.
**Portée :** Système boîte noire
**Niveau :** Utilisateur 
**Acteur principal :** Visiteur
**Précondition :** L'utilisateur ne doit pas être connecté.
**Garantie minimale :** Seuls les 10 derniers tickets ouverts peuvent être affichés. Aucun ticket fermé ne sera affiché.
**Garantie en cas de succès :** Afficher les 10 derniers tickets ouverts.
**Déclencheur :** On souhaite consultes les tickets sur la page d'accueil.
**Scénario nominal :**
1. Les 10 derniers tickets ouverts sont affichés sur un tableau dans la page d'accueil des utilisateurs non connectés.

**Extensions :**
1. a. Aucun ticket ouvert ou en cours de traitement n'existe :
    1. Un tableau contenant un message qui en informe l'utilisateur est affiché.

    b. Il y a moins de 10 tickets ouverts :
    1. Le tableau affiche des rangées vides là où il manque des tickets.

**Exceptions :**
1. a. Impossible de récupérer les tickets :
    1. Un tableau contenant un message qui en informe l'utilisateur est affiché.
***
#### Cas d'utilisation 12 : Affichage de tous les tickets ouverts
**Nom :** Consulter tous les tickets ouverts
**Contexte d'utilisation :** L'administrateur web et les techniciens doivent pouvoir voir tous les tickets ouverts.
**Portée :** Système boîte noire
**Niveau :** Utilisateur 
**Acteur principal :** Administrateur web / Technicien
**Précondition :** Personne connectée sur un compte d'administrateur web ou de technicien.
**Garantie minimale :** Aucun ticket fermé ne sera affiché.
**Garantie en cas de succès :** Afficher tous les tickets ouverts et en cours de traitement.
**Déclencheur :** Connexion en tant que technicien ou administrateur web.
**Scénario nominal :**
1. Tous les tickets ouverts et en cours de traitement sont affichés dans un grand tableau de bord.

**Extensions :**
1. a. Aucun ticket ouvert ou en cours de traitement n'existe :
    1. Un tableau contenant un message qui en informe l'utilisateur est affiché.

**Exceptions :**
1. a. Impossible de récupérer les tickets :
    1. Un tableau contenant un message qui en informe l'utilisateur est affiché.
***
#### Cas d'utilisation 13 : Tableau de Bord
**Nom :** Consulter son tableau de bord
**Contexte d'utilisation :** L'utilisateur connecté doit pouvoir visualiser tous les tickets qui le concernent dans un tableau de bord.
**Portée :** Système boîte noire
**Niveau :** Utilisateur 
**Acteur principal :** Utilisateur
**Précondition :** Personne connectée sur un compte inscrit.
**Garantie minimale :** Seuls les tickets ouverts concernant la personne connectée seront visibles.
**Garantie en cas de succès :** Afficher les tickets ouverts qui concernent l'utilisateur connecté.
**Déclencheur :** Connexion à un compte inscrit sur la plateforme.
**Scénario nominal :**
1. Tous les tickets ouverts ou en cours de traitement qui concernent l'utilisateur (soit ayant été formulés par lui ou ceux qui l'ont en personne concernée) sont affichés dans un tableau de bord.

**Extensions :**
1. a. L'utilisateur ne possède aucun ticket le concernant :
    1. Un tableau contenant un message qui en informe l'utilisateur est affiché.

**Exceptions :**
1. a. Impossible de récupérer les tickets :
    1. Un tableau contenant un message qui en informe l'utilisateur est affiché.
***
#### Cas d'utilisation 14 : Gestion du profil d'un compte inscrit
**Nom :** Gérer son profil
**Contexte d'utilisation :** Un utilisateur possédant un compte inscrit souhaite consulter ses informations, changer et/ou récupérer son mot de passe.
**Portée :** Système boîte blanche
**Niveau :** Stratégique 
**Acteur principal :** Utilisateur
**Intervenants :** IUT
**Précondition :** Personne possédant un compte inscrit.
**Garantie minimale :** Les données seront privées et le mot de passe masqué.
**Déclencheur :** Besoin d'accéder aux informations personnelles du compte.
**Scénario nominal :**
1. L'utilisateur se connecte à son compte.
2. Il consulte les informations de son profil.

**Extensions :**
1. a. L'utilisateur n'arrive pas à se connecter :
    1. L'utilisateur fait une demande de récupération de mot de passe.

2. a. L'utilisateur souhaite modifier son mot de passe :
    1. L'utilisateur entre un nouveau mot de passe.
    2. Le mot de passe du compte est modifié.

**Exceptions :**
2. a. L'utilisateur n'a pas accès à ses informations :
    1. Un message d'erreur est affiché pour l'utilisateur.
***
#### Cas d'utilisation 15 : Récupérer mot de passe
**Nom :** Récupérer mot de passe\
**Contexte d'utilisation :** Un utilisateur cherche à faire une demande de récupération de mot de passe.\
**Portée :** Sous-système\
**Niveau :** Utilisateur\
**Acteur principal :** Utilisateur\
**Intervenants :** IUT\
**Précondition :** Utilisateur sur le site.\
**Garantie minimale :** Son mot de passe ne sera pas divulgué à des personnes extérieures.\
**Déclencheur :** Demande de récuperation de mot de passe.\
**Scénario nominal :**
1. Affichage d'une page en maintenance
***
#### Cas d'utilisation 16 : Consulter son profil
**Nom :** Consulter son profil
**Contexte d'utilisation :** Un utilisateur consulte les informations de son profil.
**Portée :** Système boîte blanche
**Niveau :** Utilisateur 
**Acteur principal :** Utilisateur
**Intervenants :** IUT
**Précondition :** Personne connectée au site avec succès.
**Garantie minimale :** Les informations de l'utilisateur ne sont visibles que par lui.
**Garantie en cas de succès :** Afficher les informations de l'utilisateur.
**Déclencheur :** Utilisateur cherche informations sur son profil.
**Scénario nominal :**
1. Recoit une demande d'accés à son profil
2. Accéde à la base de données 
3. Comparaison les données de l'utilisateur
4. Affiche les informations de l'utilisateur

**Exceptions :**
2. a. Impossible d'accéder à la base de données :
    1. Afficher une erreur. (ECHEC)
3. a. Données utilisateur introuvables :
    1.Deconnexion du compte 
***
#### Cas d'utilisation 17 : Changement du mot de passe
**Nom :** Changer mot de passe
**Contexte d'utilisation :** Un utilisateur cherche à changer son mot de passe.
**Portée :** Système boîte blanche
**Niveau :** Utilisateur 
**Acteur principal :** Utilisateur
**Intervenants :** IUT
**Précondition :** Utilisateur inscrit.
**Garantie minimale :** Son mot de passe n'est pas divulgué à des personnes extérieures.
**Garantie en cas de succès :** Le mot de passe de l'utilisateur est modifié.
**Déclencheur :** Réception d'une demande d'un changement de mot de passe.
**Scénario nominal :**
1. Réception du formulaire
2. Accède à la base de données 
3. Vérification des informations du formulaire avec celles contenues dans la base de données
4. Changement du mot de passe entre l'ancien et le nouveau dans la base de données

**Exceptions :**
1. a. L'utilisateur n'a pas remplit tous les champs obligatoires du formulaire :
    1. Envoi d'un message d'échec de changement de mot de passe (ECHEC)
2. a. Impossible d'accéder à la base de données :
    1. Envoi d'un message d'échec de changement de mot de passe (ECHEC)
3. a. Le login de l'utilisateur n'est pas dans la base de données :
    1. Déconnexion de la session
3. b. Le mot de passe renseigné n'est pas validé :
    1. Envoi d'un message d'échec de changement de mot de passe (ECHEC)
***
#### Cas d'utilisation 18 : Administration
**Nom :** Administration
**Contexte d'utilisation :** La plateforme doit permettre aux administrateurs de pouvoir gérer les éléments extérieurs aux tickets.
**Portée :** Organisation boîte noire
**Niveau :** Stratégique
**Acteur principal :** Administrateur web
**Intervenants :** IUT, Administrateur système
**Garantie en cas de succès :** Mise à jour des métadonnées (comptes techniciens et/ou libellés).
**Déclencheur :** Besoin de mettre à jour les métadonnées de la plateforme.
**Scénario nominal :**
1. L'administrateur web accède aux métadonnées
2. Les métadonnées sont modifiées par l'administrateur

**Exceptions :**
1. a. Impossible d'accéder à la base de données :
   1. Envoi d'un message d'échec de chargement des métadonnées (ECHEC)
2. a. Erreur lors de la modification :
   1. Envoi d'un message d'échec de modification des métadonnées (ECHEC)
***
#### Cas d'utilisation 19 : Gérer les utilisateurs
**Nom :** Gerer les utilisateurs
**Contexte d'utilisation :** La plateforme doit permettre aux administrateurs de gérer les comptes utilisateur.
**Portée :** Organisation boîte blanche
**Niveau :** Stratégique
**Acteur principal :** Administrateur web
**Intervenants :** IUT
**Précondition :** L'administrateur web est connecté
**Garantie en cas de succès :** Création d'un compte utilisateur
**Déclencheur :** Réception d'un formulaire de création d'utilisateur
**Scénario nominal :**
1. Réception du formulaire
2. Accès à la base de données
3. Création du nouveau compte utilisateur

**Exceptions :**
1. a. Les champs obligatoires du formulaire n'ont pas tous été remplis:
   1. Envoi d'un message d'échec de création du compte utilisateur (ECHEC)
2. a. Impossible d'accéder à la base de données :
   1. Envoi d'un message d'échec de création du compte utilisateur (ECHEC)
3. a. Erreur lors de la création du compte :
   1. Envoi d'un message d'échec de création du compte utilisateur (ECHEC)
***
#### Cas d'utilisation 20 : Inscription
**Nom :** S'inscrire
**Contexte d'utilisation :** On utilise les données renseignées dans un formulaire d'inscription pour créer un nouveau compte sur la plateforme.
**Portée :** Système boîte noire
**Niveau :** Utilisateur
**Acteur principal :** Utilisateur
**Intervenants :** IUT
**Précondition :** Utilisateur non inscrit
**Garantie minimale :** Les données seront privées et le mot de passe encrypté
**Garantie en cas de succès :** Création d'un compte utilisateur dans la base de données
**Déclencheur :** Récéption d'un formulaire d'inscription remplit par l'utilisateur
**Scénario nominal :**
1. Réception d'un formulaire d'inscription
2. Encryptage du mot de passe
3. Insertion des données du formulaire dans la base de données => Création d'un nouveau compte sur la plateforme
4. Envoi d'un message de confirmation d'inscription à l'utilisateur

**Exceptions :**
1. a. L'utilisateur n'a pas remplit tous les champs obligatoires du formulaire :
    1. Envoi d'un message d'échec de création de compte à l'utilisateur (ECHEC)


3. a. Le login de l'utilisateur est déjà présent dans la base de données :
    1. Envoi d'un message d'échec de création de compte à l'utilisateur (ECHEC)
    
    b. Impossible d'accéder à la base de données :
    1. Envoi d'un message d'échec de création de compte à l'utilisateur (ECHEC)
***
#### Cas d'utilisation 21 : Créer un compte technicien
**Nom :** Créer un compte technicien
**Contexte d'utilisation :**  L'administrateur web crée un compte technicien
**Portée :** Système boîte blanche
**Niveau :** Utilisateur 
**Acteur principal :** Administrateur web
**Intervenants :** IUT, Technicien
**Précondition :** Administrateur web est connecté.
**Garantie minimale :** Le compte n'est pas crée et les informations du compte ne sont pas divulguées.
**Garantie en cas succès :** Le compte technicien est crée.
**Déclencheur :** L'administrateur crée un compte technicien.
**Scénario nominal :**
1. Réception d'un formulaire d'inscription
2. Encryptage du mot de passe
3. Insertion des données du formulaire dans la base de données => Création d'un nouveau compte technicien sur la plateforme
4. Envoi d'un message de confirmation d'inscription à l'utilisateur

**Exceptions :**
1. a. L'utilisateur n'a pas remplit tous les champs obligatoires du formulaire :
    1. Envoi d'un message d'échec de création du compte technicien (ECHEC)


3. a. Le login de l'utilisateur est déjà présent dans la base de données :
    1. Envoi d'un message d'échec de création du compte technicien  (ECHEC)
    
    b. Impossible d'accéder à la base de données :
    1. Envoi d'un message d'échec de création du compte technicien (ECHEC)
***
#### Cas d'utilisation 22 : Consulter les journaux d'activité de connexion ratée
**Nom :** Consulter les jounaux d'activité de création de connexion ratée
**Contexte d'utilisation :** L'administrateur système consulte le journal d'activité de connexion ratée.
**Portée :** Sous-système
**Niveau :** Utilisateur
**Acteur principal :** Administrateur système
**Garantie minimale :** Les journaux d'activités de connexion ratée ne sont pas divulgués.
**Garantie en cas succès :** Les journaux d'activités de connexion ratée sont affichés.
**Déclencheur :** L'administrateur système souhaite consulter un journal d'activité de connexion ratée.
**Scénario nominal :**
1. Les journaux d'activité de connexion ratée sont affichés sur la page de l'administrateur système.

**Extensions :**
1. a. Aucun journal d'activité de connexion ratée n'est enregistré :
   1. Un tableau contenant un message qui en informe l'administrateur système est affiché.
***
#### Cas d'utilisation 23 : Gérer les libellés
**Nom :** Gérer les libellés
**Contexte d'utilisation :**  Manipuler la base de données des libellés
**Portée :** Système boîte noire
**Niveau :** Stratégique 
**Acteur principal :** Administrateur web
**Intervenants :** IUT
**Précondition :** Administrateur web est connecté.
**Garantie en cas succès :** Possibilité d'une action sur les libellés
**Déclencheur :** Demande d'action sur les libellés
**Scénario nominal :**
1. Récupére la demande d'action sur le libellé
2. Accéder à la base de données
3. Gérer la demande en fonction de l'action demandée

**Exceptions :**
2. a. Impossible d'accéder à la base de données :
    1. Afficher une erreur. (ECHEC)
***
#### Cas d'utilisation 24 : Créer un libellé
**Nom :** Créer un libellé
**Contexte d'utilisation :**  Ajout d'un nouveau libellé
**Portée :** Système boîte blanche
**Niveau :** Utilisateur
**Acteur principal :** Administrateur web
**Intervenants :**  IUT
**Précondition :** Administrateur web est connecté à la base de données
**Garantie minimales :** Le libellé n'est pas enregisté
**Garantie en cas succès :** Un nouveau libellé est créé
**Déclencheur :** Demande d'ajout de libellé
**Scénario nominal :**
1. Saisir les informations du libellé
2. Insertion du libellé dans la base de données

**Exceptions :**

1. a. Les informations du libéllé ne sont pas completes :
    1. Envoi d'un message d'échec de création de libellé (ECHEC)
2. a. Le libellé est déjà existant :
    1. Envoi d'un message d'échec de création de libellé (ECHEC)

    b. Le libellé n'a pas pu être inséré :
    1. Envoi d’un message d’échec de création de libellé (ECHEC)
***
### Cas d'utilisation 25 : Archiver un libéllé
**Nom :** Archiver un libellé
**Contexte d'utilisation :**  Mise en archive d'un libellé
**Portée :** Sous-système
**Niveau :** Utilisateur
**Acteur principal :** Administrateur web
**Intervenants :**  IUT
**Précondition :** Administrateur web est connecté à la base de données
**Garantie minimales :** Le libellé n'est pas supprimé
**Garantie en cas succès :** Le libellé est supprimé
**Déclencheur :** Demande de suppression d'un libellé
**Scénario nominal :**
1. Saisir le libellé supprimé
2. Suppression du libellé

**Extensions :**

2. a. Le libellé possède des sous-libellés
    1. Suppression de ses sous-libellés

**Exceptions :**

1. a. Les informations du libéllé supprimé ne sont pas complètes :
    1. Envoi d'un message d'échec de suppression de libellé (ECHEC)
2. a. Le libellé n'existe pas :
    1. Envoi d'un message d'échec de suppression de libellé (ECHEC)

    b. Le libellé n'a pas pu être supprimé :
    1. Envoi d’un message d’échec de suppression de libellé (ECHEC)
***
### Cas d'utilisation 26 : Modifier l'intitulé d'un libéllé
**Nom :** Modifier intitulé d'un libellé
**Contexte d'utilisation :**  Modification d'un libellé
**Portée :** Système boîte blanche
**Niveau :** Utilisateur
**Acteur principal :** Administrateur web
**Intervenants :**  IUT
**Précondition :** Administrateur web est connecté à la base de données
**Garantie minimales :** Le libellé n'est pas modifié
**Garantie en cas succès :** Le libellé est modifié
**Déclencheur :** Demande de modification d'un libellé
**Scénario nominal :**
1. Saisir les informations du libellé modifiés
2. Modification du libellé dans la base de données

**Exceptions :**

1. a. Les informations de modification du libellé ne sont pas complètes :
    1. Envoi d'un message d'échec de suppression de libellé (ECHEC)
2. a. Le libellé n'existe pas :
    1. Envoi d'un message d'échec de suppression de libellé (ECHEC)

    b. Le libellé n'a pas pu être modifié :
    1. Envoi d’un message d’échec de suppression de libellé (ECHEC)
***
### Cas d'utilisation 27 : Définir un libéllé supérieur
**Nom :** Définir un libéllé supérieur
**Contexte d'utilisation :**  Affecter le libellé comme étant un sous-libellé d'un libellé
**Portée :** Système boîte blanche
**Niveau :** Utilisateur
**Acteur principal :** Administrateur web
**Intervenants :**  IUT
**Précondition :** Administrateur web est connecté à la base de données
**Garantie minimales :** Les libellés ne sont pas affectés
**Garantie en cas succès :** Le libellé est affecté à son supérieur
**Déclencheur :** Demande d'affectation d'un sous-libellé
**Scénario nominal :**
1. Saisir les informations du libellé affecter
2. Modifier le libellé comme étant sous-libellé d'un supérieur donné

**Exceptions :**

1. a. Les informations données ne sont pas complètes :
    1. Envoi d'un message d'échec de suppression de libellé (ECHEC)
2. a. Un des libellés n'existe pas :
    1. Envoi d'un message d'échec de suppression de libellé (ECHEC)

    b. Le libellé n'a pas pu être affecté :
    1. Envoi d’un message d’échec de suppression de libellé (ECHEC)
***
#### Cas d'utilisation 28 : Page d'accueil
**Nom :** Consulter page d'accueil
**Contexte d'utilisation :** Une personne accédant au site doit arriver sur une page d'accueil.
**Portée :** Système boîte noire
**Niveau :** Utilisateur 
**Acteur principal :** Utilisateur
**Intervenants :** IUT
**Précondition :** Personne connectée connectée au site avec succès.
**Garantie minimale :** Affichage d'une explication sur le but du site, d'une vidéo de présentation et des 10 derniers tickets. Ainsi qu'un accès à un formulaire de connexion et d'inscription.
**Déclencheur :** Personne consultant la plateforme.
**Scénario nominal :**
1. Afficher le contenu statique de la page
2. Accéder à la base de données
3. Afficher les 10 derniers tickets

**Extensions :**
3. a. Aucun ticket n'est ouvert :
    1. Afficher un message pour en informer l'utilisateur
***
#### Cas d'utilisation 29 : Authentification
**Nom :** Authentification
**Contexte d'utilisation :** Un utilisateur souhaite passer d'un état connecté/déconnecté à l'autre
**Portée :** Sous-système
**Niveau :** Sous-fonction 
**Acteur principal :** Système
**Intervenants :** Utilisateur, IUT
**Précondition :** Utilisateur inscrit
**Garantie minimale :** Les données seront privées et le mot de passe encrypté
**Garantie en cas de succès :** Déconnexion/Connexion au compte de l'utilisateur
**Déclencheur :** Réception d'une demande d'authentification
**Scénario nominal CONNEXION :**
1. Réception d'une formulaire de connexion
2. Accéder à la base de données
3. Vérification des informations du formulaire avec celles contenues dans la base de données
4. Connexion au compte utilisateur

**Exceptions CONNEXION :**
1. a. L'utilisateur n'a pas remplit tous les champs obligatoires du formulaire :
    1. Envoi d'un message d'échec de connexion à l'utilisateur (ECHEC)
    2. Enregistrement d'un journal de connexion infructueuse
2. a. Impossible d'accéder à la base de données :
    1. Envoi d'un message d'échec de connexion à l'utilisateur (ECHEC)
    2. Enregistrement d'un journal de connexion infructueuse
3. a. Le login de l'utilisateur n'est pas dans la base de données :
    1. Envoi d'un message d'échec de connexion à l'utilisateur (ECHEC)
    2. Enregistrement d'un journal de connexion infructueuse

    b. Le mot de passe renseigné n'est pas validé :
    1. Envoi d'un message d'échec de connexion à l'utilisateur (ECHEC)
    2. Enregistrement d'un journal de connexion infructueuse
4. a. Échec de la connexion :
    1. Envoi d'un message d'échec de connexion à l'utilisateur (ECHEC)
    2. Enregistrement d'un journal de connexion infructueuse

**Scénario nominal DÉCONNEXION :**
1. Réception d'une demande de déconnexion
2. Déconnexion du compte utilisateur
3. Envoi d'un message de confirmation de déconnexion de l'utilisateur

**Exceptions DÉCONNEXION :**
2. a. Échec de la déconnexion
    1. Envoi d'un message d'échec de déconnexion à l'utilisateur (ECHEC)
***
#### Cas d'utilisation 30 : Journal d'activité de connexion infructueuse
**Nom :** Enregistrer journal d'activité de connexion ratée
**Contexte d'utilisation :** Enregistrement dans un log d'une connexion infructueuse
**Portée :** Sous-système
**Niveau :** Sous-fonction 
**Acteur principal :** Système
**Intervenants :** Utilisateur, IUT
**Précondition :** Un test de connexion est raté
**Garantie en cas de succès :** Enregistrement d'un journal d'activité de connexion infructueuse
**Déclencheur :** Connexion échouée
**Scénario nominal :**
1. Réception d'une connexion échouée
2. Création d'un journal d'activité de connexion infructueuse
3. Enregistrement du journal

**Exceptions :**
2. a. Échec de la création d'un journal d'activité
    1. L'information n'est pas stockée (ECHEC)
***
#### Cas d'utilisation 31 : Aller sur le site web
**Nom :** Aller sur le site web
**Contexte d'utilisation :** On assure la connexion au site web.
**Portée** : Sous-système
**Niveau** : Sous-fonction
**Garantie en cas de succès :** Connexion au site web.
***

## <a id="part5"></a>Intégration

### Introduction

Il nous est demandé dans le cahier des charges d’intégrer notre site sur un Raspberry Pi 4b fourni par le client. Ainsi y doivent être centralisés la base de données, le serveur web et les fichiers source. Naturellement, nous avons pensé à utiliser une solution contenairisée : Docker / Podman, NixOS… Finalement, nous avons opté pour une solution proposée directement par Raspberry sur GitHub, `pi-gen`.

### Pi-Gen

Pi-Gen est un outil de génération d’image Linux et plus précisément RaspOS, la distribution de Raspberry basée sur Debian. L’outil assemble des stages Linux du *bootstrap* à l'environnement graphique complet. Ces stages sont représentés sous forme de dossiers avec des scripts et des listes de paquets à installer exécutables sur l’hôte et l’invité. En effet, après le *bootstrap* initial, l'environnement RaspOS est *chrootable* c’est-à-dire qu’on peut émuler ses composants “à froid”, sans *boot*. La construction est donc entièrement personnalisable et nous permet de produire une image sur-mesure sans intervention manuelle.

Notre image se limite au stage 2 qui équivaut à une installation *lite* ou minimale en ligne de commande. Nous avons ajouté une étape au stage 2 en ajoutant un sous-dossier au dossier du stage avec à l’intérieur de quoi installer entre autres mariadb (mysql), php et apache2 et un script qui ajoute l’utilisateur "p6nj", lui permet un *sudo* sans mot de passe, clone la repo de Macrosoft en exécutant le script d’installation qui y est contenu et configure la connexion automatique en local.
Un snapshot de la repo dans laquelle cette configuration a eu lieu vous sera fournie en zip.

Ce processus pose tout-de-même un problème ; même si la portabilité de l’outil est garanti par un script passant par Docker (install-docker.sh), la génération prend au moins 30 minutes pendants lesquelles on ne peut ni toucher aux scripts ni éteindre la machine sur laquelle le script tourne. Il faut aussi une bonne puissance et une bonne connexion réseau. Nous avons donc pensé à déléguer cette tâche à un serveur en ligne, et nous sommes partis sur une *GitHub Action*.

### Github Actions

Une action GitHub est un fichier en YML décrivant une procédure à effectuer en rapport avec le projet GitHub sur laquelle elle est exécutée. Cela peut servir à compiler du code, effectuer des tests avant un commit ou un merge, etc. Nous avons ainsi détaillé la génération de l’image customisée par une machine virtuelle sous Ubuntu à réaliser à chaque commit. Cette image est ensuite mise en ligne dans une release avec une description informative.
La génération a lieu sur un "runner" public de GitHub (image Docker sur un serveur) ; toute erreur est traçable avec un log et notifiée par mail. Cela rend le tout plus pratique et portable tout en fournissant un accès constant aux différentes images qui sont lourdes à déplacer (>1Go compressées).

### Conclusion

Cette pile permet donc de générer des images prêtes à l’emploi de manière complètement automatique.
Ces images sont ensuite simplement gravées sur une carte SD (via dd) et insérées dans la Raspberry.
