# SunuAuto

Dans le cadre de notre formation universitaire, nous avons développé une application web
complète pour une agence de location de véhicules. Le cahier des charges défini par notre
professeur nous imposait de créer un système permettant la gestion intégrale du processus de
location, depuis la consultation du catalogue jusqu'au suivi des réservations.

Le projet a été réalisé en utilisant les technologies suivantes :
- PHP pour la logique métier côté serveur
- MySQL pour la base de données relationnelle
- HTML et CSS pour la structure et le style des pages
- JavaScript pour les interactions dynamiques

Notre application devait répondre à des exigences fonctionnelles spécifiques pour trois types
d'utilisateurs :
- Gérants : gestion du parc automobile, suivi des réservations, administration des comptes clients
- Clients : réservation de véhicules, gestion des favoris, consultation de l'historique
- Visiteurs : consultation du catalogue, inscription au service

## Essayer l'application en local

Pour lancer SunuAuto sur votre propre machine, il vous faut PHP avec l'extension PDO MySQL, ainsi qu'un serveur MySQL. Vous pouvez installer ces outils séparément ou utiliser une solution tout-en-un comme [XAMPP](https://www.apachefriends.org/), qui inclut Apache, PHP et MySQL/MariaDB.

1. **Récupérez le projet** et ouvrez un terminal dans son dossier :
   ```bash
   git clone https://github.com/NoSeaBass/SunuAuto.git
   cd SunuAuto
   ```

2. **Démarrez MySQL** (et Apache si vous utilisez XAMPP), puis créez la base de données en important `src/sql/tables.sql` avec phpMyAdmin ou un client MySQL. Ce script crée la base `locationvoiture` et ses tables.

   > **Attention :** `tables.sql` commence par supprimer puis recréer la base `locationvoiture`. Ne l'exécutez pas si cette base contient des données à conserver.

3. **Ajoutez les données de démonstration** en important ensuite `src/sql/exemples.sql` dans la base `locationvoiture`. Cette étape est facultative, mais permet de tester l'application avec des voitures déjà renseignées.

4. **Vérifiez la connexion à la base de données** dans `src/fonctions/connexion.php`. Par défaut, l'application utilise MySQL sur `localhost`, la base `locationvoiture`, l'utilisateur `root` et un mot de passe vide. Adaptez ces valeurs à votre configuration MySQL si nécessaire.

5. **Lancez le serveur PHP intégré** depuis le dossier `src` :
   ```bash
   cd src
   php -S localhost:8000
   ```
   Vous pouvez aussi placer le dossier du projet dans le répertoire web de votre installation XAMPP, puis démarrer Apache et MySQL depuis le panneau de contrôle.

6. **Ouvrez l'application** dans votre navigateur à l'adresse [http://localhost:8000](http://localhost:8000) (ou via l'URL locale de votre serveur Apache, par exemple `http://localhost/SunuAuto/src/`).

Pour arrêter le serveur PHP intégré, revenez au terminal et faites `Ctrl+C`.
