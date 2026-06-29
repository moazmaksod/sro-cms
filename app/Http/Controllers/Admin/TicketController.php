<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TicketController extends Controller
{
    public function index()
    {
        $data = Ticket::getAdminTickets(20);

        return view('admin.tickets.index', compact('data'));
    }

    public function show(Ticket $ticket)
    {
        $replies = Ticket::getReplies($ticket);
        $replies = collect([$ticket])->merge($replies);

        return view('admin.tickets.show', [
            'ticket'  => $ticket,
            'data'    => $replies,
        ]);
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        Ticket::replyTo($ticket, [
            'message'  => $request->message,
            'type'     => 'admin',
            'admin_id' => Auth::id(),
        ]);

        return back()->with('success', 'Reply sent!');
    }

    public function close(Ticket $ticket)
    {
        Ticket::close($ticket);

        return back()->with('success', 'Ticket closed!');
    }
}
