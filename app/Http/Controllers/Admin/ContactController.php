<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display a listing of contact messages.
     */
    public function index(Request $request): View
    {
        $query = Contact::query();

        // Search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // Read / Unread status filter
        if ($readStatus = $request->input('read_status')) {
            if ($readStatus === 'unread') {
                $query->unread();
            } elseif ($readStatus === 'read') {
                $query->read();
            }
        }

        // Connected / Not connected status filter
        if ($connStatus = $request->input('connection_status')) {
            if ($connStatus === 'connected') {
                $query->connected();
            } elseif ($connStatus === 'not_connected') {
                $query->notConnected();
            }
        }

        // Sorting: default to unread first, then newest
        $sortBy = $request->input('sort', 'smart');
        if ($sortBy === 'newest') {
            $query->latest();
        } elseif ($sortBy === 'oldest') {
            $query->oldest();
        } else {
            // Smart: unread first, then latest created
            $query->orderByRaw('read_at IS NULL DESC')->latest('id');
        }

        $contacts = $query->paginate(15)->withQueryString();

        // Counts for metric cards and filter badges
        $totalCount = Contact::count();
        $unreadCount = Contact::unread()->count();
        $readCount = Contact::read()->count();
        $connectedCount = Contact::connected()->count();
        $notConnectedCount = Contact::notConnected()->count();

        return view('admin.contacts.index', compact(
            'contacts',
            'totalCount',
            'unreadCount',
            'readCount',
            'connectedCount',
            'notConnectedCount'
        ));
    }

    /**
     * Display the specified contact message details.
     * Automatically marks the message as read (opened) if not already read.
     */
    public function show(Contact $contact): View
    {
        // Mark as read if not already read
        if (! $contact->is_read) {
            $contact->markAsRead();
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Toggle read / unread status for a contact message.
     */
    public function toggleRead(Request $request, Contact $contact): JsonResponse|RedirectResponse
    {
        $contact->toggleRead();

        $unreadCount = Contact::unread()->count();
        $statusText = $contact->is_read ? 'Read' : 'Unread';
        $message = "Message marked as {$statusText}.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_read' => $contact->is_read,
                'read_at' => $contact->read_at?->format('M d, Y h:i A'),
                'unread_count' => $unreadCount,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Toggle connected / not connected status for a contact message.
     */
    public function toggleConnected(Request $request, Contact $contact): JsonResponse|RedirectResponse
    {
        $contact->toggleConnected();

        $statusText = $contact->is_connected ? 'Connected' : 'Not Connected';
        $message = "Contact status updated to {$statusText}.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_connected' => $contact->is_connected,
                'connected_at' => $contact->connected_at?->format('M d, Y h:i A'),
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Update admin notes on a contact message.
     */
    public function updateNotes(Request $request, Contact $contact): RedirectResponse
    {
        $data = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:3000'],
        ]);

        $contact->update($data);

        return back()->with('success', 'Admin notes updated successfully.');
    }

    /**
     * Mark all unread messages as read.
     */
    public function markAllRead(): RedirectResponse
    {
        Contact::unread()->update(['read_at' => now()]);

        return back()->with('success', 'All unread messages have been marked as read.');
    }

    /**
     * Delete the specified contact message.
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Contact message deleted successfully.');
    }
}
