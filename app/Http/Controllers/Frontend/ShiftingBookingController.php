<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\PropertyType;
use App\Models\Service;
use App\Models\ShiftingBooking;
use App\Models\ShiftingBookingImage;
use App\Models\ShiftingBookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ShiftingBookingController extends Controller
{
    public function create()
    {
        $propertyTypes = PropertyType::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        $allServices = Service::where('status', true)->orderBy('sort_order')->orderBy('id')->get();

        // Categorize services into Packing, Labour, AC/Electrical, and Others
        $categorizedServices = [
            'packing' => $allServices->filter(fn ($s) => str_contains(strtolower($s->title . ' ' . $s->category), 'pack') || str_contains($s->title, 'প্যাকিং')),
            'labour' => $allServices->filter(fn ($s) => str_contains(strtolower($s->title . ' ' . $s->category), 'labour') || str_contains($s->title, 'লেবার') || str_contains($s->title, 'লোডিং')),
            'electrical' => $allServices->filter(fn ($s) => str_contains(strtolower($s->title . ' ' . $s->category), 'ac') || str_contains(strtolower($s->title), 'electric') || str_contains($s->title, 'এসি') || str_contains($s->title, 'ইলেকট্রিক')),
            'others' => $allServices->reject(function ($s) {
                $text = strtolower($s->title . ' ' . $s->category);
                return str_contains($text, 'pack') || str_contains($s->title, 'প্যাকিং') ||
                       str_contains($text, 'labour') || str_contains($s->title, 'লেবার') || str_contains($s->title, 'লোডিং') ||
                       str_contains($text, 'ac') || str_contains($text, 'electric') || str_contains($s->title, 'এসি') || str_contains($s->title, 'ইলেকট্রিক');
            }),
        ];

        return view('frontend.bookings.create', compact('propertyTypes', 'allServices', 'categorizedServices'));
    }

    public function checkCoupon(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        $code = strtoupper(trim($request->code));
        $amount = (float) $request->amount;

        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            return response()->json([
                'success' => false,
                'message' => 'কুপন কোডটি সঠিক নয়।',
            ], 422);
        }

        if (! $coupon->isValidForAmount($amount)) {
            $msg = 'কুপনটি এই অর্ডারে প্রযোজ্য নয়।';
            if ($amount < (float) $coupon->min_booking_amount) {
                $msg = 'এই কুপনের জন্য সর্বনিম্ন ৳' . number_format($coupon->min_booking_amount) . ' টাকার বুকিং আবশ্যক।';
            } elseif ($coupon->expires_at && now()->startOfDay()->gt($coupon->expires_at)) {
                $msg = 'এই কুপনের মেয়াদ শেষ হয়ে গেছে।';
            } elseif ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
                $msg = 'এই কুপনের সর্বোচ্চ ব্যবহারের সীমা শেষ হয়ে গেছে।';
            }

            return response()->json([
                'success' => false,
                'message' => $msg,
            ], 422);
        }

        $discount = $coupon->calculateDiscount($amount);

        return response()->json([
            'success' => true,
            'coupon_id' => $coupon->id,
            'code' => $coupon->code,
            'discount' => $discount,
            'message' => 'কুপন সফলভাবে প্রয়োগ হয়েছে! ৳' . number_format($discount) . ' ছাড় পাওয়া গেছে।',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:25'],
            'customer_email' => ['nullable', 'email', 'max:120'],
            'customer_whatsapp' => ['nullable', 'string', 'max:25'],

            'property_type_selection' => ['required', 'string'],
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

            // Special items
            'special_items' => ['nullable', 'array'],
            'special_items.*' => ['nullable', 'string', 'max:100'],

            // Luggage photos
            'luggage_images' => ['nullable', 'array', 'max:10'],
            'luggage_images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],

            // Coupon
            'coupon_code' => ['nullable', 'string', 'max:50'],
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
            $grossEstimate = $this->calculateEstimatedCost(
                $propertyTypeId,
                (int)$validated['pickup_floor'],
                $request->boolean('pickup_has_lift'),
                (int)$validated['delivery_floor'],
                $request->boolean('delivery_has_lift'),
                $validated['vehicle_type'],
                (int)$validated['labour_count'],
                $validated['selected_services'] ?? [],
                $validated['special_items'] ?? []
            );

            // Handle coupon validation & discount
            $couponId = null;
            $discountAmount = 0.00;
            if (!empty($validated['coupon_code'])) {
                $coupon = Coupon::where('code', strtoupper(trim($validated['coupon_code'])))->first();
                if ($coupon && $coupon->isValidForAmount($grossEstimate)) {
                    $couponId = $coupon->id;
                    $discountAmount = $coupon->calculateDiscount($grossEstimate);
                    $coupon->increment('used_count');
                }
            }

            $netEstimate = max(0, $grossEstimate - $discountAmount);

            // Capture UTM attribution from request or session
            $utmSource = $request->input('utm_source', session('utm_source'));
            $utmMedium = $request->input('utm_medium', session('utm_medium'));
            $utmCampaign = $request->input('utm_campaign', session('utm_campaign'));
            $utmTerm = $request->input('utm_term', session('utm_term'));
            $utmContent = $request->input('utm_content', session('utm_content'));
            $referrerUrl = $request->input('referrer_url', session('referrer_url', $request->headers->get('referer')));

            $booking = ShiftingBooking::create([
                'user_id' => auth()->id(),
                'booking_code' => $bookingCode,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? (auth()->user()?->email),
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
                'preferred_time_slot' => $validated['preferred_time_slot'] ?? 'সকাল (৮টা - ১২টা)',
                'vehicle_type' => $validated['vehicle_type'],
                'labour_count' => (int)$validated['labour_count'],
                'coupon_id' => $couponId,
                'discount_amount' => $discountAmount,
                'estimated_cost' => $netEstimate,
                'due_amount' => $netEstimate,
                'payment_status' => 'unpaid',
                'custom_service_requests' => $validated['custom_service_requests'] ?? null,
                'special_items_json' => !empty($validated['special_items']) ? array_values(array_filter($validated['special_items'])) : null,
                'status' => 'new_request',
                'special_notes' => $validated['special_notes'] ?? null,
                'utm_source' => $utmSource,
                'utm_medium' => $utmMedium,
                'utm_campaign' => $utmCampaign,
                'utm_term' => $utmTerm,
                'utm_content' => $utmContent,
                'referrer_url' => $referrerUrl,
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

            // Save luggage photos
            if ($request->hasFile('luggage_images')) {
                foreach ($request->file('luggage_images') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store('booking_images/' . date('Y/m'), 'public');
                        ShiftingBookingImage::create([
                            'shifting_booking_id' => $booking->id,
                            'image_path' => $path,
                            'file_size' => $file->getSize(),
                        ]);
                    }
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
        $booking = ShiftingBooking::with(['propertyType', 'bookingServices', 'images', 'coupon'])->where('booking_code', $code)->firstOrFail();

        // Build WhatsApp notification link for customer
        $adminPhone = config('app.admin_whatsapp', '8801700000000');
        $message = "আসসালামু আলাইকুম সহজ শিফটিং,\nআমি একটি শিফটিং বুকিং করেছি।\nবুকিং কোড: {$booking->booking_code}\nনাম: {$booking->customer_name}\nফোন: {$booking->customer_phone}\nপ্রপার্টি: {$booking->display_property_type}\nতারিখ: " . $booking->shifting_date->format('d M, Y') . "\nপিকআপ: {$booking->pickup_area} ➔ ডেলিভারি: {$booking->delivery_area}\nঅনুগ্রহ করে নিশ্চিত করবেন।";
        $whatsappUrl = "https://wa.me/{$adminPhone}?text=" . rawurlencode($message);

        return view('frontend.bookings.confirmation', compact('booking', 'whatsappUrl'));
    }

    public function track(Request $request)
    {
        $query = $request->input('search');
        $booking = null;

        if ($query) {
            $booking = ShiftingBooking::with(['propertyType', 'bookingServices', 'images', 'latestQuotation', 'latestInvoice'])
                ->where('booking_code', trim($query))
                ->orWhere('customer_phone', trim($query))
                ->latest()
                ->first();
        }

        return view('frontend.bookings.track', compact('booking', 'query'));
    }

    private function calculateEstimatedCost(
        ?int $propertyTypeId,
        int $pickupFloor,
        bool $pickupLift,
        int $deliveryFloor,
        bool $deliveryLift,
        string $vehicleType,
        int $labourCount,
        array $selectedServiceIds,
        array $specialItems = []
    ): float {
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

        // Special items handling surcharge
        if (!empty($specialItems)) {
            foreach ($specialItems as $item) {
                $itemLower = strtolower($item);
                if (str_contains($itemLower, 'safe') || str_contains($itemLower, 'লকার') || str_contains($itemLower, 'সিন্দুক')) {
                    $cost += 1200.00;
                } elseif (str_contains($itemLower, 'piano') || str_contains($itemLower, 'পিয়ানো')) {
                    $cost += 2000.00;
                } elseif (str_contains($itemLower, 'ac') || str_contains($itemLower, 'এসি')) {
                    $cost += 800.00;
                } elseif (str_contains($itemLower, 'fridge') || str_contains($itemLower, 'ফ্রিজ')) {
                    $cost += 500.00;
                } elseif (str_contains($itemLower, 'glass') || str_contains($itemLower, 'কাঁচ')) {
                    $cost += 500.00;
                } else {
                    $cost += 300.00;
                }
            }
        }

        return round($cost, 2);
    }
}
