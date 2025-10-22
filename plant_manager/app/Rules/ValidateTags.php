<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Tag;

class ValidateTags implements ValidationRule
{
    /**
     * Valide que tous les tag IDs existent en base de données.
     *
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Vérifier que c'est un array
        if (!is_array($value)) {
            $fail('Les tags doivent être un tableau.');
            return;
        }

        // Récupérer tous les IDs de tags valides en base
        $validTagIds = Tag::pluck('id')->toArray();

        // Vérifier chaque tag dans le formulaire
        foreach ($value as $tagId) {
            // Vérifier que c'est un entier
            if (!is_numeric($tagId)) {
                $fail("Le tag ID '{$tagId}' n'est pas valide (doit être numérique).");
                return;
            }

            // Vérifier que le tag existe en base
            if (!in_array((int)$tagId, $validTagIds)) {
                $fail("Le tag avec l'ID {$tagId} n'existe pas en base de données.");
                return;
            }
        }
    }
}
