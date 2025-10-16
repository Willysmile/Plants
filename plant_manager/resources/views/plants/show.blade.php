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
        <div class="flex-1">
          <div class="flex items-start gap-4">
            <div>
              @if($plant->scientific_name)
                <h1 class="text-3xl font-semibold italic text-green-700">{{ $plant->scientific_name }}</h1>
                <p class="text-base text-gray-700 mt-2">{{ $plant->name }}</p>
              @else
                <h1 class="text-3xl font-semibold">{{ $plant->name }}</h1>
              @endif
            </div>

            <!-- catégorie à côté du titre -->
            <div class="ml-2 self-center">
              <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-medium border border-blue-200">
                {{ $plant->category->name ?? '—' }}
              </span>
            </div>
          </div>
        </div>
        <div class="flex items-center gap-2 ml-4">
          <a href="{{ route('plants.edit', $plant) }}" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md transition">Modifier</a>
          <a href="{{ route('plants.index') }}" class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-md transition">Retour</a>
        </div>
      </div>

      <!-- Contenu principal - occupe les 2/3 supérieurs -->
      <div class="flex-grow overflow-hidden p-4" style="height: 66%;">
        <div class="grid grid-cols-3 gap-6 h-full">
          <!-- Image principale et description - 1/3 de la largeur (col-span-1) -->
          <div class="col-span-1 flex flex-col gap-4 overflow-y-auto pr-4">
            <!-- Photo principale -->
            <div class="flex items-center justify-center">
              @if($plant->main_photo)
                <button type="button" onclick="openLightboxGlobal(0)" class="bg-transparent border-0 p-0 w-full flex items-center justify-center">
                  <img src="{{ Storage::url($plant->main_photo) }}" alt="{{ $plant->name }}" class="max-h-80 max-w-full object-contain rounded-lg shadow-md">
                </button>
              @else
                <div class="w-full h-80 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                  <div class="text-center">
                    <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Aucune image
                  </div>
                </div>
              @endif
            </div>

            <!-- Description sous la photo -->
            @if($plant->description)
              <div class="bg-gray-50 p-3 rounded-lg border-l-4 border-green-500">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Description</h3>
                <p class="mt-2 text-gray-700 leading-relaxed text-sm">{{ $plant->description }}</p>
              </div>
            @endif
          </div>

          <!-- Cartes à droite - 2/3 de la largeur (col-span-2) -->
          <aside class="col-span-2 overflow-y-auto pr-4">
            <div class="grid grid-cols-2 gap-4">
              <!-- Cartes colonne gauche -->
              <div class="space-y-4">
                <div class="bg-yellow-50 p-3 rounded-lg border-l-4 border-yellow-500">
                  <div class="text-center">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Besoins</h3>
                  </div>

                  @php
                    $wf = $plant->watering_frequency ?? 3;
                    $lf = $plant->light_requirement ?? 3;
                    $wIcon = \App\Models\Plant::$wateringIcons[$wf] ?? 'droplet';
                    $lIcon = \App\Models\Plant::$lightIcons[$lf] ?? 'sun';
                    $wColor = \App\Models\Plant::$wateringColors[$wf] ?? 'blue';
                    $lColor = \App\Models\Plant::$lightColors[$lf] ?? 'yellow';
                    $wLabel = \App\Models\Plant::$wateringLabels[$wf] ?? 'N/A';
                    $lLabel = \App\Models\Plant::$lightLabels[$lf] ?? 'N/A';
                  @endphp

                  <div class="mt-3 flex items-center justify-around gap-6">
                    <!-- Arrosage -->
                    <div class="flex flex-col items-center gap-1" title="Arrosage : {{ $wLabel }}">
                      <span class="text-xs text-gray-600 font-medium">Arrosage</span>
                      <i data-lucide="{{ $wIcon }}" class="w-8 h-8 text-{{ $wColor }}"></i>
                      <span class="text-xs text-gray-600">{{ $wLabel }}</span>
                    </div>

                    <!-- Lumière -->
                    <div class="flex flex-col items-center gap-1" title="Lumière : {{ $lLabel }}">
                      <span class="text-xs text-gray-600 font-medium">Lumière</span>
                      <i data-lucide="{{ $lIcon }}" class="w-8 h-8 text-{{ $lColor }}"></i>
                      <span class="text-xs text-gray-600">{{ $lLabel }}</span>
                    </div>
                  </div>
                </div>

                @if($plant->temperature_min || $plant->temperature_max || $plant->humidity_level)
                  <div class="bg-red-50 p-3 rounded-lg border-l-4 border-red-500 col-span-2">
                    <div class="text-center">
                      <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Température & Humidité</h3>
                    </div>
                    <div class="mt-3 flex items-center justify-around gap-6">
                      <!-- Température -->
                      <div class="flex flex-col items-center gap-1 min-w-32">
                        <span class="text-xs text-gray-600 font-medium">Température</span>
                        <div class="text-gray-800 text-sm font-medium">
                          @if($plant->temperature_min || $plant->temperature_max)
                            @php
                              $minTemp = $plant->temperature_min ?? '?';
                              $maxTemp = $plant->temperature_max ?? '?';
                            @endphp
                            <div>{{ $minTemp }}°- {{ $maxTemp }}°</div>
                          @else
                            <div class="text-gray-500">—</div>
                          @endif
                        </div>
                      </div>
                      <!-- Humidité -->
                      <div class="flex flex-col items-center gap-1 min-w-32">
                        <span class="text-xs text-gray-600 font-medium">Humidité</span>
                        <div class="text-gray-800 text-sm font-medium">
                          @if($plant->humidity_level)
                            <div>{{ $plant->humidity_level }}%</div>
                          @else
                            <div class="text-gray-500">—</div>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
              </div>

              <!-- Cartes colonne droite -->
              <div class="space-y-4">
                @if($plant->notes)
                  <div class="bg-purple-50 p-3 rounded-lg border-l-4 border-purple-500">
                    <div class="text-center">
                      <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Notes</h3>
                      <p class="text-xs text-gray-500 mt-1">Observations personnelles</p>
                    </div>
                    <p class="mt-2 text-gray-700 leading-relaxed text-sm">{{ $plant->notes }}</p>
                  </div>
                @endif

                @if($plant->purchase_date)
                  <div class="bg-indigo-50 p-3 rounded-lg border-l-4 border-indigo-500">
                    <div class="text-center">
                      <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Date d'achat</h3>
                      <p class="text-xs text-gray-500 mt-1">Historique d'acquisition</p>
                    </div>
                    <div class="mt-2 text-gray-800 font-medium text-sm text-center">{{ $plant->purchase_date->format('d/m/Y') }}</div>
                  </div>
                @endif
              </div>
            </div>
          </aside>
        </div>
      </div>

      <!-- Galerie - occupe le 1/3 inférieur -->
      @php $lightboxStart = $plant->main_photo ? 1 : 0; @endphp
      @if($plant->photos->count())
        <div class="border-t p-4 bg-gray-50" style="height: 34%;">
          <h2 class="text-lg font-semibold mb-3 text-gray-800">Galerie ({{ $plant->photos->count() }} photo{{ $plant->photos->count() > 1 ? 's' : '' }})</h2>
          <div class="overflow-auto h-[calc(100%-2.5rem)]">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
              @foreach($plant->photos as $i => $photo)
                <button type="button" onclick="openLightboxGlobal({{ $lightboxStart + $i }})" class="bg-transparent border-0 p-0 hover:opacity-80 transition">
                  <div class="w-full aspect-square flex items-center justify-center bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition border border-gray-200">
                    <img src="{{ Storage::url($photo->filename) }}" alt="{{ $photo->description ?? $plant->name }}" class="h-full w-full object-cover">
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
    window.globalLightboxImages = [
      @if($plant->main_photo)
        { url: {!! json_encode(Storage::url($plant->main_photo)) !!}, caption: {!! json_encode($plant->name) !!} }{{ $plant->photos->count() ? ',' : '' }}
      @endif
      @foreach($plant->photos as $photo)
        { url: {!! json_encode(Storage::url($photo->filename)) !!}, caption: {!! json_encode($photo->description ?? '') !!} }{{ !$loop->last ? ',' : '' }}
      @endforeach
    ];
  </script>

    <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    lucide.createIcons();
  </script>

  @include('partials.lightbox')
</body>
</html>