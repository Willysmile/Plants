# 📋 Phase 1.3 : UI Admin Tags - Spécification Détaillée

**Date** : 22 octobre 2025  
**Branche** : v1.13 (prochaine : feature/admin-tags)  
**Statut** : 📝 En Planification

---

## 🎯 Objectif Phase 1.3

Créer une **interface d'administration complète** pour gérer les tags :
- ✅ Page index : Liste tous les tags par catégorie
- ✅ Page create : Formulaire création tag
- ✅ Page edit : Modification tag
- ✅ Destroy : Suppression tag
- ✅ Authentification : Admin only
- ✅ Tests : CRUD complet

---

## 📊 État Actuel des Tags

### Structure BD

```sql
-- Table tags (existante)
id (PK)
name (string, unique)
category (string)
created_at, updated_at
```

### Catégories Existantes (95 tags)

**10 catégories** :
1. **Origine climatique** (7 tags)
   - Tropicale, Subtropicale, Méditerranéenne, Désertique, Tempérée, Alpine, Équatoriale

2. **Type de feuillage** (11 tags)
   - Persistant, Caduc, Panaché, Décoratif, Argenté, Pourpre/rouge, Brillant, Duveteux, Épineux, Charnu, Découpé

3. **Type de plante** (7 tags)
   - Succulente, Épiphyte, Aquatique, Carnivore, Aromatique, Bulbeuse, Rhizomateuse

4. **Port de la plante** (8 tags)
   - Grimpante, Rampante, Retombante, Arbustive, Arborescente, En rosette, Tapissante, Érigée

5. **Floraison** (8 tags)
   - Décorative, Parfumée, Longue durée, Printanière, Estivale, Automnale, Hivernale, Remontante

6. **Taille de la plante** (5 tags)
   - Miniature, Petite, Moyenne, Grande, Très grande

7. **Vitesse de croissance** (3 tags)
   - Rapide, Moyenne, Lente

8. **Caractéristiques spéciales** (8 tags)
   - Dépolluante, Mellifère, Toxique, Pet-friendly, Résistante maladies, Résistante gel, Résistante sécheresse, Facile entretien

9. **Texture/Aspect** (5 tags)
   - Brillant, Duveteux, Épineux, Charnu, Découpé

10. **Système racinaire** (3 tags)
    - Racines aériennes, Racines superficielles, Racines profondes

---

## 🏗️ Architecture Détaillée

### 1. Contrôleur TagController

**Fichier** : `app/Http/Controllers/TagController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Middleware admin pour toutes les actions
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Index : Liste tous les tags par catégorie
     */
    public function index()
    {
        $tags = Tag::orderBy('category')
                   ->orderBy('name')
                   ->get()
                   ->groupBy('category');

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Create : Formulaire création
     */
    public function create()
    {
        $categories = $this->getCategories();
        return view('admin.tags.create', compact('categories'));
    }

    /**
     * Store : Sauvegarde nouveau tag
     */
    public function store(StoreTagRequest $request)
    {
        Tag::create($request->validated());
        return redirect()->route('tags.index')
                       ->with('success', 'Tag créé avec succès.');
    }

    /**
     * Edit : Formulaire édition
     */
    public function edit(Tag $tag)
    {
        $categories = $this->getCategories();
        return view('admin.tags.edit', compact('tag', 'categories'));
    }

    /**
     * Update : Mise à jour
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $tag->update($request->validated());
        return redirect()->route('tags.index')
                       ->with('success', 'Tag modifié avec succès.');
    }

    /**
     * Destroy : Suppression
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('tags.index')
                       ->with('success', 'Tag supprimé avec succès.');
    }

    /**
     * Helper : Récupère liste des catégories
     */
    private function getCategories()
    {
        return [
            'Origine climatique',
            'Type de feuillage',
            'Type de plante',
            'Port de la plante',
            'Floraison',
            'Taille de la plante',
            'Vitesse de croissance',
            'Caractéristiques spéciales',
            'Texture/Aspect',
            'Système racinaire',
        ];
    }
}
```

### 2. Form Requests

#### StoreTagRequest

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:tags,name',
            'category' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du tag est obligatoire.',
            'name.unique' => 'Ce tag existe déjà.',
            'category.required' => 'La catégorie est obligatoire.',
        ];
    }
}
```

#### UpdateTagRequest

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:tags,name,' . $this->route('tag')->id,
            'category' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return (new StoreTagRequest())->messages();
    }
}
```

### 3. Routes

**Fichier** : `routes/web.php` (section admin)

```php
// Routes admin - protégées par middleware 'admin'
Route::middleware(['auth', 'admin'])->group(function () {
    // Tags CRUD
    Route::resource('admin/tags', TagController::class);
});
```

### 4. Views (Blade)

#### `admin/tags/index.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Gestion des Tags')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <header class="mb-8 flex justify-between items-center">
        <h1 class="text-3xl font-bold">Gestion des Tags</h1>
        <a href="{{ route('tags.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            ➕ Nouveau Tag
        </a>
    </header>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Affichage par catégorie -->
    @foreach($tags as $category => $categoryTags)
        <section class="mb-8 bg-white rounded-lg shadow">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white">
                    {{ $category }}
                    <span class="ml-2 bg-white/30 px-2 py-1 rounded text-sm">
                        {{ count($categoryTags) }}
                    </span>
                </h2>
            </div>

            <div class="divide-y">
                @foreach($categoryTags as $tag)
                    <div class="p-6 flex justify-between items-center hover:bg-gray-50 transition">
                        <div>
                            <p class="text-lg font-semibold">{{ $tag->name }}</p>
                            <p class="text-sm text-gray-500">{{ $category }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('tags.edit', $tag) }}" 
                               class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                ✏️ Éditer
                            </a>
                            <form method="POST" action="{{ route('tags.destroy', $tag) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                                        onclick="return confirm('Sûr?')">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endforeach
</div>
@endsection
```

#### `admin/tags/create.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Créer un Tag')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Créer un nouveau Tag</h1>

    <form method="POST" action="{{ route('tags.store') }}" class="bg-white rounded-lg shadow p-8">
        @csrf

        <!-- Nom -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-semibold mb-2">
                Nom du Tag *
            </label>
            <input type="text" id="name" name="name" 
                   class="w-full px-4 py-2 border border-gray-300 rounded @error('name') border-red-500 @enderror"
                   value="{{ old('name') }}"
                   placeholder="Ex: Facile d'entretien">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Catégorie -->
        <div class="mb-6">
            <label for="category" class="block text-sm font-semibold mb-2">
                Catégorie *
            </label>
            <select id="category" name="category" 
                    class="w-full px-4 py-2 border border-gray-300 rounded @error('category') border-red-500 @enderror">
                <option value="">Sélectionner une catégorie</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @selected(old('category') === $cat)>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
            @error('category')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Boutons -->
        <div class="flex gap-4">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                ✅ Créer
            </button>
            <a href="{{ route('tags.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
                ❌ Annuler
            </a>
        </div>
    </form>
</div>
@endsection
```

#### `admin/tags/edit.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Éditer Tag')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Éditer Tag</h1>

    <form method="POST" action="{{ route('tags.update', $tag) }}" class="bg-white rounded-lg shadow p-8">
        @csrf @method('PUT')

        <!-- Nom -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-semibold mb-2">
                Nom du Tag *
            </label>
            <input type="text" id="name" name="name" 
                   class="w-full px-4 py-2 border border-gray-300 rounded @error('name') border-red-500 @enderror"
                   value="{{ old('name', $tag->name) }}">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Catégorie -->
        <div class="mb-6">
            <label for="category" class="block text-sm font-semibold mb-2">
                Catégorie *
            </label>
            <select id="category" name="category" 
                    class="w-full px-4 py-2 border border-gray-300 rounded @error('category') border-red-500 @enderror">
                <option value="">Sélectionner une catégorie</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @selected(old('category', $tag->category) === $cat)>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
            @error('category')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Boutons -->
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                ✅ Mettre à jour
            </button>
            <a href="{{ route('tags.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">
                ❌ Annuler
            </a>
        </div>
    </form>
</div>
@endsection
```

### 5. Middleware Admin

**Fichier** : `app/Http/Middleware/AdminMiddleware.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Accès refusé');
        }

        return $next($request);
    }
}
```

**Enregistrer** dans `bootstrap/app.php` :
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);
})
```

---

## 🧪 Tests à Créer

### TagControllerTest (12 tests minimum)

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->user = User::factory()->create(['is_admin' => false]);
    }

    // ==================== INDEX ====================

    /**
     * Test : Index affiche tous les tags
     */
    public function test_index_displays_tags(): void
    {
        Tag::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)->get(route('tags.index'));

        $response->assertStatus(200);
        $response->assertViewHas('tags');
    }

    /**
     * Test : Index requiert admin
     */
    public function test_index_requires_admin(): void
    {
        $response = $this->actingAs($this->user)->get(route('tags.index'));

        $response->assertStatus(403);
    }

    // ==================== CREATE ====================

    /**
     * Test : Create affiche formulaire
     */
    public function test_create_displays_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('tags.create'));

        $response->assertStatus(200);
        $response->assertViewHas('categories');
    }

    // ==================== STORE ====================

    /**
     * Test : Store crée tag valide
     */
    public function test_store_creates_tag(): void
    {
        $response = $this->actingAs($this->admin)->post(route('tags.store'), [
            'name' => 'Nouvelle Catégorie',
            'category' => 'Type de plante',
        ]);

        $response->assertRedirect(route('tags.index'));
        $this->assertDatabaseHas('tags', ['name' => 'Nouvelle Catégorie']);
    }

    /**
     * Test : Store rejette doublon
     */
    public function test_store_rejects_duplicate_name(): void
    {
        Tag::create(['name' => 'Existant', 'category' => 'Type']);

        $response = $this->actingAs($this->admin)->post(route('tags.store'), [
            'name' => 'Existant',
            'category' => 'Type',
        ]);

        $response->assertSessionHasErrors('name');
    }

    // ==================== EDIT ====================

    /**
     * Test : Edit affiche formulaire
     */
    public function test_edit_displays_form(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('tags.edit', $tag));

        $response->assertStatus(200);
        $response->assertSeeText($tag->name);
    }

    // ==================== UPDATE ====================

    /**
     * Test : Update modifie tag
     */
    public function test_update_modifies_tag(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('tags.update', $tag), [
            'name' => 'Tag Modifié',
            'category' => 'Nouvelle Catégorie',
        ]);

        $response->assertRedirect(route('tags.index'));
        $this->assertDatabaseHas('tags', ['id' => $tag->id, 'name' => 'Tag Modifié']);
    }

    // ==================== DESTROY ====================

    /**
     * Test : Destroy supprime tag
     */
    public function test_destroy_deletes_tag(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('tags.destroy', $tag));

        $response->assertRedirect(route('tags.index'));
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }

    // ==================== SECURITY ====================

    /**
     * Test : Utilisateur non-admin ne peut pas créer
     */
    public function test_non_admin_cannot_create(): void
    {
        $response = $this->actingAs($this->user)->get(route('tags.create'));

        $response->assertStatus(403);
    }

    /**
     * Test : Non authentifié redirigé vers login
     */
    public function test_unauthenticated_redirected_to_login(): void
    {
        $response = $this->get(route('tags.index'));

        $response->assertRedirect(route('login'));
    }
}
```

---

## 📋 Checklist Détaillée

### Étape 1 : Modèle & BD ✅
- [x] Tag model (déjà existe)
- [x] Migration tags (déjà existe)
- [x] 95 tags seedés (déjà existe)
- [x] Colonne category (déjà existe)

### Étape 2 : Backend 🔄
- [ ] TagController créé
- [ ] StoreTagRequest validée
- [ ] UpdateTagRequest validée
- [ ] AdminMiddleware créé
- [ ] Routes enregistrées

### Étape 3 : Frontend 🔄
- [ ] View index.blade.php
- [ ] View create.blade.php
- [ ] View edit.blade.php
- [ ] Navigation menu admin

### Étape 4 : Tests 🔄
- [ ] 12+ tests TagController
- [ ] Tests sécurité (admin only)
- [ ] Tests validation
- [ ] Tests CRUD complet

### Étape 5 : Documentation 🔄
- [ ] README admin tags
- [ ] Commentaires code
- [ ] Documentation utilisateur

---

## 🎨 Design UI

### Index Page Layout
```
┌─────────────────────────────────────────┐
│  Gestion des Tags    [➕ Nouveau Tag]    │
├─────────────────────────────────────────┤
│ ┌──────────────────────────────────────┐ │
│ │ 🌍 Origine climatique (7)             │ │
│ ├──────────────────────────────────────┤ │
│ │ Tropicale     [✏️ Éditer] [🗑️ Suppr.]│ │
│ │ Subtropicale  [✏️ Éditer] [🗑️ Suppr.]│ │
│ │ ...                                    │ │
│ └──────────────────────────────────────┘ │
├─────────────────────────────────────────┤
│ ┌──────────────────────────────────────┐ │
│ │ 🌿 Type de feuillage (11)            │ │
│ ├──────────────────────────────────────┤ │
│ │ Persistant    [✏️ Éditer] [🗑️ Suppr.]│ │
│ │ Caduc         [✏️ Éditer] [🗑️ Suppr.]│ │
│ │ ...                                    │ │
│ └──────────────────────────────────────┘ │
└─────────────────────────────────────────┘
```

### Create/Edit Form
```
┌────────────────────────────────┐
│ Créer un nouveau Tag            │
├────────────────────────────────┤
│ Nom du Tag *                    │
│ [____________________________]  │
│ Catégorie *                     │
│ [▼ Sélectionner une catégorie]  │
│ [✅ Créer] [❌ Annuler]         │
└────────────────────────────────┘
```

---

## 🔐 Sécurité

### Authentification
- ✅ Admin middleware sur toutes les routes
- ✅ `is_admin` field dans User
- ✅ Authorization dans Form Requests

### Validation
- ✅ Nom unique
- ✅ Catégorie requis
- ✅ Max 255 caractères

### Données Sensibles
- ✅ Cascading delete avec plants (si applicable)
- ✅ Confirmation avant suppression

---

## ⏱️ Estimation

| Tâche | Effort | Temps |
|-------|--------|-------|
| TagController | M | 1h |
| Form Requests | S | 30m |
| Views (3) | M | 1.5h |
| Middleware | S | 30m |
| Tests (12) | M | 1h |
| Routes | S | 15m |
| Documentation | S | 30m |
| **Total** | | **~5.5h** |

---

## 📝 Prochaines Phases

### Phase 1.3.1 (Optionnel)
- [ ] Bulk edit tags
- [ ] Recherche/filtre
- [ ] Export tags CSV
- [ ] Import tags CSV

### Phase 2 (Après Phase 1.3)
- [ ] Audit WCAG
- [ ] Export/Import données
- [ ] Notifications rappels

---

## 🚀 Commandes Laravel

```bash
# Générer contrôleur
php artisan make:controller TagController --resource

# Générer Form Requests
php artisan make:request StoreTagRequest
php artisan make:request UpdateTagRequest

# Générer Middleware
php artisan make:middleware AdminMiddleware

# Générer Tests
php artisan make:test TagControllerTest --feature

# Executer tests
php artisan test tests/Feature/TagControllerTest.php
```

---

**Approuvé pour développement ? Y/N** 🚦
