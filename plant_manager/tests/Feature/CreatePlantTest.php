<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Plant;

class CreatePlantTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: création d'une plante via la route plants.store
     * Vérifie la redirection, l'enregistrement en base, le pivot tags et le stockage de la photo.
     */
    public function test_user_can_create_plant_with_photo_and_tags(): void
    {
        Storage::fake('public');

        // Préparer données nécessaires
        $category = Category::create(['name' => 'Test Category']);
        $tag1 = Tag::create(['name' => 'Tag A']);
        $tag2 = Tag::create(['name' => 'Tag B']);

        // Fichier image factice
        $file = UploadedFile::fake()->image('plant.jpg');

        // Appel POST vers la route de création (form multipart géré automatiquement)
        $response = $this->post(route('plants.store'), [
            'name' => 'Test Plant',
            'scientific_name' => 'Specimen test',
            'category_id' => $category->id,
            'watering_frequency' => 3,
            'light_requirement' => 4,
            'description' => 'Une plante de test',
            'main_photo' => $file,
            'tags' => [$tag1->id, $tag2->id],
        ]);

        // Assertions basiques de flux
        $response->assertRedirect(route('plants.index'));

        // Vérifier en base
        $this->assertDatabaseHas('plants', [
            'name' => 'Test Plant',
            'category_id' => $category->id,
        ]);

        $plant = Plant::where('name', 'Test Plant')->first();
        $this->assertNotNull($plant);

        // Vérifier que la photo a été stockée sur le disque public et référencée
        $this->assertNotEmpty($plant->main_photo);
        Storage::disk('public')->assertExists($plant->main_photo);

        // Vérifier liaison tags (pivot)
        // éviter l'ambiguïté : qualifier la colonne ou charger la relation puis pluck côté collection
        // Option A (qualifier) :
        $tagsIds = $plant->tags()->pluck('tags.id')->toArray();
        // Option B (charger la relation puis pluck côté collection) :
        // $plant->load('tags');
        // $tagsIds = $plant->tags->pluck('id')->toArray();

        $this->assertEqualsCanonicalizing([$tag1->id, $tag2->id], $tagsIds);
    }
}