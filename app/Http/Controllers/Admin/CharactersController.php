<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SRO\Log\LogEventChar;
use App\Models\SRO\Shard\Char;
use App\Models\SRO\Shard\InvCOS;
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
        $user = User::where('CharID', $char->CharID)->first()->tbUser;
        $userJID = $user->UserJID ?? 0;

        $inventoryAll = $inventoryService->getInventorySet($char->CharID, 108, 13, 0);
        $storageItems = $inventoryService->getStorageItems($userJID, 180, 0);
        $petNames = InvCOS::getPetNames($char->CharID);

        $PetID = request('pet') ?? optional($petNames->first())->ID;
        if ($PetID) {
            $petItems = $inventoryService->getPetItems($char->CharID, $PetID, 196, 0);
        }

        return view('admin.characters.view', [
            'char' => $char,
            'status' => $status,
            'user' => $user,
            'userJID' => $userJID,
            'inventorySet' => $inventoryAll,
            'storageItems' => $storageItems,
            'petNames' => $petNames,
            'PetID' => $PetID,
            'petItems' => $petItems ?? [],
        ]);
    }

    public function update(Request $request, Char $char)
    {
        return back()->with('success', 'Test!');
    }
}
