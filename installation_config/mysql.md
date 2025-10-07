# Configuration MySQL/MariaDB pour Laravel

## 1. Installer MariaDB/MySQL et l’extension PHP

### Sous Debian/Ubuntu (MariaDB recommandé)
```bash
sudo apt update
sudo apt install -y mariadb-server mariadb-client php-mysql
sudo systemctl enable --now mariadb
sudo mysql_secure_installation
```

---

## 2. Créer la base de données et l’utilisateur

```bash
sudo mysql -u root -p
```
Puis dans le shell MariaDB :
```sql
CREATE DATABASE plant_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER '<utilisateur>'@'localhost' IDENTIFIED BY '<mot_de_passe>';
GRANT ALL PRIVILEGES ON plant_manager.* TO '<utilisateur>'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```
Remplace `<utilisateur>` et `<mot_de_passe>` par tes valeurs.

---

## 3. Configurer Laravel

Ouvre le fichier `.env` à la racine de ton projet et adapte :

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=plant_manager
DB_USERNAME=<utilisateur>
DB_PASSWORD=<mot_de_passe>
SESSION_DRIVER=file
```

---

## 4. Vérifier l’extension PHP

```bash
php -m | grep mysql
```
Si rien ne s’affiche :
```bash
sudo apt install php-mysql
sudo systemctl restart apache2 # ou php-fpm selon ton serveur
```

---

## 5. Tester la connexion

### a. En ligne de commande
```bash
mysql -u <utilisateur> -p plant_manager
```
Tu dois accéder au prompt MariaDB/MySQL sans erreur.

### b. Depuis Laravel
```bash
php artisan tinker
```
Puis dans tinker :
```php
DB::connection()->getPdo();
```
Aucune erreur = connexion OK.

---

## 6. Lancer les migrations Laravel

```bash
php artisan migrate
```
Cela crée les tables nécessaires dans la base.

---

## 7. Conseils de sécurité

- Ne partage jamais ton fichier `.env` ou tes identifiants en clair.
- Utilise un mot de passe fort pour l’utilisateur MySQL/MariaDB.
- Limite l’accès à `localhost` (déjà fait dans les commandes ci-dessus).

---

**Tu es prêt à utiliser Laravel avec MySQL/MariaDB !**