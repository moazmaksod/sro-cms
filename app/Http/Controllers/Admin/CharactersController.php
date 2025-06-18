<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SRO\Log\LogEventChar;
use App\Models\SRO\Shard\Char;
use App\Models\SRO\Shard\User;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class CharactersController extends Controller
{
    public function index(Request $request)
    {
        $query = Char::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('CharName16', 'like', "%{$search}%");
            });
        }

        $data = $query->paginate(20);

        return view('admin.characters.index', compact('data'));
    }

    public function view(Char $char, InventoryService $inventoryService)
    {
        $status = LogEventChar::getCharStatus($char->CharID)->take(5);
        $inventorySet = $inventoryService->getInventorySet($char->CharID, 97, 14, 0);
        $userJID = User::where('CharID', $char->CharID)->first()->UserJID;

        return view('admin.characters.view', [
            'char' => $char,
            'status' => $status,
            'inventorySet' => $inventorySet,
            'userJID' => $userJID,
        ]);
    }

    public function update(Request $request, Char $char)
    {
        return back()->with('success', 'Test!');
    }
}
