<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Province;

class CityController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:city-list|city-create|city-edit|city-delete', ['only' => ['index','show']]);
        $this->middleware('permission:city-create', ['only' => ['create','store']]);
        $this->middleware('permission:city-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:city-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $city = city::all();
        return view('city.index', compact('city'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::all();
        return view('city.create', compact('provinces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        City::create($request->all());
        return redirect()->route('city.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(city $city)
    {
        return view('city.show', compact('city'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(city $city)
    {
        $provinces = Province::all();
        return view('city.edit', compact('city', 'provinces'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, city $city)
    {
        $city->update($request->all());
        return redirect()->route('city.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(city $city)
    {
        $city->delete();
        return redirect()->route('city.index');
    }
}
