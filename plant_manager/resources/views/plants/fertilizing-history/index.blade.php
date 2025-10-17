<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $plant->name }} - Historique de fertilisation</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-gray-50">

<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $plant->name }}</h1>
            <p class="text-gray-600">Historique de fertilisation</p>
        </div>
        <div class="space-x-3">
            <a href="{{ route('plants.show', $plant) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Retour
            </a>
            <a href="{{ route('plants.fertilizing-history.create', $plant) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Nouvelle fertilisation
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($histories->count())
        <div class="grid gap-4">
            @foreach($histories as $history)
                <div class="bg-white rounded-lg shadow-md p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-gray-500">{{ $history->fertilizing_date->format('d/m/Y à H:i') }}</p>
                            @if($history->fertilizer_type)
                                <p class="text-gray-700"><span class="font-semibold">Type d'engrais :</span> {{ $history->fertilizer_type }}</p>
                            @endif
                            @if($history->amount)
                                <p class="text-gray-700"><span class="font-semibold">Quantité :</span> {{ $history->amount }}</p>
                            @endif
                            @if($history->notes)
                                <p class="text-gray-700 mt-2"><span class="font-semibold">Notes :</span> {{ $history->notes }}</p>
                            @endif
                        </div>
                        <div class="space-x-2">
                            <a href="{{ route('plants.fertilizing-history.edit', [$plant, $history]) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-sm">
                                Éditer
                            </a>
                            <form action="{{ route('plants.fertilizing-history.destroy', [$plant, $history]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm" onclick="return confirm('Êtes-vous sûr ?')">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $histories->links() }}
        </div>
    @else
        <div class="bg-gray-100 border border-gray-300 text-gray-700 px-4 py-3 rounded">
            Aucun historique de fertilisation enregistré.
        </div>
    @endif
</div>

</body>
</html>
