<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BookingSupportTicket;
use App\Models\ShiftingBooking;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class UserDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user?->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        $activeBookings = $user->bookings()
            ->with(['propertyType', 'bookingServices', 'images', 'latestQuotation', 'latestInvoice'])
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->get();

        $pastBookings = $user->bookings()
            ->with(['propertyType', 'bookingServices', 'images', 'latestQuotation', 'latestInvoice'])
            ->whereIn('status', ['completed', 'cancelled'])
            ->get();

        $tickets = $user->tickets()
            ->with('booking')
            ->get();

        return view('user.dashboard', compact('user', 'activeBookings', 'pastBookings', 'tickets'));
    }

    public function showBooking(ShiftingBooking $shifting_booking): View
    {
        $this->authorizeBookingOwnership($shifting_booking);

        $shifting_booking->load([
            'propertyType',
            'bookingServices',
            'images',
            'quotations',
            'invoices',
            'tickets',
        ]);

        return view('user.bookings.show', compact('shifting_booking'));
    }

    public function cancelBooking(Request $request, ShiftingBooking $shifting_booking)
    {
        $this->authorizeBookingOwnership($shifting_booking);

        $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:500'],
        ]);

        $reason = $request->cancellation_reason;

        // If it's a new request, can cancel directly
        if (in_array($shifting_booking->status, ['new_request', 'pending'])) {
            $shifting_booking->update([
                'status' => 'cancelled',
                'cancellation_reason' => $reason,
            ]);

            return back()->with('success', 'আপনার বুকিং বাতিল করা হয়েছে।');
        }

        // Otherwise mark as cancellation requested
        $shifting_booking->update([
            'cancellation_reason' => 'গ্রাহক বাতিলের অনুরোধ করেছেন: ' . $reason,
        ]);

        return back()->with('success', 'আপনার বুকিং বাতিলের অনুরোধ জমা নেওয়া হয়েছে, শীঘ্রই টিম যোগাযোগ করবে।');
    }

    public function rescheduleBooking(Request $request, ShiftingBooking $shifting_booking)
    {
        $this->authorizeBookingOwnership($shifting_booking);

        $request->validate([
            'reschedule_requested_date' => ['required', 'date', 'after_or_equal:today'],
            'reschedule_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $shifting_booking->update([
            'reschedule_requested_date' => $request->reschedule_requested_date,
            'reschedule_notes' => $request->reschedule_notes,
        ]);

        return back()->with('success', 'আপনার শিফটিং তারিখ পরিবর্তনের অনুরোধ সফলভাবে জমা হয়েছে।');
    }

    public function showQuotation(ShiftingBooking $shifting_booking): View
    {
        $this->authorizeBookingOwnership($shifting_booking);

        $quotation = $shifting_booking->latestQuotation;

        if (! $quotation) {
            abort(404, 'এই বুকিংয়ের জন্য এখনও কোনো কোটেশন তৈরি হয়নি।');
        }

        return view('user.quotations.show', compact('shifting_booking', 'quotation'));
    }

    public function approveQuotation(Request $request, ShiftingBooking $shifting_booking)
    {
        $this->authorizeBookingOwnership($shifting_booking);

        $quotation = $shifting_booking->latestQuotation;

        if (! $quotation) {
            return back()->withErrors(['quotation' => 'কোটেশন পাওয়া যায়নি।']);
        }

        $quotation->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $shifting_booking->update([
            'status' => 'customer_approved',
            'final_cost' => $quotation->total_amount,
            'due_amount' => max(0, $quotation->total_amount - (float) $shifting_booking->advance_amount),
        ]);

        return back()->with('success', 'ধন্যবাদ! আপনি কোটেশন অনুমোদন করেছেন। আমাদের অপারেশন টিম দ্রুত কাজ শুরু করবে।');
    }

    public function showInvoice(ShiftingBooking $shifting_booking): View
    {
        $this->authorizeBookingOwnership($shifting_booking);

        $invoice = $shifting_booking->latestInvoice;

        if (! $invoice) {
            abort(404, 'এই বুকিংয়ের জন্য এখনও কোনো অফিসিয়াল ইনভয়েস তৈরি হয়নি।');
        }

        return view('user.invoices.show', compact('shifting_booking', 'invoice'));
    }

    public function storeTicket(Request $request, ShiftingBooking $shifting_booking)
    {
        $this->authorizeBookingOwnership($shifting_booking);

        $request->validate([
            'subject' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'in:complaint,feedback,query,reschedule'],
            'priority' => ['required', 'string', 'in:normal,high,urgent'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $ticket = BookingSupportTicket::create([
            'shifting_booking_id' => $shifting_booking->id,
            'user_id' => auth()->id(),
            'ticket_number' => BookingSupportTicket::generateTicketNumber(),
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => 'open',
            'messages' => [
                [
                    'sender' => 'customer',
                    'sender_name' => auth()->user()->name,
                    'message' => $request->message,
                    'timestamp' => now()->toIso8601String(),
                ],
            ],
        ]);

        return back()->with('success', 'আপনার মেসেজ/অভিযোগ গ্রহণ করা হয়েছে (টিকিট #' . $ticket->ticket_number . ')।');
    }

    public function replyTicket(Request $request, BookingSupportTicket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $messages = $ticket->messages ?: [];
        $messages[] = [
            'sender' => 'customer',
            'sender_name' => auth()->user()->name,
            'message' => $request->message,
            'timestamp' => now()->toIso8601String(),
        ];

        $ticket->update([
            'messages' => $messages,
            'status' => 'open',
        ]);

        return back()->with('success', 'আপনার উত্তর পাঠানো হয়েছে।');
    }

    private function authorizeBookingOwnership(ShiftingBooking $booking): void
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'এই বুকিং দেখার অনুমতি আপনার নেই।');
        }
    }
}
