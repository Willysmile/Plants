<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ApproveExistingUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:approve-existing-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marquer les utilisateurs existants comme approuvés';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = User::whereNull('approved_at')->update(['approved_at' => now()]);
        
        $this->info("✓ {$count} utilisateur(s) marqué(s) comme approuvés.");
    }
}
