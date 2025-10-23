# Debug: 500 Error on /plants/101/histories

## Problem
User reported: "plants:1432 GET http://127.0.0.1:8001/plants/101/histories 500 (Internal Server Error) quand je valide une fertilisation dans la modale plantes"

The error occurs when:
1. User creates a fertilization via the quick modal in the plants list modal
2. After success, the JavaScript calls `reloadHistoriesInModal()`
3. Which makes an AJAX GET request to `/plants/101/histories`
4. This returns a 500 error

## Investigation

### Route
- Route: `GET /plants/{plant}/histories` → `PlantController@histories()`
- View: `plants.partials.histories`

### Controller Code
```php
public function histories(Plant $plant)
{
    $plant->load([
        'tags',
        'wateringHistories',
        'fertilizingHistories.fertilizerType',
        'repottingHistories',
    ]);
    return view('plants.partials.histories', compact('plant'));
}
```

### View Structure
`plants/partials/histories.blade.php` includes:
- `plants.partials.watering-history-modal`
- `plants.partials.fertilizing-history-modal`
- `plants.partials.repotting-history-modal`

### Testing Results
✅ Model loading works fine:
```
Plant found: Nouvelle Plante
Watering histories: 1
Fertilizing histories: 5
```

✅ View rendering works fine:
```
View rendered successfully
HTML length: 3009
```

## Possible Causes
The error likely occurs at runtime when rendering specific data or from middleware/authentication. Possible issues:

1. **Middleware Issue**: The request might not be properly authenticated or authorized
2. **Data-related**: A specific plant might have corrupted data causing rendering issues
3. **Method missing**: A relation method being called on a plant without eager loading
4. **Blade compilation**: Syntax error in one of the included partials

## Solutions to Try
1. Check Laravel logs: `tail -f storage/logs/laravel.log`
2. Add debugging to the controller
3. Test with different plants (IDs)
4. Verify all relations are properly defined
5. Check that all included views exist and have proper syntax

## Files Involved
- Controller: `app/Http/Controllers/PlantController.php` line 204-211
- Views: `resources/views/plants/partials/histories.blade.php`
- Partials:
  - `resources/views/plants/partials/watering-history-modal.blade.php`
  - `resources/views/plants/partials/fertilizing-history-modal.blade.php`
  - `resources/views/plants/partials/repotting-history-modal.blade.php`
- AJAX caller: `resources/views/plants/index.blade.php` line 98-123

## Next Steps
Need to capture the actual error details from Laravel logs when the 500 error occurs.
