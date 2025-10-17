<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Éditer la fertilisation - {{ $plant->name }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-gray-50">

<div class="container mx-auto px-4 py-6 max-w-2xl">
    <div class="flex items-center mb-6">
        <a href="{{ route('plants.fertilizing-history.index', $plant) }}" class="text-blue-500 hover:text-blue-700">
            ← Retour
        </a>
        <h1 class="text-3xl font-bold text-gray-900 ml-4">Éditer la fertilisation</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('plants.fertilizing-history.update', [$plant, $fertilizingHistory]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Plante : {{ $plant->name }}</label>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="fertilizing_date">
                    Date et heure de fertilisation <span class="text-red-500">*</span>
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('fertilizing_date') border-red-500 @enderror" 
                    type="datetime-local" 
                    id="fertilizing_date" 
                    name="fertilizing_date" 
                    value="{{ old('fertilizing_date', $fertilizingHistory->fertilizing_date->format('Y-m-d\TH:i')) }}" 
                    required>
                @error('fertilizing_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="fertilizer_type">
                    Type d'engrais
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('fertilizer_type') border-red-500 @enderror" 
                    type="text" 
                    id="fertilizer_type" 
                    name="fertilizer_type" 
                    value="{{ old('fertilizer_type', $fertilizingHistory->fertilizer_type) }}" 
                    placeholder="ex: Engrais NPK, Engrais biologique...">
                @error('fertilizer_type')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="amount">
                    Quantité
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error('amount') border-red-500 @enderror" 
                    type="text" 
                    id="amount" 
                    name="amount" 
                    value="{{ old('amount', $fertilizingHistory->amount) }}" 
                    placeholder="ex: 10ml, 1 cuillère à soupe...">
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
                    placeholder="Observations, remarques...">{{ old('notes', $fertilizingHistory->notes) }}</textarea>
                @error('notes')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('plants.fertilizing-history.index', $plant) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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
