<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $plant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="mb-4">
            <a href="{{ route('plants.index') }}" class="text-blue-500">&larr; Retour à la liste</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="md:flex">
                <!-- Photo principale -->
                <div class="md:w-1/3 p-4">
                    @if($plant->main_photo)
                        <img src="{{ Storage::url($plant->main_photo) }}" alt="{{ $plant->name }}" class="w-full h-auto rounded">
                    @else
                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded">
                            <span class="text-gray-500">Aucune photo</span>
                        </div>
                    @endif
                </div>
                
                <!-- Informations principales -->
                <div class="md:w-2/3 p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold">{{ $plant->name }}</h1>
                            @if($plant->scientific_name)
                                <p class="text-gray-600 italic">{{ $plant->scientific_name }}</p>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('plants.edit', $plant) }}" class="bg-yellow-500 text-white px-3 py-1 rounded">Modifier</a>
                            <form action="{{ route('plants.destroy', $plant) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette plante ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Supprimer</button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h2 class="text-lg font-semibold">Informations générales</h2>
                            <ul class="mt-2">
                                <li><strong>Catégorie:</strong> {{ $plant->category->name ?? 'Non spécifiée' }}</li>
                                @if($plant->purchase_date)
                                    <li><strong>Date d'achat:</strong> {{ $plant->purchase_date->format('d/m/Y') }}</li>
                                @endif
                                @if($plant->purchase_place)
                                    <li><strong>Lieu d'achat:</strong> {{ $plant->purchase_place }}</li>
                                @endif
                            </ul>
                        </div>
                        
                        <div>
                            <h2 class="text-lg font-semibold">Besoins</h2>
                            <ul class="mt-2">
                                <li><strong>Arrosage:</strong> {{ \App\Models\Plant::$wateringLabels[$plant->watering_frequency] }}</li>
                                <li><strong>Lumière:</strong> {{ \App\Models\Plant::$lightLabels[$plant->light_requirement] }}</li>
                                @if($plant->temperature_min && $plant->temperature_max)
                                    <li><strong>Température:</strong> {{ $plant->temperature_min }}°C - {{ $plant->temperature_max }}°C</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    
                    @if($plant->description)
                        <div class="mt-4">
                            <h2 class="text-lg font-semibold">Description</h2>
                            <p class="mt-2">{{ $plant->description }}</p>
                        </div>
                    @endif
                    
                    @if($plant->tags->count() > 0)
                        <div class="mt-4">
                            <h2 class="text-lg font-semibold">Tags</h2>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($plant->tags as $tag)
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Photos galerie -->
            @if($plant->photos->count() > 0)
                <div class="p-4 border-t">
                    <h2 class="text-lg font-semibold">Galerie photos</h2>
                    <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($plant->photos as $photo)
                            <div class="aspect-square bg-gray-100 rounded overflow-hidden">
                                <img src="{{ Storage::url($photo->filename) }}" alt="{{ $plant->name }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>