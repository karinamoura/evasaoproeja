<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    /**
     * Display a institution listing of the resource.
     */
    public function __construct()
    {
        $institution = Institution::orderBy('id','DESC')->get();
        view()->share('institution',$institution);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = School::orderBy('id','DESC')->get();
        return view('admin.school.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.school.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|max:255',
            'institution'=>'required',
        ]);
        $baseSlug = Str::slug($request->name);
        $uniqueSlug = $baseSlug;
        $counter = 1;
        while (School::where('slug', $uniqueSlug)->exists()) {
            $uniqueSlug = $baseSlug . '-' . $counter;
            $counter++;
        }
        School::create([
            'name'=>$request->name,
            'slug'=>$uniqueSlug,
            'institution_id'=>$request->institution
        ]);
        return redirect()->route('admin.escola.index')->with('success','Escola cadastrada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($school)
    {
        $data = School::where('id',decrypt($school))->first();
        return view('admin.school.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        $request->validate([
            'name'=>'required|max:255',
            'institution'=>'required',
        ]);
        $baseSlug = Str::slug($request->name);
        $uniqueSlug = $baseSlug;
        $counter = 1;

        while (School::where('slug', $uniqueSlug)->where('id', '!=', $request->id)->exists()) {
            $uniqueSlug = $baseSlug . '-' . $counter;
            $counter++;
        }

        School::where('id', $request->id)->update([
            'name' => $request->name,
            'slug' => $uniqueSlug,
            'institution_id'=>$request->institution
        ]);
        return redirect()->route('admin.escola.index')->with('info','Escola atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        School::where('id',decrypt($id))->delete();
        return redirect()->route('admin.escola.index')->with('error','School deleted successfully.');
    }
}
