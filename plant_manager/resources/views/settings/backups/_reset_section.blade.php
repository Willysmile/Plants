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
        Supprime toutes les plantes, photos et historiques. Créez une sauvegarde avant de continuer.
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
              <th class="px-4 py-2 text-left font-semibold">Détails</th>
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
  // Toggle reset section
  document.getElementById('toggle-reset-btn')?.addEventListener('click', function() {
    const section = document.querySelector('[data-reset-section]');
    section.classList.toggle('hidden');
    this.textContent = section.classList.contains('hidden') ? 'Afficher options avancées' : 'Masquer options avancées';
  });

  // Reset Preview
  document.getElementById('reset-preview-btn')?.addEventListener('click', async function() {
    const createBackup = document.getElementById('reset-backup-checkbox').checked;
    const reason = document.getElementById('reset-reason').value;
    const previewDiv = document.getElementById('reset-preview');
    const contentDiv = document.getElementById('reset-preview-content');
    
    this.disabled = true;
    
    try {
      const response = await fetch('{{ route("backups.reset-preview") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
          create_backup: createBackup,
          reason: reason,
        }),
      });
      
      const data = await response.json();
      
      if (data.success) {
        const result = data.result;
        contentDiv.innerHTML = `
          <table class="min-w-full text-sm border-collapse">
            <tr class="border-b">
              <td class="px-2 py-1">Plantes à supprimer</td>
              <td class="px-2 py-1 font-semibold text-red-700">${result.plants_count || 0}</td>
            </tr>
            <tr class="border-b">
              <td class="px-2 py-1">Photos à supprimer</td>
              <td class="px-2 py-1 font-semibold text-red-700">${result.photos_count || 0}</td>
            </tr>
            <tr class="border-b">
              <td class="px-2 py-1">Historiques à supprimer</td>
              <td class="px-2 py-1 font-semibold text-red-700">${result.histories_count || 0}</td>
            </tr>
            <tr>
              <td class="px-2 py-1">Sauvegarde créée</td>
              <td class="px-2 py-1 font-semibold text-blue-700">${createBackup ? '✓ Oui' : '✗ Non'}</td>
            </tr>
          </table>
          ${result.recovery_deadline ? `<p class="text-xs text-red-600 mt-2">Récupération possible jusqu'au: ${result.recovery_deadline}</p>` : ''}
        `;
        previewDiv.classList.remove('hidden');
      } else {
        alert('Erreur: ' + (data.message || 'Impossible de générer l\'aperçu'));
      }
    } catch (error) {
      alert('Erreur: ' + error.message);
    } finally {
      this.disabled = false;
    }
  });

  // Confirm Reset
  document.getElementById('confirm-reset-btn')?.addEventListener('click', async function() {
    if (!confirm('⚠️ ATTENTION: Vous êtes sur le point de supprimer toutes les plantes.\nCette action est irréversible pendant 30 jours.\nÊtes-vous sûr ?')) {
      return;
    }
    
    if (!confirm('🚨 DEUXIÈME CONFIRMATION: Êtes-vous absolument sûr ? Cette action supprimera TOUTES les données !')) {
      return;
    }

    const createBackup = document.getElementById('reset-backup-checkbox').checked;
    const reason = document.getElementById('reset-reason').value;
    const statusDiv = document.getElementById('reset-status');
    const previewDiv = document.getElementById('reset-preview');
    
    this.disabled = true;
    statusDiv.classList.remove('hidden');
    previewDiv.classList.add('hidden');
    
    try {
      const response = await fetch('{{ route("backups.reset") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
          confirmed: true,
          create_backup: createBackup,
          reason: reason,
        }),
      });
      
      console.log('Reset response status:', response.status);
      console.log('Reset response headers:', response.headers);
      
      if (!response.ok) {
        const text = await response.text();
        console.error('Reset error response:', text);
        alert('✗ Erreur serveur: ' + response.status + ' ' + response.statusText);
        return;
      }
      
      const data = await response.json();
      
      if (data.success) {
        alert('✓ Réinitialisation complète!\nLes données restent récupérables pendant 30 jours.');
        setTimeout(() => window.location.reload(), 2000);
      } else {
        alert('✗ Erreur: ' + (data.message || 'Erreur inconnue'));
      }
    } catch (error) {
      console.error('Reset fetch error:', error);
      alert('✗ Erreur: ' + error.message);
    } finally {
      this.disabled = false;
      statusDiv.classList.add('hidden');
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
          // Format action name
          const actionLabel = log.action === 'reset_completed' ? '🔴 Réinitialisation' : 
                             log.action === 'recover' ? '🔵 Récupération' :
                             log.action === 'import_completed' ? '📥 Import' : log.action;
          
          // Format details for aggregated actions
          let details = '—';
          if (log.details) {
            const d = log.details;
            if (d.plants_deleted !== undefined) {
              details = `${d.plants_deleted} plante(s), ${d.photos_deleted} photo(s), ${d.histories_deleted} historique(s)`;
            } else if (d.plants_recovered !== undefined) {
              details = `${d.plants_recovered} plante(s) récupérée(s)`;
            } else if (d.plants_imported !== undefined) {
              details = `${d.plants_imported} plante(s), ${d.photos_imported || 0} photo(s)`;
            }
          }
          
          const row = `<tr class="hover:bg-gray-50">
            <td class="px-4 py-2 font-semibold">${actionLabel}</td>
            <td class="px-4 py-2 text-sm">${details}</td>
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
