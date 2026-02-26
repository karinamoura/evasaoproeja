<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Encryption\DecryptException;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /** Labels para exibição dos perfis (nome técnico => nome amigável) */
    public const ROLE_LABELS = [
        'admin' => 'Admin',
        'pedagogico' => 'Pedagógico',
        'professor' => 'Professor',
        'user' => 'Usuário comum',
    ];

    public function __construct()
    {
        $roles = Role::where('guard_name', 'web')->orderBy('name')->get();
        view()->share('roles', $roles);
        view()->share('roleLabels', self::ROLE_LABELS);
    }
    public function index()
    {
        $data = User::orderBy('id','DESC')->get();
        return view('admin.user.index', compact('data'));
    }
    public function create()
    {
        return view('admin.user.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required', 'string', 'max:255',
            'email' => 'required', 'string', 'email', 'max:255', 'unique:'.User::class,
            'password' => 'required|max:255|min:6',
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->syncRoles([$request->role]);
        return redirect()->route('admin.user.index')->with('success', 'Usuário criado com sucesso.');
    }
    public function edit($id)
    {
        $user = User::where('id',decrypt($id))->first();
        return view('admin.user.edit',compact('user'));
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'string', 'exists:roles,name']
        ]); 
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        $user->syncRoles([$request->role]);
        return redirect()->route('admin.user.index')->with('success', 'Usuário atualizado com sucesso.');
    }
    public function destroy($id)
    {
        try {
            $userId = decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Link inválido. Tente novamente.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->back()->with('error', 'Usuário não encontrado.');
        }

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Você não pode excluir seu próprio usuário.');
        }

        $user->syncRoles([]);
        $user->delete();

        return redirect()->back()->with('success', 'Usuário excluído com sucesso.');
    }
}
