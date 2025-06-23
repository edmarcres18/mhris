<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gender;

class GenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware(['permission:gender-list|gender-create|gender-edit|gender-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:gender-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:gender-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:gender-delete'], ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $genders = Gender::latest()->paginate(10);
        return view('genders.index', compact('genders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('genders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
        ]);

        Gender::create($request->all());

        return redirect()->route('genders.index')
            ->with('success', 'Gender created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function edit(Gender $gender)
    {
        return view('genders.edit', compact('gender'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\\Models\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gender $gender)
    {
        request()->validate([
            'name' => 'required',
        ]);

        $gender->update($request->all());

        return redirect()->route('genders.index')
            ->with('success', 'Gender updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gender $gender)
    {
        $gender->delete();

        return redirect()->route('genders.index')
            ->with('success', 'Gender deleted successfully');
    }
}
