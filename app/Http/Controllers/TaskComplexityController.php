<?php

namespace App\Http\Controllers;

use App\Models\TaskComplexity;
use Illuminate\Http\Request;

class TaskComplexityController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }

    public function index()
    {
        $complexities = TaskComplexity::orderBy('level')->get();
        return view('admin.complexities.index', compact('complexities'));
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
    {
        return view('admin.complexities.show', compact('complexity'));
    }

    public function edit(TaskComplexity $complexity)
    {
        return view('admin.complexities.edit', compact('complexity'));
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