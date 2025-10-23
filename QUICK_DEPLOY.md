# 🎯 Quick Deploy Checklist - Plants Manager

## Pre-Deployment (Local)

### 1. Final Code Review
```bash
git status
git diff --stat
git log --oneline -10
```

### 2. Test Locally
```bash
php artisan serve
# Test: http://localhost:8000
# ✅ Login works
# ✅ All CRUD operations work
# ✅ Modals open/close correctly
# ✅ Date validation works
# ✅ Photos upload
# ✅ Notifications display
```

### 3. Final Commit & Push
```bash
git add .
git commit -m "production: final release v1.15 ready for Hostinger"
git push origin main
git checkout main
git pull
```

---

## Hostinger SSH Deployment (5 minutes)

### Step 1: Connect
```bash
ssh -i ~/.ssh/id_rsa user@ssh-XXX.hostinger.com
```

### Step 2: Navigate & Update
```bash
cd ~/public_html/plant_manager
git pull origin main
```

### Step 3: Install Dependencies
```bash
composer install --no-dev --optimize-autoloader --no-interaction
```

### Step 4: Database & Cache
```bash
php artisan migrate --force
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Step 5: Verify
```bash
tail -20 storage/logs/laravel.log
# Should see no errors
```

---

## Post-Deploy Verification

### Access
- [ ] https://votre-domaine.com loads without errors
- [ ] Login page appears
- [ ] CSS/JS/Images load correctly

### Core Features
- [ ] Can login with test account
- [ ] Can view plant list
- [ ] Can open plant modal
- [ ] Quick modals work (watering, fertilizing, etc.)
- [ ] Disease modal works on show page
- [ ] Photos display properly
- [ ] Notifications appear on success

### Data
- [ ] Database connected
- [ ] All tables present
- [ ] Sample data loaded (if seeded)
- [ ] No 500 errors in logs

---

## Rollback (if needed)

```bash
cd ~/public_html/plant_manager

# Revert to previous version
git revert HEAD
git push origin main

# Clear caches
php artisan cache:clear
php artisan config:clear

# Restart PHP
# Contact Hostinger support or wait for auto-restart
```

---

## Emergency Contacts

- **Hostinger Support**: https://support.hostinger.com
- **Your Email**: Check .env MAIL_FROM_ADDRESS
- **Database Backup**: Check Hostinger hPanel → Databases

---

**⏱️ Estimated Time**: 10-15 minutes  
**🔒 Risk Level**: Low (existing migration pattern)  
**📊 Monitoring**: Check logs hourly for first 24h
