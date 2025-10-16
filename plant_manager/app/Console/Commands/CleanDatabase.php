<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class CleanDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vide la base de données et supprime toutes les photos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->confirm('Êtes-vous sûr de vouloir vider la base de données et supprimer toutes les photos ? Cette action est irréversible.')) {
            // Désactiver les contraintes de clés étrangères
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Tronquer les tables
            $tables = ['plants', 'photos', 'categories', 'histories'];
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->truncate();
                    $this->info("Table {$table} vidée.");
                }
            }
            
            // Réactiver les contraintes de clés étrangères
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            // Supprimer les photos
            Storage::deleteDirectory('public/plants');
            Storage::makeDirectory('public/plants');
            $this->info('Toutes les photos ont été supprimées.');
            
            $this->info('Opération terminée avec succès. La base de données et les photos ont été réinitialisées.');
        } else {
            $this->info('Opération annulée.');
        }
    }
}
