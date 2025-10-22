<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Plant;

class AssignMainPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plants:assign-main-photos {--dry-run : Preview changes without making them}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign the first photo of each plant as the main photo';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
        }

        // Find plants without main_photo but with photos
        $plants = Plant::whereNull('main_photo')
            ->orWhere('main_photo', '')
            ->with('photos')
            ->get()
            ->filter(fn($p) => $p->photos->count() > 0);

        if ($plants->isEmpty()) {
            $this->info('✓ Toutes les plantes ont déjà une photo principale');
            return 0;
        }

        $this->info("Assignation de photos principales pour {$plants->count()} plantes...");
        $this->newLine();

        $updated = 0;

        foreach ($plants as $plant) {
            $firstPhoto = $plant->photos->first();

            if (!$firstPhoto) {
                $this->warn("⚠️  {$plant->name} - Aucune photo trouvée");
                continue;
            }

            $this->line("⏳ {$plant->name}: {$firstPhoto->filename}");

            if (!$dryRun) {
                $plant->update([
                    'main_photo' => $firstPhoto->filename,
                ]);
                
                // Mark this photo as main in the photos table too
                $plant->photos()->update(['is_main' => false]);
                $firstPhoto->update(['is_main' => true]);
            }

            $this->info("   ✓ Assigning as main photo");
            $updated++;
        }

        $this->newLine();
        $this->info("📊 Résumé:");
        $this->info("   ✓ Photos assignées: {$updated}");

        if ($dryRun) {
            $this->info("\n💡 Exécutez sans --dry-run pour appliquer les changements");
        }

        return 0;
    }
}
