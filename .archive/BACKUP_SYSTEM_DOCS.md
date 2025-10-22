# Système de Sauvegarde et Récupération - Documentation Complète

## Vue d'ensemble

Le système de sauvegarde (Backup System) permet aux administrateurs de :
- **Exporter** toutes les données de l'application en un fichier ZIP
- **Importer** des données depuis une sauvegarde précédente (3 modes)
- **Réinitialiser** complètement la base de données avec récupération sur 30 jours
- **Récupérer** les données supprimées dans la fenêtre de 30 jours
- **Auditer** toutes les actions effectuées

---

## Phase A : Export

### Fonctionnalité
Exporte toutes les données de l'application dans un fichier ZIP compressé.

### Données exportées
- ✅ **Plantes** : Tous les attributs et relations
- ✅ **Photos** : Images attachées aux plantes
- ✅ **Tags** : Classification et catégorisation
- ✅ **Historiques** : Historique des modifications
- ✅ **Métadonnées** : Timestamp, versioning, checksums

### Format du fichier
```
export_2025-10-21_23-00-00_uuid.zip
├── backup.json (métadonnées + structure)
├── photos/
│   ├── plant_1_photo_1.jpg
│   ├── plant_1_photo_2.jpg
│   └── ...
└── checksums.txt (intégrité)
```

### Utilisation
1. Accédez à `/settings/backups`
2. Cliquez sur **"Exporter les données"**
3. Le fichier se télécharge automatiquement

### API
```bash
POST /settings/backups/export
Response: ZIP file download
```

---

## Phase B : Import

### Fonctionnalité
Importe les données depuis un fichier de sauvegarde.

### Modes d'import

#### 1️⃣ FRESH (Nettoyage complet)
- Supprime **TOUTES** les données existantes (force-delete)
- Importe les données du backup
- ⚠️ **Données non-soft-deleted seront perdues**
- Idéal pour : Restauration complète après crash

```
Avant: [30 plantes actives + 30 soft-deleted]
↓
Après: [0 plantes] → Puis import
↓
Résultat: [30 plantes de la sauvegarde]
```

#### 2️⃣ MERGE (Fusion intelligente)
- **Ajoute** les données qui n'existent pas
- **Restaure** les données soft-deleted
- **Met à jour** les données existantes
- Idéal pour : Récupération partielle ou fusion de données

```
Avant: [10 plantes actives + 5 soft-deleted]
Backup: [15 plantes]
↓
Après: [15 plantes actives] (ajout + restore)
```

#### 3️⃣ REPLACE (Remplacement par clé)
- Remplace par clé unique (`reference` pour plantes, `name` pour tags)
- Conserve les données n'ayant pas de conflit
- Idéal pour : Mise à jour sélective

### Étapes d'import

#### 1. Sélectionner le fichier
- Cliquez sur **"Sélectionner un fichier"**
- Choisissez un backup ZIP (max 100MB)
- Ou uploadez depuis votre ordinateur

#### 2. Aperçu (Preview)
- Le système affiche le contenu du backup
- Nombre de plantes, photos, tags
- Validation de l'intégrité du fichier

#### 3. Sélectionner le mode
- FRESH / MERGE / REPLACE
- Lisez les avertissements attentivement

#### 4. Confirmation (double vérification)
- Première confirmation dans la modale
- Deuxième confirmation par formulaire
- Protection contre les supprimées accidentelles

### API
```bash
# Preview
POST /settings/backups/preview
Payload: { backup: "filename.zip" }
Response: { counts: { plants: 30, tags: 60, photos: 4 }, ... }

# Import
POST /settings/backups/import
Payload: { 
  backup: "filename.zip",
  mode: "FRESH|MERGE|REPLACE",
  confirmed: true
}
Response: { success: true, result: { status: "completed", ... } }
```

### Gestion des erreurs

**Erreur 422 - Validation échouée**
```
Causes possibles:
- Fichier ZIP corrompu
- Format backup.json invalide
- Violations de contraintes uniques
```

**Erreur 500 - Serveur**
```
Causes possibles:
- Permissions de fichier insuffisantes
- Disque plein
- Mémoire insuffisante
```

---

## Phase C : Reset & Recovery

### Reset (Réinitialisation)

#### Fonctionnalité
Supprime TOUTES les données de manière réversible pendant 30 jours.

#### Processus
1. **Soft-delete** : Toutes les données sont marquées `deleted_at = NOW()`
2. **Backup optionnel** : Crée une sauvegarde avant suppression
3. **Audit** : Enregistre qui a supprimé, quand et pourquoi
4. **Recovery window** : 30 jours pour récupérer

#### Double confirmation
```
Première alerte:
"⚠️ ATTENTION: Vous êtes sur le point de supprimer toutes les plantes.
Cette action est irréversible pendant 30 jours."

Deuxième alerte:
"🚨 DEUXIÈME CONFIRMATION: Êtes-vous absolument sûr ? 
Cette action supprimera TOUTES les données !"
```

#### Champs
- **Raison** : Motif de la réinitialisation (audit)
- **Créer une sauvegarde** : Backup avant suppression (optionnel)

### Recovery (Récupération)

#### Affichage des données récupérables
```
/settings/backups/deleted-items
├── Plantes supprimées (30)
├── Photos supprimées (12)
├── Historiques supprimés (9)
└── Recovery deadline: 2025-11-20
```

#### Processus de récupération
1. Sélectionner les éléments à restaurer
2. Cliquer **"Restaurer les sélectionnés"**
3. Les éléments sont restaurés (deleted_at = NULL)
4. Audit enregistre la récupération

#### Cas limite
- **Après 30 jours** : Données supprimées définitivement
- **Avant 30 jours** : Données en attente de suppression permanente

### API
```bash
# Preview (dry-run)
POST /settings/backups/reset-preview
Response: { 
  status: "dry-run-completed",
  plants_count: 30,
  recovery_deadline: "2025-11-20T..."
}

# Reset
POST /settings/backups/reset
Payload: {
  confirmed: true,
  create_backup: true|false,
  reason: "Nettoyage après test"
}

# List deleted items
GET /settings/backups/deleted-items
Response: {
  plants: [ { id, name, deleted_at, deletion_reason, ... } ],
  photos: [...],
  histories: [...]
}

# Recover
POST /settings/backups/recover
Payload: {
  plant_ids: [1, 2, 3],
  photo_ids: [5, 6],
  history_ids: [10]
}
```

---

## Audit Logs

### Enregistrement
Toutes les actions sont enregistrées :
- ✅ Export effectué
- ✅ Import commencé/terminé
- ✅ Reset confirmé
- ✅ Items restaurés
- ✅ Modifications pendant import

### Visualisation
```
/settings/backups/audit-logs
├── Action
├── Modèle (Plant, Photo, Import, Reset)
├── Utilisateur
├── Raison
├── Ancien/Nouveau valeur
└── Timestamp
```

### API
```bash
GET /settings/backups/audit-logs?action=reset&limit=50
Response: [
  {
    action: "reset",
    model_type: "Plant",
    model_id: 1,
    old_values: {...},
    new_values: null,
    reason: "Nettoyage après test",
    created_at: "2025-10-21T23:00:00Z"
  },
  ...
]
```

---

## Architecture Technique

### Base de données

#### Tables principales
- `plants` : Plantes (avec soft-delete)
- `photos` : Photos attachées
- `plant_histories` : Historique des modifications
- `tags` : Classification
- `plant_tag` : Relation many-to-many

#### Soft-delete
```sql
ALTER TABLE plants ADD COLUMN deleted_at TIMESTAMP NULL;
ALTER TABLE plants ADD COLUMN deleted_by_user_id BIGINT UNSIGNED NULL;
ALTER TABLE plants ADD COLUMN deletion_reason VARCHAR(255) NULL;
ALTER TABLE plants ADD COLUMN recovery_deadline TIMESTAMP NULL;
```

### Services Laravel

#### BackupService (`app/Services/BackupService.php`)
- `export()` : Crée ZIP avec toutes les données
- `listBackups()` : Liste les fichiers de sauvegarde
- `validateBackup()` : Vérifie l'intégrité

#### ImportService (`app/Services/ImportService.php`)
- `import()` : Importe un backup (3 modes)
- `importFresh()` : Mode FRESH
- `importMerge()` : Mode MERGE avec restore soft-deleted
- `importReplace()` : Mode REPLACE

#### ResetService (`app/Services/ResetService.php`)
- `reset()` : Soft-delete toutes les données
- `recover()` : Restaure les données supprimées
- `performReset()` : Transaction de suppression
- `purgeExpired()` : Supprime définitivement après 30j

### Contrôleur
`app/Http/Controllers/BackupController.php`

Endpoints :
- `index()` : Page de gestion
- `export()` : Export ZIP
- `upload()` : Upload fichier
- `import()` : Import confirmé
- `reset()` : Reset confirmé
- `recover()` : Restauration

---

## Configuration

### Limites PHP
```ini
upload_max_filesize = 100M
post_max_size = 100M
memory_limit = 512M
```

### Disque de stockage
```php
// config/filesystems.php
'backups' => [
    'driver' => 'local',
    'root' => storage_path('app/backups'),
]
```

### Période de récupération
```php
const RECOVERY_WINDOW_DAYS = 30;
```

---

## Sécurité

### Authentification
- ✅ Middleware `auth` requis
- ✅ Middleware `admin` requis (sauf pour preview)

### Validation
- ✅ Fichiers ZIP uniquement
- ✅ Limite de taille 100MB
- ✅ Validation de structure

### Audit
- ✅ Logging de chaque action
- ✅ Tracking utilisateur (qui a supprimé)
- ✅ Raison de suppression

### Protection
- ✅ Double confirmation pour reset
- ✅ Transactions ACID
- ✅ Soft-delete (réversible)

---

## Troubleshooting

### L'upload échoue (422)
**Cause** : Fichier trop gros ou formatcorrompu
**Solution** : Vérifier `php.ini` et réuploader

### L'import échoue avec "Integrity constraint"
**Cause** : Données dupliquées dans le backup
**Solution** : Utiliser mode MERGE au lieu de FRESH

### Les photos affichent 403 Forbidden
**Cause** : Permissions insuffisantes sur les fichiers
**Solution** : `chmod -R 755 storage/app/public`

### Le reset ne supprime rien
**Cause** : Transaction échouée silencieusement
**Solution** : Vérifier les logs et permissions BD

---

## Checklist de déploiement

- [ ] PHP limits configurés (100M+)
- [ ] Disque backups créé et accessible
- [ ] Lien symbolique public/storage existe
- [ ] Permissions 755 sur storage/
- [ ] Migrations exécutées (soft-delete)
- [ ] Admin user créé
- [ ] Tests end-to-end réussis

---

## Version
- **Date** : 21 octobre 2025
- **Statut** : Phase C - Production Ready
- **Prochaine** : Phase D (Tests + Docs avancées)
