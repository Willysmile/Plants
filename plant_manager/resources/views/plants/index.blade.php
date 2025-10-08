<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Plantes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Mes Plantes</h1>
            <a href="{{ route('plants.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">Ajouter une plante</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($plants as $plant)
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between">
                        <h2 class="text-xl font-semibold">{{ $plant->name }}</h2>
                        <span class="text-sm text-gray-500">{{ $plant->category->name ?? 'Sans catégorie' }}</span>
                    </div>
                    <p class="text-gray-600 italic">{{ $plant->scientific_name }}</p>
                    
                    @if($plant->main_photo)
                        <img src="{{ Storage::url($plant->main_photo) }}" alt="{{ $plant->name }}" class="mt-2 w-full h-40 object-cover rounded">
                    @endif

                    <div class="mt-4 flex justify-between">
                        <a href="{{ route('plants.show', $plant) }}" class="text-blue-500">Détails</a>
                        <a href="{{ route('plants.edit', $plant) }}" class="text-yellow-500">Modifier</a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-8">
                    <p>Aucune plante trouvée. Ajoutez votre première plante !</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $plants->links() }}
        </div>
    </div>
</body>
</html>