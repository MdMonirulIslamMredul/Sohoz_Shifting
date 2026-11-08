<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingFollowUp;
use App\Models\BookingInvoice;
use App\Models\BookingQuotation;
use App\Models\ShiftingBooking;
use Illuminate\Http\Request;

class ShiftingBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = ShiftingBooking::with(['propertyType', 'bookingServices', 'user', 'latestQuotation', 'latestInvoice'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('pickup_area', 'like', "%{$search}%")
                  ->orWhere('delivery_area', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('shifting_date', $request->date);
        }

        $bookings = $query->paginate(15)->withQueryString();

        $statusCounts = [
            'all' => ShiftingBooking::count(),
            'new_request' => ShiftingBooking::whereIn('status', ['new_request', 'pending'])->count(),
            'contacted' => ShiftingBooking::where('status', 'contacted')->count(),
            'assessment_required' => ShiftingBooking::where('status', 'assessment_required')->count(),
            'site_visit_scheduled' => ShiftingBooking::where('status', 'site_visit_scheduled')->count(),
            'quotation_sent' => ShiftingBooking::where('status', 'quotation_sent')->count(),
            'customer_approved' => ShiftingBooking::where('status', 'customer_approved')->count(),
            'confirmed' => ShiftingBooking::where('status', 'confirmed')->count(),
            'in_progress' => ShiftingBooking::where('status', 'in_progress')->count(),
            'completed' => ShiftingBooking::where('status', 'completed')->count(),
            'cancelled' => ShiftingBooking::where('status', 'cancelled')->count(),
        ];

        return view('admin.shifting-bookings.index', compact('bookings', 'statusCounts'));
    }

    public function show(ShiftingBooking $shiftingBooking)
    {
        $shiftingBooking->load([
            'propertyType',
            'bookingServices.service',
            'images',
            'user',
            'quotations',
            'invoices',
            'followUps.admin',
            'tickets',
        ]);

        return view('admin.shifting-bookings.show', compact('shiftingBooking'));
    }

    public function update(Request $request, ShiftingBooking $shiftingBooking)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:new_request,pending,contacted,assessment_required,site_visit_scheduled,quotation_sent,customer_approved,confirmed,in_progress,completed,cancelled'],
            'site_visit_date' => ['nullable', 'date'],
            'final_cost' => ['nullable', 'numeric', 'min:0'],
            'advance_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_status' => ['required', 'string', 'in:unpaid,partial_advance,paid_in_full'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'transaction_id' => ['nullable', 'string', 'max:100'],
            'assigned_supervisor_name' => ['nullable', 'string', 'max:100'],
            'assigned_supervisor_phone' => ['nullable', 'string', 'max:30'],
            'driver_name' => ['nullable', 'string', 'max:100'],
            'driver_phone' => ['nullable', 'string', 'max:30'],
            'driver_truck_number' => ['nullable', 'string', 'max:50'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $finalCost = (isset($validated['final_cost']) && $validated['final_cost'] !== null && $validated['final_cost'] !== '')
            ? (float)$validated['final_cost']
            : (float)($shiftingBooking->final_cost ?? $shiftingBooking->estimated_cost ?? 0);
        $advance = (isset($validated['advance_amount']) && $validated['advance_amount'] !== null && $validated['advance_amount'] !== '')
            ? (float)$validated['advance_amount']
            : (float)($shiftingBooking->advance_amount ?? 0);
        $due = max(0, $finalCost - $advance);

        $validated['final_cost'] = $finalCost;
        $validated['advance_amount'] = $advance;
        $validated['due_amount'] = $due;

        $shiftingBooking->update($validated);

        return redirect()->route('admin.shifting-bookings.show', $shiftingBooking)
            ->with('success', 'Booking status and dispatch details updated successfully.');
    }

    public function storeQuotation(Request $request, ShiftingBooking $shiftingBooking)
    {
        $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string'],
            'items.*.quantity' => ['required', 'numeric', 'min:1'],
            'items.*.rate' => ['required', 'numeric', 'min:0'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'advance_required' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'terms_conditions' => ['nullable', 'string'],
        ]);

        $lineItems = [];
        $subtotal = 0.0;

        foreach ($request->items as $item) {
            $qty = (float)$item['quantity'];
            $rate = (float)$item['rate'];
            $lineTotal = $qty * $rate;
            $subtotal += $lineTotal;

            $lineItems[] = [
                'description' => $item['description'],
                'quantity' => $qty,
                'rate' => $rate,
                'total' => $lineTotal,
                'note' => $item['note'] ?? null,
            ];
        }

        $discount = (float)($request->discount_amount ?? 0);
        $advance = (float)($request->advance_required ?? 0);
        $total = max(0, $subtotal - $discount);

        $quotation = BookingQuotation::create([
            'shifting_booking_id' => $shiftingBooking->id,
            'quotation_number' => BookingQuotation::generateQuotationNumber(),
            'line_items' => $lineItems,
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'total_amount' => $total,
            'advance_required' => $advance,
            'terms_conditions' => $request->terms_conditions,
            'notes' => $request->notes,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $shiftingBooking->update([
            'status' => 'quotation_sent',
            'final_cost' => $total,
            'due_amount' => max(0, $total - (float)$shiftingBooking->advance_amount),
        ]);

        return back()->with('success', 'Quotation #' . $quotation->quotation_number . ' generated and sent to customer.');
    }

    public function storeInvoice(Request $request, ShiftingBooking $shiftingBooking)
    {
        $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string'],
            'items.*.quantity' => ['required', 'numeric', 'min:1'],
            'items.*.rate' => ['required', 'numeric', 'min:0'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'advance_paid' => ['nullable', 'numeric', 'min:0'],
            'payment_status' => ['required', 'string', 'in:unpaid,partial_advance,paid_in_full'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'transaction_id' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $lineItems = [];
        $subtotal = 0.0;

        foreach ($request->items as $item) {
            $qty = (float)$item['quantity'];
            $rate = (float)$item['rate'];
            $lineTotal = $qty * $rate;
            $subtotal += $lineTotal;

            $lineItems[] = [
                'description' => $item['description'],
                'quantity' => $qty,
                'rate' => $rate,
                'total' => $lineTotal,
                'note' => $item['note'] ?? null,
            ];
        }

        $discount = (float)($request->discount_amount ?? 0);
        $total = max(0, $subtotal - $discount);
        $paid = (float)($request->advance_paid ?? 0);
        $due = max(0, $total - $paid);

        $invoice = BookingInvoice::create([
            'shifting_booking_id' => $shiftingBooking->id,
            'invoice_number' => BookingInvoice::generateInvoiceNumber(),
            'invoice_date' => now()->toDateString(),
            'line_items' => $lineItems,
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'total_amount' => $total,
            'advance_paid' => $paid,
            'due_balance' => $due,
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'notes' => $request->notes,
        ]);

        $shiftingBooking->update([
            'final_cost' => $total,
            'advance_amount' => $paid,
            'due_amount' => $due,
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
        ]);

        return back()->with('success', 'Official Invoice #' . $invoice->invoice_number . ' generated successfully.');
    }

    public function storeFollowUp(Request $request, ShiftingBooking $shiftingBooking)
    {
        $request->validate([
            'contact_type' => ['required', 'string', 'in:phone_call,whatsapp,site_visit,email'],
            'notes' => ['required', 'string', 'max:1000'],
            'next_follow_up_date' => ['nullable', 'date'],
            'outcome' => ['nullable', 'string', 'max:50'],
        ]);

        BookingFollowUp::create([
            'shifting_booking_id' => $shiftingBooking->id,
            'admin_user_id' => auth()->id(),
            'contact_type' => $request->contact_type,
            'notes' => $request->notes,
            'next_follow_up_date' => $request->next_follow_up_date,
            'outcome' => $request->outcome,
        ]);

        return back()->with('success', 'CRM follow-up log saved successfully.');
    }

    public function handleRescheduleOrCancel(Request $request, ShiftingBooking $shiftingBooking)
    {
        $action = $request->input('action');

        if ($action === 'approve_reschedule') {
            if ($shiftingBooking->reschedule_requested_date) {
                $shiftingBooking->update([
                    'shifting_date' => $shiftingBooking->reschedule_requested_date,
                    'reschedule_notes' => 'অনুমোদিত: ' . $shiftingBooking->reschedule_notes,
                    'reschedule_requested_date' => null,
                ]);
                return back()->with('success', 'নতুন শিফটিং তারিখ সফলভাবে নির্ধারণ করা হয়েছে।');
            }
        } elseif ($action === 'approve_cancel') {
            $shiftingBooking->update([
                'status' => 'cancelled',
            ]);
            return back()->with('success', 'বুকিং বাতিল সম্পন্ন করা হয়েছে।');
        }

        return back();
    }

    public function checkNewBookings(Request $request)
    {
        $newCount = ShiftingBooking::whereIn('status', ['new_request', 'pending'])->count();
        $latest = ShiftingBooking::latest()->first();

        return response()->json([
            'new_count' => $newCount,
            'latest_id' => $latest?->id,
            'latest_code' => $latest?->booking_code,
            'customer_name' => $latest?->customer_name,
        ]);
    }

    public function printChallan(ShiftingBooking $shiftingBooking)
    {
        $shiftingBooking->load(['propertyType', 'bookingServices.service']);

        return view('admin.shifting-bookings.print-challan', compact('shiftingBooking'));
    }

    public function destroy(ShiftingBooking $shiftingBooking)
    {
        $shiftingBooking->delete();

        return redirect()->route('admin.shifting-bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }
}
