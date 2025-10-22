<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\TagCategory;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;

class TagController extends Controller
{
    /**
     * Display a listing of the tags grouped by category
     */
    public function index()
    {
        $categories = TagCategory::with('tags')
                                  ->orderBy('name')
                                  ->get();
        
        $tags = Tag::with('tagCategory')
                   ->orderBy('name')
                   ->get()
                   ->groupBy('category');

        return view('admin.tags.index', compact('tags', 'categories'));
    }

    /**
     * Show the form for creating a new tag
     */
    public function create()
    {
        $categories = TagCategory::orderBy('name')->get();
        return view('admin.tags.create', compact('categories'));
    }

    /**
     * Store a newly created tag in storage
     */
    public function store(StoreTagRequest $request)
    {
        Tag::create($request->validated());

        return redirect()->route('tags.index')
                       ->with('success', 'Tag créé avec succès.');
    }

    /**
     * Show the form for editing the specified tag
     */
    public function edit(Tag $tag)
    {
        $categories = TagCategory::orderBy('name')->get();
        return view('admin.tags.edit', compact('tag', 'categories'));
    }

    /**
     * Update the specified tag in storage
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $tag->update($request->validated());

        return redirect()->route('tags.index')
                       ->with('success', 'Tag modifié avec succès.');
    }

    /**
     * Remove the specified tag from storage
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('tags.index')
                       ->with('success', 'Tag supprimé avec succès.');
    }

    /**
     * Remove all tags from a category
     */
    public function destroyCategory(TagCategory $tagCategory)
    {
        $tagsCount = $tagCategory->tags()->count();
        $categoryName = $tagCategory->name;
        
        // Supprime d'abord les tags
        $tagCategory->tags()->delete();
        
        // Puis supprime la catégorie
        $tagCategory->delete();

        return redirect()->route('tags.index')
                       ->with('success', "Catégorie '{$categoryName}' et ses {$tagsCount} tag(s) supprimés avec succès.");
    }
}
