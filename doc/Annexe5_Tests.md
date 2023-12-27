# Dossier de test
Version : 0.1

| Document                    | Dossier de test  |
| --------------------------- | ---------------- |
| Date                        | 21/10/2023       |
| Responsable de la rédaction | Baptiste Fournié |

## Dossier de tests

### Introduction
Ce document est un dossier de tests pour le projet de plateforme de ticketing interne. Les objectifs de chaque livrables sont:

* Les objectifs du premier livrable sont de faire une maquette des pages de l'application web et de vérifier si les pages HTML ressemblent aux maquettes graphiques. 
* Les objectifs du second livrable sont de créer la base de données pour l'application web et de vérifier si la base de données est opérationnelle (Les tests sont dans le dossier src/bd).
* Les objectifs du troisiéme livrable sont de faire les pages dynamiques de l'application web grâce aux maquettes du premier livrable, de faire une étude analytique sur les données de l'application web et de vérifier si les outils pour réaliser l'étude sont opérationnelles .

### Description de la procédure de test
Pour le premier livrable ,les tests seront des tests d'intégration visuels. Ils consisteront à comparer le visuel de la page web à la maquette graphique et de voir si elles sont identiques. Donc il n'y aura de partition.
Pour le second livrable , les tests de la base de données font partie intégrantes des différents fichiers SQL dans la section bd.
Pour le troisiéme  livrable , les tests seront des tests unitaires en utilisant la méthode des boites noires pour tout ce qui est du backend . Pour le fronted, le test sera un test d'intégration visuels.
### Description des informations à enregistrer pour les tests

1. Campagne de test
    
    1.1 Livrable 1

| Paramètre                          | Valeur                                               |
| ---------------------------------- |------------------------------------------------------|
| Produit testé                      | Site web                                             |
| Configuration logicielle           | Webstorm, Windows 10                                 |
| Configuration matérielle           |                                                      |
| Date de début                      | 18/09/2023                                           |
| Date de finalisation               | 27/10/2023                                           |
| Tests à appliquer                  | Test d'intégration visuel sur la page d'accueil.     |
|                                    | Test d'intégration visuel sur la page d'inscription. |
| Responsable de la campagne de test | Baptiste Fournié                                     |

    1.2 Livrable 3

| Paramètre                          | Valeur                                    |
| ---------------------------------- |-------------------------------------------|
| Produit testé                      | etude analytique                          |
| Configuration logicielle           | Rstudio, Windows 10                       |
| Configuration matérielle           |                                           |
| Date de début                      | 7/11/2023                                 |
| Date de finalisation               | 27/11/2023                                |
| Tests à appliquer                  |                                           |
|                                    |                                           |
| Responsable de la campagne de test | Baptiste Fournié, Lucas Da silva Ferreira |

2. Tests

     2.1 Livrable 1

| Paramètre              | Valeur                                           |
| ---------------------- | ------------------------------------------------ |
| Identification du test | Page d'accueil                                   |
| Description du test    | Test d'intégration visuel sur la page d'accueil. |
| Ressources Requises    |                                                  |
| Responsable            | Baptiste Fournié                                 |

On compare l'image de la maquette web de la page d'accueil à la page d'accueil statique du site web.
La maquette web est la suivante :

![](img/maqAccueilVisiteur.png)

| Paramètre              | Valeur                                               |
| ---------------------- | ---------------------------------------------------- |
| Identification du test | page accueil                                         |
| Description du test    | Test d'intégration visuel sur la page d'inscription. |
| Ressources Requises    |                                                      |
| Responsable            | Baptiste Fournié                                     |

On compare l'image de la maquette web de la page d'inscription à la page d'inscription statique du site web.
La maquette web est la suivante :

![](img/maqInscription.png)

     2.2 Livrable 3

3. Résultats de test

     3.1 Livrable 1

| Référence du test appliqué | Responsable      | Date de l'application du test | Résultat du test | Occurrences des résultats |
| -------------------------- | ---------------- | ----------------------------- | ---------------- | ------------------------- |
| Page d'accueil             | Baptiste Fournié | 22/10/2023                    | OK               | Systématique              |
| Page d'inscription         | Baptiste Fournié | 22/10/2023                    | OK               | Systématique              |

    3.2 Livrable 3

4. Conclusion

Les pages du site web ressemblent à leur maquette graphique. Le dossier tests sera mis à jour lors du 2ᵉ livrable.
