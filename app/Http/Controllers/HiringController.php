<?php

namespace App\Http\Controllers;

use App\Models\Hiring;
use App\Models\Department;
use Illuminate\Http\Request;

class HiringController extends Controller
{

                /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware(['permission:hiring-list|hiring-create|hiring-edit|hiring-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:hiring-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:hiring-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:hiring-delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hirings = Hiring::all();
        return view('hirings.index', compact('hirings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        return view('hirings.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'position' => 'required|max:255',
            'description' => 'required',
            'requirements' => 'required',
            'responsibilities' => 'required',
            'benefits' => 'required',
            'location' => 'required|max:255',
            'department_id' => 'required|exists:departments,id',
            'employment_type' => 'required|max:255',
        ]);

        Hiring::create($validated);

        return redirect()->route('hirings.index')->with('success', 'Hiring created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Hiring $hiring)
    {
        return view('hirings.show', compact('hiring'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hiring $hiring)
    {
        $departments = Department::all();
        return view('hirings.edit', compact('hiring', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hiring $hiring)
    {
        $validated = $request->validate([
            'position' => 'required|max:255',
            'description' => 'required',
            'requirements' => 'required',
            'responsibilities' => 'required',
            'benefits' => 'required',
            'location' => 'required|max:255',
            'department_id' => 'required|exists:departments,id',
            'employment_type' => 'required|max:255',
        ]);

        $hiring->update($validated);

        return redirect()->route('hirings.index')->with('success', 'Hiring updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hiring $hiring)
    {
        $hiring->delete();

        return redirect()->route('hirings.index')->with('success', 'Hiring deleted successfully.');
    }
}
