<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Plant;
use App\Models\Photo;

class UpdatePlantTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_plant_and_replace_main_photo_and_tags(): void
    {
        Storage::fake('public');

        // Préparer données
        $category = Category::create(['name' => 'Cat A']);
        $tagOld = Tag::create(['name' => 'Old']);
        $tagNew1 = Tag::create(['name' => 'New A']);
        $tagNew2 = Tag::create(['name' => 'New B']);

        // Créer plante initiale
        $plant = Plant::create([
            'name' => 'Plante Initiale',
            'category_id' => $category->id,
            'watering_frequency' => 3,
            'light_requirement' => 3,
        ]);

        // Créer et attacher ancienne photo (fichier stocké)
        $oldFile = UploadedFile::fake()->image('old.jpg');
        $oldPath = $oldFile->store("plants/{$plant->id}", 'public');
        $plant->update(['main_photo' => $oldPath]);
        $plant->photos()->create([
            'filename' => $oldPath,
            'mime_type' => $oldFile->getClientMimeType(),
            'size' => $oldFile->getSize(),
            'is_main' => true,
        ]);

        // Attacher ancien tag
        $plant->tags()->attach($tagOld->id);

        // Préparer nouveau fichier et données de mise à jour
        $newFile = UploadedFile::fake()->image('new.jpg');

        $response = $this->put(route('plants.update', $plant), [
            'name' => 'Plante Modifiée',
            'category_id' => $category->id,
            'watering_frequency' => 4,
            'light_requirement' => 5,
            'main_photo' => $newFile,
            'tags' => [$tagNew1->id, $tagNew2->id],
        ]);

        $response->assertRedirect(route('plants.show', $plant));

        $plant->refresh();

        // Vérifications
        $this->assertEquals('Plante Modifiée', $plant->name);
        $this->assertEquals(4, $plant->watering_frequency);
        // ancienne photo supprimée du disque
        Storage::disk('public')->assertMissing($oldPath);
        // nouvelle photo présente
        $this->assertNotEmpty($plant->main_photo);
        Storage::disk('public')->assertExists($plant->main_photo);

        // tags bien synchronisés
        $plant->load('tags');
        $this->assertEqualsCanonicalizing(
            [$tagNew1->id, $tagNew2->id],
            $plant->tags->pluck('id')->toArray()
        );
    }
}