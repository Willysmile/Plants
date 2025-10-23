<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserApprovalController extends Controller
{
    public function __construct()
    {
        // Vérifier que l'utilisateur est admin
        $this->middleware(function ($request, $next) {
            if (!$request->user() || !$request->user()->is_admin) {
                abort(403, 'Accès non autorisé');
            }
            return $next($request);
        });
    }

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
        $user->approve();

        return redirect()->route('admin.users.approval')
            ->with('success', "L'utilisateur {$user->name} a été approuvé.");
    }

    /**
     * Rejeter un utilisateur (l'empêcher de se connecter).
     */
    public function reject(User $user): RedirectResponse
    {
        $user->reject();

        return redirect()->route('admin.users.approval')
            ->with('success', "L'approbation de l'utilisateur {$user->name} a été rejetée.");
    }

    /**
     * Supprimer un utilisateur en attente.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->isApproved()) {
            return redirect()->route('admin.users.approval')
                ->with('error', 'Impossible de supprimer un utilisateur approuvé.');
        }

        $user->delete();

        return redirect()->route('admin.users.approval')
            ->with('success', "L'utilisateur {$user->name} a été supprimé.");
    }
}
