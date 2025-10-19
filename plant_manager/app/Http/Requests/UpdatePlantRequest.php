<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
    return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = (new StorePlantRequest())->rules(); // Réutiliser les mêmes règles
        
        // Adapter la validation unique pour la référence (exclure l'ID courant)
        if ($this->route('plant')) {
            $rules['reference'] = 'nullable|string|max:50|unique:plants,reference,' . $this->route('plant')->id;
        }
        
        return $rules;
    }
}
