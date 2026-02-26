<?php

namespace App\Http\Controllers;

use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /** Nomes amigáveis dos perfis padrão */
    public const ROLE_LABELS = [
        'admin' => 'Admin',
        'pedagogico' => 'Pedagógico',
        'professor' => 'Professor',
        'user' => 'Usuário comum',
    ];

    public function index()
    {
        $data = Role::where('guard_name', 'web')->orderBy('name')->get();
        return view('admin.role.index', compact('data'));
    }

    public function create()
    {
        $permissions = Permission::where('guard_name', 'web')->orderBy('name')->get();
        $groupedPermissions = $this->groupPermissionsByModule($permissions);
        return view('admin.role.create', compact('permissions', 'groupedPermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255', $request->id ? 'unique:roles,name,' . $request->id : 'unique:roles'],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        if($request->id) {
            $role = Role::find($request->id);
            $role->name = $request->name;
            $role->save();
            $msg = 'Perfil atualizado com sucesso.';
        } else {
            $role = Role::create([
                'name' => $request->name,
            ]);
            $msg = 'Perfil criado com sucesso.';
        }

        // Sincronizar permissões
        if($request->has('permissions')) {
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('admin.role.index')->with('success',$msg);
    }

    public function edit($id)
    {
        $data = Role::where('id', decrypt($id))->first();
        $permissions = Permission::where('guard_name', 'web')->orderBy('name')->get();
        $groupedPermissions = $this->groupPermissionsByModule($permissions);
        return view('admin.role.edit', compact('data', 'permissions', 'groupedPermissions'));
    }

    public function destroy($id)
    {
        $role = Role::where('id', decrypt($id))->first();
        if ($role && $role->name === RoleSeeder::ROLE_ADMIN) {
            return redirect()->route('admin.role.index')->with('error', 'Não é permitido excluir o perfil de administrador.');
        }
        if ($role) {
            $role->delete();
        }
        return redirect()->route('admin.role.index')->with('success', 'Perfil excluído com sucesso.');
    }

    /**
     * Agrupa permissões por módulo para exibição no formulário.
     */
    private function groupPermissionsByModule($permissions): array
    {
        $modules = PermissionSeeder::getModules();
        $grouped = [];
        foreach ($modules as $moduleName => $names) {
            $grouped[$moduleName] = $permissions->filter(fn ($p) => in_array($p->name, $names))->values();
        }
        return $grouped;
    }
}
