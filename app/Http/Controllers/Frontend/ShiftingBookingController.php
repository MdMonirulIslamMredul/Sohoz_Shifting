<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PropertyType;
use App\Models\Service;
use App\Models\ShiftingBooking;
use App\Models\ShiftingBookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftingBookingController extends Controller
{
    public function create()
    {
        $propertyTypes = PropertyType::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        $addonServices = Service::where('status', true)->orderBy('sort_order')->orderBy('id')->get();

        return view('frontend.bookings.create', compact('propertyTypes', 'addonServices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:25'],
            'customer_email' => ['nullable', 'email', 'max:120'],
            'customer_whatsapp' => ['nullable', 'string', 'max:25'],

            'property_type_selection' => ['required', 'string'], // can be an ID or 'custom'
            'custom_property_type' => ['nullable', 'string', 'max:255'],

            'pickup_address' => ['required', 'string'],
            'pickup_area' => ['required', 'string', 'max:100'],
            'pickup_floor' => ['required', 'integer', 'min:0', 'max:50'],
            'pickup_has_lift' => ['nullable', 'boolean'],

            'delivery_address' => ['required', 'string'],
            'delivery_area' => ['required', 'string', 'max:100'],
            'delivery_floor' => ['required', 'integer', 'min:0', 'max:50'],
            'delivery_has_lift' => ['nullable', 'boolean'],

            'shifting_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time_slot' => ['nullable', 'string', 'max:50'],
            'vehicle_type' => ['required', 'string', 'in:pickup_7ft,covered_9ft,covered_14ft,customer_managed'],
            'labour_count' => ['required', 'integer', 'min:0', 'max:20'],

            'selected_services' => ['nullable', 'array'],
            'selected_services.*' => ['integer', 'exists:services,id'],
            'custom_service_requests' => ['nullable', 'string', 'max:1000'],
            'special_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $bookingCode = ShiftingBooking::generateBookingCode();

            $propertyTypeId = null;
            $customPropertyType = null;

            if ($validated['property_type_selection'] === 'custom' || $validated['property_type_selection'] === 'others') {
                $customPropertyType = $validated['custom_property_type'] ?: 'অন্যান্য / কাস্টম প্রপার্টি';
            } else {
                $type = PropertyType::find($validated['property_type_selection']);
                if ($type) {
                    $propertyTypeId = $type->id;
                    if (!empty($validated['custom_property_type'])) {
                        $customPropertyType = $validated['custom_property_type'];
                    }
                } else {
                    $customPropertyType = $validated['custom_property_type'] ?: 'অন্যান্য';
                }
            }

            // Estimate Calculation
            $estimatedCost = $this->calculateEstimatedCost(
                $propertyTypeId,
                (int)$validated['pickup_floor'],
                $request->boolean('pickup_has_lift'),
                (int)$validated['delivery_floor'],
                $request->boolean('delivery_has_lift'),
                $validated['vehicle_type'],
                (int)$validated['labour_count'],
                $validated['selected_services'] ?? []
            );

            $booking = ShiftingBooking::create([
                'booking_code' => $bookingCode,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? null,
                'customer_whatsapp' => $validated['customer_whatsapp'] ?? $validated['customer_phone'],
                'property_type_id' => $propertyTypeId,
                'custom_property_type' => $customPropertyType,
                'pickup_address' => $validated['pickup_address'],
                'pickup_area' => $validated['pickup_area'],
                'pickup_floor' => (int)$validated['pickup_floor'],
                'pickup_has_lift' => $request->boolean('pickup_has_lift'),
                'delivery_address' => $validated['delivery_address'],
                'delivery_area' => $validated['delivery_area'],
                'delivery_floor' => (int)$validated['delivery_floor'],
                'delivery_has_lift' => $request->boolean('delivery_has_lift'),
                'shifting_date' => $validated['shifting_date'],
                'preferred_time_slot' => $validated['preferred_time_slot'] ?? 'Morning (8 AM - 12 PM)',
                'vehicle_type' => $validated['vehicle_type'],
                'labour_count' => (int)$validated['labour_count'],
                'estimated_cost' => $estimatedCost,
                'due_amount' => $estimatedCost,
                'payment_status' => 'unpaid',
                'custom_service_requests' => $validated['custom_service_requests'] ?? null,
                'status' => 'pending',
                'special_notes' => $validated['special_notes'] ?? null,
            ]);

            // Save selected services
            if (!empty($validated['selected_services'])) {
                $services = Service::whereIn('id', $validated['selected_services'])->get();
                foreach ($services as $service) {
                    $unitPrice = $service->price ?? 0.00;
                    ShiftingBookingService::create([
                        'shifting_booking_id' => $booking->id,
                        'service_id' => $service->id,
                        'service_name' => $service->title,
                        'unit_price' => $unitPrice,
                        'quantity' => 1,
                        'total_price' => $unitPrice,
                    ]);
                }
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'booking_code' => $booking->booking_code,
                    'redirect_url' => route('bookings.confirmation', $booking->booking_code),
                ]);
            }

            return redirect()->route('bookings.confirmation', $booking->booking_code)
                ->with('success', 'আপনার বুকিং রিকোয়েস্ট সফলভাবে জমা হয়েছে!');
        });
    }

    public function confirmation($code)
    {
        $booking = ShiftingBooking::with(['propertyType', 'bookingServices'])->where('booking_code', $code)->firstOrFail();

        // Build WhatsApp notification link for customer
        $adminPhone = config('app.admin_whatsapp', '8801700000000'); // Default fallback or config
        $message = "আসসালামু আলাইকুম সহজ শিফটিং,\nআমি একটি শিফটিং বুকিং করেছি।\nবুকিং কোড: {$booking->booking_code}\nনাম: {$booking->customer_name}\nফোন: {$booking->customer_phone}\nপ্রপার্টি: {$booking->display_property_type}\nতারিখ: " . $booking->shifting_date->format('d M, Y') . "\nপিকআপ: {$booking->pickup_area} ➔ ডেলিভারি: {$booking->delivery_area}\nঅনুগ্রহ করে নিশ্চিত করবেন।";
        $whatsappUrl = "https://wa.me/{$adminPhone}?text=" . rawurlencode($message);

        return view('frontend.bookings.confirmation', compact('booking', 'whatsappUrl'));
    }

    public function track(Request $request)
    {
        $query = $request->input('search');
        $booking = null;

        if ($query) {
            $booking = ShiftingBooking::with(['propertyType', 'bookingServices'])
                ->where('booking_code', trim($query))
                ->orWhere('customer_phone', trim($query))
                ->latest()
                ->first();
        }

        return view('frontend.bookings.track', compact('booking', 'query'));
    }

    private function calculateEstimatedCost(?int $propertyTypeId, int $pickupFloor, bool $pickupLift, int $deliveryFloor, bool $deliveryLift, string $vehicleType, int $labourCount, array $selectedServiceIds): float
    {
        $cost = 0.0;

        // Base rate from PropertyType
        if ($propertyTypeId) {
            $type = PropertyType::find($propertyTypeId);
            $cost += (float)($type->base_price ?? 4000.00);
        } else {
            $cost += 4500.00;
        }

        // Floor penalty without lift (300 BDT per floor above ground)
        if (!$pickupLift && $pickupFloor > 1) {
            $cost += ($pickupFloor - 1) * 300;
        }
        if (!$deliveryLift && $deliveryFloor > 1) {
            $cost += ($deliveryFloor - 1) * 300;
        }

        // Vehicle cost
        $cost += match ($vehicleType) {
            'pickup_7ft' => 2500.00,
            'covered_9ft' => 3800.00,
            'covered_14ft' => 5500.00,
            default => 0.00,
        };

        // Labour cost (approx 800 BDT per person)
        $cost += ($labourCount * 800.00);

        // Addon services
        if (!empty($selectedServiceIds)) {
            $services = Service::whereIn('id', $selectedServiceIds)->get();
            foreach ($services as $srv) {
                if ($srv->price && $srv->price > 0) {
                    $cost += (float)$srv->price;
                }
            }
        }

        return round($cost, 2);
    }
}
