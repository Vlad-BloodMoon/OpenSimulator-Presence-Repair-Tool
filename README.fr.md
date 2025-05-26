# Outil de Réparation de la Table Presence pour OpenSimulator

Ce petit utilitaire nettoie automatiquement les sessions fantômes dans OpenSimulator — particulièrement utile lorsque des utilisateurs plantent ou se déconnectent mal, et restent bloqués "en ligne" dans la base de données.

## 🛠️ Ce que fait le script

- Analyse la table `Presence` de la base `robust` pour repérer les entrées invalides (`RegionID = 00000000-0000-0000-0000-000000000000`)
- Supprime ces lignes erronées
- Trouve l’utilisateur correspondant dans `GridUser` (y compris les utilisateurs Hypergrid)
- Met à jour le champ `Online` à `False`

## 📂 Fichiers inclus

- `repair_ghosts.php` – Le script principal
- `config.php` – Paramètres de connexion à la base de données et chemin du fichier log
- `repair_ghosts.log` – Fichier journal automatiquement géré (100 lignes max)
- `.htaccess` – Empêche l’accès direct et le listing du dossier `/presence`

## ✅ Prérequis

- PHP 7.4 ou supérieur
- MySQL/MariaDB avec base `robust`
- Droits d’écriture dans le dossier du script
- (Facultatif) Serveur web si utilisation via HTTP

## ⚙️ Utilisation

### 1. Configurer votre base de données

Modifier `config.php` :

```php
return [
    'db_host' => 'localhost',
    'db_name' => 'robust',
    'db_user' => 'utilisateur_mysql',
    'db_pass' => 'mot_de_passe_mysql',
    'log_path' => __DIR__ . '/repair_ghosts.log'
];
2. Lancer manuellement :
bash
Copier
Modifier
php repair_ghosts.php
3. Automatiser avec cron (exemple toutes les heures) :
bash
Copier
Modifier
0 * * * * /usr/bin/php /chemin/vers/repair_ghosts.php >> /dev/null 2>&1
🔁 Rotation du log
Le script conserve uniquement les 100 dernières lignes dans repair_ghosts.log.

🔐 Sécurité
.htaccess empêche l’accès aux fichiers depuis un navigateur.

🧡 Forkez, modifiez, partagez — c’est un outil simple mais utile pour toute grille OpenSim.
