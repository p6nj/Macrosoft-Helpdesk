# Notice d'utilisation du site
## Site principal
Pour consulter le site actuel, il suffit d'ouvrir le fichier `home.html` dans un navigateur web. Ce fichier se situe dans le répertoire de code source du site, le dossier `src/site`.
Pour cela, il est possible de double-cliquer le fichier dans un explorateur de fichiers si le navigateur par défaut est configuré pour ouvrir les fichiers html. Sinon, la majorité de navigateurs supportent l'ouverture de fichier sur l'interface directement ; le plus simple est d'utiliser le raccourci clavier Ctrl+O (ou Cmd+O sur Mac) et de sélectionner le fichier `home.html`.
## Manuel :

### Matériel nécessaire : 
Carte SD + Raspberry + Clavier + Ecran + (Cable HDMI - micro HDMI) + Cable RJ45 (optionnel) + Alimentation 5V 3A

### Installation de la Raspberry : 
Après que tous les branchements soient effectués, dès que l’alimentation est branchée à la raspberry, elle s’allumera toute seule. Après le démarrage vous serez sur une invite de commande déjà connecté sous l’utilisateur `p6nj`, dès ce moment vous pourrez faire la configuration réseau via les commandes `ip`, ou l’utilitaire `nmtui`, ou bien pour du wireless via `iwconfig` ou les autres commandes en `iw-`. Référez-vous aux pages man pour plus d'informations. La raspberry sera en permanence accessible en ssh via le port `22` avec le login et le mot de passe qui sera fourni dans le fichier `mdp`. Pour la connexion sur cet utilisateur `sshpass` va nous permettre de nous connecter en `ssh` sans afficher ni connaitre le mot de passe. Avec la commande ci-dessous, la connexion sera possible sur cet utilisateur :
```sshpass -fmdp ssh p6nj@heaven.local```
Remplacez `mdp` par le chemin vers le fichier mdp et `.local` par le nom de domaine correspondant si la commande échoue.

## Autres documents
Les autres documents tels que le recueil de besoins sont accessibles depuis le répertoire `doc`.