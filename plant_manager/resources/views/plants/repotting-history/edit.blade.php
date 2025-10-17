<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Modifier rempotage - {{ $plant->name }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-gray-50">

<div class="container mx-auto px-4 py-6 max-w-2xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('plants.repotting-history.index', $plant) }}" class="text-blue-500 hover:text-blue-700">
            ← Retour
        </a>
        <h1 class="text-3xl font-bold text-gray-900 ml-4">Éditer le rempotage</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('plants.repotting-history.update', [$plant, $reppotingHistory]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Plante : {{ $plant->name }}</label>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="repotting_date">
                    Date et heure du rempotage <span class="text-red-500">*</span>
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('repotting_date') border-red-500 @enderror" 
                    type="datetime-local" 
                    id="repotting_date" 
                    name="repotting_date" 
                    value="{{ old('repotting_date', $reppotingHistory->repotting_date->format('Y-m-d\TH:i')) }}" 
                    required>
                @error('repotting_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="old_pot_size">
                    Ancien pot (taille)
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('old_pot_size') border-red-500 @enderror" 
                    type="text" 
                    id="old_pot_size" 
                    name="old_pot_size" 
                    value="{{ old('old_pot_size', $reppotingHistory->old_pot_size) }}" 
                    placeholder="ex: 15cm, pot 3L...">
                @error('old_pot_size')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="new_pot_size">
                    Nouveau pot (taille) <span class="text-red-500">*</span>
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('new_pot_size') border-red-500 @enderror" 
                    type="text" 
                    id="new_pot_size" 
                    name="new_pot_size" 
                    value="{{ old('new_pot_size', $reppotingHistory->new_pot_size) }}" 
                    placeholder="ex: 20cm, pot 5L..." 
                    required>
                @error('new_pot_size')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="soil_type">
                    Type de terreau
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('soil_type') border-red-500 @enderror" 
                    type="text" 
                    id="soil_type" 
                    name="soil_type" 
                    value="{{ old('soil_type', $reppotingHistory->soil_type) }}" 
                    placeholder="ex: Terreau universel, Terreau pour orchidées...">
                @error('soil_type')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="notes">
                    Notes
                </label>
                <textarea class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('notes') border-red-500 @enderror" 
                    id="notes" 
                    name="notes" 
                    rows="4" 
                    placeholder="Observations, remarques...">{{ old('notes', $reppotingHistory->notes) }}</textarea>
                @error('notes')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('plants.repotting-history.index', $plant) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Annuler
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
