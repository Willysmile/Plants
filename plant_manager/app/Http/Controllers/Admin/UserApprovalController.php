<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserApprovalController extends Controller
{
    /**
     * Afficher la liste des utilisateurs en attente d'approbation et approuvés.
     */
    public function index(): View
    {
        $pendingUsers = User::whereNull('approved_at')->get();
        $approvedUsers = User::whereNotNull('approved_at')->get();

        return view('admin.users.approval', [
            'pendingUsers' => $pendingUsers,
            'approvedUsers' => $approvedUsers,
        ]);
    }

    /**
     * Approuver un utilisateur.
     */
    public function approve(User $user): RedirectResponse
    {
        $this->authorize('admin', $user);
        
        $user->approve();

        return redirect()->route('admin.users.approval')
            ->with('success', "L'utilisateur {$user->name} a été approuvé.");
    }

    /**
     * Rejeter un utilisateur (l'empêcher de se connecter).
     */
    public function reject(User $user): RedirectResponse
    {
        $this->authorize('admin', $user);
        
        $user->reject();

        return redirect()->route('admin.users.approval')
            ->with('success', "L'approbation de l'utilisateur {$user->name} a été rejetée.");
    }

    /**
     * Supprimer un utilisateur en attente.
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('admin', $user);
        
        if ($user->isApproved()) {
            return redirect()->route('admin.users.approval')
                ->with('error', 'Impossible de supprimer un utilisateur approuvé.');
        }

        $user->delete();

        return redirect()->route('admin.users.approval')
            ->with('success', "L'utilisateur {$user->name} a été supprimé.");
    }
}
