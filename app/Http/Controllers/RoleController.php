<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use DB;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','show']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
{
    // Get all roles
    $roles = Role::all();

    // Filter out the "Super Admin" role
    $roles = $roles->reject(function ($role) {
        return $role->name === 'Super Admin';
    });

    return view('roles.index', compact('roles'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('roles.create', [
            'permissions' => Permission::get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::create(['name' => $request->name]);

        $permissions = Permission::whereIn('id', $request->permissions)->get(['name'])->toArray();
        
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')
                ->withSuccess('New role is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): View
    {
        $rolePermissions = Permission::join("role_has_permissions","permission_id","=","id")
            ->where("role_id",$role->id)
            ->select('name')
            ->get();
        return view('roles.show', [
            'role' => $role,
            'rolePermissions' => $rolePermissions
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): View
    {
        $rolePermissions = DB::table("role_has_permissions")->where("role_id",$role->id)
            ->pluck('permission_id')
            ->all();

        return view('roles.edit', [
            'role' => $role,
            'permissions' => Permission::get(),
            'rolePermissions' => $rolePermissions
        ]);
    }

    /**
 * Update the specified resource in storage.
 */
public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
{
    $input = $request->only('name');
    $role->update($input);

    // Get permission IDs
    $permissions = $request->permissions ?? [];
    $permissions = Permission::whereIn('id', $permissions)->pluck('id');

    // Sync permissions
    $role->syncPermissions($permissions);    

    return redirect()->route('roles.index')->withSuccess('Role is updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        // if($role->name=='Super Admin'){
        //     abort(403, 'SUPER ADMIN ROLE CAN NOT BE DELETED');
        // }
        // if(auth()->user()->hasRole($role->name)){
        //     abort(403, 'CAN NOT DELETE SELF ASSIGNED ROLE');
        // }
        $role->delete();
        return redirect()->route('roles.index')
                ->withSuccess('Role is deleted successfully.');
    }
}