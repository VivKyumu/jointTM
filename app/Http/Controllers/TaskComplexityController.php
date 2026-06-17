<?php

namespace App\Http\Controllers;

use App\Models\TaskComplexity;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

class TaskComplexityController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }

   public function index()
{
    $complexities = TaskComplexity::orderBy('level')->get();
    $tasks = Task::with(['user', 'complexity'])->paginate(10); // Paginate 10 per page

    return view('admin.tasks.index', compact('complexities', 'tasks'));
}



    public function create()
    {
        return view('admin.complexities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|between:1,5|unique:task_complexities',
            'color' => 'required|string|size:7|starts_with:#'
        ]);

        TaskComplexity::create($validated);
        return redirect()->route('admin.complexities.index')->with('success', 'Complexity level added successfully');
    }

    public function show(TaskComplexity $complexity)
    {$tasks = Task::with(['user', 'complexity'])->get(); // Eager load relationships
    $complexities = TaskComplexity::all(); // For the dropdown

    return view('admin.tasks.index', compact('tasks', 'complexities'));
    }

    public function edit(Task $task)
{
    $users = User::all();

    $complexities = [
        ['name' => 'Very Simple', 'level' => 1],
        ['name' => 'Simple', 'level' => 2],
        ['name' => 'Medium', 'level' => 3],
        ['name' => 'Complex', 'level' => 4],
        ['name' => 'Very Complex', 'level' => 5],
    ];

    return view('admin.complexities.edit', compact('task', 'users', 'complexities'));
}

    public function update(Request $request, TaskComplexity $complexity)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|integer|between:1,5|unique:task_complexities,level,' . $complexity->id,
            'color' => 'required|string|size:7|starts_with:#',
        ]);

        $complexity->update($validated);

        return redirect()->route('admin.complexities.index')
            ->with('success', 'Complexity updated successfully');
    }

    public function destroy(TaskComplexity $complexity)
    {
        $complexity->delete();
        
        return redirect()->route('admin.complexities.index')
            ->with('success', 'Complexity deleted successfully');
    }
}