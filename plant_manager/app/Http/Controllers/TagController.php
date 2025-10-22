<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;

class TagController extends Controller
{
    /**
     * Display a listing of the tags grouped by category
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
     * Show the form for creating a new tag
     */
    public function create()
    {
        $categories = $this->getCategories();
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
        $categories = $this->getCategories();
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
     * Get available categories for tag selection
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
