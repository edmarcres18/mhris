<?php

namespace App\Http\Controllers;

use App\Models\RouteManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class RouteManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check()) {
                return redirect()->route('login');
            }
            
            // Check if user has Super Admin role through direct DB query
            $hasRole = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', Auth::id())
                ->where('roles.name', 'Super Admin')
                ->exists();
                
            if (!$hasRole) {
                abort(403, 'Unauthorized. Only Super Admin can access route management.');
            }
            
            return $next($request);
        });
    }

    /**
     * Clear the route cache in a way that's compatible with all cache drivers
     */
    private function clearRouteCache()
    {
        try {
            // Try to use tags if supported
            if (method_exists(Cache::getStore(), 'tags')) {
                Cache::tags('routes')->flush();
            } else {
                // If tags not supported, use a more direct approach
                Cache::forget('routes.all');
                Cache::forget('routes.active');
                
                // Clear Laravel's route cache
                if (app()->routesAreCached()) {
                    $exitCode = Artisan::call('route:clear');
                }
            }
        } catch (\Exception $e) {
            // Log the exception but don't fail
            Log::warning('Failed to clear route cache: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $routes = RouteManagement::select([
                'id',
                'route_name',
                'route_path',
                'method',
                'controller',
                'action',
                'type',
                'is_active',
                'description'
            ]);

            return DataTables::of($routes)
                ->addColumn('checkbox', function($route) {
                    return '<input type="checkbox" class="route-checkbox" value="'.$route->id.'">';
                })
                ->addColumn('controller_action', function($route) {
                    return $route->controller . '@' . $route->action;
                })
                ->addColumn('status', function($route) {
                    $checked = $route->is_active ? 'checked' : '';
                    return '<div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" 
                            onchange="toggleStatus('.$route->id.')"
                            '.$checked.'>
                    </div>';
                })
                ->addColumn('actions', function($route) {
                    return '<button class="btn btn-sm btn-primary" onclick="editRoute('.$route->id.')">
                        Edit
                    </button>';
                })
                ->rawColumns(['checkbox', 'status', 'actions'])
                ->make(true);
        }

        return view('route-management.index');
    }

    public function sync()
    {
        $webRoutes = Route::getRoutes()->getRoutesByName();
        $existingRoutes = RouteManagement::pluck('route_name')->toArray();
        $newRoutes = [];

        foreach ($webRoutes as $name => $route) {
            if (!in_array($name, $existingRoutes)) {
                $controller = '';
                $action = '';

                if ($route->getAction('controller')) {
                    list($controller, $action) = explode('@', $route->getAction('controller'));
                }

                $newRoutes[] = [
                    'route_name' => $name,
                    'route_path' => $route->uri(),
                    'method' => implode('|', $route->methods()),
                    'controller' => $controller,
                    'action' => $action,
                    'middleware' => implode('|', $route->middleware()),
                    'type' => 'web',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($newRoutes)) {
            RouteManagement::insert($newRoutes);
            $this->clearRouteCache();
        }

        return redirect()->route('route-management.index')
            ->with('success', count($newRoutes) . ' new routes have been synchronized.');
    }

    public function toggleStatus(RouteManagement $route)
    {
        $route->update(['is_active' => !$route->is_active]);
        $this->clearRouteCache();

        return response()->json([
            'success' => true,
            'message' => 'Route status updated successfully',
            'is_active' => $route->is_active
        ]);
    }

    public function update(Request $request, RouteManagement $route)
    {
        $validated = $request->validate([
            'description' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean'
        ]);

        $route->update($validated);
        $this->clearRouteCache();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Route updated successfully'
            ]);
        }

        return redirect()->route('route-management.index')
            ->with('success', 'Route updated successfully.');
    }

    /**
     * Get a single route for editing
     */
    public function show(RouteManagement $route)
    {
        return response()->json([
            'success' => true,
            'route' => $route
        ]);
    }

    public function bulkToggle(Request $request)
    {
        $validated = $request->validate([
            'route_ids' => 'required|array',
            'route_ids.*' => 'exists:route_management,id',
            'status' => 'required|boolean'
        ]);

        RouteManagement::whereIn('id', $validated['route_ids'])
            ->update(['is_active' => $validated['status']]);
        
        $this->clearRouteCache();

        return response()->json([
            'success' => true,
            'message' => 'Routes updated successfully'
        ]);
    }
}
