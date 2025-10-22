# 📋 Migrations Summary

## Vue d'ensemble
- **Total** : 47 migrations
- **Status** : Toutes exécutées ✅

## Groupe 1: Framework Laravel (3)
- `0001_01_01_000000_create_users_table.php`
- `0001_01_01_000001_create_cache_table.php`
- `0001_01_01_000002_create_jobs_table.php`

## Groupe 2: Tables principales (6)
- `2025_10_07_215149_create_categories_table.php` - Categories for plants
- `2025_10_07_215200_create_plants_table.php`
- `2025_10_08_200156_create_tags_table.php`
- `2025_10_08_200414_create_plant_tag_table.php`
- `2025_10_08_200908_create_photos_table.php`
- `2025_10_08_201009_create_plant_propagations_table.php`

## Groupe 3: Modifications initiales (2)
- `2025_10_08_213127_fix_plant_propagations_table.php`
- `2025_10_16_072948_add_description_to_categories_table.php`
- `2025_10_16_add_is_admin_to_users_table.php`

## Groupe 4: Tables d'historique (3)
- `2025_10_17_074411_create_watering_history_table.php`
- `2025_10_17_074414_create_fertilizing_history_table.php`
- `2025_10_17_074417_create_repotting_history_table.php`

## Groupe 5: Settings & Types (2)
- `2025_10_17_082649_create_settings_table.php`
- `2025_10_19_171714_create_fertilizer_types_table.php`

## Groupe 6: Relations et modifications (3)
- `2025_10_19_171750_add_fertilizer_type_id_to_fertilizing_history_table.php`
- `2025_10_19_173438_add_unit_to_fertilizer_types_table.php`
- `2025_10_19_211518_add_reference_and_archived_to_plants_table.php`

## Groupe 7: Lookups/Enums (4)
- `2025_10_19_215846_create_watering_frequencies_table.php`
- `2025_10_19_215847_create_light_requirements_table.php`
- `2025_10_19_215848_create_purchase_places_table.php`
- `2025_10_19_215849_create_locations_table.php`

## Groupe 8: Colonnes lookup (5)
- `2025_10_19_215850_add_foreign_keys_to_plants_table.php`
- `2025_10_19_224350_add_columns_to_watering_frequencies_table.php`
- `2025_10_19_224413_add_columns_to_light_requirements_table.php`
- `2025_10_19_224413_add_columns_to_locations_table.php`
- `2025_10_19_224413_add_columns_to_purchase_places_table.php`

## Groupe 9: Date conversions (3)
- `2025_10_19_change_fertilizing_date_to_date.php`
- `2025_10_19_change_repotting_date_to_date.php`
- `2025_10_19_change_watering_date_to_date.php`

## Groupe 10: Améliorations (2)
- `2025_10_20_000001_add_pot_unit_to_settings_table.php`
- `2025_10_20_000002_add_botanical_classification_to_plants_table.php`

## Groupe 11: Nettoyage categories (3)
- `2025_10_20_000003_drop_categories_table.php`
- `2025_10_20_000004_add_family_to_plants_table.php`
- `2025_10_20_000005_add_subfamily_to_plants_table.php`

## Groupe 12: Reset données (1)
- `2025_10_20_000006_truncate_plants_data.php`

## Groupe 13: Audit & Admin (4)
- `2025_10_20_230023_create_plant_histories_table.php`
- `2025_10_21_000000_limit_plant_description_to_200_chars.php`
- `2025_10_21_220000_add_soft_delete_and_audit_to_plants.php`
- `2025_10_21_220001_create_audit_logs_table.php`

## Groupe 14: Tags Tagging (2)
- `2025_10_21_000001_add_category_to_tags_table.php`
- `2025_10_21_231749_add_categories_to_tags_table.php`

## Groupe 15: Audit refinement (1)
- `2025_10_21_220002_fix_soft_delete_columns_type.php`

## Groupe 16: Audit details (1)
- `2025_10_22_000000_add_details_to_audit_logs_table.php`

## Groupe 17: Tags Categories (RECENT) (4) ⭐
- `2025_10_22_182640_create_tag_categories_table.php` - New table
- `2025_10_22_182645_modify_tags_table_add_category_foreign_key.php` - FK relation
- `2025_10_22_183333_migrate_tag_categories.php` - Data migration (9 categories)
- `2025_10_22_184254_drop_category_column_from_tags_table.php` - Cleanup

## ⚠️ Notes importantes

1. **Ne pas utiliser `--prune`** sur les migrations sans quitter le mode production !
2. Les migrations sont triées par date et s'exécutent dans cet ordre
3. La consolidation manuelle risque de casser des dépendances FK
4. Les 47 migrations actuelles sont optimales pour le dev

## 🎯 Prochaines étapes possibles

Pour réduire vraiment les migrations (en production):
1. Créer un `database/schema/mysql-schema.sql` via `php artisan schema:dump`
2. Supprimer les anciennes migrations (sauf les plus récentes)
3. Utiliser le schema dump pour les nouveaux environnements

Mais pour l'instant, gardons les 47 migrations - c'est safe et traçable !
