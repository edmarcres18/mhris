<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barangay;
use App\Models\City;

class BarangayController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:barangay-list|barangay-create|barangay-edit|barangay-delete', ['only' => ['index','show']]);
        $this->middleware('permission:barangay-create', ['only' => ['create','store']]);
        $this->middleware('permission:barangay-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:barangay-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangay = barangay::all();
        return view('barangay.index', compact('barangay'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $city = City::all();
        return view('barangay.create', compact('city'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        barangay::create($request->all());
        return redirect()->route('barangay.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(barangay $barangay)
    {
        return view('barangay.show', compact('barangay'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(barangay $barangay)
    {
        $city = City::all();
        return view('barangay.edit', compact('barangay', 'city'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, barangay $barangay)
    {
        $barangay->update($request->all());
        return redirect()->route('barangay.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(barangay $barangay)
    {
        $barangay->delete();
        return redirect()->route('barangay.index');
    }
}
