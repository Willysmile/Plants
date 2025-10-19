@extends('layouts.simple')

@section('title', 'Éditer le rempotage - ' . $plant->name)

@section('content')
  <div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center mb-6">
      <a href="{{ route('plants.repotting-history.index', $plant) }}" class="text-blue-500 hover:text-blue-700">
        ← Retour
      </a>
      <h1 class="text-3xl font-bold text-gray-900 ml-4">Éditer le rempotage</h1>
    </div>

    <x-history-form :plant="$plant" :history="$reppotingHistory" type="repotting" />
  </div>
@endsection

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
