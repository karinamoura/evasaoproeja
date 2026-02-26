<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\Oferta;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OfertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $institution = Institution::get();
        view()->share('institution', $institution);

        $users = User::get();
        view()->share('users', $users);

        $data = Oferta::orderBy('id', 'DESC')->get();
        view()->share('data', $data);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.ofertas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = School::all();
        return view('admin.ofertas.create', compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category' => 'required',
            'school' => 'nullable',
            'turno' => 'required|in:Matutino,Vespertino,Noturno',
            'coordenador' => 'required|exists:users,id',
            'codigo_sistema_academico' => 'required|max:50',
            'turma' => 'required|max:10',
            'nome_curso' => 'required|max:255',
            'ano_letivo' => 'required|max:10',
            'periodo_letivo' => 'required|max:50',
            'responsavel_transporte_estudante' => 'nullable|in:Instituição,Escola,Não há disponibilização de transporte',
            'oferta_auxilio_financeiro' => 'nullable|in:Sim,Não',
        ]);

        // Generate unique slug
        $baseSlug = Str::slug($request->name);
        $uniqueSlug = $baseSlug;
        $counter = 1;
        while (Oferta::where('slug', $uniqueSlug)->exists()) {
            $uniqueSlug = $baseSlug . '-' . $counter;
            $counter++;
        }

        // Create new oferta instance
        $oferta = new Oferta();
        $oferta->name = $request->name;
        $oferta->institution_id = $request->category;
        $oferta->school_id = $request->school;
        $oferta->turno = $request->turno;
        $oferta->coordenador_id = $request->coordenador;
        $oferta->codigo_sistema_academico = $request->codigo_sistema_academico;
        $oferta->turma = $request->turma;
        $oferta->nome_curso = $request->nome_curso;
        $oferta->ano_letivo = $request->ano_letivo;
        $oferta->periodo_letivo = $request->periodo_letivo;
        $oferta->responsavel_transporte_estudante = $request->responsavel_transporte_estudante;
        $oferta->oferta_auxilio_financeiro = $request->oferta_auxilio_financeiro;
        $oferta->slug = $uniqueSlug;
        $oferta->save();

        return redirect()->route('admin.oferta.index')->with('success', 'Oferta criada com sucesso.');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = Oferta::where('id', decrypt($id))->first();
        $schools = School::all();
        return view('admin.ofertas.edit', compact('data', 'schools'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category' => 'required',
            'school' => 'nullable',
            'turno' => 'required|in:Matutino,Vespertino,Noturno',
            'coordenador' => 'required|exists:users,id',
            'codigo_sistema_academico' => 'required|max:50',
            'turma' => 'required|max:10',
            'nome_curso' => 'required|max:255',
            'ano_letivo' => 'required|max:10',
            'periodo_letivo' => 'required|max:50',
            'responsavel_transporte_estudante' => 'nullable|in:Instituição,Escola,Não há disponibilização de transporte',
            'oferta_auxilio_financeiro' => 'nullable|in:Sim,Não',
        ]);
        $baseSlug = Str::slug($request->name);
        $uniqueSlug = $baseSlug;
        $counter = 1;
        while (Oferta::where('slug', $uniqueSlug)->where('id', '!=', $request->id)->exists()) {
            $uniqueSlug = $baseSlug . '-' . $counter;
            $counter++;
        }
        $oferta = Oferta::find($request->id);
        $oferta->name = $request->name;
        $oferta->institution_id = $request->category;
        $oferta->school_id = $request->school;
        $oferta->turno = $request->turno;
        $oferta->coordenador_id = $request->coordenador;
        $oferta->codigo_sistema_academico = $request->codigo_sistema_academico;
        $oferta->turma = $request->turma;
        $oferta->nome_curso = $request->nome_curso;
        $oferta->ano_letivo = $request->ano_letivo;
        $oferta->periodo_letivo = $request->periodo_letivo;
        $oferta->responsavel_transporte_estudante = $request->responsavel_transporte_estudante;
        $oferta->oferta_auxilio_financeiro = $request->oferta_auxilio_financeiro;
        $oferta->slug = $uniqueSlug;
        $oferta->save();

        return redirect()->route('admin.oferta.index')->with('success', 'Oferta atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $oferta = Oferta::where('id', decrypt($id))->first();
        if ($oferta) {
            $oferta->delete();
        }
        return redirect()->route('admin.oferta.index')->with('success', 'Oferta removida com sucesso.');
    }
}
