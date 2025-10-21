@extends('layouts.app')

@section('title', 'Paramètres — Sauvegardes')

@section('content')
  <div class="max-w-6xl mx-auto p-6">
    <header class="mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Sauvegardes & Exports</h1>
      <p class="text-gray-600 mt-2">Gérez vos sauvegardes de données et vos exports</p>
    </header>

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
    <div class="bg-white rounded-lg shadow p-6">
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
  </script>
@endsection
