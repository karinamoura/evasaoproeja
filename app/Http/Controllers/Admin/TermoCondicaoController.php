<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TermoCondicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TermoCondicaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $termos = TermoCondicao::orderBy('id', 'DESC')->get();
        return view('admin.termo-condicao.index', compact('termos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.termo-condicao.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Log para debug
            Log::info('=== INÍCIO STORE TERMO ===', [
                'request_all' => $request->all(),
                'has_titulo' => $request->has('titulo'),
                'titulo' => $request->input('titulo'),
                'has_conteudo' => $request->has('conteudo'),
                'conteudo_length' => strlen($request->input('conteudo', ''))
            ]);

            $validated = $request->validate([
                'titulo' => 'required|max:255',
                'conteudo' => 'required',
                'ativo' => 'sometimes|accepted'
            ]);

            Log::info('Validação passou', ['validated' => $validated]);

            $termo = TermoCondicao::create([
                'titulo' => $validated['titulo'],
                'conteudo' => $validated['conteudo'],
                'ativo' => $request->filled('ativo')
            ]);

            Log::info('Termo criado', ['termo' => $termo->toArray()]);
            Log::info('=== FIM STORE TERMO ===');

            return redirect()->route('admin.termo-condicao.index')
                            ->with('success', 'Termo e condições criado com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erro de validação', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Erro ao criar termo', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()->with('error', 'Erro ao criar termo e condições: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $termo = TermoCondicao::findOrFail($id);
        return view('admin.termo-condicao.show', compact('termo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $termo = TermoCondicao::findOrFail($id);
        return view('admin.termo-condicao.edit', compact('termo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'titulo' => 'required|max:255',
                'conteudo' => 'required|string',
                'ativo' => 'sometimes|accepted'
            ]);

            $termo = TermoCondicao::findOrFail($id);
            $termo->update([
                'titulo' => $validated['titulo'],
                'conteudo' => $validated['conteudo'],
                'ativo' => $request->filled('ativo')
            ]);

            return redirect()->route('admin.termo-condicao.index')
                            ->with('success', 'Termo e condições atualizado com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao atualizar termo e condições: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $termo = TermoCondicao::findOrFail($id);

        // Verificar se há questionários usando este termo
        if ($termo->questionarioOfertas()->count() > 0) {
            return redirect()->route('admin.termo-condicao.index')
                            ->with('error', 'Não é possível excluir este termo pois ele está sendo usado por um ou mais questionários ofertas.');
        }

        $termo->delete();

        return redirect()->route('admin.termo-condicao.index')
                        ->with('success', 'Termo e condições removido com sucesso!');
    }
}
