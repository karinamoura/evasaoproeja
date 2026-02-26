<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Institution::orderBy('id','DESC')->get();
        return view('admin.institution.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.institution.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|max:255',
        ]);
        $baseSlug = Str::slug($request->name);
        $uniqueSlug = $baseSlug;
        $counter = 1;
        while (Institution::where('slug', $uniqueSlug)->exists()) {
            $uniqueSlug = $baseSlug . '-' . $counter;
            $counter++;
        }
        Institution::create([
            'name'=>$request->name,
            'slug'=>$uniqueSlug,
        ]);
        return redirect()->route('admin.campi.index')->with('success','Campus cadastrado com sucesso.');
    }

    public function edit($institution)
    {
        $data = Institution::where('id',decrypt($institution))->first();
        return view('admin.institution.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name'=>'required|max:255',
        ]);
        $baseSlug = Str::slug($request->name);
        $uniqueSlug = $baseSlug;
        $counter = 1;

        while (Institution::where('slug', $uniqueSlug)->where('id', '!=', $request->id)->exists()) {
            $uniqueSlug = $baseSlug . '-' . $counter;
            $counter++;
        }

        Institution::where('id', $request->id)->update([
            'name' => $request->name,
            'slug' => $uniqueSlug,
        ]);
        return redirect()->route('admin.campi.index')->with('info','Campus atualizado com sucesso.');
    }

    public function destroy($id)
    {
        Institution::where('id',decrypt($id))->delete();
        return redirect()->route('admin.campi.index')->with('error','Campus removido com sucesso.');
    }
}
