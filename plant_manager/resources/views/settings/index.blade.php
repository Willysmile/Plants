<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Paramètres</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50 text-gray-900">
  <div class="min-h-screen py-12 px-4">
    <div class="max-w-2xl mx-auto">
      <!-- En-tête -->
      <div class="mb-8 flex items-center justify-between">
        <div>
          <h1 class="text-4xl font-bold text-gray-900">Paramètres</h1>
          <p class="text-gray-600 mt-2">Configurez les détails de votre application</p>
        </div>
        <a href="{{ route('plants.index') }}" class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-md transition">
          ← Retour
        </a>
      </div>

      @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
          <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
      @endif

      <!-- Formulaire -->
      <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('settings.update') }}" method="POST">
          @csrf
          @method('PUT')

          <!-- Zone géographique -->
          <div class="mb-8 pb-8 border-b">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">📍 Zone géographique</h2>
            
            <!-- Fuseau horaire -->
            <div class="mb-6">
              <label class="block text-gray-700 font-bold mb-2" for="timezone">
                Fuseau horaire
              </label>
              <select id="timezone" name="timezone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('timezone') border-red-500 @enderror">
                <option value="">-- Sélectionner un fuseau horaire --</option>
                <optgroup label="Europe">
                  <option value="Europe/Paris" {{ $settings->timezone === 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris (GMT+1)</option>
                  <option value="Europe/London" {{ $settings->timezone === 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                  <option value="Europe/Berlin" {{ $settings->timezone === 'Europe/Berlin' ? 'selected' : '' }}>Europe/Berlin (GMT+1)</option>
                  <option value="Europe/Madrid" {{ $settings->timezone === 'Europe/Madrid' ? 'selected' : '' }}>Europe/Madrid (GMT+1)</option>
                  <option value="Europe/Amsterdam" {{ $settings->timezone === 'Europe/Amsterdam' ? 'selected' : '' }}>Europe/Amsterdam (GMT+1)</option>
                </optgroup>
                <optgroup label="Amériques">
                  <option value="America/New_York" {{ $settings->timezone === 'America/New_York' ? 'selected' : '' }}>America/New_York (GMT-5)</option>
                  <option value="America/Los_Angeles" {{ $settings->timezone === 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles (GMT-8)</option>
                  <option value="America/Chicago" {{ $settings->timezone === 'America/Chicago' ? 'selected' : '' }}>America/Chicago (GMT-6)</option>
                </optgroup>
                <optgroup label="Asie">
                  <option value="Asia/Tokyo" {{ $settings->timezone === 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo (GMT+9)</option>
                  <option value="Asia/Shanghai" {{ $settings->timezone === 'Asia/Shanghai' ? 'selected' : '' }}>Asia/Shanghai (GMT+8)</option>
                  <option value="Asia/Bangkok" {{ $settings->timezone === 'Asia/Bangkok' ? 'selected' : '' }}>Asia/Bangkok (GMT+7)</option>
                  <option value="Asia/Dubai" {{ $settings->timezone === 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GMT+4)</option>
                </optgroup>
                <optgroup label="Océanie">
                  <option value="Australia/Sydney" {{ $settings->timezone === 'Australia/Sydney' ? 'selected' : '' }}>Australia/Sydney (GMT+10)</option>
                  <option value="Australia/Melbourne" {{ $settings->timezone === 'Australia/Melbourne' ? 'selected' : '' }}>Australia/Melbourne (GMT+10)</option>
                </optgroup>
              </select>
              @error('timezone')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>

            <!-- Langue -->
            <div class="mb-6">
              <label class="block text-gray-700 font-bold mb-2" for="locale">
                Langue
              </label>
              <select id="locale" name="locale" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('locale') border-red-500 @enderror">
                <option value="fr" {{ $settings->locale === 'fr' ? 'selected' : '' }}>Français</option>
                <option value="en" {{ $settings->locale === 'en' ? 'selected' : '' }}>English</option>
              </select>
              @error('locale')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <!-- Paramètres des plantes -->
          <div class="mb-8 pb-8 border-b">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">🌱 Paramètres des plantes</h2>
            
            <!-- Unité de température -->
            <div class="mb-6">
              <label class="block text-gray-700 font-bold mb-2" for="temperature_unit">
                Unité de température
              </label>
              <select id="temperature_unit" name="temperature_unit" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('temperature_unit') border-red-500 @enderror">
                <option value="celsius" {{ $settings->temperature_unit === 'celsius' ? 'selected' : '' }}>Celsius (°C)</option>
                <option value="fahrenheit" {{ $settings->temperature_unit === 'fahrenheit' ? 'selected' : '' }}>Fahrenheit (°F)</option>
              </select>
              @error('temperature_unit')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>

            <!-- Unité des pots -->
            <div class="mb-6">
              <label class="block text-gray-700 font-bold mb-2" for="pot_unit">
                Unité des pots
              </label>
              <select id="pot_unit" name="pot_unit" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('pot_unit') border-red-500 @enderror">
                <option value="cm" {{ $settings->pot_unit === 'cm' ? 'selected' : '' }}>Centimètres (cm)</option>
                <option value="in" {{ $settings->pot_unit === 'in' ? 'selected' : '' }}>Pouces (in)</option>
              </select>
              @error('pot_unit')
                <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <!-- Boutons -->
          <div class="flex justify-end gap-4">
            <a href="{{ route('plants.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition">
              Annuler
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition">
              Enregistrer
            </button>
          </div>
        </form>

        <!-- Gestion des types d'engrais -->
        <div class="mt-8 pt-8 border-t">
          <h2 class="text-2xl font-semibold text-gray-900 mb-6">🧪 Gestion des types d'engrais</h2>
          <p class="text-gray-600 mb-4">Gérez les types d'engrais disponibles et leurs unités de mesure.</p>
          <a href="{{ route('fertilizer-types.index') }}" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition inline-block">
            Gérer les types d'engrais →
          </a>
        </div>
      </div>

      <!-- Informations -->
      <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">ℹ️ Informations</h3>
        <ul class="space-y-2 text-blue-800 text-sm">
          <li>• <strong>Fuseau horaire :</strong> Définit l'heure par défaut pour tous les enregistrements</li>
          <li>• <strong>Langue :</strong> Change la langue de l'interface (Français/English)</li>
          <li>• <strong>Unité de température :</strong> Affiche les températures en Celsius ou Fahrenheit</li>
          <li>• <strong>Unité des pots :</strong> Définit l'unité par défaut pour les diamètres de pots (cm ou pouces)</li>
        </ul>
      </div>
    </div>
  </div>
</body>
</html>
