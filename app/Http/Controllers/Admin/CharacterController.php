<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SRO\Shard\Char;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    public function index(Request $request)
    {
        $data = Char::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = substr($request->search, 0, 64);
                $q->where('CharName16', 'like', "%{$search}%");
            })
            ->paginate(20);

        return view('admin.characters.index', compact('data'));
    }

    public function view(Char $char)
    {
        return view('admin.characters.view', ['data' => $char]);
    }

    public function update()
    {
        abort(501, 'Not implemented yet.');
    }

    public function unstuck(Char $char)
    {
        if ($char->isOnline) {
            return back()->with('error', 'This char is still logged in.');
        }

        if (!$char->isOffline) {
            return back()->with('error', 'Cannot unstuck this char at the moment.');
        }

        if ($char->hasJobSuit) {
            return back()->with('error', 'This char is wearing a Job Suit.');
        }

        if (!$char->setCharUnstuckPosition()) {
            return back()->with('error', 'Failed to unstuck character.');
        }

        return back()->with('success', 'Your action was successful.');
    }

    public function addItem(Request $request, Char $char)
    {
        $validated = $request->validate([
            'code' => 'required|string|exists:shard.dbo._RefObjCommon,CodeName128',
            'quantity' => 'nullable|integer|min:1|max:999',
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $result = $char->addItem($validated['code'], $quantity);

        if ($result === 1) {
            return back()->with('success', "Item '{$validated['code']}' (x{$quantity}) has been added successfully.");
        }

        return back()->with('error', "Failed to add item. In-game error code: {$result}.");
    }
}
