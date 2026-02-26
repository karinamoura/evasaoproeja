<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SobreController extends Controller
{
    /**
     * Página "Sobre" para visitantes (não autenticados).
     */
    public function guest(): View
    {
        return view('sobre');
    }

    /**
     * Página "Sobre" no painel administrativo (autenticados).
     */
    public function admin(): View
    {
        return view('admin.sobre');
    }
}
