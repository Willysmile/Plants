<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\Location;
use App\Models\PurchasePlace;
use App\Models\Tag;
use App\Models\Disease;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StatisticsController extends Controller
{
    /**
     * Afficher le dashboard de statistiques
     */
    public function index()
    {
        // Statistiques générales - même condition que index pour cohérence
        $totalPlants = Plant::count();
        $activePlants = Plant::where(function ($query) {
            $query->whereNull('is_archived')
                  ->orWhere('is_archived', false);
        })->count();
        $archivedPlants = Plant::where('is_archived', true)->count();

        // Statistiques par famille
        $plantsByFamily = Plant::select('family', DB::raw('count(*) as total'))
            ->where(function ($query) {
                $query->whereNull('is_archived')
                      ->orWhere('is_archived', false);
            })
            ->groupBy('family')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Statistiques par emplacement (seulement ceux avec des plantes actives)
        $plantsByLocation = Location::withCount(['plants' => function ($query) {
            $query->where(function ($q) {
                $q->whereNull('is_archived')
                  ->orWhere('is_archived', false);
            });
        }])
            ->having('plants_count', '>', 0)
            ->orderByDesc('plants_count')
            ->get();

        // Statistiques par lieu d'achat
        $plantsByPurchasePlace = PurchasePlace::withCount(['plants' => function ($query) {
            $query->where(function ($q) {
                $q->whereNull('is_archived')
                  ->orWhere('is_archived', false);
            });
        }])
            ->orderByDesc('plants_count')
            ->get();

        // Top tags utilisés
        $topTags = Tag::withCount(['plants' => function ($query) {
            $query->where(function ($q) {
                $q->whereNull('is_archived')
                  ->orWhere('is_archived', false);
            });
        }])
            ->orderByDesc('plants_count')
            ->limit(15)
            ->get();

        // Historique d'arrosage - utiliser la table si elle existe
        $lastWatering = [];
        if (Schema::hasTable('watering_histories')) {
            $lastWatering = Plant::select('plants.id', 'plants.name', 'watering_histories.watering_date')
                ->join('watering_histories', 'plants.id', '=', 'watering_histories.plant_id')
                ->where(function ($query) {
                    $query->whereNull('plants.is_archived')
                          ->orWhere('plants.is_archived', false);
                })
                ->orderByDesc('watering_histories.watering_date')
                ->limit(10)
                ->get();
        }

        // Plantes dues pour arrosage
        $plantsDueForWatering = Plant::where(function ($query) {
            $query->whereNull('is_archived')
                  ->orWhere('is_archived', false);
        })
            ->limit(10)
            ->get();

        if (Schema::hasTable('watering_histories')) {
            $plantsDueForWatering = Plant::where(function ($query) {
                $query->whereNull('is_archived')
                      ->orWhere('is_archived', false);
            })
                ->whereRaw('id NOT IN (
                    SELECT DISTINCT plant_id FROM watering_histories
                    WHERE watering_date > DATE_SUB(NOW(), INTERVAL 14 DAY)
                )')
                ->orWhereDoesntHave('wateringHistories')
                ->where(function ($query) {
                    $query->whereNull('is_archived')
                          ->orWhere('is_archived', false);
                })
                ->limit(10)
                ->get();
        }

        // Statistiques des maladies - utiliser la table si elle existe
        $activeDiseases = [];
        $curedDiseases = [];
        $diseasesStats = [
            'detected' => 0,
            'treated' => 0,
            'recurring' => 0,
            'healthy' => 0
        ];
        
        if (Schema::hasTable('disease_histories')) {
            $activeDiseases = DB::table('disease_histories')
                ->join('plants', 'disease_histories.plant_id', '=', 'plants.id')
                ->join('diseases', 'disease_histories.disease_id', '=', 'diseases.id')
                ->where('disease_histories.status', '!=', 'cured')
                ->where(function ($query) {
                    $query->whereNull('plants.is_archived')
                          ->orWhere('plants.is_archived', false);
                })
                ->select('diseases.name', 'disease_histories.status', DB::raw('count(*) as count'))
                ->groupBy('diseases.name', 'disease_histories.status')
                ->orderByDesc('count')
                ->get();

            $curedDiseases = DB::table('disease_histories')
                ->join('diseases', 'disease_histories.disease_id', '=', 'diseases.id')
                ->where('disease_histories.status', 'cured')
                ->select('diseases.name', DB::raw('count(*) as count'))
                ->groupBy('diseases.name')
                ->orderByDesc('count')
                ->limit(10)
                ->get();
            
            // Compter les plantes par état de santé
            $diseasesStats['detected'] = DB::table('disease_histories')
                ->join('plants', 'disease_histories.plant_id', '=', 'plants.id')
                ->where('disease_histories.status', 'detected')
                ->where(function ($query) {
                    $query->whereNull('plants.is_archived')
                          ->orWhere('plants.is_archived', false);
                })
                ->distinct('plant_id')
                ->count('plant_id');
                
            $diseasesStats['treated'] = DB::table('disease_histories')
                ->join('plants', 'disease_histories.plant_id', '=', 'plants.id')
                ->where('disease_histories.status', 'treated')
                ->where(function ($query) {
                    $query->whereNull('plants.is_archived')
                          ->orWhere('plants.is_archived', false);
                })
                ->distinct('plant_id')
                ->count('plant_id');
                
            $diseasesStats['recurring'] = DB::table('disease_histories')
                ->join('plants', 'disease_histories.plant_id', '=', 'plants.id')
                ->where('disease_histories.status', 'recurring')
                ->where(function ($query) {
                    $query->whereNull('plants.is_archived')
                          ->orWhere('plants.is_archived', false);
                })
                ->distinct('plant_id')
                ->count('plant_id');
            
            // Plantes en bonne santé = actives - celles avec des maladies
            $plantsWithDiseases = DB::table('disease_histories')
                ->join('plants', 'disease_histories.plant_id', '=', 'plants.id')
                ->where(function ($query) {
                    $query->whereNull('plants.is_archived')
                          ->orWhere('plants.is_archived', false);
                })
                ->where('disease_histories.status', '!=', 'cured')
                ->distinct('plant_id')
                ->count('plant_id');
                
            $diseasesStats['healthy'] = $activePlants - $plantsWithDiseases;
        }

        // Statistiques de besoins en arrosage
        $wateringFrequencies = Plant::select('watering_frequency', DB::raw('count(*) as total'))
            ->where(function ($query) {
                $query->whereNull('is_archived')
                      ->orWhere('is_archived', false);
            })
            ->groupBy('watering_frequency')
            ->get();

        $lightRequirements = Plant::select('light_requirement', DB::raw('count(*) as total'))
            ->where(function ($query) {
                $query->whereNull('is_archived')
                      ->orWhere('is_archived', false);
            })
            ->groupBy('light_requirement')
            ->get();

        // Historique de fertilisation
        $lastFertilizing = [];
        if (Schema::hasTable('fertilizing_histories')) {
            $lastFertilizing = Plant::select('plants.id', 'plants.name', 'fertilizing_histories.fertilizing_date')
                ->join('fertilizing_histories', 'plants.id', '=', 'fertilizing_histories.plant_id')
                ->where(function ($query) {
                    $query->whereNull('plants.is_archived')
                          ->orWhere('plants.is_archived', false);
                })
                ->orderByDesc('fertilizing_histories.fertilizing_date')
                ->limit(10)
                ->get();
        }

        // Historique de rempotage
        $lastRepotting = [];
        if (Schema::hasTable('repotting_histories')) {
            $lastRepotting = Plant::select('plants.id', 'plants.name', 'repotting_histories.repotting_date')
                ->join('repotting_histories', 'plants.id', '=', 'repotting_histories.plant_id')
                ->where(function ($query) {
                    $query->whereNull('plants.is_archived')
                          ->orWhere('plants.is_archived', false);
                })
                ->orderByDesc('repotting_histories.repotting_date')
                ->limit(10)
                ->get();
        }

        return view('statistics.index', compact(
            'totalPlants',
            'activePlants',
            'archivedPlants',
            'plantsByFamily',
            'plantsByLocation',
            'plantsByPurchasePlace',
            'topTags',
            'lastWatering',
            'plantsDueForWatering',
            'activeDiseases',
            'curedDiseases',
            'diseasesStats',
            'wateringFrequencies',
            'lightRequirements',
            'lastFertilizing',
            'lastRepotting'
        ));
    }
}
