<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoteController extends Controller
{
    public function index()
    {
        $data = Vote::all();
        return view('admin.votes.index', compact('data'));
    }

    public function create()
    {
        return view('admin.votes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'site' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'ip' => 'required|string|max:255',
            'param' => 'required|string|max:255',
            'reward' => 'required|integer',
            'timeout' => 'required|integer',
            'active' => 'required|boolean',
        ]);
        Vote::create($data);
        return redirect()->route('admin.votes.index')->with('success', 'Vote created!');
    }

    public function edit(Vote $vote)
    {
        return view('admin.votes.edit', compact('vote'));
    }

    public function update(Request $request, Vote $vote)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'site' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'ip' => 'required|string|max:255',
            'param' => 'required|string|max:255',
            'reward' => 'required|integer',
            'timeout' => 'required|integer',
            'active' => 'required|boolean',
        ]);
        $vote->update($data);
        return redirect()->route('admin.votes.index')->with('success', 'Vote updated!');
    }

    public function delete(Vote $vote)
    {
        return view('admin.votes.delete', compact('vote'));
    }

    public function destroy(Vote $vote)
    {
        $vote->delete();
        return redirect()->route('admin.votes.index')->with('success', 'Vote deleted!');
    }
}
