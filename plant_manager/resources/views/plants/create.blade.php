<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Nouvelle plante</title>
</head>
<body class="p-6 bg-gray-50">
  <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-4">Ajouter une plante</h1>

    @if($errors->any())
      <div class="mb-4 text-red-700">
        <ul>
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('plants.store') }}" method="post" enctype="multipart/form-data">
      @csrf

      <label class="block mb-2">Nom *
        <input name="name" required class="w-full border p-2 rounded" value="{{ old('name') }}">
      </label>

      <label class="block mb-2">Nom scientifique
        <input name="scientific_name" class="w-full border p-2 rounded" value="{{ old('scientific_name') }}">
      </label>

      <label class="block mb-2">Catégorie *
        <select name="category_id" required class="w-full border p-2 rounded">
          <option value="">Sélectionner...</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
          @endforeach
        </select>
      </label>

      <label class="block mb-2">Fréquence d'arrosage *
        <select name="watering_frequency" required class="w-full border p-2 rounded">
          @foreach(\App\Models\Plant::$wateringLabels as $k=>$v)
            <option value="{{ $k }}" {{ old('watering_frequency') == $k ? 'selected' : '' }}>{{ $v }}</option>
          @endforeach
        </select>
      </label>

      <label class="block mb-2">Besoin en lumière *
        <select name="light_requirement" required class="w-full border p-2 rounded">
          @foreach(\App\Models\Plant::$lightLabels as $k=>$v)
            <option value="{{ $k }}" {{ old('light_requirement') == $k ? 'selected' : '' }}>{{ $v }}</option>
          @endforeach
        </select>
      </label>

      <label class="block mb-2">Description
        <textarea name="description" class="w-full border p-2 rounded">{{ old('description') }}</textarea>
      </label>

      <label class="block mb-2">Tags
        <div class="flex flex-wrap gap-2 mt-2">
          @foreach($tags as $tag)
            <label class="inline-flex items-center">
              <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
              <span class="ml-2">{{ $tag->name }}</span>
            </label>
          @endforeach
        </div>
      </label>

      <label class="block mb-2">Photo principale
        <input type="file" name="main_photo" accept="image/*" class="w-full">
      </label>

      <label class="block mb-2">Galerie (plusieurs images acceptées)
        <input type="file" name="photos[]" accept="image/*" multiple class="w-full">
      </label>

      <div class="flex gap-2 mt-4">
        <button class="px-4 py-2 bg-blue-600 text-white rounded">Créer</button>
        <a href="{{ route('plants.index') }}" class="px-4 py-2 bg-gray-200 rounded">Annuler</a>
      </div>
    </form>
  </div>
</body>
</html>