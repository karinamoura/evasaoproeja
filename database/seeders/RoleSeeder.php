<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public const GUARD = 'web';

    /** Nomes dos perfis padrão do sistema */
    public const ROLE_ADMIN = 'admin';
    public const ROLE_PEDAGOGICO = 'pedagogico';
    public const ROLE_PROFESSOR = 'professor';
    public const ROLE_USER = 'user';

    public function run(): void
    {
        $allPermissions = Permission::where('guard_name', self::GUARD)->pluck('name')->toArray();

        $admin = Role::firstOrCreate(
            ['name' => self::ROLE_ADMIN, 'guard_name' => self::GUARD],
            ['name' => self::ROLE_ADMIN, 'guard_name' => self::GUARD]
        );
        $admin->syncPermissions($allPermissions);

        $pedagogico = Role::firstOrCreate(
            ['name' => self::ROLE_PEDAGOGICO, 'guard_name' => self::GUARD],
            ['name' => self::ROLE_PEDAGOGICO, 'guard_name' => self::GUARD]
        );
        $pedagogicoPerms = array_filter($allPermissions, fn ($p) => !self::isUserAccessPermission($p));
        $pedagogico->syncPermissions($pedagogicoPerms);

        $professor = Role::firstOrCreate(
            ['name' => self::ROLE_PROFESSOR, 'guard_name' => self::GUARD],
            ['name' => self::ROLE_PROFESSOR, 'guard_name' => self::GUARD]
        );
        $professorPerms = [
            'disciplinas.view',
            'estudantes.view',
            'frequencias.view',
            'frequencias.create',
            'frequencias.edit',
            'frequencias.delete',
        ];
        $professor->syncPermissions(array_intersect($professorPerms, $allPermissions));

        $user = Role::firstOrCreate(
            ['name' => self::ROLE_USER, 'guard_name' => self::GUARD],
            ['name' => self::ROLE_USER, 'guard_name' => self::GUARD]
        );
        $user->syncPermissions([]);
    }

    private static function isUserAccessPermission(string $name): bool
    {
        $prefixes = ['usuarios.', 'perfis.', 'permissoes.'];
        foreach ($prefixes as $prefix) {
            if (str_starts_with($name, $prefix)) {
                return true;
            }
        }
        return false;
    }
}
