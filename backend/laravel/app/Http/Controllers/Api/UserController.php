<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Somente ADMIN pode listar usuários
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Somente ADMIN pode criar usuários
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:admin,user',
        ]);

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);
        $user->assignRole($data['role']);

        return response()->json($user, 201);
    }

    public function destroy(User $user)
    {
        // Remove o papel
        $user->syncRoles([]);

        // Exclui o usuário
        $user->delete();

        return response()->json(['message' => 'Usuário excluído com sucesso.']);
    }

}
