<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Plant;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PhotoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Plant $plant;

    /**
     * Setup : Créer utilisateur, plante et fichiers de test
     */
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->user = User::factory()->create();
        $this->plant = Plant::factory()->create();
        $this->actingAs($this->user);
    }

    // ==================== UPDATE DESCRIPTION ====================

    /**
     * Test : Update met à jour la description d'une photo
     */
    public function test_update_modifies_photo_description(): void
    {
        $photo = Photo::factory()->create(['plant_id' => $this->plant->id]);

        $response = $this->patch(
            route('plants.photos.update', [$this->plant, $photo]),
            ['description' => 'Updated description']
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('photos', [
            'id' => $photo->id,
            'description' => 'Updated description',
        ]);
    }

    /**
     * Test : Update avec description vide
     */
    public function test_update_accepts_empty_description(): void
    {
        $photo = Photo::factory()->create(['plant_id' => $this->plant->id, 'description' => 'Old description']);

        $response = $this->patch(
            route('plants.photos.update', [$this->plant, $photo]),
            ['description' => '']
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('photos', [
            'id' => $photo->id,
            'description' => null,
        ]);
    }

    /**
     * Test : Update retourne 404 si photo n'appartient pas à la plante
     */
    public function test_update_returns_404_if_photo_not_owned_by_plant(): void
    {
        $otherPlant = Plant::factory()->create();
        $photo = Photo::factory()->create(['plant_id' => $otherPlant->id]);

        $response = $this->patch(
            route('plants.photos.update', [$this->plant, $photo]),
            ['description' => 'New description']
        );

        $response->assertNotFound();
    }

    /**
     * Test : Update JSON retourne JSON response
     */
    public function test_update_returns_json_for_ajax_request(): void
    {
        $photo = Photo::factory()->create(['plant_id' => $this->plant->id]);

        $response = $this->patchJson(
            route('plants.photos.update', [$this->plant, $photo]),
            ['description' => 'JSON description']
        );

        $response->assertStatus(200);
        $response->assertJson([
            'ok' => true,
            'photo_id' => $photo->id,
        ]);
    }

    /**
     * Test : Update rejette description trop longue
     */
    public function test_update_rejects_long_description(): void
    {
        $photo = Photo::factory()->create(['plant_id' => $this->plant->id]);
        $longDescription = str_repeat('a', 1001); // > 1000 caractères

        $response = $this->patch(
            route('plants.photos.update', [$this->plant, $photo]),
            ['description' => $longDescription]
        );

        $response->assertSessionHasErrors('description');
    }

    // ==================== DESTROY ====================

    /**
     * Test : Destroy supprime une photo (soft delete)
     */
    public function test_destroy_soft_deletes_photo(): void
    {
        $photo = Photo::factory()->create(['plant_id' => $this->plant->id]);
        $photoId = $photo->id;

        $response = $this->delete(route('plants.photos.destroy', [$this->plant, $photo]));

        $response->assertRedirect();
        // Photo peut être soft-deleted, vérifier qu'elle n'apparaît pas
        $this->assertNull(Photo::find($photoId));
    }

    /**
     * Test : Destroy supprime le fichier du disque
     */
    public function test_destroy_deletes_file_from_storage(): void
    {
        Storage::disk('public')->put('plants/1/test.jpg', 'fake image content');
        $photo = Photo::factory()->create([
            'plant_id' => $this->plant->id,
            'filename' => 'plants/1/test.jpg',
        ]);

        $this->assertTrue(Storage::disk('public')->exists('plants/1/test.jpg'));

        $response = $this->delete(route('plants.photos.destroy', [$this->plant, $photo]));

        $response->assertRedirect();
        $this->assertFalse(Storage::disk('public')->exists('plants/1/test.jpg'));
    }

    /**
     * Test : Destroy retourne 404 si photo n'appartient pas à la plante
     */
    public function test_destroy_returns_404_if_photo_not_owned_by_plant(): void
    {
        $otherPlant = Plant::factory()->create();
        $photo = Photo::factory()->create(['plant_id' => $otherPlant->id]);

        $response = $this->delete(route('plants.photos.destroy', [$this->plant, $photo]));

        $response->assertNotFound();
    }

    /**
     * Test : Destroy requiert authentification
     */
    public function test_destroy_requires_authentication(): void
    {
        $photo = Photo::factory()->create(['plant_id' => $this->plant->id]);

        $response = $this->get(route('login'));
        $response = $this->delete(route('plants.photos.destroy', [$this->plant, $photo]));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test : Destroy retourne 404 pour photo inexistante
     */
    public function test_destroy_returns_404_for_nonexistent_photo(): void
    {
        $response = $this->delete(route('plants.photos.destroy', [$this->plant, 999]));

        $response->assertNotFound();
    }
}
