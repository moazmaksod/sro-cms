<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        abort_if(!config('global.tickets.enabled', false), 403);
        $data = Ticket::getUserTickets(auth()->id(), 20);

        return view('account.tickets.index', compact('data'));
    }

    public function show(int $id)
    {
        $ticket = Ticket::getUserTicket($id, auth()->id());

        abort_if(!$ticket, 403);

        $replies = Ticket::getReplies($ticket);

        return view('account.tickets.show', [
            'data' => $ticket,
            'replies' => $replies,
        ]);
    }

    public function create()
    {
        abort_if(!config('global.tickets.enabled', false), 403);

        $data = config('global.tickets.categories');

        return view('account.tickets.create', compact('data'));
    }

    public function send(Request $request)
    {
        abort_if(!config('global.tickets.enabled', false), 403);
        $config = array_keys(config('global.tickets.categories', []));

        $validated = $request->validate([
            'subject' => 'required_without:parent_id|string|max:255',
            'message' => 'required|string',
            'category' => 'required_without:parent_id|in:' . implode(',', $config),
            'parent_id' => 'nullable|integer',
        ]);

        if ($request->filled('parent_id')) {

            $parent = Ticket::getUserTicket($validated['parent_id'], auth()->id());
            abort_if(!$parent, 404);
            abort_if(!$parent->status, 403, 'Ticket closed');

            Ticket::replyTo($parent, [
                'message' => $validated['message'],
                'type' => 'player',
            ]);

            return back()->with('success', 'Reply sent!');
        }

        Ticket::open($validated, auth()->id());

        return redirect()->route('account.tickets')->with('success', 'Ticket created!');
    }
}
