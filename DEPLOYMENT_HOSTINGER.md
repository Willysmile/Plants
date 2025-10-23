# 🚀 Guide de Déploiement Plants Manager sur Hostinger

## Table des matières
1. [Prérequis](#prérequis)
2. [Préparation du projet](#préparation-du-projet)
3. [Configuration Hostinger](#configuration-hostinger)
4. [Déploiement](#déploiement)
5. [Post-déploiement](#post-déploiement)
6. [Dépannage](#dépannage)

---

## Prérequis

### Requis Hostinger
- **Plan**: Premium ou supérieur (PHP 8.1+, MySQL 5.7+)
- **PHP**: 8.1 ou 8.2
- **MySQL**: 5.7 ou 8.0
- **Extensions PHP**: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo
- **Accès SSH**: Activé

### Localement
- Git configuré avec SSH
- Composer installé
- Node.js & npm (si besoin de rebuilder assets)

---

## Préparation du Projet

### 1. Préparer le code pour la production

#### A. Nettoyer les fichiers temporaires
```bash
cd /home/willysmile/Documents/Plants/plant_manager

# Supprimer les fichiers temporaires
rm -rf storage/logs/*.log
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
```

#### B. Compiler les assets (optionnel si déjà fait)
```bash
npm run build
```

#### C. Vérifier l'environnement .env
```bash
cat .env
```

Le `.env` doit contenir:
```env
APP_NAME="Plants Manager"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com

LOG_CHANNEL=single

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=plants_db
DB_USERNAME=plants_user
DB_PASSWORD=mot_de_passe_fort

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=votremail@votre-domaine.com
MAIL_PASSWORD=mot_de_passe_email

SESSION_DRIVER=cookie
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

#### D. Générer la clé d'application
```bash
php artisan key:generate
```

#### E. Faire un commit final
```bash
git add .
git commit -m "feat: prepare for production deployment on Hostinger"
git push origin main
```

---

## Configuration Hostinger

### 1. Accéder à hPanel (Hostinger Control Panel)

#### A. SSH - Configurer les clés SSH
1. Aller à **hPanel** → **Avancé** → **Clés SSH**
2. Ajouter votre clé publique SSH
3. Noter votre serveur SSH: `ssh-XXX.hostinger.com` ou IP fournie

#### B. Créer une base de données MySQL
1. **Bases de données** → **Gérer**
2. **Créer une nouvelle base de données**
   - Nom: `plants_db`
   - Utilisateur: `plants_user`
   - Mot de passe: (générer un mot de passe fort)
3. Sauvegarder les identifiants

#### C. Créer un domaine parked (optionnel)
Si vous avez un domaine externe:
1. **Domaines** → **Gérer les domaines**
2. Ajouter votre domaine
3. Configurer DNS

### 2. Accéder au serveur via SSH

```bash
ssh -i ~/.ssh/id_rsa username@ssh-XXX.hostinger.com
```

### 3. Préparer le dossier public_html

```bash
# Aller dans le répertoire public_html
cd ~/public_html

# Supprimer le contenu par défaut (si existe)
rm -rf *

# Cloner le projet (option 1: avec SSH)
git clone git@github.com:Willysmile/Plants.git .

# Ou avec HTTPS (option 2)
git clone https://github.com/Willysmile/Plants.git .
```

### 4. Installer les dépendances

```bash
# Aller à la racine du projet
cd ~/public_html/plant_manager

# Installer Composer
curl -sS https://getcomposer.org/installer | php
php composer.phar install --no-dev --optimize-autoloader

# Ou si Composer est global
composer install --no-dev --optimize-autoloader
```

### 5. Configurer les permissions

```bash
# Donner les permissions appropriées
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs storage/framework

# Vérifier que le user est correct
ls -la storage/ | head -5
```

### 6. Configurer le .env sur le serveur

```bash
# Copier depuis l'existant
cp .env.example .env

# Éditer avec nano ou vim
nano .env
```

Définir les variables critiques:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com

DB_DATABASE=plants_db
DB_USERNAME=plants_user
DB_PASSWORD=<mot_de_passe_de_hostinger>

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
```

### 7. Générer la clé d'application

```bash
php artisan key:generate
```

### 8. Exécuter les migrations

```bash
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder --force
```

### 9. Optimiser pour la production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## Déploiement

### Option 1: Via Git (Recommandé)

#### A. Mettre à jour le projet
```bash
cd ~/public_html/plant_manager

git pull origin main

composer install --no-dev --optimize-autoloader --no-interaction

php artisan migrate --force
php artisan cache:clear
php artisan config:cache
php artisan route:cache
```

#### B. Automatiser avec Git Hooks
Créer un script de déploiement automatique:

```bash
# Sur le serveur, créer un hook
mkdir -p ~/.webhooks
nano ~/.webhooks/deploy.sh
```

Contenu de `deploy.sh`:
```bash
#!/bin/bash
cd ~/public_html/plant_manager

git pull origin main
composer install --no-dev --optimize-autoloader --no-interaction
php artisan migrate --force
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deployment completed at $(date)" >> ~/deploy.log
```

Donner les permissions:
```bash
chmod +x ~/.webhooks/deploy.sh
```

### Option 2: Via FTP (Moins recommandé)

1. Télécharger le projet compilé
2. Uploader via FTP en écrasant les anciens fichiers
3. Exécuter les commandes de migration

---

## Post-Déploiement

### 1. Vérifier le fonctionnement

```bash
# Vérifier les logs
tail -f storage/logs/laravel.log

# Tester l'application
curl https://votre-domaine.com
```

### 2. Configurer le HTTPS/SSL

1. **hPanel** → **SSL/TLS**
2. **Installer un certificat Autofirm AutoSSL** (généralement automatique)
3. Forcer HTTPS dans `.env`:
```env
APP_URL=https://votre-domaine.com
```

### 3. Configurer les emails (optionnel)

Si vous avez des fonctionnalités d'email:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=votremail@votre-domaine.com
MAIL_PASSWORD=<mot_de_passe_email>
MAIL_FROM_ADDRESS=noreply@votre-domaine.com
MAIL_FROM_NAME="Plants Manager"
```

### 4. Configurer les sauvegardes

```bash
# Créer un cron job pour les backups
crontab -e

# Ajouter:
0 2 * * * ~/public_html/plant_manager/backup.sh
```

### 5. Tester les fonctionnalités critiques

- ✅ Accès à l'application
- ✅ Login/Logout
- ✅ CRUD plantes
- ✅ Upload de photos
- ✅ Historiques (arrosage, engrais, rempotage, maladies)
- ✅ Modal des plantes
- ✅ Notifications

---

## Dépannage

### Problème: "500 Internal Server Error"

**Solution:**
```bash
# Vérifier les logs
tail -100 storage/logs/laravel.log

# Réinitialiser les caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Vérifier les permissions
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs
```

### Problème: "Class not found" après déploiement

**Solution:**
```bash
composer dump-autoload --optimize --no-dev

php artisan optimize
```

### Problème: Photos n'apparaissent pas

**Solution:**
```bash
# Créer le lien symbolique pour le stockage
php artisan storage:link

# Vérifier les permissions
chmod -R 755 storage/app/public
```

### Problème: Emails ne s'envoient pas

**Solution:**
1. Vérifier `.env` (MAIL_HOST, MAIL_USERNAME, etc.)
2. Tester la connexion SMTP:
```bash
telnet smtp.hostinger.com 465
```
3. Vérifier les logs:
```bash
tail -f storage/logs/laravel.log | grep -i mail
```

### Problème: Base de données vide après migration

**Solution:**
```bash
# Réinitialiser la base
php artisan migrate:refresh --force

# Ou migrer + seed
php artisan migrate --force
php artisan db:seed --force
```

### Problème: Assets CSS/JS ne se chargent pas

**Solution:**
```bash
# Recompiler les assets
npm run build

# Ou simplement rafraîchir le cache
php artisan view:clear
php artisan cache:clear
```

---

## Monitoring & Maintenance

### Daily Checks
```bash
# Vérifier les erreurs
tail -20 storage/logs/laravel.log

# Vérifier l'espace disque
df -h

# Vérifier la base de données
mysqlcheck -u plants_user -p plants_db
```

### Weekly Tasks
```bash
# Nettoyer les caches
php artisan cache:prune

# Optimiser la base de données
php artisan tinker
>>> DB::statement('OPTIMIZE TABLE plants')
>>> DB::statement('OPTIMIZE TABLE disease_histories')
```

### Monthly Tasks
```bash
# Créer une sauvegarde manuelle
mysqldump -u plants_user -p plants_db > ~/backups/plants_backup_$(date +%Y%m%d).sql

# Archiver les anciens logs
gzip storage/logs/*.log
```

---

## Checklist de Déploiement

- [ ] `.env` configuré en production
- [ ] Base de données créée et alimentée
- [ ] Clé d'application générée
- [ ] Assets compilés (CSS, JS, images)
- [ ] Permissions de dossiers correctes (storage, bootstrap)
- [ ] Migrations exécutées
- [ ] Seeders exécutés
- [ ] HTTPS/SSL configuré
- [ ] Caches générés (config, routes, views)
- [ ] Emails testés
- [ ] Photos uploadées et affichées
- [ ] Backups configurées
- [ ] Logs monitoring en place
- [ ] Monitoring uptime configuré

---

## Support & Ressources

- **Laravel Docs**: https://laravel.com/docs
- **Hostinger Docs**: https://support.hostinger.com
- **PHP Docs**: https://www.php.net/docs.php
- **MySQL Docs**: https://dev.mysql.com/doc

---

**Version**: 1.0  
**Date**: 23 Octobre 2025  
**Auteur**: Coding Agent  
**Statut**: Production Ready ✅
