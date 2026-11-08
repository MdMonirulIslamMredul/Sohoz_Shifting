<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingSupportTicket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = BookingSupportTicket::with(['booking', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $tickets = $query->paginate(15)->withQueryString();

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(BookingSupportTicket $ticket)
    {
        $ticket->load(['booking', 'user']);
        return view('admin.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, BookingSupportTicket $ticket)
    {
        $request->validate([
            'message' => ['required', 'string'],
        ]);

        $messages = $ticket->messages ?: [];
        $messages[] = [
            'sender' => 'admin',
            'sender_name' => auth()->user()->name . ' (Support Team)',
            'message' => $request->message,
            'timestamp' => now()->toIso8601String(),
        ];

        $ticket->update([
            'messages' => $messages,
            'status' => 'in_review',
        ]);

        return back()->with('success', 'Reply sent to customer successfully.');
    }

    public function updateStatus(Request $request, BookingSupportTicket $ticket)
    {
        $request->validate([
            'status' => ['required', 'in:open,in_review,resolved,closed'],
        ]);

        $ticket->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Ticket status updated to ' . ucfirst($request->status));
    }
}

