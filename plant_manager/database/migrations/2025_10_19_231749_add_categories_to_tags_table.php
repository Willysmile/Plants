<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Catégoriser les tags existants
        DB::table('tags')->whereIn('name', ['Tropicale', 'Subtropicale', 'Méditerranéenne', 'Désertique', 'Tempérée', 'Alpine', 'Équatoriale'])->update(['category' => 'Climat']);
        
        DB::table('tags')->whereIn('name', ['Feuillage persistant', 'Feuillage caduc', 'Feuillage panaché', 'Feuillage décoratif', 'Feuillage argenté', 'Feuillage pourpre/rouge', 'Feuillage brillant', 'Feuillage duveteux', 'Feuillage épineux', 'Feuillage charnu', 'Feuillage découpé'])->update(['category' => 'Feuillage']);
        
        DB::table('tags')->whereIn('name', ['Succulente', 'Épiphyte', 'Aquatique', 'Carnivore', 'Aromatique', 'Bulbeuse', 'Rhizomateuse'])->update(['category' => 'Type']);
        
        DB::table('tags')->whereIn('name', ['Grimpante', 'Rampante', 'Retombante', 'Arbustive', 'Arborescente', 'En rosette', 'Tapissante', 'Érigée'])->update(['category' => 'Forme']);
        
        DB::table('tags')->whereIn('name', ['Floraison décorative', 'Floraison parfumée', 'Floraison longue durée', 'Floraison printanière', 'Floraison estivale', 'Floraison automnale', 'Floraison hivernale', 'Remontante'])->update(['category' => 'Floraison']);
        
        DB::table('tags')->whereIn('name', ['Miniature', 'Petite', 'Moyenne', 'Grande', 'Très grande'])->update(['category' => 'Taille']);
        
        DB::table('tags')->whereIn('name', ['Croissance rapide', 'Croissance moyenne', 'Croissance lente'])->update(['category' => 'Croissance']);
        
        DB::table('tags')->whereIn('name', ['Dépolluante', 'Mellifère', 'Toxique', 'Pet-friendly', 'Résistante aux maladies', 'Résistante au gel', 'Résistante à la sécheresse', 'Facile d\'entretien'])->update(['category' => 'Caractéristiques']);
        
        DB::table('tags')->whereIn('name', ['Racines aériennes', 'Racines superficielles', 'Racines profondes'])->update(['category' => 'Système racinaire']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('tags')->update(['category' => null]);
    }
};
