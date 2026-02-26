<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RespostaQuestionarioController;
use App\Http\Controllers\SobreController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/sobre', [SobreController::class, 'guest'])->name('sobre');

// Rota pública para questionários
Route::get('/questionario/{urlPublica}', [RespostaQuestionarioController::class, 'publico'])->name('questionario.publico');
Route::post('/questionario/{urlPublica}/resposta', [RespostaQuestionarioController::class, 'store'])->name('questionario.resposta.store');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
