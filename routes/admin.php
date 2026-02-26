<?php

use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\QuestionarioController;
use App\Http\Controllers\QuestionarioOfertaController;
use App\Http\Controllers\RespostaQuestionarioController;
use App\Http\Controllers\Admin\TermoCondicaoController;
use App\Http\Controllers\DisciplinaController;
use App\Http\Controllers\EstudanteController;
use App\Http\Controllers\FrequenciaController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\SobreController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/sobre', [SobreController::class, 'admin'])->name('sobre');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ========== Usuários e Acesso (usuarios, perfis, permissoes) ==========
    Route::middleware(['permission:usuarios.view|usuarios.create|usuarios.edit|usuarios.delete'])->group(function () {
        Route::get('user', [UserController::class, 'index'])->name('user.index')->middleware('permission:usuarios.view');
        Route::get('user/create', [UserController::class, 'create'])->name('user.create')->middleware('permission:usuarios.create');
        Route::post('user', [UserController::class, 'store'])->name('user.store')->middleware('permission:usuarios.create');
        Route::get('user/{user}', [UserController::class, 'show'])->name('user.show')->middleware('permission:usuarios.view');
        Route::get('user/{id}/edit', [UserController::class, 'edit'])->name('user.edit')->middleware('permission:usuarios.edit');
        Route::put('user/{user}', [UserController::class, 'update'])->name('user.update')->middleware('permission:usuarios.edit');
        Route::delete('user/{id}', [UserController::class, 'destroy'])->name('user.destroy')->middleware('permission:usuarios.delete');
    });
    Route::middleware(['permission:perfis.view|perfis.create|perfis.edit|perfis.delete'])->group(function () {
        Route::get('role', [RoleController::class, 'index'])->name('role.index')->middleware('permission:perfis.view');
        Route::get('role/create', [RoleController::class, 'create'])->name('role.create')->middleware('permission:perfis.create');
        Route::post('role', [RoleController::class, 'store'])->name('role.store')->middleware('permission:perfis.create');
        Route::get('role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit')->middleware('permission:perfis.edit');
        Route::put('role/{id}', [RoleController::class, 'store'])->name('role.update')->middleware('permission:perfis.edit');
        Route::delete('role/{id}', [RoleController::class, 'destroy'])->name('role.destroy')->middleware('permission:perfis.delete');
    });
    Route::middleware(['permission:permissoes.view|permissoes.create|permissoes.edit|permissoes.delete'])->group(function () {
        Route::get('permission', [PermissionController::class, 'index'])->name('permission.index')->middleware('permission:permissoes.view');
        Route::get('permission/create', [PermissionController::class, 'create'])->name('permission.create')->middleware('permission:permissoes.create');
        Route::post('permission', [PermissionController::class, 'store'])->name('permission.store')->middleware('permission:permissoes.create');
        Route::get('permission/{id}/edit', [PermissionController::class, 'edit'])->name('permission.edit')->middleware('permission:permissoes.edit');
        Route::put('permission/{id}', [PermissionController::class, 'store'])->name('permission.update')->middleware('permission:permissoes.edit');
        Route::delete('permission/{id}', [PermissionController::class, 'destroy'])->name('permission.destroy')->middleware('permission:permissoes.delete');
    });

    // ========== Cadastros Básicos (instituicoes, escolas, ofertas) ==========
    Route::middleware(['permission:instituicoes.view|instituicoes.create|instituicoes.edit|instituicoes.delete'])->group(function () {
        Route::get('campi', [InstitutionController::class, 'index'])->name('campi.index')->middleware('permission:instituicoes.view');
        Route::get('campi/create', [InstitutionController::class, 'create'])->name('campi.create')->middleware('permission:instituicoes.create');
        Route::post('campi', [InstitutionController::class, 'store'])->name('campi.store')->middleware('permission:instituicoes.create');
        Route::get('campi/{campi}', [InstitutionController::class, 'show'])->name('campi.show')->middleware('permission:instituicoes.view');
        Route::get('campi/{campi}/edit', [InstitutionController::class, 'edit'])->name('campi.edit')->middleware('permission:instituicoes.edit');
        Route::put('campi/{campi}', [InstitutionController::class, 'update'])->name('campi.update')->middleware('permission:instituicoes.edit');
        Route::delete('campi/{campi}', [InstitutionController::class, 'destroy'])->name('campi.destroy')->middleware('permission:instituicoes.delete');
    });
    Route::middleware(['permission:escolas.view|escolas.create|escolas.edit|escolas.delete'])->group(function () {
        Route::get('escola', [SchoolController::class, 'index'])->name('escola.index')->middleware('permission:escolas.view');
        Route::get('escola/create', [SchoolController::class, 'create'])->name('escola.create')->middleware('permission:escolas.create');
        Route::post('escola', [SchoolController::class, 'store'])->name('escola.store')->middleware('permission:escolas.create');
        Route::get('escola/{escola}', [SchoolController::class, 'show'])->name('escola.show')->middleware('permission:escolas.view');
        Route::get('escola/{escola}/edit', [SchoolController::class, 'edit'])->name('escola.edit')->middleware('permission:escolas.edit');
        Route::put('escola/{escola}', [SchoolController::class, 'update'])->name('escola.update')->middleware('permission:escolas.edit');
        Route::delete('escola/{escola}', [SchoolController::class, 'destroy'])->name('escola.destroy')->middleware('permission:escolas.delete');
    });
    Route::middleware(['permission:ofertas.view|ofertas.create|ofertas.edit|ofertas.delete'])->group(function () {
        Route::get('oferta', [OfertaController::class, 'index'])->name('oferta.index')->middleware('permission:ofertas.view');
        Route::get('oferta/create', [OfertaController::class, 'create'])->name('oferta.create')->middleware('permission:ofertas.create');
        Route::post('oferta', [OfertaController::class, 'store'])->name('oferta.store')->middleware('permission:ofertas.create');
        Route::get('oferta/{id}', [OfertaController::class, 'show'])->name('oferta.show')->middleware('permission:ofertas.view');
        Route::get('oferta/{id}/edit', [OfertaController::class, 'edit'])->name('oferta.edit')->middleware('permission:ofertas.edit');
        Route::put('oferta/{id}', [OfertaController::class, 'update'])->name('oferta.update')->middleware('permission:ofertas.edit');
        Route::delete('oferta/{id}', [OfertaController::class, 'destroy'])->name('oferta.destroy')->middleware('permission:ofertas.delete');
        Route::get('remove-external-img/{id}', [OfertaController::class, 'removeImage'])->name('remove.image');
    });

    // ========== Questionários ==========
    Route::middleware(['permission:questionarios.view|questionarios.create|questionarios.edit|questionarios.delete'])->group(function () {
        Route::get('questionario', [QuestionarioController::class, 'index'])->name('questionario.index')->middleware('permission:questionarios.view');
        Route::get('questionario/create', [QuestionarioController::class, 'create'])->name('questionario.create')->middleware('permission:questionarios.create');
        Route::post('questionario', [QuestionarioController::class, 'store'])->name('questionario.store')->middleware('permission:questionarios.create');
        Route::get('questionario/{questionario}', [QuestionarioController::class, 'show'])->name('questionario.show')->middleware('permission:questionarios.view');
        Route::get('questionario/{questionario}/edit', [QuestionarioController::class, 'edit'])->name('questionario.edit')->middleware('permission:questionarios.edit');
        Route::put('questionario/{questionario}', [QuestionarioController::class, 'update'])->name('questionario.update')->middleware('permission:questionarios.edit');
        Route::delete('questionario/{questionario}', [QuestionarioController::class, 'destroy'])->name('questionario.destroy')->middleware('permission:questionarios.delete');
        Route::patch('questionario/{id}/toggle-status', [QuestionarioController::class, 'toggleStatus'])->name('questionario.toggle-status')->middleware('permission:questionarios.edit');
        Route::get('questionario/{id}/perguntas', [QuestionarioController::class, 'getPerguntas'])->name('questionario.perguntas')->middleware('permission:questionarios.view');
        Route::get('questionario/{id}/secoes', [QuestionarioController::class, 'getSecoes'])->name('questionario.secoes')->middleware('permission:questionarios.view');
    });
    Route::middleware(['permission:questionario-oferta.view|questionario-oferta.create|questionario-oferta.edit|questionario-oferta.delete|questionario-oferta.respostas|questionario-oferta.export'])->group(function () {
        Route::get('questionario-oferta', [QuestionarioOfertaController::class, 'index'])->name('questionario-oferta.index')->middleware('permission:questionario-oferta.view');
        Route::get('questionario-oferta/create', [QuestionarioOfertaController::class, 'create'])->name('questionario-oferta.create')->middleware('permission:questionario-oferta.create');
        Route::post('questionario-oferta', [QuestionarioOfertaController::class, 'store'])->name('questionario-oferta.store')->middleware('permission:questionario-oferta.create');
        Route::get('questionario-oferta/{questionario_oferta}', [QuestionarioOfertaController::class, 'show'])->name('questionario-oferta.show')->middleware('permission:questionario-oferta.view');
        Route::get('questionario-oferta/{questionario_oferta}/edit', [QuestionarioOfertaController::class, 'edit'])->name('questionario-oferta.edit')->middleware('permission:questionario-oferta.edit');
        Route::put('questionario-oferta/{questionario_oferta}', [QuestionarioOfertaController::class, 'update'])->name('questionario-oferta.update')->middleware('permission:questionario-oferta.edit');
        Route::delete('questionario-oferta/{questionario_oferta}', [QuestionarioOfertaController::class, 'destroy'])->name('questionario-oferta.destroy')->middleware('permission:questionario-oferta.delete');
        Route::patch('questionario-oferta/{id}/toggle-status', [QuestionarioOfertaController::class, 'toggleStatus'])->name('questionario-oferta.toggle-status')->middleware('permission:questionario-oferta.edit');
        Route::get('questionario-oferta/{questionarioOfertaId}/respostas', [RespostaQuestionarioController::class, 'index'])->name('questionario-oferta.respostas')->middleware('permission:questionario-oferta.respostas');
        Route::get('questionario-oferta/{questionarioOfertaId}/respostas/{respostaId}', [RespostaQuestionarioController::class, 'show'])->name('questionario-oferta.resposta-detalhe')->middleware('permission:questionario-oferta.respostas');
        Route::get('questionario-oferta/{questionarioOfertaId}/export-csv', [RespostaQuestionarioController::class, 'exportCsv'])->name('questionario-oferta.export-csv')->middleware('permission:questionario-oferta.export');
    });
    Route::middleware(['permission:termo-condicao.view|termo-condicao.create|termo-condicao.edit|termo-condicao.delete'])->group(function () {
        Route::get('termo-condicao', [TermoCondicaoController::class, 'index'])->name('termo-condicao.index')->middleware('permission:termo-condicao.view');
        Route::get('termo-condicao/create', [TermoCondicaoController::class, 'create'])->name('termo-condicao.create')->middleware('permission:termo-condicao.create');
        Route::post('termo-condicao', [TermoCondicaoController::class, 'store'])->name('termo-condicao.store')->middleware('permission:termo-condicao.create');
        Route::get('termo-condicao/{termo_condicao}', [TermoCondicaoController::class, 'show'])->name('termo-condicao.show')->middleware('permission:termo-condicao.view');
        Route::get('termo-condicao/{termo_condicao}/edit', [TermoCondicaoController::class, 'edit'])->name('termo-condicao.edit')->middleware('permission:termo-condicao.edit');
        Route::put('termo-condicao/{termo_condicao}', [TermoCondicaoController::class, 'update'])->name('termo-condicao.update')->middleware('permission:termo-condicao.edit');
        Route::delete('termo-condicao/{termo_condicao}', [TermoCondicaoController::class, 'destroy'])->name('termo-condicao.destroy')->middleware('permission:termo-condicao.delete');
    });

    // ========== Disciplinas e Estudantes ==========
    Route::middleware(['permission:disciplinas.view|disciplinas.create|disciplinas.edit|disciplinas.delete|disciplinas.attach-estudantes'])->group(function () {
        Route::get('disciplina', [DisciplinaController::class, 'index'])->name('disciplina.index')->middleware('permission:disciplinas.view');
        Route::get('disciplina/create', [DisciplinaController::class, 'create'])->name('disciplina.create')->middleware('permission:disciplinas.create');
        Route::post('disciplina', [DisciplinaController::class, 'store'])->name('disciplina.store')->middleware('permission:disciplinas.create');
        Route::get('disciplina/{disciplina}', [DisciplinaController::class, 'show'])->name('disciplina.show')->middleware('permission:disciplinas.view');
        Route::get('disciplina/{disciplina}/edit', [DisciplinaController::class, 'edit'])->name('disciplina.edit')->middleware('permission:disciplinas.edit');
        Route::put('disciplina/{disciplina}', [DisciplinaController::class, 'update'])->name('disciplina.update')->middleware('permission:disciplinas.edit');
        Route::delete('disciplina/{disciplina}', [DisciplinaController::class, 'destroy'])->name('disciplina.destroy')->middleware('permission:disciplinas.delete');
        Route::post('disciplina/{id}/attach-estudantes', [DisciplinaController::class, 'attachEstudantes'])->name('disciplina.attach-estudantes')->middleware('permission:disciplinas.attach-estudantes');
    });
    Route::middleware(['permission:estudantes.view|estudantes.create|estudantes.edit|estudantes.delete|estudantes.upload'])->group(function () {
        Route::get('estudante', [EstudanteController::class, 'index'])->name('estudante.index')->middleware('permission:estudantes.view');
        Route::get('estudante/create', [EstudanteController::class, 'create'])->name('estudante.create')->middleware('permission:estudantes.create');
        Route::post('estudante', [EstudanteController::class, 'store'])->name('estudante.store')->middleware('permission:estudantes.create');
        Route::get('estudante/upload', [EstudanteController::class, 'uploadForm'])->name('estudante.upload')->middleware('permission:estudantes.upload');
        Route::post('estudante/process-upload', [EstudanteController::class, 'processUpload'])->name('estudante.process-upload')->middleware('permission:estudantes.upload');
        Route::get('estudante/import-resultados/{token}', [EstudanteController::class, 'importResultados'])->name('estudante.import-resultados')->middleware('permission:estudantes.upload');
        Route::get('estudante/disciplinas/{ofertaId}', [EstudanteController::class, 'getDisciplinasByOferta'])->name('estudante.disciplinas')->middleware('permission:estudantes.view');
        Route::get('estudante/{estudante}', [EstudanteController::class, 'show'])->name('estudante.show')->middleware('permission:estudantes.view');
        Route::get('estudante/{id}/edit', [EstudanteController::class, 'edit'])->name('estudante.edit')->middleware('permission:estudantes.edit');
        Route::put('estudante/{estudante}', [EstudanteController::class, 'update'])->name('estudante.update')->middleware('permission:estudantes.edit');
        Route::delete('estudante/{id}', [EstudanteController::class, 'destroy'])->name('estudante.destroy')->middleware('permission:estudantes.delete');
    });

    // ========== Frequências ==========
    Route::middleware(['permission:frequencias.view|frequencias.create|frequencias.edit|frequencias.delete'])->group(function () {
        Route::get('frequencia', [FrequenciaController::class, 'index'])->name('frequencia.index')->middleware('permission:frequencias.view');
        Route::get('frequencia/create/{disciplinaId}', [FrequenciaController::class, 'create'])->name('frequencia.create')->middleware('permission:frequencias.create');
        Route::post('frequencia', [FrequenciaController::class, 'store'])->name('frequencia.store')->middleware('permission:frequencias.create');
        Route::get('frequencia/{disciplinaId}/estudante/{estudanteId}/historico', [FrequenciaController::class, 'estudanteHistorico'])->name('frequencia.estudante-historico')->middleware('permission:frequencias.view');
        Route::get('frequencia/{id}', [FrequenciaController::class, 'show'])->name('frequencia.show')->middleware('permission:frequencias.view');
        Route::get('frequencia/{id}/edit', [FrequenciaController::class, 'edit'])->name('frequencia.edit')->middleware('permission:frequencias.edit');
        Route::put('frequencia/{id}', [FrequenciaController::class, 'update'])->name('frequencia.update')->middleware('permission:frequencias.edit');
        Route::delete('frequencia/{id}', [FrequenciaController::class, 'destroy'])->name('frequencia.destroy')->middleware('permission:frequencias.delete');
    });

    // ========== Relatórios ==========
    Route::middleware(['permission:relatorios.view'])->group(function () {
        Route::get('relatorio', [RelatorioController::class, 'index'])->name('relatorio.index');
        Route::get('relatorio/evasao-por-oferta', [RelatorioController::class, 'evasaoPorOferta'])->name('relatorio.evasao-por-oferta');
        Route::get('relatorio/frequencia-por-disciplina', [RelatorioController::class, 'frequenciaPorDisciplina'])->name('relatorio.frequencia-por-disciplina');
        Route::get('relatorio/estudantes-em-risco', [RelatorioController::class, 'estudantesEmRisco'])->name('relatorio.estudantes-em-risco');
        Route::get('relatorio/frequencia-por-periodo', [RelatorioController::class, 'frequenciaPorPeriodo'])->name('relatorio.frequencia-por-periodo');
        Route::get('relatorio/questionarios-respondidos', [RelatorioController::class, 'questionariosRespondidos'])->name('relatorio.questionarios-respondidos');
        Route::get('relatorio/comparativo-ofertas', [RelatorioController::class, 'comparativoOfertas'])->name('relatorio.comparativo-ofertas');
    });
});
