# CHANGELOG

Toutes les modifications notables du projet Plants sont documentées dans ce fichier.

## [v1.1] - 2025-10-20
### Ajoutées
- Restructuration base de données : 4 tables lookup (`watering_frequencies`, `light_requirements`, `purchase_places`, `locations`) + seeders.
- Système d'archivage : archiver / restaurer les plantes ; vue "Plantes archivées".
- Génération automatique de références via endpoint API (`POST /plants/generate-reference`) avec incrémentation et feedback utilisateur.
- Réorganisation de l'UI : refonte de la vue `show.blade.php` (layout 1/3 - 2/3), amélioration des modal et des cartes d'information.
- Système de tags amélioré : 9 catégories, modal checkbox 4-colonnes, badges colorés par catégorie, affichage dynamique côté client.
- Correction d'affichage : ajout de `@stack('scripts')` au layout `simple.blade.php` pour garantir l'exécution des scripts pushés par les composants.

### Corrigées
- Icônes Lucide ne s'initialisant pas correctement dans la modal → ajout du script et `lucide.createIcons()`.
- Problème d'initialisation des tags sur la page create → résolu (scripts exécutés via @stack).

### Divers
- Plusieurs migrations et seeders ajoutés.
- Documentation interne mise à jour (`RAPPORT_COMPLET_PROJET.md`).

---

## [Unreleased] - Objectifs pour v1.101 (prochaine version)
Priorité élevée (à traiter en premier)
- Tests automatisés :
  - Écrire une suite initiale de tests (Pest/phpunit) couvrant : création, édition, suppression de plantes ; upload de photos ; génération de référence.
  - Critère d'acceptation : couverture smoke tests OK, pipeline local vert.
- Validation serveur côté tags :
  - Valider côté serveur que les `tags[]` existent et appartiennent à la table `tags` lors du store/update.
  - Critère : injections/ids invalides rejetés (422).
- UI Admin pour création/édition de tags :
  - Petite interface accessible uniquement aux administrateurs pour créer/catégoriser tags.
  - Critère : tags créés disponibles immédiatement dans le modal (via seeders ou API temps réel).

Priorité moyenne
- Export/Import : export JSON/CSV des plantes + images packées en ZIP ; import avec validation.
- Batch actions sur index : sélectionner plusieurs plantes et archiver/restaurer.
- Accessibilité : audit WCAG, correction contraste et focus, labels ARIA.

Priorité basse
- Thème sombre (Tailwind Dark Mode) et préférence utilisateur.
- Internationalisation : externaliser les chaînes dans `resources/lang`.
- Notifications & rappels (vérif conceptuel + notifications locales).

### Notes de planification v1.101
- Estimer chaque tâche (T-shirt sizing). Prioriser tests + validation serveur + UI tags.
- Créer branche `v1.101` depuis `v1.1` pour développement isolé.
- Ajouter CI minimal (workflow GitHub Actions) pour exécuter tests unitaires à chaque PR.

---

*Fichier généré automatiquement lors de la clôture du chantier v1.1.*
