@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900">Gestion des approbations d'utilisateurs</h1>
            <p class="mt-2 text-slate-600">Approuvez ou rejetez les nouveaux comptes utilisateurs</p>
        </div>

        <!-- Messages de statut -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <!-- Utilisateurs en attente d'approbation -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-yellow-50 border-b border-yellow-200">
                    <h2 class="text-xl font-semibold text-yellow-900">
                        ⏳ En attente d'approbation ({{ count($pendingUsers) }})
                    </h2>
                </div>

                @if($pendingUsers->isEmpty())
                    <div class="px-6 py-12 text-center text-slate-500">
                        Aucun utilisateur en attente d'approbation
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Nom</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Email</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Date d'inscription</th>
                                    <th class="px-6 py-3 text-right text-sm font-semibold text-slate-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($pendingUsers as $user)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4 text-sm text-slate-900">{{ $user->name }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-600">{{ $user->email }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-600">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 text-right text-sm">
                                            <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition mr-2">
                                                    ✓ Approuver
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition mr-2">
                                                    ⊘ Rejeter
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                                    🗑 Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Utilisateurs approuvés -->
        <div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-green-50 border-b border-green-200">
                    <h2 class="text-xl font-semibold text-green-900">
                        ✓ Utilisateurs approuvés ({{ count($approvedUsers) }})
                    </h2>
                </div>

                @if($approvedUsers->isEmpty())
                    <div class="px-6 py-12 text-center text-slate-500">
                        Aucun utilisateur approuvé
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Nom</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Email</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Admin</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Approuvé le</th>
                                    <th class="px-6 py-3 text-right text-sm font-semibold text-slate-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($approvedUsers as $user)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4 text-sm text-slate-900">{{ $user->name }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-600">{{ $user->email }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            @if($user->is_admin)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    Administrateur
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                                    Utilisateur
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-600">{{ $user->approved_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 text-right text-sm">
                                            <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="inline" onsubmit="return confirm('Retirer l\'approbation de cet utilisateur ?');">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition">
                                                    ⊘ Retirer l'approbation
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Retour aux paramètres -->
        <div class="mt-8">
            <a href="{{ route('settings.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white rounded-md hover:bg-slate-700 transition">
                ← Retour aux paramètres
            </a>
        </div>
    </div>
</div>
@endsection
