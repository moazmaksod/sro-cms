<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SRO\Portal\AphChangedSilk;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        //$data = User::get();
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $data = $query->paginate(10);

        return view('admin.users.index', compact('data'));
    }

    public function view(User $user)
    {
        return view('admin.users.view', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'type' => 'required',
            'amount' => 'required|numeric',
        ]);

        AphChangedSilk::setChangedSilk($user->jid, $validated['type'], $validated['amount']);

        return back()->with('success', 'Silk have been Sent!');
    }
}
