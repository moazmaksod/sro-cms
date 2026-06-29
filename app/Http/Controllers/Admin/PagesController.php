<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class PagesController extends Controller
{
    public function index()
    {
        $data = Pages::latest()->paginate(20);

        return view('admin.pages.index', compact('data'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['content'] = Purifier::clean($validated['content'], 'full');

        Pages::create($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Pages created successfully!');
    }

    public function edit(Pages $pages)
    {
        return view('admin.pages.edit', ['data' => $pages]);
    }

    public function update(Request $request, Pages $pages)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($validated['title'] !== $pages->title) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['content'] = Purifier::clean($validated['content'], 'full');

        $pages->update($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Pages updated successfully.');
    }

    public function confirmDelete(Pages $pages)
    {
        return view('admin.pages.delete', ['data' => $pages]);
    }

    public function destroy(Pages $pages)
    {
        $pages->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Pages deleted successfully.');
    }
}
