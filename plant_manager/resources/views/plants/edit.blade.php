<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier {{ $plant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="mb-4">
            <a href="{{ route('plants.show', $plant) }}" class="text-blue-500">&larr; Retour au détail</a>
        </div>

        <h1 class="text-2xl font-bold mb-6">Modifier {{ $plant->name }}</h1>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('plants.update', $plant) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Champs obligatoires -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom *</label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $plant->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label for="scientific_name" class="block text-sm font-medium text-gray-700">Nom scientifique</label>
                    <input type="text" name="scientific_name" id="scientific_name" value="{{ old('scientific_name', $plant->scientific_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Catégorie *</label>
                    <select name="category_id" id="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Sélectionner...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $plant->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="watering_frequency" class="block text-sm font-medium text-gray-700">Fréquence d'arrosage *</label>
                    <select name="watering_frequency" id="watering_frequency" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @foreach(\App\Models\Plant::$wateringLabels as $key => $label)
                            <option value="{{ $key }}" {{ old('watering_frequency', $plant->watering_frequency) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="light_requirement" class="block text-sm font-medium text-gray-700">Besoin en lumière *</label>
                    <select name="light_requirement" id="light_requirement" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @foreach(\App\Models\Plant::$lightLabels as $key => $label)
                            <option value="{{ $key }}" {{ old('light_requirement', $plant->light_requirement) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $plant->description) }}</textarea>
            </div>

            <!-- Tags -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">Tags</label>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                {{ in_array($tag->id, old('tags', $plant->tags->pluck('id')->toArray())) ? 'checked' : '' }} 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm">
                            <span class="ml-2">{{ $tag->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Photo actuelle + changement -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">Photo principale</label>
                
                @if($plant->main_photo)
                    <div class="mt-2 flex items-center">
                        <img src="{{ Storage::url($plant->main_photo) }}" alt="{{ $plant->name }}" class="h-20 w-auto rounded">
                        <span class="ml-2 text-sm text-gray-500">Photo actuelle</span>
                    </div>
                @endif
                
                <input type="file" name="main_photo" id="main_photo" accept="image/*" class="mt-2 block w-full">
                <p class="text-sm text-gray-500">Laissez vide pour conserver l'image actuelle</p>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-between">
                <a href="{{ route('plants.show', $plant) }}" class="bg-gray-300 px-4 py-2 rounded">Annuler</a>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Enregistrer</button>
            </div>
        </form>
    </div>
</body>
</html>