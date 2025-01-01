<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        $products = Product::all();

        return view('products.products', compact('products', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);

        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'section_id' => 'required|exists:sections,id',
            'description' => 'nullable|string',
        ]);

        Product::create($validated);
        return redirect()->route('products.index')->with('Add', 'تم إضافة المنتج بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {


        $id = Section::where('section_name', $request->section_name)->first()->id;

        $Products = Product::findOrFail($request->pro_id);

        $Products->update([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'section_id' => $id,
        ]);

        session()->flash('Edit', 'تم تعديل المنتج بنجاح');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $Products = Product::findOrFail($request->pro_id);
        $Products->delete();
        session()->flash('delete', 'تم حذف المنتج بنجاح');
        return back();
    }
}
