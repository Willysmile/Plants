<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Location;
use App\Models\PurchasePlace;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrer les données existantes de location (string) vers location_id (FK)
        $plants = DB::table('plants')->whereNotNull('location')->get();
        
        foreach ($plants as $plant) {
            $location = Location::firstOrCreate(['name' => $plant->location]);
            DB::table('plants')
                ->where('id', $plant->id)
                ->update(['location_id' => $location->id]);
        }

        // Migrer les données existantes de purchase_place (string) vers purchase_place_id (FK)
        $plants = DB::table('plants')->whereNotNull('purchase_place')->get();
        
        foreach ($plants as $plant) {
            $purchasePlace = PurchasePlace::firstOrCreate(['name' => $plant->purchase_place]);
            DB::table('plants')
                ->where('id', $plant->id)
                ->update(['purchase_place_id' => $purchasePlace->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reversement: restaurer les colonnes string à partir des relations
        $plants = DB::table('plants')
            ->join('locations', 'plants.location_id', '=', 'locations.id')
            ->select('plants.id', 'locations.name')
            ->get();

        foreach ($plants as $plant) {
            DB::table('plants')
                ->where('id', $plant->id)
                ->update(['location' => $plant->name]);
        }

        $plants = DB::table('plants')
            ->join('purchase_places', 'plants.purchase_place_id', '=', 'purchase_places.id')
            ->select('plants.id', 'purchase_places.name')
            ->get();

        foreach ($plants as $plant) {
            DB::table('plants')
                ->where('id', $plant->id)
                ->update(['purchase_place' => $plant->name]);
        }

        // Nettoyer les colonnes FK
        DB::table('plants')->update([
            'location_id' => null,
            'purchase_place_id' => null
        ]);
    }
};
