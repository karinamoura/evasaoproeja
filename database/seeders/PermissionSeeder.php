<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public const GUARD = 'web';

    /**
     * Permissões agrupadas por módulo/entidade do sistema.
     * Padrão: entidade.ação (ex: usuarios.view, ofertas.create)
     */
    protected array $permissionsByModule = [
        'Usuários e Acesso' => [
            'usuarios.view',
            'usuarios.create',
            'usuarios.edit',
            'usuarios.delete',
            'perfis.view',
            'perfis.create',
            'perfis.edit',
            'perfis.delete',
            'permissoes.view',
            'permissoes.create',
            'permissoes.edit',
            'permissoes.delete',
        ],
        'Cadastros Básicos' => [
            'instituicoes.view',
            'instituicoes.create',
            'instituicoes.edit',
            'instituicoes.delete',
            'escolas.view',
            'escolas.create',
            'escolas.edit',
            'escolas.delete',
            'ofertas.view',
            'ofertas.create',
            'ofertas.edit',
            'ofertas.delete',
        ],
        'Questionários' => [
            'questionarios.view',
            'questionarios.create',
            'questionarios.edit',
            'questionarios.delete',
            'questionario-oferta.view',
            'questionario-oferta.create',
            'questionario-oferta.edit',
            'questionario-oferta.delete',
            'questionario-oferta.respostas',
            'questionario-oferta.export',
            'termo-condicao.view',
            'termo-condicao.create',
            'termo-condicao.edit',
            'termo-condicao.delete',
        ],
        'Frequência e Acadêmico' => [
            'disciplinas.view',
            'disciplinas.create',
            'disciplinas.edit',
            'disciplinas.delete',
            'disciplinas.attach-estudantes',
            'estudantes.view',
            'estudantes.create',
            'estudantes.edit',
            'estudantes.delete',
            'estudantes.upload',
            'frequencias.view',
            'frequencias.create',
            'frequencias.edit',
            'frequencias.delete',
        ],
        'Relatórios' => [
            'relatorios.view',
        ],
    ];

    public function run(): void
    {
        foreach ($this->permissionsByModule as $module => $permissions) {
            foreach ($permissions as $name) {
                Permission::firstOrCreate(
                    ['name' => $name, 'guard_name' => self::GUARD],
                    ['name' => $name, 'guard_name' => self::GUARD]
                );
            }
        }
    }

    /**
     * Retorna o mapa módulo => permissões (para uso em views/controllers).
     */
    public static function getModules(): array
    {
        $instance = new self;
        return $instance->permissionsByModule;
    }

    /**
     * Retorna o nome do módulo para uma permissão (para exibição na listagem).
     */
    public static function getModuleForPermission(string $name): string
    {
        foreach (self::getModules() as $module => $permissions) {
            if (in_array($name, $permissions)) {
                return $module;
            }
        }
        return 'Outros';
    }
}
