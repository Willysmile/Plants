# 🚀 Propositions de Features - v1.14

## Vue d'ensemble

Plant Manager v1.13 est complète et stable. Voici **7 features** proposées pour v1.14, classées par **impact** et **complexité**.

---

## 📊 Features proposées

### 🔥 Priorité HAUTE - Facile à implémenter

#### 1. **Dashboard amélioré avec statistiques**
**Impact:** ⭐⭐⭐⭐ TRÈS HAUT  
**Complexité:** ⭐⭐ FACILE  
**Temps estimé:** 4-6h

**Description:**
Remplacer le dashboard simple par un vrai dashboard avec:
- Nombre total de plantes (avec breakdown par état)
- Plantes ayant besoin d'eau (aujourd'hui, cette semaine)
- Prochaines tâches (arrosage, rempotage, fertilisation)
- Dernières photos ajoutées
- Statistiques (plantes par famille, par location, etc.)

**Fichiers concernés:**
- `resources/views/dashboard.blade.php`
- `app/Http/Controllers/DashboardController.php`
- `app/Models/Plant.php` (scopes)

**Exemple:**
```
Dashboard
├── 47 plantes totales
│   ├── 45 actives
│   ├── 2 archivées
│   └── 87% avec photo
├── À faire aujourd'hui
│   ├── 8 plantes à arroser
│   ├── 2 à fertiliser
│   └── 0 à rempoter
├── Graphiques
│   ├── Plantes par famille (top 5)
│   └── Plantes par location
└── Récemment ajoutées (3 dernières photos)
```

---

#### 2. **Recherche & Filtrage avancé**
**Impact:** ⭐⭐⭐⭐ TRÈS HAUT  
**Complexité:** ⭐⭐ FACILE  
**Temps estimé:** 3-4h

**Description:**
Ajouter une barre de recherche avec filtres sur `/plants`:
- Recherche par nom (live search)
- Filtrer par tags
- Filtrer par location
- Filtrer par famille
- Combiner plusieurs filtres (AND/OR)
- Sauvegarder les recherches favorites

**Fichiers concernés:**
- `resources/views/plants/index.blade.php`
- `app/Http/Controllers/PlantController.php`
- `public/js/search-filter.js` (nouveau)

**Exemple:**
```
Recherche: "bromelia"
Filtres: Location = "Salon", Tags = "plante facile"
Résultats: 2 plantes
```

---

### 🟠 Priorité MOYENNE - Modérément complexe

#### 3. **Export de données (CSV, PDF, JSON)**
**Impact:** ⭐⭐⭐ HAUT  
**Complexité:** ⭐⭐ FACILE  
**Temps estimé:** 4-5h

**Description:**
Permettre l'export de toutes les données en:
- **CSV** - Pour Excel/Google Sheets (plantes, photos, historique)
- **PDF** - Rapport avec: liste plantes, photos, horaires d'arrosage
- **JSON** - Pour sauvegarde structurée ou import ailleurs

**Fichiers concernés:**
- `app/Http/Controllers/ExportController.php` (nouveau)
- `app/Services/ExportService.php` (nouveau)
- `resources/views/settings/export.blade.php` (nouveau)
- Route dans `routes/web.php`

**Exemple de PDF:**
```
📄 RAPPORT PLANT MANAGER - 22 OCT 2025
=====================================
47 plantes - 137 photos - 58 tags

PLANTES PAR FAMILLE (TOP 5)
1. Araceae (8 plantes)
2. Asparagaceae (7 plantes)
...

CALENDRIER D'ARROSAGE (7 JOURS)
Lundi:  8 plantes
Mardi:  5 plantes
...

CONTACTS POUR SEMIS
[Imprimable avec emplacement photo]
```

---

#### 4. **Système de notifications (prochaines tâches)**
**Impact:** ⭐⭐⭐ HAUT  
**Complexité:** ⭐⭐⭐ MOYEN  
**Temps estimé:** 6-8h

**Description:**
Système qui notifie l'utilisateur des:
- Arrosages à faire (aujourd'hui, demain)
- Engrais à appliquer
- Rempotages prévus
- Tâches de maintenance

Peut être:
- **Bell icon** (notification dans l'app)
- **Email** (optionnel)
- **Browser notifications** (si PWA)

**Fichiers concernés:**
- `app/Models/Notification.php` (nouveau)
- `app/Services/NotificationService.php` (nouveau)
- `resources/views/components/notifications.blade.php` (nouveau)
- Middleware pour charger notifications
- Artisan command pour générer notifications (cron)

**Exemple:**
```
🔔 3 notifications
├── 🚨 BROMELIA: À arroser aujourd'hui
├── ⏰ ANTHURIUM: À fertiliser demain
└── 📅 MONSTERA: À rempoter dans 3 jours
```

---

### 🟡 Priorité BASSE - Plus complexe

#### 5. **Galerie partageable (liens publics)**
**Impact:** ⭐⭐ MOYEN  
**Complexité:** ⭐⭐⭐ MOYEN  
**Temps estimé:** 8-10h

**Description:**
Créer des **liens publics** pour partager une galerie:
- Lien unique par plante
- Lien pour partager toute la collection
- Lien avec expirationDate
- Protégé par mot de passe (optionnel)
- Vue lecture-seule (pas d'édition)

**Fichiers concernés:**
- `app/Models/ShareLink.php` (nouveau - avec soft-delete)
- `app/Http/Controllers/ShareController.php` (nouveau)
- `resources/views/plants/share/show.blade.php` (nouveau)
- Routes: `routes/public.php` (nouveau)

**Exemple:**
```
https://plantmanager.app/share/abc123def456
[Affiche: Photo + Nom + Tags + Description]
[Pas besoin de login]
[Expire le 30 nov 2025]
```

---

#### 6. **API REST complète (pour mobile app future)**
**Impact:** ⭐⭐ MOYEN  
**Complexité:** ⭐⭐⭐⭐ ÉLEVÉ  
**Temps estimé:** 12-16h

**Description:**
Créer une vraie API REST avec:
- Endpoints CRUD complets
- Authentication (Bearer tokens)
- Pagination
- Filtering
- Documentation (OpenAPI/Swagger)

**Endpoints:**
```
GET    /api/plants                 - Lister
POST   /api/plants                 - Créer
GET    /api/plants/{id}            - Détail
PUT    /api/plants/{id}            - Éditer
DELETE /api/plants/{id}            - Supprimer

GET    /api/plants/{id}/photos     - Photos
POST   /api/plants/{id}/photos     - Ajouter photo
GET    /api/plants/{id}/history    - Historique

GET    /api/tags                   - Lister tags
POST   /api/plants/{id}/tags       - Assigner tags
```

**Fichiers concernés:**
- `routes/api.php` (complet)
- `app/Http/Controllers/Api/*` (nouveaux controllers)
- `app/Http/Resources/*` (API resources)
- Documentation OpenAPI

**Cas d'usage:**
- Futur mobile app (React Native, Flutter)
- Intégration tiers

---

#### 7. **Planification & Reminders (Calendrier)**
**Impact:** ⭐⭐⭐ HAUT  
**Complexité:** ⭐⭐⭐⭐ ÉLEVÉ  
**Temps estimé:** 10-14h

**Description:**
Calendrier complet pour:
- Vue calendrier mois/semaine/jour
- Voir tous les événements (arrosage, engrais, rempotage)
- Créer des rappels custom
- Drag-drop pour reschedul
- Intégration Google Calendar (export)
- Alarm avant l'événement

**Fichiers concernés:**
- `app/Models/Reminder.php` (nouveau)
- `app/Http/Controllers/CalendarController.php` (nouveau)
- `resources/views/calendar/` (nouveau dossier)
- Library JS: FullCalendar ou similar
- `public/js/calendar-manager.js` (nouveau)

**Exemple:**
```
Octobre 2025
┌─ Lu ─┬─ Ma ─┬─ Me ─┬─ Je ─┬─ Ve ─┬─ Sa ─┬─ Di ─┐
│     │  1   │  2   │  3   │  4   │  5   │  6   │
│     │ 💧(2)│      │ 🌿(1)│      │      │      │
│  7   │  8   │  9   │ 10   │ 11   │ 12   │ 13   │
│ 💧(4)│      │ 🌿(2)│      │      │      │      │
└─────┴─────┴─────┴─────┴─────┴─────┴─────┘

Légende: 💧 = Arrosage, 🌿 = Engrais, 🪴 = Rempotage
```

---

## 📈 Tableau de synthèse

| # | Feature | Impact | Complexité | Temps | Priorité |
|---|---------|--------|------------|-------|----------|
| 1 | Dashboard amélioré | ⭐⭐⭐⭐ | ⭐⭐ | 4-6h | 🔥 HAUTE |
| 2 | Recherche avancée | ⭐⭐⭐⭐ | ⭐⭐ | 3-4h | 🔥 HAUTE |
| 3 | Export CSV/PDF/JSON | ⭐⭐⭐ | ⭐⭐ | 4-5h | 🟠 MOYENNE |
| 4 | Notifications | ⭐⭐⭐ | ⭐⭐⭐ | 6-8h | 🟠 MOYENNE |
| 5 | Partage public | ⭐⭐ | ⭐⭐⭐ | 8-10h | 🟡 BASSE |
| 6 | API REST | ⭐⭐ | ⭐⭐⭐⭐ | 12-16h | 🟡 BASSE |
| 7 | Calendrier/Planning | ⭐⭐⭐ | ⭐⭐⭐⭐ | 10-14h | 🟡 BASSE |

---

## 🎯 Recommandations pour v1.14

### Phase 1 (Sprint rapide - 1-2 semaines)
**Faire les 2 features HAUTE priorité:**
- ✅ Dashboard amélioré (4-6h)
- ✅ Recherche avancée (3-4h)
- **Total:** 7-10h = **1 semaine facile**

### Phase 2 (Sprint moyen - 2-3 semaines)
**Ajouter les 2 features MOYENNE priorité:**
- ✅ Export CSV/PDF/JSON (4-5h)
- ✅ Notifications (6-8h)
- **Total:** 10-13h = **1.5-2 semaines**

### Phase 3 (Sprint plus long - 3-4 semaines)
**Choisir 1-2 features BASSE priorité:**
- Soit: Galerie partageable (8-10h) ✅ Utile & pas trop complexe
- Soit: Calendrier/Planning (10-14h) ✅ Plus complexe mais très utile

---

## 💡 Autres idées bonus (si temps)

- **Dark mode** (1-2h) - Toggle dans settings
- **Tri des plantes** (1h) - Par nom, date création, nbr photos
- **Clonage de plante** (2h) - Créer nouvelle plante à partir d'une existante
- **Bulk actions** (2-3h) - Sélectionner plusieurs plantes, éditer/supprimer/tagger
- **Comments/Notes** (2-3h) - Ajouter des notes collaboratives
- **Historique audit complet** (1-2h) - Voir toutes les modifications
- **Modèles de plantes** (3-4h) - Utiliser des templates pour créer rapidement

---

## ✅ Prochaines étapes

1. **Choisir** 2-3 features à implémenter
2. **Créer des branches** `feature/...` depuis v1.14
3. **Tester** chaque feature
4. **Merger** vers v1.14
5. **Merger** v1.14 → main quand prêt
6. **Tagger** et **release** v1.14

---

**Qu'en penses-tu? Laquelle préfères-tu commencer?** 🚀

*Créé:** 22 octobre 2025 | Branch: v1.14
