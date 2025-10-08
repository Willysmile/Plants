<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Autorise tous les utilisateurs (à adapter selon votre logique d'authentification)
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // Champs obligatoires
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'watering_frequency' => 'required|integer|min:1|max:5',
            'light_requirement' => 'required|integer|min:1|max:5',
            
            // Informations générales
            'scientific_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            
            // Informations d'achat
            'purchase_date' => 'nullable|date',
            'purchase_place' => 'nullable|string|max:255',
            'purchase_price' => 'nullable|numeric|min:0',
            
            // Conditions de culture
            'temperature_min' => 'nullable|numeric',
            'temperature_max' => 'nullable|numeric',
            'humidity_level' => 'nullable|string|max:255',
            'soil_humidity' => 'nullable|string|max:255',
            'soil_ideal_ph' => 'nullable|numeric|between:0,14',
            'soil_type' => 'nullable|string|max:255',
            
            // Informations complémentaires
            'info_url' => 'nullable|url|max:2048',
            'location' => 'nullable|string|max:255',
            'pot_size' => 'nullable|string|max:255',
            'health_status' => 'nullable|string|max:255',
            
            // Entretien
            'last_watering_date' => 'nullable|date',
            'last_fertilizing_date' => 'nullable|date',
            'fertilizing_frequency' => 'nullable|integer|min:1|max:5',
            'last_repotting_date' => 'nullable|date',
            'next_repotting_date' => 'nullable|date|after_or_equal:last_repotting_date',
            
            // Caractéristiques
            'growth_speed' => 'nullable|string|in:lente,moyenne,rapide',
            'max_height' => 'nullable|numeric|min:0',
            'is_toxic' => 'nullable|boolean',
            'flowering_season' => 'nullable|string|max:255',
            'difficulty_level' => 'nullable|integer|min:1|max:5',
            
            // Options d'affichage
            'is_indoor' => 'nullable|boolean',
            'is_outdoor' => 'nullable|boolean',
            'is_favorite' => 'nullable|boolean',
            'is_archived' => 'nullable|boolean',
            'archived_date' => 'nullable|date|required_if:is_archived,1',
            'archived_reason' => 'nullable|string|required_if:is_archived,1',
            
            // Photos
            'main_photo' => 'nullable|image|max:5120', // 5MB max
            
            // Relations
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }
    
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'La catégorie est obligatoire.',
            'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'name.required' => 'Le nom de la plante est obligatoire.',
            'watering_frequency.required' => 'La fréquence d\'arrosage est obligatoire.',
            'light_requirement.required' => 'Le besoin en lumière est obligatoire.',
            'next_repotting_date.after_or_equal' => 'La date du prochain rempotage doit être postérieure au dernier rempotage.',
            'main_photo.image' => 'Le fichier doit être une image (jpeg, png, bmp, gif, svg, ou webp).',
            'main_photo.max' => 'La photo ne doit pas dépasser 5 Mo.',
        ];
    }
}
