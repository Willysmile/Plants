<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Plant;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlantTagValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Créer et authentifier un utilisateur avant chaque test
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * Test : Tags valides sont acceptés lors de la création d'une plante
     */
    public function test_valid_tags_are_accepted_when_creating_plant(): void
    {
        // Créer des tags de test
        $tag1 = Tag::factory()->create();
        $tag2 = Tag::factory()->create();

        $response = $this->post(route('plants.store'), [
            'name' => 'Test Plant',
            'watering_frequency' => 3,
            'light_requirement' => 2,
            'tags' => [$tag1->id, $tag2->id],
        ]);

        // Doit rediriger (succès)
        $response->assertRedirect(route('plants.index'));

        // Vérifier que la plante est créée avec les tags
        $plant = Plant::where('name', 'Test Plant')->first();
        $this->assertNotNull($plant);
        $this->assertEquals(2, $plant->tags()->count());
    }

    /**
     * Test : Tags invalides (IDs inexistants) sont rejetés avec 302 + erreurs session
     */
    public function test_invalid_tag_ids_are_rejected(): void
    {
        $response = $this->post(route('plants.store'), [
            'name' => 'Test Plant',
            'watering_frequency' => 3,
            'light_requirement' => 2,
            'tags' => [999, 1000], // IDs inexistants
        ]);

        // Doit rediriger avec erreurs (302)
        $response->assertStatus(302);
        $response->assertSessionHasErrors('tags');
    }

    /**
     * Test : Tags non-numériques sont rejetés
     */
    public function test_non_numeric_tag_ids_are_rejected(): void
    {
        $response = $this->post(route('plants.store'), [
            'name' => 'Test Plant',
            'watering_frequency' => 3,
            'light_requirement' => 2,
            'tags' => ['invalid', 'tags'], // Non-numériques
        ]);

        // Doit rediriger avec erreurs (302)
        $response->assertStatus(302);
        $response->assertSessionHasErrors('tags');
    }

    /**
     * Test : Tags mélangés (valides + invalides) sont rejetés
     */
    public function test_mixed_valid_and_invalid_tags_are_rejected(): void
    {
        $validTag = Tag::factory()->create();

        $response = $this->post(route('plants.store'), [
            'name' => 'Test Plant',
            'watering_frequency' => 3,
            'light_requirement' => 2,
            'tags' => [$validTag->id, 999], // Mix
        ]);

        // Doit rediriger avec erreurs (302)
        $response->assertStatus(302);
        $response->assertSessionHasErrors('tags');
    }

    /**
     * Test : Tags vides sont acceptés (nullable)
     */
    public function test_empty_tags_array_is_accepted(): void
    {
        $response = $this->post(route('plants.store'), [
            'name' => 'Test Plant',
            'watering_frequency' => 3,
            'light_requirement' => 2,
            'tags' => [], // Vide mais valide
        ]);

        $response->assertRedirect(route('plants.index'));
        $plant = Plant::where('name', 'Test Plant')->first();
        $this->assertEquals(0, $plant->tags()->count());
    }

    /**
     * Test : Tags null sont acceptés (nullable)
     */
    public function test_null_tags_is_accepted(): void
    {
        $response = $this->post(route('plants.store'), [
            'name' => 'Test Plant',
            'watering_frequency' => 3,
            'light_requirement' => 2,
            // Pas de 'tags' envoyé
        ]);

        $response->assertRedirect(route('plants.index'));
        $plant = Plant::where('name', 'Test Plant')->first();
        $this->assertEquals(0, $plant->tags()->count());
    }

    /**
     * Test : Lors de l'édition, tags invalides sont rejetés
     */
    public function test_invalid_tags_on_update_are_rejected(): void
    {
        $plant = Plant::factory()->create();
        $plant->tags()->sync([]);

        $response = $this->put(route('plants.update', $plant), [
            'name' => 'Updated Plant',
            'watering_frequency' => 3,
            'light_requirement' => 2,
            'tags' => [999], // ID invalide
        ]);

        // Doit rediriger avec erreurs (302)
        $response->assertStatus(302);
        $response->assertSessionHasErrors('tags');
    }

    /**
     * Test : Tags valides lors de l'édition fonctionnent
     */
    public function test_valid_tags_on_update_work(): void
    {
        $plant = Plant::factory()->create();
        $tag1 = Tag::factory()->create();
        $tag2 = Tag::factory()->create();

        $response = $this->put(route('plants.update', $plant), [
            'name' => 'Updated Plant',
            'watering_frequency' => 3,
            'light_requirement' => 2,
            'tags' => [$tag1->id, $tag2->id],
        ]);

        $response->assertRedirect();
        $this->assertEquals(2, $plant->fresh()->tags()->count());
    }

    /**
     * Test : Tags non-array sont rejetés
     */
    public function test_non_array_tags_are_rejected(): void
    {
        $response = $this->post(route('plants.store'), [
            'name' => 'Test Plant',
            'watering_frequency' => 3,
            'light_requirement' => 2,
            'tags' => 'invalid', // String au lieu d'array
        ]);

        // Doit rediriger avec erreurs (302)
        $response->assertStatus(302);
        $response->assertSessionHasErrors('tags');
    }
}
