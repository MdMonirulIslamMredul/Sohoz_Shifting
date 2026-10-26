<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shifting Challan - {{ $shiftingBooking->booking_code }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            color: #1e293b;
            background: #fff;
            padding: 20px;
        }
        .challan-box {
            max-width: 800px;
            margin: auto;
            border: 2px solid #0f172a;
            padding: 30px;
            border-radius: 8px;
        }
        @media print {
            body { padding: 0; }
            .challan-box { border: none; padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="text-center mb-3 no-print">
        <button onclick="window.print()" class="btn btn-primary px-4 py-2 fw-bold">
            Print Challan / Work Order (চালান প্রিন্ট করুন)
        </button>
    </div>

    <div class="challan-box">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-dark">সহজ শিফটিং (Sohoz Shifting)</h2>
                <p class="text-muted small mb-0">বাসা ও অফিস শিফটিং সার্ভিস | ঢাকা, বাংলাদেশ</p>
                <p class="text-muted small mb-0">হটলাইন: 01700-000000 | ওয়েবসাইট: sohozshifting.com</p>
            </div>
            <div class="text-end">
                <span class="badge bg-dark fs-6 px-3 py-2">শিফটিং চালান ও রিসিট</span>
                <div class="mt-2 fw-bold">বুকিং কোড: {{ $shiftingBooking->booking_code }}</div>
                <div class="text-muted small">তারিখ: {{ date('d M, Y') }}</div>
            </div>
        </div>

        {{-- Customer & Moving Schedule --}}
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="p-3 bg-light rounded border">
                    <h6 class="fw-bold mb-2">গ্রাহকের তথ্য (Customer Details)</h6>
                    <div><strong>নাম:</strong> {{ $shiftingBooking->customer_name }}</div>
                    <div><strong>মোবাইল:</strong> {{ $shiftingBooking->customer_phone }}</div>
                    @if($shiftingBooking->customer_whatsapp)
                        <div><strong>হোয়াটসঅ্যাপ:</strong> {{ $shiftingBooking->customer_whatsapp }}</div>
                    @endif
                    <div><strong>প্রপার্টির ধরন:</strong> {{ $shiftingBooking->display_property_type }}</div>
                </div>
            </div>

            <div class="col-6">
                <div class="p-3 bg-light rounded border">
                    <h6 class="fw-bold mb-2">শিফটিং সময়সূচী (Schedule)</h6>
                    <div><strong>তারিখ:</strong> {{ $shiftingBooking->shifting_date->format('l, d F Y') }}</div>
                    <div><strong>টাইম স্লট:</strong> {{ $shiftingBooking->preferred_time_slot }}</div>
                    <div><strong>গাড়ি:</strong> {{ $shiftingBooking->vehicle_type_label_bn }}</div>
                    <div><strong>লেবার সংখ্যা:</strong> {{ $shiftingBooking->labour_count }} জন</div>
                </div>
            </div>
        </div>

        {{-- Route Details --}}
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="border rounded p-3 h-100">
                    <span class="badge bg-primary mb-2">যাত্রা শুরু (Pickup Address)</span>
                    <div class="fw-bold fs-6">{{ $shiftingBooking->pickup_area }}</div>
                    <div class="text-muted small mb-2">{{ $shiftingBooking->pickup_address }}</div>
                    <div><strong>ফ্লোর:</strong> {{ $shiftingBooking->pickup_floor }} তলা ({{ $shiftingBooking->pickup_has_lift ? 'লিফট আছে' : 'লিফট নেই - সিঁড়ি' }})</div>
                </div>
            </div>

            <div class="col-6">
                <div class="border rounded p-3 h-100">
                    <span class="badge bg-success mb-2">গন্তব্য (Delivery Address)</span>
                    <div class="fw-bold fs-6">{{ $shiftingBooking->delivery_area }}</div>
                    <div class="text-muted small mb-2">{{ $shiftingBooking->delivery_address }}</div>
                    <div><strong>ফ্লোর:</strong> {{ $shiftingBooking->delivery_floor }} তলা ({{ $shiftingBooking->delivery_has_lift ? 'লিফট আছে' : 'লিফট নেই - সিঁড়ি' }})</div>
                </div>
            </div>
        </div>

        {{-- Staff Assigned --}}
        <div class="p-3 bg-light rounded border mb-4">
            <h6 class="fw-bold mb-2">দায়িত্বপ্রাপ্ত টিম (Assigned Team)</h6>
            <div class="row">
                <div class="col-4">
                    <strong>টিম সুপারভাইজার:</strong><br>
                    {{ $shiftingBooking->assigned_supervisor_name ?: 'নির্ধারণ করা হয়নি' }}
                    @if($shiftingBooking->assigned_supervisor_phone)
                        ({{ $shiftingBooking->assigned_supervisor_phone }})
                    @endif
                </div>
                <div class="col-4">
                    <strong>ড্রাইভার:</strong><br>
                    {{ $shiftingBooking->driver_name ?: 'নির্ধারণ করা হয়নি' }}
                    @if($shiftingBooking->driver_phone)
                        ({{ $shiftingBooking->driver_phone }})
                    @endif
                </div>
                <div class="col-4">
                    <strong>গাড়ির নাম্বার:</strong><br>
                    {{ $shiftingBooking->driver_truck_number ?: 'নির্ধারণ করা হয়নি' }}
                </div>
            </div>
        </div>

        {{-- Services Table --}}
        <div class="mb-4">
            <h6 class="fw-bold mb-2">সার্ভিস ও মালামাল স্থানান্তর বিবরণ</h6>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>বিবরণ (Service Item)</th>
                        <th class="text-center" style="width: 100px;">পরিমাণ</th>
                        <th class="text-end" style="width: 150px;">রেট (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>মূল শিফটিং সার্ভিস ({{ $shiftingBooking->display_property_type }})</td>
                        <td class="text-center">১ টি ট্রিপ</td>
                        <td class="text-end">৳{{ number_format($shiftingBooking->estimated_cost, 2) }}</td>
                    </tr>
                    @foreach($shiftingBooking->bookingServices as $bs)
                        <tr>
                            <td>{{ $bs->service_name }}</td>
                            <td class="text-center">{{ $bs->quantity }}</td>
                            <td class="text-end">
                                @if($bs->unit_price > 0)
                                    ৳{{ number_format($bs->unit_price, 2) }}
                                @else
                                    আলোচনা সাপেক্ষে
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if($shiftingBooking->custom_service_requests)
                        <tr>
                            <td colspan="3" class="bg-light">
                                <strong>বিশেষ রিকোয়েস্ট:</strong> {{ $shiftingBooking->custom_service_requests }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Financial Summary --}}
        <div class="row justify-content-end mb-4">
            <div class="col-5">
                <table class="table table-sm">
                    <tr>
                        <th>মোট খরচ (Total):</th>
                        <td class="text-end fw-bold">৳{{ number_format($shiftingBooking->final_cost ?: $shiftingBooking->estimated_cost, 2) }}</td>
                    </tr>
                    <tr>
                        <th>অগ্রিম পরিশোধ (Advance):</th>
                        <td class="text-end text-success fw-bold">(-) ৳{{ number_format($shiftingBooking->advance_amount, 2) }}</td>
                    </tr>
                    <tr class="table-light border-top border-dark">
                        <th>বাকি টাকা (Due Amount):</th>
                        <td class="text-end text-danger fw-bold fs-6">৳{{ number_format($shiftingBooking->due_amount, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Signatures --}}
        <div class="row pt-5 mt-4 border-top">
            <div class="col-6 text-center">
                <div class="border-top border-dark w-75 mx-auto pt-2">
                    গ্রাহকের স্বাক্ষর (Customer Signature)
                </div>
            </div>
            <div class="col-6 text-center">
                <div class="border-top border-dark w-75 mx-auto pt-2">
                    টিম সুপারভাইজারের স্বাক্ষর (Supervisor Signature)
                </div>
            </div>
        </div>
    </div>
</body>
</html>
