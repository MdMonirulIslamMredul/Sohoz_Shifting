<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShiftingBooking;
use Illuminate\Http\Request;

class ShiftingBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = ShiftingBooking::with(['propertyType', 'bookingServices'])->latest();

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
            'pending' => ShiftingBooking::where('status', 'pending')->count(),
            'confirmed' => ShiftingBooking::where('status', 'confirmed')->count(),
            'team_assigned' => ShiftingBooking::where('status', 'team_assigned')->count(),
            'in_progress' => ShiftingBooking::where('status', 'in_progress')->count(),
            'completed' => ShiftingBooking::where('status', 'completed')->count(),
            'cancelled' => ShiftingBooking::where('status', 'cancelled')->count(),
        ];

        return view('admin.shifting-bookings.index', compact('bookings', 'statusCounts'));
    }

    public function show(ShiftingBooking $shiftingBooking)
    {
        $shiftingBooking->load(['propertyType', 'bookingServices.service']);

        return view('admin.shifting-bookings.show', compact('shiftingBooking'));
    }

    public function update(Request $request, ShiftingBooking $shiftingBooking)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,contacted,confirmed,team_assigned,in_progress,completed,cancelled'],
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

        $finalCost = $validated['final_cost'] !== null ? (float)$validated['final_cost'] : (float)$shiftingBooking->estimated_cost;
        $advance = (float)($validated['advance_amount'] ?? 0);
        $due = max(0, $finalCost - $advance);

        $validated['final_cost'] = $finalCost;
        $validated['advance_amount'] = $advance;
        $validated['due_amount'] = $due;

        $shiftingBooking->update($validated);

        return redirect()->route('admin.shifting-bookings.show', $shiftingBooking)
            ->with('success', 'Booking updated and dispatch details saved successfully.');
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
