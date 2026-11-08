<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>কোটেশন #{{ $quotation->quotation_number }} | সহজ শিফটিং</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Hind Siliguri', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
        }
        .invoice-card {
            max-width: 850px;
            margin: 30px auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.08);
        }
        .invoice-header {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        @media print {
            body {
                background: #ffffff;
                margin: 0;
            }
            .invoice-card {
                box-shadow: none;
                padding: 0;
                margin: 0;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="container py-4">
    {{-- Control Buttons (Hidden on Print) --}}
    <div class="d-flex justify-content-between align-items-center max-w-850 mx-auto mb-3 no-print" style="max-width: 850px;">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> ড্যাশবোর্ডে ফিরে যান
        </a>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-primary fw-bold">
                <i class="bi bi-printer me-1"></i> প্রিন্ট বা PDF ডাউনলোড
            </button>
            @if($quotation->status !== 'approved' && in_array($shifting_booking->status, ['quotation_sent', 'site_visit_scheduled']))
                <form action="{{ route('user.quotations.approve', $shifting_booking) }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-success fw-bold">
                        <i class="bi bi-check2-circle me-1"></i> কোটেশন অনুমোদন করুন
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Quotation Paper Document --}}
    <div class="invoice-card">
        {{-- Header --}}
        <div class="invoice-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">সহজ শিফটিং (Sohoz Shifting)</h2>
                <div class="text-muted small">বাসা ও অফিস শিফটিংয়ের বিশ্বস্ত প্রতিষ্ঠান</div>
                <div class="text-muted small">হটলাইন: ০১৭০০-০০০০০০ | ইমেইল: info@sohozshifting.com</div>
                <div class="text-muted small">ঢাকা, বাংলাদেশ</div>
            </div>
            <div class="text-end">
                <span class="badge bg-primary px-3 py-2 fs-6 mb-2">শিফটিং কোটেশন</span>
                <h5 class="fw-bold mb-0">#{{ $quotation->quotation_number }}</h5>
                <div class="text-muted small">তারিখ: {{ $quotation->created_at->format('d M, Y') }}</div>
                <div class="text-muted small">স্ট্যাটাস: <strong class="text-uppercase">{{ $quotation->status }}</strong></div>
            </div>
        </div>

        {{-- Customer & Route Info --}}
        <div class="row g-3 mb-4">
            <div class="col-6">
                <h6 class="fw-bold text-secondary text-uppercase mb-2 small">গ্রাহকের তথ্য</h6>
                <div class="fw-bold fs-6">{{ $shifting_booking->customer_name }}</div>
                <div>ফোন: {{ $shifting_booking->customer_phone }}</div>
                @if($shifting_booking->customer_email)
                    <div>ইমেইল: {{ $shifting_booking->customer_email }}</div>
                @endif
                <div>বুকিং কোড: <strong>{{ $shifting_booking->booking_code }}</strong></div>
            </div>
            <div class="col-6 text-end">
                <h6 class="fw-bold text-secondary text-uppercase mb-2 small">শিফটিং বিবরণ</h6>
                <div><strong>পিকআপ:</strong> {{ $shifting_booking->pickup_area }} (তলা: {{ $shifting_booking->pickup_floor }})</div>
                <div><strong>ডেলিভারি:</strong> {{ $shifting_booking->delivery_area }} (তলা: {{ $shifting_booking->delivery_floor }})</div>
                <div><strong>শিফটিং তারিখ:</strong> {{ $shifting_booking->shifting_date->format('d M, Y') }}</div>
                <div><strong>গাড়ি:</strong> {{ $shifting_booking->vehicle_type_label_bn }}</div>
            </div>
        </div>

        {{-- Line Items Table --}}
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>সেবা / বিবরণের বিবরণ</th>
                        <th class="text-center" style="width: 80px;">পরিমাণ</th>
                        <th class="text-end" style="width: 130px;">দর (BDT)</th>
                        <th class="text-end" style="width: 140px;">মোট (BDT)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @if(!empty($quotation->line_items))
                        @foreach($quotation->line_items as $item)
                            <tr>
                                <td class="text-center">{{ $i++ }}</td>
                                <td>
                                    <strong>{{ $item['description'] ?? '' }}</strong>
                                    @if(!empty($item['note']))
                                        <div class="text-muted small">{{ $item['note'] }}</div>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item['quantity'] ?? 1 }}</td>
                                <td class="text-end">৳{{ number_format($item['rate'] ?? 0) }}</td>
                                <td class="text-end">৳{{ number_format(($item['quantity'] ?? 1) * ($item['rate'] ?? 0)) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center">1</td>
                            <td>
                                <strong>শিফটিং ও পরিবহন সেবা প্যাকেজ</strong>
                                <div class="text-muted small">{{ $shifting_booking->display_property_type }} শিফটিং, গাড়ি ও দক্ষ লেবারসহ।</div>
                            </td>
                            <td class="text-center">1</td>
                            <td class="text-end">৳{{ number_format($quotation->subtotal) }}</td>
                            <td class="text-end">৳{{ number_format($quotation->subtotal) }}</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">সাবটোটাল:</th>
                        <th class="text-end">৳{{ number_format($quotation->subtotal) }}</th>
                    </tr>
                    @if($quotation->discount_amount > 0)
                        <tr>
                            <th colspan="4" class="text-end text-success">বিশেষ ডিসকাউন্ট:</th>
                            <th class="text-end text-success">-৳{{ number_format($quotation->discount_amount) }}</th>
                        </tr>
                    @endif
                    <tr class="table-dark">
                        <th colspan="4" class="text-end fs-6">মোট প্রস্তাবিত ভাড়া:</th>
                        <th class="text-end fs-6 text-warning">৳{{ number_format($quotation->total_amount) }}</th>
                    </tr>
                    @if($quotation->advance_required > 0)
                        <tr>
                            <th colspan="4" class="text-end text-primary">বুকিং নিশ্চিত করতে অগ্রিম প্রয়োজন:</th>
                            <th class="text-end text-primary">৳{{ number_format($quotation->advance_required) }}</th>
                        </tr>
                    @endif
                </tfoot>
            </table>
        </div>

        {{-- Terms & Conditions --}}
        <div class="p-3 bg-light rounded-3 mb-4 small">
            <h6 class="fw-bold mb-1">শর্তাবলী ও নির্দেশনাবলী:</h6>
            <p class="mb-1 text-muted">১. এই কোটেশনের মেয়াদ প্রেরণের তারিখ হতে ৭ দিন পর্যন্ত বলবৎ থাকবে।</p>
            <p class="mb-1 text-muted">২. শিফটিংয়ের দিন অতিরিক্ত কোনো মালামাল বা অতিরিক্ত ট্রিপ যুক্ত হলে আলোচনা সাপেক্ষে নির্ধারিত চার্জ প্রযোজ্য হবে।</p>
            <p class="mb-0 text-muted">৩. টাকা পরিশোধের মাধ্যম: নগদ (Cash), বিকাশ বা নগদ পার্সোনাল/মার্চেন্ট একাউন্ট।</p>
            @if($quotation->notes)
                <div class="mt-2 text-dark"><strong>বিশেষ নোট:</strong> {{ $quotation->notes }}</div>
            @endif
        </div>

        {{-- Signature Sign-off --}}
        <div class="row pt-4 mt-4 border-top">
            <div class="col-6">
                <div class="text-muted small">গ্রাহকের সম্মতি স্বাক্ষর</div>
                <div class="mt-4 pt-3 border-top d-inline-block" style="min-width: 180px;">তারিখ সহ</div>
            </div>
            <div class="col-6 text-end">
                <div class="text-muted small">সহজ শিফটিং কর্তৃপক্ষ</div>
                <div class="mt-4 pt-3 border-top d-inline-block text-center" style="min-width: 180px;">
                    <strong>কর্তৃপক্ষের সিল ও স্বাক্ষর</strong>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

