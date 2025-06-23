<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;

class ProvinceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:province-list|province-create|province-edit|province-delete', ['only' => ['index','show']]);
        $this->middleware('permission:province-create', ['only' => ['create','store']]);
        $this->middleware('permission:province-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:province-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $provinces = Province::all();
        return view('provinces.index', compact('provinces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('provinces.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Province::create($request->all());
        return redirect()->route('provinces.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Province $province)
    {
        return view('provinces.show', compact('province'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Province $province)
    {
        return view('provinces.edit', compact('province'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Province $province)
    {
        $province->update($request->all());
        return redirect()->route('provinces.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Province $province)
    {
        $province->delete();
        return redirect()->route('provinces.index');
    }
}
