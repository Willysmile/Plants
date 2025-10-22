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

## 7. Sécurité et assignation de masse

### À propos de `$fillable` dans les modèles Laravel

- **L’assignation de masse** permet de remplir plusieurs champs d’un modèle d’un coup, par exemple avec `Model::create($request->all())`.
- **Risque** : Si tu n’utilises pas `$fillable`, un utilisateur peut tenter d’injecter des champs non prévus (ex : `is_admin`).
- **Solution** : `$fillable` liste explicitement les champs autorisés à être remplis automatiquement.  
  Cela protège ton application et te permet d’utiliser l’assignation de masse en toute sécurité.

**Exemple dans `app/Models/Plant.php`** :
```php
protected $fillable = [
    'name', 'scientific_name', 'purchase_date', 'purchase_place', 'purchase_price',
    'category_id', 'description', 'watering_frequency', 'last_watering_date',
    'light_requirement', 'temperature_min', 'temperature_max', 'humidity_level',
    'soil_humidity', 'soil_ideal_ph', 'soil_type', 'info_url', 'main_photo',
    'location', 'pot_size', 'health_status', 'last_fertilizing_date',
    'fertilizing_frequency', 'last_repotting_date', 'next_repotting_date',
    'growth_speed', 'max_height', 'is_toxic', 'flowering_season', 'difficulty_level',
    'is_indoor', 'is_outdoor', 'is_favorite', 'is_archived', 'archived_date', 'archived_reason'
];
```

---

## 8. À propos de `HasFactory`

- `HasFactory` est un trait Laravel qui permet de générer facilement des données de test via les **factories**.
- Il est inclus par défaut dans chaque modèle généré.
- Il n’est pas obligatoire pour le fonctionnement de base, mais recommandé pour les tests et le développement.

**Exemple d’utilisation** :
```php
Plant::factory()->count(10)->create();
```

---

## 9. Conseils de sécurité

- Ne partage jamais ton fichier `.env` ou tes identifiants en clair.
- Utilise un mot de passe fort pour l’utilisateur MySQL/MariaDB.
- Limite l’accès à `localhost` (déjà fait dans les commandes ci-dessus).

---

**Tu es prêt à utiliser Laravel avec MySQL/MariaDB !**