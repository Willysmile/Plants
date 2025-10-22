<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:tags,name',
            'tag_category_id' => 'required|exists:tag_categories,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du tag est obligatoire.',
            'name.unique' => 'Ce tag existe déjà.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'tag_category_id.required' => 'La catégorie est obligatoire.',
            'tag_category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
        ];
    }
}
