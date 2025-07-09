<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }

    public function index()
    {
        $statuses = Status::latest()->paginate(10); // Changed from get() to paginate()
        return view('admin.statuses.index', compact('statuses'));
    }

    // Rest of the methods remain exactly the same as they're correct
    public function create()
    {
        return view('admin.statuses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:statuses',
            'color' => 'required|string|size:7|starts_with:#'
        ]);

        Status::create($validated);
        return redirect()->route('admin.statuses.index')->with('success', 'Status created successfully');
    }

    public function show(Status $status)
    {
        return view('admin.statuses.show', compact('status'));
    }

    public function edit(Status $status)
    {
        return view('admin.statuses.edit', compact('status'));
    }

    public function update(Request $request, Status $status)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:statuses,name,' . $status->id,
            'color' => 'required|string|size:7|starts_with:#',
        ]);

        $status->update($validated);

        return redirect()->route('admin.statuses.index')
            ->with('success', 'Status updated successfully');
    }

    public function destroy(Status $status)
    {
        $status->delete();
        
        return redirect()->route('admin.statuses.index')
            ->with('success', 'Status deleted successfully');
    }
}