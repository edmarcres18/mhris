<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Notifications\NewTaskAssigned;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('employee')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $employees = Employee::where('employee_status', 'Active')->get();
        return view('tasks.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'employee_id' => 'required|exists:employees,id',
        ]);

        $task = Task::create($validatedData);

        $employee = Employee::find($request->employee_id);
        Notification::send($employee, new NewTaskAssigned($task));

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        $this->markAsRead($task);
        return view('tasks.show', compact('task'));
    }

    private function markAsRead(Task $task)
    {
        if (!$task->is_read) {
            $task->is_read = true;
            $task->read_at = now();
            $task->save();
        }
    }

    public function edit(Task $task)
    {
        $employees = Employee::where('employee_status', 'Active')->get();
        return view('tasks.edit', compact('task', 'employees'));
    }

    public function update(Request $request, Task $task)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'title' => 'nullable|max:255',
        'description' => 'nullable',
        'employee_id' => 'nullable|exists:employees,id',
        'status' => 'required|in:Pending,On Progress,Done,Abandoned',
    ]);

    // Update the task with the validated data
    $task->update($validatedData);

    // Redirect to the appropriate route based on the role
    if (auth()->user()->hasRole('Employee')) {
        return redirect()->route('myTasks')->with('success', 'Task updated successfully.');
    }

    return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
}


    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
    // Show the logged-in employee's tasks
    public function myTasks()
    {
        $user = Auth::user();

        // Find the employee record corresponding to the logged-in user
        $employee = Employee::where('first_name', $user->first_name)->first();

        if (!$employee) {
            return redirect()->route('tasks.index')
                             ->withErrors(['error' => 'No corresponding employee record found for the user.']);
        }

        // Fetch the tasks assigned to the logged-in employee
        $tasks = Task::with('employee')
            ->where('employee_id', $employee->id)
            ->get()
            ->map(function ($task) {
                $task->is_read = !is_null($task->read_at);
                return $task;
            });

        return view('tasks.my_tasks', compact('employee', 'tasks'));
    }

    // Check user role and execute appropriate function
    public function checkUserAndShowTasks()
    {
        $user = Auth::user();

        // Assuming roles are defined and you have a method to check user roles
        if ($user->hasRole('admin') || $user->hasRole('super-admin')) {
            return redirect()->route('tasks.index'); // Admins redirect to the main tasks index
        } else {
            return $this->myTasks(); // Employees see their own tasks
        }
    }

}
