<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use App\Models\Oferta;
use App\Models\User;
use App\Models\Estudante;
use Illuminate\Http\Request;

class DisciplinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $disciplinas = Disciplina::with(['professor', 'oferta'])->orderBy('id', 'DESC')->get();
        return view('admin.disciplinas.index', compact('disciplinas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ofertas = Oferta::all();
        $professores = User::role('professor')->get();
        return view('admin.disciplinas.create', compact('ofertas', 'professores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|max:255',
            'professor_id' => 'required|exists:users,id',
            'oferta_id' => 'required|exists:ofertas,id',
            'periodo' => 'required|max:20',
            'carga_horaria_total' => 'required|integer|min:1',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
        ]);

        Disciplina::create($request->all());

        return redirect()->route('admin.disciplina.index')->with('success', 'Disciplina criada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $disciplina = Disciplina::with(['professor', 'oferta', 'estudantes'])->findOrFail(decrypt($id));
        $estudantesOferta = Estudante::where('oferta_id', $disciplina->oferta_id)->get();
        $estudantesMatriculados = $disciplina->estudantes->pluck('id')->toArray();
        return view('admin.disciplinas.show', compact('disciplina', 'estudantesOferta', 'estudantesMatriculados'));
    }

    /**
     * Attach students to discipline.
     */
    public function attachEstudantes(Request $request, string $id)
    {
        $disciplina = Disciplina::findOrFail(decrypt($id));

        $request->validate([
            'estudantes' => 'required|array',
            'estudantes.*' => 'exists:estudantes,id',
        ]);

        $disciplina->estudantes()->sync($request->estudantes);

        return redirect()->route('admin.disciplina.show', encrypt($disciplina->id))
            ->with('success', 'Estudantes vinculados à disciplina com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $disciplina = Disciplina::findOrFail(decrypt($id));
        $ofertas = Oferta::all();
        $professores = User::role('professor')->get();
        return view('admin.disciplinas.edit', compact('disciplina', 'ofertas', 'professores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nome' => 'required|max:255',
            'professor_id' => 'required|exists:users,id',
            'oferta_id' => 'required|exists:ofertas,id',
            'periodo' => 'required|max:20',
            'carga_horaria_total' => 'required|integer|min:1',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
        ]);

        $disciplina = Disciplina::findOrFail(decrypt($id));
        $disciplina->update($request->all());

        return redirect()->route('admin.disciplina.index')->with('success', 'Disciplina atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $disciplina = Disciplina::findOrFail(decrypt($id));
        $disciplina->delete();

        return redirect()->route('admin.disciplina.index')->with('success', 'Disciplina removida com sucesso.');
    }
}
