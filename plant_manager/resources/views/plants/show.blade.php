<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $plant->name }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex items-start justify-between">
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

      @if($plant->description)
        <p class="mt-4 text-gray-700">{{ $plant->description }}</p>
      @endif

      <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
          @if($plant->main_photo)
            <button type="button" onclick="openLightbox(0)" style="background:none;border:0;padding:0;">
              <img src="{{ Storage::url($plant->main_photo) }}" alt="{{ $plant->name }}" class="w-full h-96 object-cover rounded">
            </button>
          @else
            <div class="w-full h-96 bg-gray-100 rounded flex items-center justify-center text-gray-400">Aucune image</div>
          @endif
        </div>

        <aside class="space-y-4">
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
@php $lightboxStart = $plant->main_photo ? 1 : 0; @endphp

@if($plant->photos->count())
  <div class="mt-6">
    <h2 class="text-lg font-semibold mb-3">Galerie</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
      @foreach($plant->photos as $i => $photo)
        <button type="button" onclick="openLightbox({{ $lightboxStart + $i }})" style="background:none;border:0;padding:0;">
          <img src="{{ Storage::url($photo->filename) }}" alt="{{ $photo->description ?? $plant->name }}" class="w-full h-36 object-cover rounded">
        </button>
      @endforeach
    </div>
  </div>
@endif
    </div>
  </div>

  <!-- lightbox : ne dépasse pas l'écran -->
<div id="lb" class="fixed inset-0 bg-black/80 z-50 hidden items-center justify-center p-6" role="dialog" aria-modal="true">
  <div class="relative" style="max-width:100%;max-height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;">
    <button onclick="closeLightbox()" aria-label="Fermer" style="position:absolute;right:12px;top:12px;z-index:60;background:#fff;border-radius:6px;padding:6px 8px;border:0;cursor:pointer;">✕</button>

    <img id="lb-img" src="" alt="" 
         style="max-width:calc(100vw - 48px); max-height:calc(100vh - 96px); width:auto; height:auto; display:block; object-fit:contain; border-radius:6px; box-shadow:0 10px 30px rgba(0,0,0,.6);">

    <div id="lb-caption" style="color:#fff;margin-top:12px;text-align:center;max-width:calc(100vw - 48px);word-break:break-word"></div>
  </div>
</div>

<script>
 const images = [
  @if($plant->main_photo)
    { url: "{{ Storage::url($plant->main_photo) }}", caption: {!! json_encode($plant->name) !!} }@if($plant->photos->count()),@endif
  @endif
  @foreach($plant->photos as $photo)
    { url: "{{ Storage::url($photo->filename) }}", caption: {!! json_encode($photo->description ?? '') !!} }@if(!$loop->last),@endif
  @endforeach
];
  function openLightbox(i){
    const lb = document.getElementById('lb');
    const img = document.getElementById('lb-img');
    const cap = document.getElementById('lb-caption');
    if(!images[i]) return;
    img.src = images[i].url;
    cap.textContent = images[i].caption || '';
    lb.classList.remove('hidden');
    lb.style.display = 'flex';
    // bloquer scroll page
    document.body.style.overflow = 'hidden';
  }
  function closeLightbox(){
    const lb = document.getElementById('lb');
    document.getElementById('lb-img').src = '';
    lb.classList.add('hidden');
    lb.style.display = 'none';
    document.body.style.overflow = '';
  }
  document.addEventListener('keydown', e => { if(e.key === 'Escape') closeLightbox(); });
</script>
</body>
</html>