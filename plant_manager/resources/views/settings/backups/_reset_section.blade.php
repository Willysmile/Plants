<!-- Reset & Recovery Section -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
      <i data-lucide="rotate-ccw" class="w-5 h-5"></i>
      Options avancées
    </h2>
    <button id="toggle-reset-btn" class="text-sm text-blue-600 hover:text-blue-800">
      Afficher options avancées
    </button>
  </div>

  <!-- Reset Subsection -->
  <div data-reset-section class="space-y-4 border-t pt-4">
    <div class="bg-red-50 border border-red-200 rounded p-4">
      <h3 class="text-lg font-semibold text-red-800 mb-2">🔴 Réinitialiser tout</h3>
      <p class="text-red-700 text-sm mb-4">
        Supprime toutes les plantes, photos et historiques. Les données peuvent être récupérées dans les 30 jours.
      </p>

      <div class="space-y-3">
        <!-- Backup option -->
        <div>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" id="reset-backup-checkbox" class="w-4 h-4 text-red-600 rounded">
            <span class="text-sm text-red-700">Créer une sauvegarde avant la réinitialisation</span>
          </label>
        </div>

        <!-- Reason -->
        <div>
          <label class="block text-sm font-medium text-red-700 mb-1">Raison (optionnel)</label>
          <input type="text" id="reset-reason" class="w-full border border-red-300 rounded px-3 py-2 text-sm" 
                 placeholder="Raison administrative pour l'audit">
        </div>

        <!-- Preview button -->
        <button id="reset-preview-btn" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
          Aperçu avant reset
        </button>
      </div>

      <!-- Preview Results -->
      <div id="reset-preview" class="mt-4 hidden bg-red-100 p-4 rounded border border-red-300">
        <h4 class="font-semibold text-red-800 mb-2">Aperçu de la réinitialisation</h4>
        <div id="reset-preview-content"></div>
        
        <div class="mt-4 flex gap-2">
          <button id="confirm-reset-btn" class="px-4 py-2 bg-red-700 hover:bg-red-800 text-white rounded-lg transition">
            Confirmer la réinitialisation
          </button>
          <button onclick="document.getElementById('reset-preview').classList.add('hidden')" 
                  class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg transition">
            Annuler
          </button>
        </div>
      </div>

      <!-- Status -->
      <div id="reset-status" class="mt-4 hidden">
        <div class="flex items-center gap-2">
          <div class="animate-spin">
            <i data-lucide="loader" class="w-4 h-4"></i>
          </div>
          <span class="text-sm text-red-600">Réinitialisation en cours...</span>
        </div>
      </div>
    </div>

    <!-- Recovery Subsection -->
    <div class="bg-blue-50 border border-blue-200 rounded p-4">
      <h3 class="text-lg font-semibold text-blue-800 mb-2">🔵 Récupération (30 jours)</h3>
      <p class="text-blue-700 text-sm mb-4">
        Récupérez les plantes supprimées dans les 30 jours suivant la réinitialisation.
      </p>

      <button id="show-deleted-btn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
        Afficher les éléments supprimés
      </button>

      <div id="deleted-items-list" class="mt-4 hidden">
        <div id="deleted-items-content"></div>
      </div>
    </div>

    <!-- Audit Logs Subsection -->
    <div class="bg-gray-50 border border-gray-200 rounded p-4">
      <h3 class="text-lg font-semibold text-gray-800 mb-2">📋 Journaux d'audit</h3>
      <p class="text-gray-700 text-sm mb-4">
        Consultez l'historique de toutes les opérations (reset, recovery, imports).
      </p>

      <button id="show-audit-btn" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
        Afficher les journaux d'audit
      </button>

      <div id="audit-logs-list" class="mt-4 hidden overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-100 border-b">
            <tr>
              <th class="px-4 py-2 text-left font-semibold">Action</th>
              <th class="px-4 py-2 text-left font-semibold">Modèle</th>
              <th class="px-4 py-2 text-left font-semibold">Utilisateur</th>
              <th class="px-4 py-2 text-left font-semibold">Raison</th>
              <th class="px-4 py-2 text-left font-semibold">Date</th>
            </tr>
          </thead>
          <tbody id="audit-logs-content" class="divide-y"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  // Show Deleted Items
  document.getElementById('show-deleted-btn')?.addEventListener('click', async function() {
    const container = document.getElementById('deleted-items-list');
    const content = document.getElementById('deleted-items-content');
    
    this.disabled = true;
    
    try {
      const response = await fetch('{{ route("backups.deleted-items") }}');
      const data = await response.json();
      
      if (data.success && data.data.count > 0) {
        let html = `<p class="text-sm text-blue-700 mb-3">${data.data.count} élément(s) peut/peuvent être récupéré(s).</p>`;
        html += '<table class="min-w-full text-sm border">';
        html += '<thead class="bg-blue-100"><tr><th class="px-3 py-2 text-left">ID</th><th class="px-3 py-2 text-left">Nom</th><th class="px-3 py-2 text-left">Délai</th><th class="px-3 py-2 text-left">Raison</th></tr></thead>';
        html += '<tbody>';
        
        data.data.items.forEach(item => {
          html += `<tr class="border-b"><td class="px-3 py-2">${item.id}</td><td class="px-3 py-2">${item.name}</td><td class="px-3 py-2">${item.days_remaining}j</td><td class="px-3 py-2 text-xs">${item.reason || '—'}</td></tr>`;
        });
        
        html += '</tbody></table>';
        content.innerHTML = html;
      } else {
        content.innerHTML = '<p class="text-sm text-blue-700">Aucun élément supprimé trouvé.</p>';
      }
      
      container.classList.remove('hidden');
    } catch (error) {
      alert('Erreur: ' + error.message);
    } finally {
      this.disabled = false;
    }
  });

  // Show Audit Logs
  document.getElementById('show-audit-btn')?.addEventListener('click', async function() {
    const container = document.getElementById('audit-logs-list');
    const tbody = document.getElementById('audit-logs-content');
    
    this.disabled = true;
    
    try {
      const response = await fetch('{{ route("backups.audit-logs") }}');
      const data = await response.json();
      
      if (data.success && data.data.logs.length > 0) {
        tbody.innerHTML = '';
        data.data.logs.forEach(log => {
          const row = `<tr class="hover:bg-gray-50">
            <td class="px-4 py-2">${log.action}</td>
            <td class="px-4 py-2">${log.model || '—'}</td>
            <td class="px-4 py-2">${log.user}</td>
            <td class="px-4 py-2 text-xs">${log.reason || '—'}</td>
            <td class="px-4 py-2 text-xs text-gray-600">${new Date(log.created_at).toLocaleString('fr-FR')}</td>
          </tr>`;
          tbody.insertAdjacentHTML('beforeend', row);
        });
      } else {
        tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-2 text-center text-gray-500">Aucun journal d\'audit</td></tr>';
      }
      
      container.classList.remove('hidden');
    } catch (error) {
      alert('Erreur: ' + error.message);
    } finally {
      this.disabled = false;
    }
  });
</script>
