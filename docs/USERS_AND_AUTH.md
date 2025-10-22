# 👥 Gestion des utilisateurs & admin

Documentation complète sur la création et gestion des utilisateurs dans Plant Manager.

---

## 📋 Vue d'ensemble

Plant Manager utilise **Laravel Breeze** pour l'authentification. Le système supporte:

- ✅ **Authentification simple** (email + mot de passe)
- ✅ **Compte unique** (pas de multi-utilisateurs)
- ✅ **Rôle Admin** (optionnel)
- ✅ **Soft-delete** (conservation des données)

---

## 🏗️ Architecture

### Model User
```php
// app/Models/User.php
class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',      // Nouveau: rôle admin
    ];
    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }
}
```

### Table Database
```
users
├── id               (PK)
├── name             string
├── email            string (unique)
├── password         string (hashed)
├── is_admin         boolean (default: false)
├── email_verified_at timestamp
├── remember_token   string
├── created_at       timestamp
└── updated_at       timestamp
```

---

## 🔧 Méthodes de création

### 1️⃣ Seed de développement (Automatique)

Exécutée au premier `php artisan migrate:fresh --seed`:

```php
// database/seeders/DatabaseSeeder.php
User::firstOrCreate(
    ['email' => 'test@example.com'],
    [
        'name' => 'Test User',
        'password' => Hash::make('password'),
        'is_admin' => false,
    ]
);

User::firstOrCreate(
    ['email' => 'admin@example.com'],
    [
        'name' => 'Admin User',
        'password' => Hash::make('admin123'),
        'is_admin' => true,
    ]
);
```

**Utilisateurs créés:**
| Email | Mot de passe | Rôle |
|-------|--------------|------|
| test@example.com | password | User |
| admin@example.com | admin123 | Admin |

---

### 2️⃣ Inscription Web (Manuelle)

Via la page d'inscription: `http://localhost:8000/register`

```php
// routes/auth.php
Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('register', [RegisteredUserController::class, 'store']);
```

**Processus:**
1. Aller à `/register`
2. Remplir: Nom, Email, Mot de passe (x2)
3. Cliquer "Register"
4. ✅ Utilisateur créé avec `is_admin = false`
5. ✅ Auto-login et redirection dashboard

**Validation:**
```php
$request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'email', 'unique:users'],
    'password' => ['required', 'confirmed', Password::defaults()],
]);
```

---

### 3️⃣ Ligne de commande (Artisan Tinker)

Pour créer rapidement via console:

```bash
php artisan tinker

# Créer un utilisateur simple
>>> User::create([
...   'name' => 'Nouveau User',
...   'email' => 'nouveau@example.com',
...   'password' => Hash::make('password123'),
...   'is_admin' => false,
... ])

# Créer un admin
>>> User::create([
...   'name' => 'Nouvel Admin',
...   'email' => 'newadmin@example.com',
...   'password' => Hash::make('adminpass123'),
...   'is_admin' => true,
... ])

# Lister tous les users
>>> User::all()

# Trouver un user par email
>>> User::where('email', 'admin@example.com')->first()

# Modifier un user
>>> $user = User::find(1)
>>> $user->is_admin = true
>>> $user->save()
```

---

### 4️⃣ Artisan Command (Custom)

Pour création automatisée (si besoin):

```bash
# Exemple: créer un admin via commande personnalisée
php artisan user:create-admin \
    --name="Nouvel Admin" \
    --email="admin@example.com" \
    --password="secure_password"
```

*(À implémenter si besoin)*

---

## 🔑 Rôles & Permissions

### Rôle User (is_admin = false)
```
✅ Peut voir les plantes
✅ Peut créer/éditer/supprimer ses plantes
✅ Peut télécharger des photos
✅ Peut modifier ses tags
❌ Pas d'accès admin
```

### Rôle Admin (is_admin = true)
```
✅ Accès à tous les droits User
✅ Peut accéder au panel admin
✅ Peut gérer les tags système
✅ Peut voir les logs d'audit
❌ Peu de fonctions admin spécifiques (à développer)
```

---

## 🔐 Authentification

### Routes disponibles

| Route | Méthode | Description |
|-------|---------|-------------|
| `/login` | GET | Formulaire login |
| `/login` | POST | Traiter login |
| `/register` | GET | Formulaire inscription |
| `/register` | POST | Traiter inscription |
| `/forgot-password` | GET | Formulaire reset |
| `/forgot-password` | POST | Envoyer email reset |
| `/reset-password/{token}` | GET | Reset avec token |
| `/reset-password` | POST | Traiter reset |
| `/logout` | POST | Logout (middleware auth) |

### Middleware d'authentification

```php
// Pour protéger une route
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', ...);
    Route::get('/plants', ...);
});

// Pour guest uniquement
Route::middleware('guest')->group(function () {
    Route::get('/register', ...);
    Route::get('/login', ...);
});
```

---

## 📊 Données existantes

### Users actuels (après seed)

```bash
$ php artisan tinker
>>> User::all()

Collection {
  0 => {
    "id": 1,
    "name": "Test User",
    "email": "test@example.com",
    "is_admin": false,
    "created_at": "2025-10-22T...",
    "updated_at": "2025-10-22T...",
  },
  1 => {
    "id": 2,
    "name": "Admin User",
    "email": "admin@example.com",
    "is_admin": true,
    "created_at": "2025-10-22T...",
    "updated_at": "2025-10-22T...",
  },
}
```

---

## ✅ Checklist opérationnel

### Après `migrate:fresh --seed`
- [x] Table `users` créée
- [x] User `test@example.com` créé
- [x] Admin `admin@example.com` créé
- [x] Mots de passe hashés
- [x] `is_admin` booléen défini

### Configuration
- [x] Laravel Breeze installé
- [x] Routes auth en place
- [x] Controllers auth fonctionnels
- [x] Vues auth disponibles
- [x] Validation input OK

### Tests
- [x] Login avec test@example.com ✅
- [x] Login avec admin@example.com ✅
- [x] Logout ✅
- [x] Register nouveau user ✅
- [x] Password reset ✅

---

## 📝 Commandes utiles

```bash
# Voir tous les users
php artisan tinker
>>> User::all()

# Créer un user rapidement
>>> User::create(['name' => 'X', 'email' => 'x@x.com', 'password' => Hash::make('pass'), 'is_admin' => false])

# Faire un user admin
>>> $user = User::find(1); $user->is_admin = true; $user->save()

# Supprimer un user
>>> User::where('email', 'test@example.com')->delete()

# Compter les users
>>> User::count()

# Compter les admins
>>> User::where('is_admin', true)->count()
```

---

## 🚀 Fonctionnalités futures

### À implémenter
- [ ] Panel d'admin pour gérer les users
- [ ] CRUD users (create/read/update/delete)
- [ ] Rôles plus granulaires (editor, viewer, etc.)
- [ ] Permissions par resource
- [ ] 2FA (authentification double facteur)
- [ ] OAuth (login Google/GitHub)
- [ ] Audit logging des actions user

---

## 🔗 Fichiers clés

| Fichier | Rôle | Ligne |
|---------|------|------|
| `app/Models/User.php` | Model | 51 lines |
| `routes/auth.php` | Routes | 66 lines |
| `database/seeders/DatabaseSeeder.php` | Seed | 44 lines |
| `app/Http/Controllers/Auth/RegisteredUserController.php` | Controller | 53 lines |
| `app/Http/Controllers/Auth/AuthenticatedSessionController.php` | Controller | Login |
| `resources/views/auth/register.blade.php` | Vue | Formulaire |
| `resources/views/auth/login.blade.php` | Vue | Formulaire |

---

## 📖 Références

- [Laravel Breeze Documentation](https://laravel.com/docs/breeze)
- [Laravel Authentication Guide](https://laravel.com/docs/authentication)
- [Laravel Seeders](https://laravel.com/docs/seeding)

---

*Dernière mise à jour: 22 octobre 2025*
