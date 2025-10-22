# 🚀 Quick Start Guide

## Pour les développeurs qui veulent comprendre vite

### 1️⃣ En 2 minutes
```
Lire: /README.md
```

### 2️⃣ En 5 minutes
```
Lire: docs/VISUAL_SUMMARY.md
```

### 3️⃣ En 15 minutes
```
Lire: docs/CHANGELOG_SESSION_V113.md
```

### 4️⃣ Détails spécifiques
```
Aller voir: docs/BUGFIX_*.md
```

---

## 📚 Navigation

| Besoin | Document |
|--------|----------|
| **Comprendre le projet** | /README.md |
| **Voir d'un coup d'œil** | docs/VISUAL_SUMMARY.md |
| **Tous les changements** | docs/CHANGELOG_SESSION_V113.md |
| **Table complète** | docs/TABLE_OF_CONTENTS.md |
| **Un bug spécifique** | docs/BUGFIX_*.md |
| **Résumé 1-page** | docs/BUGS_SUMMARY.md |

---

## 🛠️ Commandes de dev

```bash
# Démarrer
composer install
npm install
php artisan migrate --seed

# Dev
php artisan serve
npm run dev

# Tests images
php artisan images:convert-to-webp --dry-run
php artisan plants:assign-main-photos --dry-run

# Console
php artisan tinker
```

---

## 📁 Structure

```
/
├── README.md                 ← DÉBUT ICI
├── docs/                     ← Toute la doc
│   ├── README.md            (complète)
│   ├── TABLE_OF_CONTENTS.md (navigation)
│   ├── VISUAL_SUMMARY.md    (vue rapide)
│   ├── CHANGELOG_*.md       (changements)
│   ├── BUGS_SUMMARY.md      (résumé bugs)
│   └── BUGFIX_*.md          (détails)
├── .archive/                ← Anciens docs
└── plant_manager/           ← Code source
```

---

## ✅ Statut

- ✅ 7 bugs corrigés
- ✅ Tous testés
- ✅ Documentation complète
- ✅ Prêt production

---

*Pour détails: voir docs/TABLE_OF_CONTENTS.md*
