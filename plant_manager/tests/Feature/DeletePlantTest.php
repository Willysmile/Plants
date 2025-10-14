<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Plant;

class DeletePlantTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_plant_and_related_pivot_and_photos_in_db(): void
    {
        Storage::fake('public');

        $category = Category::create(['name' => 'Cat Del']);
        $tag = Tag::create(['name' => 'ToRemove']);

        $plant = Plant::create([
            'name' => 'Plante à supprimer',
            'category_id' => $category->id,
            'watering_frequency' => 2,
            'light_requirement' => 2,
        ]);

        // stocker une photo et créer l'enregistrement DB
        $file = UploadedFile::fake()->image('del.jpg');
        $path = $file->store("plants/{$plant->id}", 'public');
        $photo = $plant->photos()->create([
            'filename' => $path,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'is_main' => true,
        ]);

        // attacher un tag (pivot)
        $plant->tags()->attach($tag->id);

        // Supprimer via route
        $response = $this->delete(route('plants.destroy', $plant));
        $response->assertRedirect(route('plants.index'));

        // Vérifier suppression en base
        $this->assertDatabaseMissing('plants', ['id' => $plant->id]);
        $this->assertDatabaseMissing('photos', ['id' => $photo->id]);
        $this->assertDatabaseMissing('plant_tag', ['plant_id' => $plant->id, 'tag_id' => $tag->id]);

        // Note: par défaut les fichiers sur disque ne sont pas supprimés automatiquement.
        // Si tu souhaites supprimer les fichiers à la suppression de la plante,
        // implémente la suppression dans le modèle ou le contrôleur et teste la présence/absence via Storage assertions.
    }
}