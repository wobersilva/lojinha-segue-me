<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Lista todos os usuários
     */
    public function index()
    {
        $users = User::with('approver')
            ->orderBy('is_approved', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingCount = User::where('is_approved', false)->count();

        return view('admin.users.index', compact('users', 'pendingCount'));
    }

    /**
     * Aprova um usuário
     */
    public function approve(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Você não tem permissão para esta ação.');
        }

        if ($user->isApproved()) {
            return redirect()->back()->with('info', 'Este usuário já está aprovado.');
        }

        $user->update([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', "Usuário {$user->name} aprovado com sucesso!");
    }

    /**
     * Rejeita/Remove aprovação de um usuário
     */
    public function reject(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Você não tem permissão para esta ação.');
        }

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Você não pode rejeitar sua própria conta.');
        }

        $user->delete();

        return redirect()->back()->with('success', "Usuário {$user->name} rejeitado e removido do sistema.");
    }

    /**
     * Alterna status de administrador
     */
    public function toggleAdmin(User $user)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Você não tem permissão para esta ação.');
        }

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Você não pode alterar suas próprias permissões de administrador.');
        }

        if (!$user->isApproved()) {
            return redirect()->back()->with('error', 'O usuário precisa estar aprovado antes de se tornar administrador.');
        }

        $user->update([
            'is_admin' => !$user->is_admin,
        ]);

        $action = $user->is_admin ? 'promovido a' : 'removido de';
        
        return redirect()->back()->with('success', "Usuário {$user->name} {$action} administrador!");
    }

    /**
     * Reseta a senha de um usuário
     */
    public function resetPassword(Request $request, User $user)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Você não tem permissão para esta ação.');
        }

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Você não pode resetar sua própria senha por aqui. Use a opção "Perfil".');
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'password.confirmed' => 'As senhas não coincidem.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', "Senha do usuário {$user->name} resetada com sucesso!");
    }
}
