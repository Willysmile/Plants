<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Nouvel arrosage - {{ $plant->name }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-gray-50">

<div class="container mx-auto px-4 py-6 max-w-2xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('plants.watering-history.index', $plant) }}" class="text-blue-500 hover:text-blue-700">
            ← Retour
        </a>
        <h1 class="text-3xl font-bold text-gray-900 ml-4">Nouvel arrosage</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('plants.watering-history.store', $plant) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Plante : {{ $plant->name }}</label>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="watering_date">
                    Date et heure d'arrosage <span class="text-red-500">*</span>
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('watering_date') border-red-500 @enderror" 
                    type="datetime-local" 
                    id="watering_date" 
                    name="watering_date" 
                    value="{{ old('watering_date') }}" 
                    required>
                @error('watering_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="amount">
                    Quantité (ex: 500ml, 1L)
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('amount') border-red-500 @enderror" 
                    type="text" 
                    id="amount" 
                    name="amount" 
                    value="{{ old('amount') }}" 
                    placeholder="ex: 500ml">
                @error('amount')
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
                    placeholder="Observations, remarques...">{{ old('notes') }}</textarea>
                @error('notes')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('plants.watering-history.index', $plant) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Annuler
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
