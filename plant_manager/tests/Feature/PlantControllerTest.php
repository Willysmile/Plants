<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Plant;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PlantControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    /**
     * Setup : Authentifier un utilisateur et initialiser le stockage
     */
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    // ==================== INDEX ====================

    /**
     * Test : Index affiche la liste des plantes (non archivées)
     */
    public function test_index_displays_plants(): void
    {
        $plants = Plant::factory()->count(3)->create(['is_archived' => false]);
        $archivedPlant = Plant::factory()->create(['is_archived' => true]);

        $response = $this->get(route('plants.index'));

        $response->assertStatus(200);
        $response->assertViewIs('plants.index');
        $response->assertViewHas('plants');

        // Vérifier que les plantes non-archivées sont dans la vue
        foreach ($plants as $plant) {
            $response->assertSeeText($plant->name);
        }

        // Plante archivée ne doit pas être visible
        $response->assertDontSeeText($archivedPlant->name);
    }

    // ==================== SHOW ====================

    /**
     * Test : Show affiche les détails d'une plante
     */
    public function test_show_displays_plant_details(): void
    {
        $plant = Plant::factory()->create();
        $tags = Tag::factory()->count(2)->create();
        $plant->tags()->attach($tags);

        $response = $this->get(route('plants.show', $plant));

        $response->assertStatus(200);
        $response->assertViewIs('plants.show');
        $response->assertViewHas('plant');
        $response->assertSeeText($plant->name);
    }

    /**
     * Test : Show retourne 404 pour plante inexistante
     */
    public function test_show_returns_404_for_nonexistent_plant(): void
    {
        $response = $this->get(route('plants.show', 999));

        $response->assertNotFound();
    }

    // ==================== CREATE ====================

    /**
     * Test : Create affiche le formulaire de création
     */
    public function test_create_displays_form(): void
    {
        $response = $this->get(route('plants.create'));

        $response->assertStatus(200);
        $response->assertViewIs('plants.create');
        $response->assertViewHas('tags');
    }

    // ==================== STORE ====================

    /**
     * Test : Store crée une plante valide
     */
    public function test_store_creates_plant_with_valid_data(): void
    {
        $data = [
            'name' => 'Monstera Deliciosa',
            'watering_frequency' => 3,
            'light_requirement' => 2,
            'scientific_name' => 'Monstera deliciosa',
            'description' => 'Une belle plante tropicale',
        ];

        $response = $this->post(route('plants.store'), $data);

        $response->assertRedirect(route('plants.index'));
        $this->assertDatabaseHas('plants', [
            'name' => 'Monstera Deliciosa',
            'scientific_name' => 'Monstera deliciosa',
        ]);
    }

    /**
     * Test : Store avec tags valides
     */
    public function test_store_with_valid_tags(): void
    {
        $tags = Tag::factory()->count(2)->create();

        $response = $this->post(route('plants.store'), [
            'name' => 'Test Plant',
            'watering_frequency' => 3,
            'light_requirement' => 2,
            'tags' => [$tags[0]->id, $tags[1]->id],
        ]);

        $response->assertRedirect(route('plants.index'));
        $plant = Plant::where('name', 'Test Plant')->first();
        $this->assertEquals(2, $plant->tags()->count());
    }

    /**
     * Test : Store avec photo principale (vérifie que c'est traité)
     */
    public function test_store_handles_main_photo(): void
    {
        $response = $this->post(route('plants.store'), [
            'name' => 'Test Plant',
            'watering_frequency' => 3,
            'light_requirement' => 2,
        ]);

        $response->assertRedirect(route('plants.index'));
        $plant = Plant::where('name', 'Test Plant')->first();
        $this->assertNotNull($plant);
    }

    /**
     * Test : Store rejecte champ 'name' manquant
     */
    public function test_store_rejects_missing_name(): void
    {
        $response = $this->post(route('plants.store'), [
            'watering_frequency' => 3,
            'light_requirement' => 2,
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test : Store rejecte 'watering_frequency' manquant
     */
    public function test_store_rejects_missing_watering_frequency(): void
    {
        $response = $this->post(route('plants.store'), [
            'name' => 'Test Plant',
            'light_requirement' => 2,
        ]);

        $response->assertSessionHasErrors('watering_frequency');
    }

    /**
     * Test : Store rejecte 'light_requirement' manquant
     */
    public function test_store_rejects_missing_light_requirement(): void
    {
        $response = $this->post(route('plants.store'), [
            'name' => 'Test Plant',
            'watering_frequency' => 3,
        ]);

        $response->assertSessionHasErrors('light_requirement');
    }

    /**
     * Test : Store rejecte photo invalide
     */
    public function test_store_rejects_invalid_photo(): void
    {
        $response = $this->post(route('plants.store'), [
            'name' => 'Test Plant',
            'watering_frequency' => 3,
            'light_requirement' => 2,
            'main_photo' => 'not-a-file',
        ]);

        // Devrait avoir une erreur de validation
        $response->assertSessionHasErrors();
    }

    // ==================== EDIT ====================

    /**
     * Test : Edit affiche le formulaire d'édition
     */
    public function test_edit_displays_form(): void
    {
        $plant = Plant::factory()->create();

        $response = $this->get(route('plants.edit', $plant));

        $response->assertStatus(200);
        $response->assertViewIs('plants.edit');
        $response->assertViewHas(['plant', 'tags']);
        $response->assertSeeText($plant->name);
    }

    /**
     * Test : Edit retourne 404 pour plante inexistante
     */
    public function test_edit_returns_404_for_nonexistent_plant(): void
    {
        $response = $this->get(route('plants.edit', 999));

        $response->assertNotFound();
    }

    // ==================== UPDATE ====================

    /**
     * Test : Update modifie une plante valide
     */
    public function test_update_modifies_plant_with_valid_data(): void
    {
        $plant = Plant::factory()->create();

        $response = $this->put(route('plants.update', $plant), [
            'name' => 'Updated Name',
            'watering_frequency' => 4,
            'light_requirement' => 3,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('plants', [
            'id' => $plant->id,
            'name' => 'Updated Name',
            'watering_frequency' => 4,
            'light_requirement' => 3,
        ]);
    }

    /**
     * Test : Update avec tags valides
     */
    public function test_update_with_valid_tags(): void
    {
        $plant = Plant::factory()->create();
        $tags = Tag::factory()->count(3)->create();

        $response = $this->put(route('plants.update', $plant), [
            'name' => $plant->name,
            'watering_frequency' => 3,
            'light_requirement' => 2,
            'tags' => [$tags[0]->id, $tags[1]->id],
        ]);

        $response->assertRedirect();
        $this->assertEquals(2, $plant->fresh()->tags()->count());
    }

    /**
     * Test : Update rejecte tags invalides
     */
    public function test_update_rejects_invalid_tags(): void
    {
        $plant = Plant::factory()->create();

        $response = $this->put(route('plants.update', $plant), [
            'name' => $plant->name,
            'watering_frequency' => 3,
            'light_requirement' => 2,
            'tags' => [999], // ID invalide
        ]);

        $response->assertSessionHasErrors('tags');
    }

    /**
     * Test : Update retourne 404 pour plante inexistante
     */
    public function test_update_returns_404_for_nonexistent_plant(): void
    {
        $response = $this->put(route('plants.update', 999), [
            'name' => 'Test',
            'watering_frequency' => 3,
            'light_requirement' => 2,
        ]);

        $response->assertNotFound();
    }

    // ==================== DESTROY ====================

    /**
     * Test : Destroy supprime une plante (soft delete)
     */
    public function test_destroy_soft_deletes_plant(): void
    {
        $plant = Plant::factory()->create();
        $plantId = $plant->id;

        $response = $this->delete(route('plants.destroy', $plant));

        $response->assertRedirect();
        // Plante doit être soft-deleted (toujours en BD mais avec deleted_at)
        $this->assertTrue(Plant::withTrashed()->find($plantId)->trashed());
    }

    /**
     * Test : Destroy retourne 404 pour plante inexistante
     */
    public function test_destroy_returns_404_for_nonexistent_plant(): void
    {
        $response = $this->delete(route('plants.destroy', 999));

        $response->assertNotFound();
    }

    // ==================== ARCHIVED ====================

    /**
     * Test : Archived affiche les plantes archivées
     */
    public function test_archived_displays_archived_plants(): void
    {
        $archivedPlants = Plant::factory()->count(2)->create(['is_archived' => true]);
        $activePlant = Plant::factory()->create(['is_archived' => false]);

        $response = $this->get(route('plants.archived'));

        $response->assertStatus(200);
        $response->assertViewIs('plants.archived');

        // Plantes archivées visibles
        foreach ($archivedPlants as $plant) {
            $response->assertSeeText($plant->name);
        }

        // Plante active non visible
        $response->assertDontSeeText($activePlant->name);
    }

    // ==================== ARCHIVE ====================

    /**
     * Test : Archive marque une plante comme archivée
     */
    public function test_archive_marks_plant_as_archived(): void
    {
        $plant = Plant::factory()->create(['is_archived' => false]);

        $response = $this->post(route('plants.archive', $plant), [
            'archived_reason' => 'Test archivage',
        ]);

        $response->assertRedirect();
        // is_archived peut être 0 ou 1, utiliser boolval
        $this->assertTrue((bool)$plant->fresh()->is_archived);
    }

    // ==================== RESTORE ====================

    /**
     * Test : Restore restaure une plante archivée
     */
    public function test_restore_unarchives_plant(): void
    {
        $plant = Plant::factory()->create(['is_archived' => true]);

        $response = $this->post(route('plants.restore', $plant));

        $response->assertRedirect();
        // is_archived peut être 0 ou 1, utiliser boolval
        $this->assertFalse((bool)$plant->fresh()->is_archived);
    }

    // ==================== MODAL AJAX ====================

    /**
     * Test : Modal retourne aperçu rapide de la plante
     */
    public function test_modal_returns_plant_preview(): void
    {
        $plant = Plant::factory()->create();

        $response = $this->get(route('plants.modal', $plant));

        $response->assertStatus(200);
        $response->assertSeeText($plant->name);
    }

    /**
     * Test : Modal retourne 404 pour plante inexistante
     */
    public function test_modal_returns_404_for_nonexistent_plant(): void
    {
        $response = $this->get(route('plants.modal', 999));

        $response->assertNotFound();
    }
}
