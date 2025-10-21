@extends('layouts.app')

@section('title', 'Paramètres — Sauvegardes')

@push('scripts')
  <script src="https://unpkg.com/lucide@latest" defer></script>
@endpush

@section('content')
  <div class="max-w-6xl mx-auto p-6">
    <header class="mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Sauvegardes & Exports</h1>
      <p class="text-gray-600 mt-2">Gérez vos sauvegardes de données et vos exports</p>
    </header>

    <!-- Import Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i data-lucide="upload" class="w-5 h-5"></i>
        Importer des données
      </h2>
      
      <p class="text-gray-600 text-sm mb-4">
        Restaurez vos données à partir d'une sauvegarde précédente.
      </p>

      <div class="space-y-4">
        <!-- Backup Selection OR Upload -->
        <div class="flex gap-4">
          <!-- Method 1: From saved backups -->
          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">📁 Choisir une sauvegarde existante</label>
            <select id="backup-select" class="w-full border-gray-300 rounded-lg shadow-sm p-2 border">
              <option value="">— Sélectionner une sauvegarde —</option>
              @foreach($backups as $backup)
                <option value="{{ $backup['filename'] }}">
                  {{ $backup['filename'] }} ({{ $backup['size_human'] }}, {{ $backup['created_at_human'] }})
                </option>
              @endforeach
            </select>
          </div>

          <div class="flex items-end">
            <span class="text-gray-400">OU</span>
          </div>

          <!-- Method 2: Upload from computer -->
          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">📤 Importer un fichier ZIP</label>
            <input type="file" id="backup-upload" accept=".zip" class="w-full border border-gray-300 rounded-lg shadow-sm p-2">
            <p class="text-xs text-gray-500 mt-1">Fichier ZIP exporté (max 50MB)</p>
          </div>
        </div>

        <!-- Mode Selection -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Mode d'import</label>
          <div class="space-y-2">
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="import-mode" value="MERGE" checked class="w-4 h-4 text-blue-600">
                <span class="text-sm text-gray-700"><strong>MERGE</strong> — Ajouter/mettre à jour les données (par défaut, sûr)</span>
              </label>
            </div>
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="import-mode" value="REPLACE" class="w-4 h-4 text-blue-600">
                <span class="text-sm text-gray-700"><strong>REPLACE</strong> — Remplacer les données existantes (par référence)</span>
              </label>
            </div>
            <div>
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="import-mode" value="FRESH" class="w-4 h-4 text-blue-600">
                <span class="text-sm text-red-600"><strong>FRESH</strong> — Supprimer et recommencer (ATTENTION: perte de données!)</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Preview Button -->
        <button id="preview-btn" 
                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition flex items-center gap-2">
          <i data-lucide="eye" class="w-4 h-4"></i>
          Aperçu avant import
        </button>
      </div>

      <!-- Preview Results -->
      <div id="preview-results" class="mt-6 hidden bg-gray-50 p-4 rounded-lg border border-gray-200">
        <h3 class="font-semibold text-gray-800 mb-3">Aperçu de l'import</h3>
        <div id="preview-content"></div>
        
        <!-- Confirmation -->
        <div class="mt-4 flex gap-2">
          <button id="confirm-import-btn" 
                  class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center gap-2">
            <i data-lucide="check" class="w-4 h-4"></i>
            Confirmer l'import
          </button>
          <button onclick="document.getElementById('preview-results').classList.add('hidden')" 
                  class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg transition">
            Annuler
          </button>
        </div>
      </div>

      <!-- Import Status -->
      <div id="import-status" class="mt-4 hidden">
        <div class="flex items-center gap-2">
          <div class="animate-spin">
            <i data-lucide="loader" class="w-4 h-4"></i>
          </div>
          <span class="text-sm text-gray-600">Import en cours...</span>
        </div>
      </div>
    </div>

    <!-- Export Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i data-lucide="download" class="w-5 h-5"></i>
        Exporter les données
      </h2>
      
      <p class="text-gray-600 text-sm mb-4">
        Créez une sauvegarde complète de tous vos données (plantes, photos, historiques).
      </p>

      <div class="space-y-3">
        <div>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" id="include-photos" checked class="w-4 h-4 text-blue-600 rounded">
            <span class="text-sm text-gray-700">Inclure les photos</span>
          </label>
        </div>
      </div>

      <button id="export-btn" 
              class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
        <i data-lucide="download" class="w-4 h-4"></i>
        Exporter maintenant
      </button>

      <div id="export-status" class="mt-4 hidden">
        <div class="flex items-center gap-2">
          <div class="animate-spin">
            <i data-lucide="loader" class="w-4 h-4"></i>
          </div>
          <span class="text-sm text-gray-600">Export en cours...</span>
        </div>
      </div>
    </div>

    <!-- Backups History -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i data-lucide="hard-drive" class="w-5 h-5"></i>
        Historique des sauvegardes
      </h2>

      @if(count($backups) > 0)
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b">
              <tr>
                <th class="px-4 py-2 text-left font-semibold text-gray-700">Nom du fichier</th>
                <th class="px-4 py-2 text-left font-semibold text-gray-700">Taille</th>
                <th class="px-4 py-2 text-left font-semibold text-gray-700">Date</th>
                <th class="px-4 py-2 text-left font-semibold text-gray-700">Plantes</th>
                <th class="px-4 py-2 text-left font-semibold text-gray-700">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              @foreach($backups as $backup)
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-3 text-gray-800">
                    <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $backup['filename'] }}</code>
                  </td>
                  <td class="px-4 py-3 text-gray-600">{{ $backup['size_human'] }}</td>
                  <td class="px-4 py-3 text-gray-600">
                    <time datetime="{{ date('c', $backup['created_at']) }}">
                      {{ $backup['created_at_human'] }}
                    </time>
                  </td>
                  <td class="px-4 py-3">
                    @if($backup['metadata'])
                      <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                        {{ $backup['metadata']['counts']['plants'] ?? '?' }} plantes
                      </span>
                    @else
                      <span class="text-gray-500 text-xs">—</span>
                    @endif
                  </td>
                  <td class="px-4 py-3 flex gap-2">
                    <a href="{{ route('backups.download', $backup['filename']) }}" 
                       class="text-blue-600 hover:text-blue-800 text-xs font-medium flex items-center gap-1">
                      <i data-lucide="download" class="w-3 h-3"></i> Télécharger
                    </a>
                    <button onclick="deleteBackup('{{ $backup['filename'] }}')" 
                            class="text-red-600 hover:text-red-800 text-xs font-medium flex items-center gap-1">
                      <i data-lucide="trash-2" class="w-3 h-3"></i> Supprimer
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="text-center py-8">
          <p class="text-gray-500">Aucune sauvegarde disponible. Créez une export pour commencer.</p>
        </div>
      @endif
    </div>

    @include('settings.backups._reset_section')
  </div>

  <script>
    // Export handler
    document.getElementById('export-btn').addEventListener('click', async function() {
      const btn = this;
      const status = document.getElementById('export-status');
      const includePhotos = document.getElementById('include-photos').checked;

      btn.disabled = true;
      status.classList.remove('hidden');

      try {
        const response = await fetch('{{ route("backups.export") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify({
            include_photos: includePhotos,
          }),
        });

        const data = await response.json();
        
        if (data.success) {
          // Trigger download
          const downloadUrl = '{{ route("backups.download", ":filename") }}'.replace(':filename', data.filename);
          window.location.href = downloadUrl;

          // Show success message
          alert('✓ Export créé avec succès!');
          
          // Reload page after 2s
          setTimeout(() => window.location.reload(), 2000);
        } else {
          alert('✗ Erreur: ' + data.message);
        }
      } catch (error) {
        alert('✗ Erreur lors de l\'export: ' + error.message);
      } finally {
        btn.disabled = false;
        status.classList.add('hidden');
      }
    });

    // Delete backup handler
    function deleteBackup(filename) {
      if (!confirm('Êtes-vous sûr de vouloir supprimer cette sauvegarde?')) {
        return;
      }

      fetch('{{ route("backups.delete", ":filename") }}'.replace(':filename', filename), {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          window.location.reload();
        } else {
          alert('Erreur: ' + data.message);
        }
      })
      .catch(err => alert('Erreur: ' + err.message));
    }

    // Upload backup handler
    document.getElementById('backup-upload')?.addEventListener('change', async function(e) {
      const file = e.target.files[0];
      if (!file) return;

      const formData = new FormData();
      formData.append('file', file);

      try {
        const response = await fetch('{{ route("backups.upload") }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
          body: formData,
        });

        const data = await response.json();
        
        if (data.success) {
          // Add the uploaded file to the select dropdown
          const select = document.getElementById('backup-select');
          const option = document.createElement('option');
          option.value = data.filename;
          option.textContent = data.filename + ' (Importé)';
          select.appendChild(option);
          select.value = data.filename;
          
          alert('✓ Fichier importé avec succès!');
          
          // Clear the file input
          this.value = '';
        } else {
          alert('✗ Erreur: ' + data.message);
        }
      } catch (error) {
        alert('✗ Erreur lors de l\'upload: ' + error.message);
      }
    });

    // Import Preview handler
    document.getElementById('preview-btn').addEventListener('click', async function() {
      const backupFile = document.getElementById('backup-select').value;
      const mode = document.querySelector('input[name="import-mode"]:checked').value;
      const previewResults = document.getElementById('preview-results');
      const previewContent = document.getElementById('preview-content');

      if (!backupFile) {
        alert('Veuillez sélectionner une sauvegarde');
        return;
      }

      previewResults.classList.add('hidden');
      this.disabled = true;

      try {
        const response = await fetch('{{ route("backups.preview") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify({
            backup: backupFile,
            mode: mode,
          }),
        });

        const data = await response.json();
        
        if (data.success) {
          const result = data.result;
          previewContent.innerHTML = generatePreviewHTML(result, mode);
          previewResults.classList.remove('hidden');
        } else {
          alert('Erreur: ' + data.message);
        }
      } catch (error) {
        alert('Erreur: ' + error.message);
      } finally {
        this.disabled = false;
      }
    });

    // Confirm Import handler
    document.getElementById('confirm-import-btn').addEventListener('click', async function() {
      const backupFile = document.getElementById('backup-select').value;
      const mode = document.querySelector('input[name="import-mode"]:checked').value;
      
      if (!confirm('⚠️ Êtes-vous vraiment sûr? Cette action ne peut pas être annulée.')) {
        return;
      }

      document.getElementById('preview-results').classList.add('hidden');
      document.getElementById('import-status').classList.remove('hidden');
      this.disabled = true;

      try {
        const response = await fetch('{{ route("backups.import") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify({
            backup: backupFile,
            mode: mode,
            confirmed: true,
          }),
        });

        const data = await response.json();
        
        if (data.success) {
          alert('✓ Import complété avec succès!');
          setTimeout(() => window.location.reload(), 2000);
        } else {
          alert('✗ Erreur: ' + data.message);
        }
      } catch (error) {
        alert('✗ Erreur: ' + error.message);
      } finally {
        document.getElementById('import-status').classList.add('hidden');
        this.disabled = false;
      }
    });

    // Generate preview HTML
    function generatePreviewHTML(result, mode) {
      const warnings = result.warnings || [];
      const counts = result.counts || {};

      let html = '<div class="space-y-3">';

      // Mode warning
      if (mode === 'FRESH') {
        html += '<div class="bg-red-100 border border-red-300 rounded p-3 text-red-800 text-sm"><strong>⚠️ Mode FRESH:</strong> Tous les données existantes seront supprimées!</div>';
      }

      // Counts
      html += '<div><strong>Données à importer:</strong><ul class="list-disc list-inside text-sm text-gray-700 mt-1">';
      html += `<li>${counts.plants_imported || 0} plantes</li>`;
      html += `<li>${counts.photos_imported || 0} photos</li>`;
      html += `<li>${counts.categories_synced || 0} catégories</li>`;
      html += `<li>${counts.tags_synced || 0} tags</li>`;
      html += `<li>${counts.histories_imported || 0} historiques</li>`;
      html += '</ul></div>';

      // Warnings
      if (warnings.length > 0) {
        html += '<div><strong>Avertissements:</strong><ul class="list-disc list-inside text-sm text-yellow-700 mt-1">';
        warnings.forEach(w => html += `<li>${w}</li>`);
        html += '</ul></div>';
      }

      html += '</div>';
      return html;
    }
  </script>

  <!-- Lucide Icons Library -->
  <script src="https://cdn.jsdelivr.net/npm/lucide@latest"></script>
  <script>
    /**
     * Initialize Lucide Icons
     * Safely waits for lucide library to load and initializes icons
     */
    (function initLucideIcons() {
      const maxAttempts = 50;
      let attempts = 0;

      function checkAndInit() {
        attempts++;
        
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
          lucide.createIcons();
        } else if (attempts < maxAttempts) {
          // Retry after 100ms if lucide not yet loaded
          setTimeout(checkAndInit, 100);
        }
      }

      // Start checking when DOM is ready
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', checkAndInit);
      } else {
        checkAndInit();
      }
    })();
  </script>
@endsection
