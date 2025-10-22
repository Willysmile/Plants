<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTagControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin and regular user
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->user = User::factory()->create(['is_admin' => false]);
    }

    // ==================== INDEX ====================

    /**
     * Test: Admin can view tags index
     */
    public function test_admin_can_view_tags_index(): void
    {
        Tag::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)->get(route('tags.index'));

        $response->assertStatus(200);
        $response->assertViewHas('tags');
    }

    /**
     * Test: Non-admin cannot view tags index
     */
    public function test_non_admin_cannot_view_tags_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('tags.index'));

        $response->assertStatus(403);
    }

    /**
     * Test: Unauthenticated user is redirected to login
     */
    public function test_unauthenticated_redirected_to_login(): void
    {
        $response = $this->get(route('tags.index'));

        $response->assertRedirect(route('login'));
    }

    // ==================== CREATE ====================

    /**
     * Test: Admin can view create form
     */
    public function test_admin_can_view_create_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('tags.create'));

        $response->assertStatus(200);
        $response->assertViewHas('categories');
        $response->assertSeeText('Créer un nouveau Tag');
    }

    /**
     * Test: Non-admin cannot view create form
     */
    public function test_non_admin_cannot_view_create_form(): void
    {
        $response = $this->actingAs($this->user)->get(route('tags.create'));

        $response->assertStatus(403);
    }

    // ==================== STORE ====================

    /**
     * Test: Admin can create valid tag
     */
    public function test_admin_can_create_valid_tag(): void
    {
        $response = $this->actingAs($this->admin)->post(route('tags.store'), [
            'name' => 'Nouvelle Catégorie',
            'category' => 'Type de plante',
        ]);

        $response->assertRedirect(route('tags.index'));
        $this->assertDatabaseHas('tags', [
            'name' => 'Nouvelle Catégorie',
            'category' => 'Type de plante',
        ]);
    }

    /**
     * Test: Cannot create tag with duplicate name
     */
    public function test_cannot_create_duplicate_tag_name(): void
    {
        Tag::create(['name' => 'Existant', 'category' => 'Type']);

        $response = $this->actingAs($this->admin)->post(route('tags.store'), [
            'name' => 'Existant',
            'category' => 'Type',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertEquals(1, Tag::where('name', 'Existant')->count());
    }

    /**
     * Test: Cannot create tag without name
     */
    public function test_cannot_create_tag_without_name(): void
    {
        $response = $this->actingAs($this->admin)->post(route('tags.store'), [
            'name' => '',
            'category' => 'Type de plante',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test: Cannot create tag without category
     */
    public function test_cannot_create_tag_without_category(): void
    {
        $response = $this->actingAs($this->admin)->post(route('tags.store'), [
            'name' => 'Mon Tag',
            'category' => '',
        ]);

        $response->assertSessionHasErrors('category');
    }

    // ==================== EDIT ====================

    /**
     * Test: Admin can view edit form
     */
    public function test_admin_can_view_edit_form(): void
    {
        $tag = Tag::factory()->create(['name' => 'TestTag']);

        $response = $this->actingAs($this->admin)->get(route('tags.edit', $tag));

        $response->assertStatus(200);
        $response->assertSeeText('Éditer le Tag');
        $response->assertViewHas('categories');
    }

    /**
     * Test: Non-admin cannot view edit form
     */
    public function test_non_admin_cannot_view_edit_form(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->actingAs($this->user)->get(route('tags.edit', $tag));

        $response->assertStatus(403);
    }

    // ==================== UPDATE ====================

    /**
     * Test: Admin can update tag
     */
    public function test_admin_can_update_tag(): void
    {
        $tag = Tag::factory()->create(['name' => 'Original']);

        $response = $this->actingAs($this->admin)->put(route('tags.update', $tag), [
            'name' => 'Tag Modifié',
            'category' => 'Nouvelle Catégorie',
        ]);

        $response->assertRedirect(route('tags.index'));
        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
            'name' => 'Tag Modifié',
            'category' => 'Nouvelle Catégorie',
        ]);
    }

    /**
     * Test: Cannot update tag with duplicate name
     */
    public function test_cannot_update_tag_with_duplicate_name(): void
    {
        $tag1 = Tag::factory()->create(['name' => 'Tag 1']);
        $tag2 = Tag::factory()->create(['name' => 'Tag 2']);

        $response = $this->actingAs($this->admin)->put(route('tags.update', $tag2), [
            'name' => 'Tag 1',
            'category' => 'Type',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseHas('tags', ['id' => $tag2->id, 'name' => 'Tag 2']);
    }

    /**
     * Test: Can update tag with same name (allowed for current tag)
     */
    public function test_can_update_tag_keeping_same_name(): void
    {
        $tag = Tag::factory()->create(['name' => 'Original']);

        $response = $this->actingAs($this->admin)->put(route('tags.update', $tag), [
            'name' => 'Original',
            'category' => 'Nouvelle Catégorie',
        ]);

        $response->assertRedirect(route('tags.index'));
        $this->assertDatabaseHas('tags', ['id' => $tag->id, 'name' => 'Original']);
    }

    // ==================== DESTROY ====================

    /**
     * Test: Admin can delete tag
     */
    public function test_admin_can_delete_tag(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('tags.destroy', $tag));

        $response->assertRedirect(route('tags.index'));
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }

    /**
     * Test: Non-admin cannot delete tag
     */
    public function test_non_admin_cannot_delete_tag(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('tags.destroy', $tag));

        $response->assertStatus(403);
        $this->assertDatabaseHas('tags', ['id' => $tag->id]);
    }

    /**
     * Test: Delete nonexistent tag returns 404
     */
    public function test_delete_nonexistent_tag_returns_404(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('tags.destroy', 9999));

        $response->assertStatus(404);
    }

    // ==================== SUCCESS MESSAGES ====================

    /**
     * Test: Create shows success message
     */
    public function test_create_shows_success_message(): void
    {
        $this->actingAs($this->admin)->post(route('tags.store'), [
            'name' => 'New Tag',
            'category' => 'Type de plante',
        ]);

        $response = $this->actingAs($this->admin)->get(route('tags.index'));
        $response->assertSeeText('Tag créé avec succès');
    }

    /**
     * Test: Update shows success message
     */
    public function test_update_shows_success_message(): void
    {
        $tag = Tag::factory()->create();

        $this->actingAs($this->admin)->put(route('tags.update', $tag), [
            'name' => 'Updated',
            'category' => 'Type',
        ]);

        $response = $this->actingAs($this->admin)->get(route('tags.index'));
        $response->assertSeeText('Tag modifié avec succès');
    }

    /**
     * Test: Delete shows success message
     */
    public function test_delete_shows_success_message(): void
    {
        $tag = Tag::factory()->create();

        $this->actingAs($this->admin)->delete(route('tags.destroy', $tag));

        $response = $this->actingAs($this->admin)->get(route('tags.index'));
        $response->assertSeeText('Tag supprimé avec succès');

        $response->assertSeeText('Tag supprimé avec succès');
    }
}
