<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Dashboard (todos os usuários autenticados) -->
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
            </a>
        </li>

        <!-- Seção: Perfis e Permissões (usuários, perfis, permissões) -->
        @if(auth()->user()->can('usuarios.view') || auth()->user()->can('perfis.view') || auth()->user()->can('permissoes.view'))
            <li class="nav-item {{ Route::is('admin.user.*') || Route::is('admin.role.*') || Route::is('admin.permission.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('admin.user.*') || Route::is('admin.role.*') || Route::is('admin.permission.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users-cog"></i>
                    <p>
                        Perfis
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @can('usuarios.view')
                    <li class="nav-item">
                        <a href="{{ route('admin.user.index') }}"
                            class="nav-link {{ Route::is('admin.user.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Usuários</p>
                        </a>
                    </li>
                    @endcan
                    @can('perfis.view')
                    <li class="nav-item">
                        <a href="{{ route('admin.role.index') }}"
                            class="nav-link {{ Route::is('admin.role.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p>Perfis</p>
                        </a>
                    </li>
                    @endcan
                    @can('permissoes.view')
                    <li class="nav-item">
                        <a href="{{ route('admin.permission.index') }}"
                            class="nav-link {{ Route::is('admin.permission.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-key"></i>
                            <p>Permissões</p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
        @endif

        <!-- Seção: Cadastros Básicos -->
        @if(auth()->user()->can('instituicoes.view') || auth()->user()->can('escolas.view') || auth()->user()->can('ofertas.view'))
            <li class="nav-item {{ Route::is('admin.campi.*') || Route::is('admin.escola.*') || Route::is('admin.oferta.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('admin.campi.*') || Route::is('admin.escola.*') || Route::is('admin.oferta.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-building"></i>
                    <p>
                        Cadastros
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @can('instituicoes.view')
                    <li class="nav-item">
                        <a href="{{ route('admin.campi.index') }}"
                            class="nav-link {{ Route::is('admin.campi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-university"></i>
                            <p>Instituições</p>
                        </a>
                    </li>
                    @endcan
                    @can('escolas.view')
                    <li class="nav-item">
                        <a href="{{ route('admin.escola.index') }}"
                            class="nav-link {{ Route::is('admin.escola.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-school"></i>
                            <p>Escolas</p>
                        </a>
                    </li>
                    @endcan
                    @can('ofertas.view')
                    <li class="nav-item">
                        <a href="{{ route('admin.oferta.index') }}"
                            class="nav-link {{ Route::is('admin.oferta.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-graduation-cap"></i>
                            <p>Ofertas</p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>

            <!-- Seção: Questionários -->
            @if(auth()->user()->can('questionarios.view') || auth()->user()->can('questionario-oferta.view') || auth()->user()->can('termo-condicao.view'))
            <li class="nav-item {{ Route::is('admin.questionario.*') || Route::is('admin.questionario-oferta.*') || Route::is('admin.termo-condicao.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('admin.questionario.*') || Route::is('admin.questionario-oferta.*') || Route::is('admin.termo-condicao.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clipboard-list"></i>
                    <p>
                        Questionários
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @can('questionarios.view')
                    <li class="nav-item">
                        <a href="{{ route('admin.questionario.index') }}"
                            class="nav-link {{ Route::is('admin.questionario.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Templates</p>
                        </a>
                    </li>
                    @endcan
                    @can('questionario-oferta.view')
                    <li class="nav-item">
                        <a href="{{ route('admin.questionario-oferta.index') }}"
                            class="nav-link {{ Route::is('admin.questionario-oferta.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-check"></i>
                            <p>Questionários Ofertados</p>
                        </a>
                    </li>
                    @endcan
                    @can('termo-condicao.view')
                    <li class="nav-item">
                        <a href="{{ route('admin.termo-condicao.index') }}"
                            class="nav-link {{ Route::is('admin.termo-condicao.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-contract"></i>
                            <p>Termos e Condições</p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif
        @endif

        <!-- Seção: Frequência (disciplinas e estudantes) -->
        @if(auth()->user()->can('disciplinas.view') || auth()->user()->can('estudantes.view'))
            <li class="nav-item {{ Route::is('admin.disciplina.*') || Route::is('admin.estudante.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('admin.disciplina.*') || Route::is('admin.estudante.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-book"></i>
                    <p>
                        Frequência
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @can('disciplinas.view')
                    <li class="nav-item">
                        <a href="{{ route('admin.disciplina.index') }}"
                            class="nav-link {{ Route::is('admin.disciplina.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Componente Curricular</p>
                        </a>
                    </li>
                    @endcan
                    @can('estudantes.view')
                    <li class="nav-item">
                        <a href="{{ route('admin.estudante.index') }}"
                            class="nav-link {{ Route::is('admin.estudante.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>Estudantes</p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
        @endif

        <!-- Registro de Frequência -->
        @can('frequencias.view')
            <li class="nav-item">
                <a href="{{ route('admin.frequencia.index') }}"
                    class="nav-link {{ Route::is('admin.frequencia.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clipboard-check"></i>
                    <p>Registro de Frequência</p>
                </a>
            </li>
        @endcan

        <!-- Seção: Relatórios -->
        @can('relatorios.view')
            <li class="nav-item {{ Route::is('admin.relatorio.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('admin.relatorio.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-chart-bar"></i>
                    <p>
                        Relatórios
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.relatorio.index') }}"
                            class="nav-link {{ Route::is('admin.relatorio.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Todos os Relatórios</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.relatorio.estudantes-em-risco') }}"
                            class="nav-link {{ Route::is('admin.relatorio.estudantes-em-risco') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-times"></i>
                            <p>Estudantes em Risco</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.relatorio.evasao-por-oferta') }}"
                            class="nav-link {{ Route::is('admin.relatorio.evasao-por-oferta') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-exclamation-triangle"></i>
                            <p>Evasão por Oferta</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.relatorio.frequencia-por-disciplina') }}"
                            class="nav-link {{ Route::is('admin.relatorio.frequencia-por-disciplina') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Frequência - Disciplina</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.relatorio.frequencia-por-periodo') }}"
                            class="nav-link {{ Route::is('admin.relatorio.frequencia-por-periodo') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Frequência - Período</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.relatorio.questionarios-respondidos') }}"
                            class="nav-link {{ Route::is('admin.relatorio.questionarios-respondidos') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-check"></i>
                            <p>Questionários</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.relatorio.comparativo-ofertas') }}"
                            class="nav-link {{ Route::is('admin.relatorio.comparativo-ofertas') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-balance-scale"></i>
                            <p>Comparativo Ofertas</p>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        <!-- Meu Perfil (todos) -->
        <li class="nav-item">
            <a href="{{ route('admin.profile.edit') }}"
                class="nav-link {{ Route::is('admin.profile.edit') ? 'active' : '' }}">
                <i class="nav-icon fas fa-id-card"></i>
                <p>Meu Perfil</p>
            </a>
        </li>

        <!-- Sobre (todos) -->
        <li class="nav-item">
            <a href="{{ route('admin.sobre') }}"
                class="nav-link {{ Route::is('admin.sobre') ? 'active' : '' }}">
                <i class="nav-icon fas fa-info-circle"></i>
                <p>Sobre</p>
            </a>
        </li>

    </ul>
</nav>
