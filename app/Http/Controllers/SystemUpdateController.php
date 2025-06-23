<?php

namespace App\Http\Controllers;

use App\Models\SystemUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemUpdateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:Super Admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $updates = SystemUpdate::with('author')
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return view('system-updates.index', compact('updates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('system-updates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'published_at' => 'nullable|date',
            'is_active' => 'boolean'
        ]);

        $update = SystemUpdate::create([
            'title' => $request->title,
            'description' => $request->description,
            'published_at' => $request->published_at ?? now(),
            'is_active' => $request->has('is_active'),
            'author_id' => Auth::id()
        ]);

        return redirect()->route('system-updates.index')
            ->with('success', 'System update created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SystemUpdate  $systemUpdate
     * @return \Illuminate\Http\Response
     */
    public function show(SystemUpdate $systemUpdate)
    {
        return view('system-updates.show', compact('systemUpdate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SystemUpdate  $systemUpdate
     * @return \Illuminate\Http\Response
     */
    public function edit(SystemUpdate $systemUpdate)
    {
        return view('system-updates.edit', compact('systemUpdate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SystemUpdate  $systemUpdate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemUpdate $systemUpdate)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'published_at' => 'nullable|date',
            'is_active' => 'boolean'
        ]);

        $systemUpdate->update([
            'title' => $request->title,
            'description' => $request->description,
            'published_at' => $request->published_at,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('system-updates.index')
            ->with('success', 'System update updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SystemUpdate  $systemUpdate
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemUpdate $systemUpdate)
    {
        $systemUpdate->delete();

        return redirect()->route('system-updates.index')
            ->with('success', 'System update deleted successfully.');
    }
} 