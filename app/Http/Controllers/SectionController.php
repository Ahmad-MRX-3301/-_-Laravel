<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        return view('sections.sections', compact('sections'));
    }
    public function create(Request $request)
    {
        /** **/
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'section_name' => 'required|string|max:30',
            'description' => 'nullable|string|max:1000',
        ], [

            'section_name.required' => 'يرجي ادخال اسم القسم',
            'section_name.unique' => 'اسم القسم مسجل مسبقا',
            'description.required' => 'يرجي ادخال البيان',
        ]);


        $input = $request->all();

        $exist = Section::where("section_name", "=", $input['section_name'])->exists();

        if ($exist) {
            session()->flash('Error', 'اسم القسم مسجل مسبقا');
            return redirect()->back();
        }

        Section::create([
            'section_name' => $input['section_name'],
            'description' => $input['description'],
            'created_by' => Auth::user()->name,
        ]);

        session()->flash('Add', 'تم اضافة القسم بنجاح');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section) {}


    public function edit($id)
    {
        $section = Section::findOrFail($id);
        return response()->json([
            'section_name' => $section->section_name,
            'description' => $section->description,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request)
    {
        $id = $request->id;

        $request->validate([

            'section_name' => 'required|max:255|unique:sections,section_name,' . $id,
            'description' => 'required',
        ], [

            'section_name.required' => 'يرجي ادخال اسم القسم',
            'section_name.unique' => 'اسم القسم مسجل مسبقا',
            'description.required' => 'يرجي ادخال البيان',

        ]);

        $sections = Section::find($id);
        $sections->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
        ]);

        session()->flash('edit', 'تم تعديل القسم بنجاج');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        Section::find($id)->delete();
        session()->flash('delete', 'تم حذف القسم بنجاح');
        return redirect()->back();
    }
}
