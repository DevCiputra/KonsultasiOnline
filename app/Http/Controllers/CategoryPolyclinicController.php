<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CategoryPolyclinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryPolyclinicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filterKeyword = $request->get('keyword');
        $query = CategoryPolyclinic::query();

        if($filterKeyword) {
            $query->where('category_polyclinic', 'like', '%' . $filterKeyword . '%');
        }

        $categoryPoly['categoryPoly'] = $query->paginate(10);
        return view('categoryPoly.index', $categoryPoly);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categoryPoly.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_polyclinic' => 'required|string|max:255'
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cek = CategoryPolyclinic::where('category_polyclinic', $request->category_polyclinic);

        if($cek->first()) {
            return redirect()->back()->with('failed', 'Kategori Jasa Ini Sudah pernah anda buat');
        }

        $input['category_polyclinic'] = strtoupper($request->category_polyclinic);
        CategoryPolyclinic::create($input);
        return redirect()->route('categoryPoly.index')->with('status', 'Category Polyclinic Berhasil dibuat');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categoryPoly['categoryPoly'] = CategoryPolyclinic::findOrFail($id);
        return view('categoryPoly.edit', $categoryPoly);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $categoryPoly = CategoryPolyclinic::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'category_polyclinic' => 'sometimes|string|max:255',

        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $input = $request->all();

        $categoryPoly->update($input);
        return redirect()->route('categoryPoly.index')->with('status', 'Category Polyclinic Berhasil di update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categoryPoly = CategoryPolyclinic::findOrFail($id);
        $categoryPoly->delete();
        return redirect()->back()->with('status', 'Category Polyclinic Berhasil didelete');
    }
}
