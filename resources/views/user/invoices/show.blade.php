<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ইনভয়েস #{{ $invoice->invoice_number }} | সহজ শিফটিং</title>
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
        <button onclick="window.print()" class="btn btn-primary fw-bold">
            <i class="bi bi-printer me-1"></i> ইনভয়েস প্রিন্ট / PDF সংরক্ষণ
        </button>
    </div>

    {{-- Official Invoice Document --}}
    <div class="invoice-card">
        {{-- Header --}}
        <div class="invoice-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark mb-1">সহজ শিফটিং (Sohoz Shifting)</h2>
                <div class="text-muted small">বাসা ও অফিস শিফটিং বিলিং ইনভয়েস</div>
                <div class="text-muted small">হটলাইন: ০১৭০০-০০০০০০ | info@sohozshifting.com</div>
                <div class="text-muted small">ঢাকা, বাংলাদেশ</div>
            </div>
            <div class="text-end">
                <span class="badge bg-success px-3 py-2 fs-6 mb-2">অফিসিয়াল ইনভয়েস</span>
                <h5 class="fw-bold mb-0">#{{ $invoice->invoice_number }}</h5>
                <div class="text-muted small">ইনভয়েস তারিখ: {{ $invoice->invoice_date->format('d M, Y') }}</div>
                <div class="mt-1">
                    <span class="badge {{ $invoice->payment_status === 'paid_in_full' ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ $invoice->payment_status === 'paid_in_full' ? 'পরিশোধিত (PAID)' : 'আংশিক / বাকি (PARTIAL)' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Billed To & Booking Info --}}
        <div class="row g-3 mb-4">
            <div class="col-6">
                <h6 class="fw-bold text-secondary text-uppercase mb-2 small">বিল প্রাপক (Billed To)</h6>
                <div class="fw-bold fs-6">{{ $shifting_booking->customer_name }}</div>
                <div>মোবাইল: {{ $shifting_booking->customer_phone }}</div>
                @if($shifting_booking->customer_email)
                    <div>ইমেইল: {{ $shifting_booking->customer_email }}</div>
                @endif
                <div>বুকিং রেফারেন্স: <strong>{{ $shifting_booking->booking_code }}</strong></div>
            </div>
            <div class="col-6 text-end">
                <h6 class="fw-bold text-secondary text-uppercase mb-2 small">শিফটিং রুট বিবরণ</h6>
                <div><strong>পিকআপ:</strong> {{ $shifting_booking->pickup_area }} (তলা: {{ $shifting_booking->pickup_floor }})</div>
                <div><strong>ডেলিভারি:</strong> {{ $shifting_booking->delivery_area }} (তলা: {{ $shifting_booking->delivery_floor }})</div>
                <div><strong>শিফটিং তারিখ:</strong> {{ $shifting_booking->shifting_date->format('d M, Y') }}</div>
                @if($invoice->payment_method)
                    <div><strong>পেমেন্ট মাধ্যম:</strong> {{ $invoice->payment_method }}</div>
                @endif
                @if($invoice->transaction_id)
                    <div><strong>TrxID:</strong> {{ $invoice->transaction_id }}</div>
                @endif
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
                    @if(!empty($invoice->line_items))
                        @foreach($invoice->line_items as $item)
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
                                <strong>শিফটিং প্যাকেজ সার্ভিস বিল</strong>
                                <div class="text-muted small">{{ $shifting_booking->display_property_type }} সার্ভিস বিল</div>
                            </td>
                            <td class="text-center">1</td>
                            <td class="text-end">৳{{ number_format($invoice->subtotal) }}</td>
                            <td class="text-end">৳{{ number_format($invoice->subtotal) }}</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">সাবটোটাল:</th>
                        <th class="text-end">৳{{ number_format($invoice->subtotal) }}</th>
                    </tr>
                    @if($invoice->discount_amount > 0)
                        <tr>
                            <th colspan="4" class="text-end text-success">ডিসকাউন্ট:</th>
                            <th class="text-end text-success">-৳{{ number_format($invoice->discount_amount) }}</th>
                        </tr>
                    @endif
                    <tr class="table-light">
                        <th colspan="4" class="text-end fs-6">মোট বিল:</th>
                        <th class="text-end fs-6 fw-bold">৳{{ number_format($invoice->total_amount) }}</th>
                    </tr>
                    <tr>
                        <th colspan="4" class="text-end text-success">পরিশোধিত টাকা (Paid):</th>
                        <th class="text-end text-success fw-bold">৳{{ number_format($invoice->advance_paid) }}</th>
                    </tr>
                    <tr class="table-dark">
                        <th colspan="4" class="text-end fs-6 text-danger">অবশিষ্ট বকেয়া (Due Balance):</th>
                        <th class="text-end fs-6 text-danger fw-bold">৳{{ number_format($invoice->due_balance) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($invoice->notes)
            <div class="p-3 bg-light rounded-3 mb-4 small">
                <strong>বিশেষ মন্তব্য:</strong> {{ $invoice->notes }}
            </div>
        @endif

        {{-- Signature Sign-off --}}
        <div class="row pt-5 mt-5 border-top">
            <div class="col-6">
                <div class="text-muted small">গ্রাহকের স্বাক্ষর</div>
                <div class="mt-4 pt-3 border-top d-inline-block" style="min-width: 180px;">তারিখ</div>
            </div>
            <div class="col-6 text-end">
                <div class="text-muted small">অনুমোদনকারী কর্মকর্তা</div>
                <div class="mt-4 pt-3 border-top d-inline-block text-center" style="min-width: 180px;">
                    <strong>সহজ শিফটিং একাউন্টস</strong>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

