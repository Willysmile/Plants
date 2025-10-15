<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $plant->name }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 h-screen">
  <div class="h-[98vh] max-w-6xl mx-auto px-4 py-2 flex flex-col">
    <div class="bg-white rounded-lg shadow flex flex-col flex-grow overflow-hidden">
      <!-- En-tête avec titre et boutons d'action -->
      <div class="flex items-start justify-between p-4 border-b">
        <div>
          <h1 class="text-2xl font-semibold">{{ $plant->name }}</h1>
          @if($plant->scientific_name)
            <p class="text-sm text-gray-500 italic mt-1">{{ $plant->scientific_name }}</p>
          @endif
        </div>
        <div class="flex items-center gap-2">
          <a href="{{ route('plants.edit', $plant) }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Modifier</a>
          <a href="{{ route('plants.index') }}" class="px-3 py-1 bg-gray-200 rounded">Retour</a>
        </div>
      </div>

      <!-- Contenu principal - occupe les 2/3 supérieurs -->
      <div class="flex-grow overflow-auto p-4" style="height: 66%;">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 h-full">
          <!-- Image principale -->
          <div class="lg:col-span-2 flex items-center justify-center">
            @if($plant->main_photo)
              <button type="button" onclick="openLightboxGlobal(0)" class="bg-transparent border-0 p-0 h-full flex items-center">
                <img src="{{ Storage::url($plant->main_photo) }}" alt="{{ $plant->name }}" class="max-h-full w-auto object-contain rounded">
              </button>
            @else
              <div class="w-full h-full bg-gray-100 rounded flex items-center justify-center text-gray-400">
                Aucune image
              </div>
            @endif
          </div>

          <!-- Informations -->
          <aside class="space-y-4">
            @if($plant->description)
              <div>
                <h3 class="text-sm font-medium text-gray-600">Description</h3>
                <p class="mt-2 text-gray-700">{{ $plant->description }}</p>
              </div>
            @endif

            <div>
              <h3 class="text-sm font-medium text-gray-600">Catégorie</h3>
              <div class="mt-1 text-gray-800">{{ $plant->category->name ?? '—' }}</div>
            </div>

            <div>
              <h3 class="text-sm font-medium text-gray-600">Besoins</h3>
              <div class="mt-1 text-gray-800">
                Arrosage : {{ \App\Models\Plant::$wateringLabels[$plant->watering_frequency] ?? $plant->watering_frequency }}<br>
                Lumière : {{ \App\Models\Plant::$lightLabels[$plant->light_requirement] ?? $plant->light_requirement }}
              </div>
            </div>
          </aside>
        </div>
      </div>

      <!-- Galerie - occupe le 1/3 inférieur -->
      @php $lightboxStart = $plant->main_photo ? 1 : 0; @endphp
      @if($plant->photos->count())
        <div class="border-t p-4" style="height: 34%;">
          <h2 class="text-lg font-semibold mb-2">Galerie</h2>
          <div class="overflow-auto h-[calc(100%-2rem)]">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
              @foreach($plant->photos as $i => $photo)
                <button type="button" onclick="openLightboxGlobal({{ $lightboxStart + $i }})" class="bg-transparent border-0 p-0">
                  <div class="w-full h-40 flex items-center justify-center bg-gray-50 rounded overflow-hidden">
                    <img src="{{ Storage::url($photo->filename) }}" alt="{{ $photo->description ?? $plant->name }}" class="h-full w-auto object-contain">
                  </div>
                </button>
              @endforeach
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>

  <script>
    // tableau d'images global utilisé par la lightbox incluse via partial
    window.globalLightboxImages = [
      @if($plant->main_photo)
        { url: {!! json_encode(Storage::url($plant->main_photo)) !!}, caption: {!! json_encode($plant->name) !!} }@if($plant->photos->count()),@endif
      @endif
      @foreach($plant->photos as $photo)
        { url: {!! json_encode(Storage::url($photo->filename)) !!}, caption: {!! json_encode($photo->description ?? '') !!} }@if(!$loop->last),@endif
      @endforeach
    ];
  </script>

  @include('partials.lightbox')
</body>
</html>