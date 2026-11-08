@extends('admin.layouts.app')
@section('title', 'Booking #' . $shiftingBooking->booking_code)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Booking: <span class="text-primary">{{ $shiftingBooking->booking_code }}</span></h4>
            <span class="{{ $shiftingBooking->status_badge_class }} fs-6">
                {{ $shiftingBooking->status_label_bn }}
            </span>
            <span class="text-muted ms-2 small">Received on {{ $shiftingBooking->created_at->format('d M, Y h:i A') }}</span>
            @if($shiftingBooking->utm_source)
                <span class="badge bg-light text-primary border ms-2">
                    <i class="bi bi-broadcast me-1"></i>{{ $shiftingBooking->utm_source }} ({{ $shiftingBooking->utm_campaign ?? 'campaign' }})
                </span>
            @endif
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-warning fw-semibold text-dark" data-bs-toggle="modal" data-bs-target="#quotationBuilderModal">
                <i class="bi bi-file-earmark-text me-1"></i> Build Quotation
            </button>
            <button type="button" class="btn btn-success fw-semibold" data-bs-toggle="modal" data-bs-target="#invoiceBuilderModal">
                <i class="bi bi-receipt me-1"></i> Create Invoice
            </button>
            <a href="{{ route('admin.shifting-bookings.print-challan', $shiftingBooking) }}" target="_blank" class="btn btn-outline-secondary">
                <i class="bi bi-printer me-1"></i> Challan
            </a>
            <a href="{{ route('admin.shifting-bookings.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Reschedule or Cancellation Alerts from Customer --}}
    @if($shiftingBooking->reschedule_requested_date)
        <div class="alert alert-warning border-warning shadow-sm rounded-3 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong><i class="bi bi-calendar-event-fill me-1"></i>তারিখ পরিবর্তনের অনুরোধ (Customer Reschedule Request):</strong>
                    <div>গ্রাহক নতুন তারিখ চেয়েছেন: <span class="badge bg-dark fs-6">{{ $shiftingBooking->reschedule_requested_date->format('d M, Y') }}</span></div>
                    @if($shiftingBooking->reschedule_notes)
                        <div class="small mt-1 text-muted">কারণ: {{ $shiftingBooking->reschedule_notes }}</div>
                    @endif
                </div>
                <div>
                    <form action="{{ route('admin.shifting-bookings.handle-request', $shiftingBooking) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="action" value="approve_reschedule">
                        <button type="submit" class="btn btn-sm btn-success fw-bold">
                            <i class="bi bi-check-lg me-1"></i> অনুমোদন করুন (Accept)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if($shiftingBooking->cancellation_reason && $shiftingBooking->status !== 'cancelled')
        <div class="alert alert-danger border-danger shadow-sm rounded-3 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong><i class="bi bi-x-circle-fill me-1"></i>বাতিলের অনুরোধ (Customer Cancellation Request):</strong>
                    <div>{{ $shiftingBooking->cancellation_reason }}</div>
                </div>
                <div>
                    <form action="{{ route('admin.shifting-bookings.handle-request', $shiftingBooking) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="action" value="approve_cancel">
                        <button type="submit" class="btn btn-sm btn-danger fw-bold">
                            <i class="bi bi-trash me-1"></i> বাতিল অনুমোদন করুন (Confirm Cancel)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-3">
        {{-- Left Column: Booking Details, Images & CRM --}}
        <div class="col-lg-7">
            {{-- Customer Card --}}
            <div class="card shadow-sm border-0 mb-3 p-3">
                <h6 class="fw-bold border-bottom pb-2 mb-3 text-dark">
                    <i class="bi bi-person-fill text-primary me-2"></i>Customer Information
                </h6>
                <div class="row g-2">
                    <div class="col-sm-6">
                        <label class="text-muted small d-block">Customer Name</label>
                        <span class="fw-bold fs-6">{{ $shiftingBooking->customer_name }}</span>
                        @if($shiftingBooking->user_id)
                            <span class="badge bg-success-subtle text-success small ms-1">Registered User</span>
                        @endif
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small d-block">Phone Number</label>
                        <span class="fw-semibold">
                            <a href="tel:{{ $shiftingBooking->customer_phone }}" class="text-decoration-none">
                                <i class="bi bi-telephone me-1"></i>{{ $shiftingBooking->customer_phone }}
                            </a>
                        </span>
                        @if($shiftingBooking->customer_whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $shiftingBooking->customer_whatsapp) }}" target="_blank" class="badge bg-success text-decoration-none ms-2">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </a>
                        @endif
                    </div>
                    @if($shiftingBooking->customer_email)
                        <div class="col-sm-6 mt-2">
                            <label class="text-muted small d-block">Email Address</label>
                            <span>{{ $shiftingBooking->customer_email }}</span>
                        </div>
                    @endif
                    @if($shiftingBooking->referrer_url)
                        <div class="col-sm-6 mt-2">
                            <label class="text-muted small d-block">Referrer URL</label>
                            <span class="small text-truncate d-block" style="max-width: 260px;">{{ $shiftingBooking->referrer_url }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Luggage & Furniture Photos Gallery (Image 4 requirement) --}}
            @if($shiftingBooking->images->isNotEmpty())
                <div class="card shadow-sm border-0 mb-3 p-3">
                    <h6 class="fw-bold border-bottom pb-2 mb-3 text-dark">
                        <i class="bi bi-images text-primary me-2"></i>Customer Uploaded Luggage Photos ({{ $shiftingBooking->images->count() }})
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($shiftingBooking->images as $img)
                            <a href="{{ $img->url }}" target="_blank" class="border rounded p-1 bg-light">
                                <img src="{{ $img->url }}" alt="Luggage" style="width: 100px; height: 80px; object-fit: cover; border-radius: 6px;">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Property & Route Card --}}
            <div class="card shadow-sm border-0 mb-3 p-3">
                <h6 class="fw-bold border-bottom pb-2 mb-3 text-dark">
                    <i class="bi bi-geo-alt-fill text-danger me-2"></i>Route &amp; Property Details
                </h6>

                <div class="mb-3 p-2 bg-light rounded border">
                    <label class="text-muted small d-block">Property Type</label>
                    <div class="fs-6 fw-bold text-dark">
                        {{ $shiftingBooking->display_property_type }}
                    </div>
                    @if($shiftingBooking->custom_property_type)
                        <div class="badge bg-warning text-dark mt-1">
                            <i class="bi bi-pencil-square me-1"></i>ম্যানুয়াল ইনপুট: {{ $shiftingBooking->custom_property_type }}
                        </div>
                    @endif
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100 bg-white">
                            <div class="badge bg-primary mb-2">Pickup Location</div>
                            <h6 class="fw-bold mb-1">{{ $shiftingBooking->pickup_area }}</h6>
                            <p class="text-muted small mb-2">{{ $shiftingBooking->pickup_address }}</p>
                            <div class="small">
                                <span class="fw-semibold">Floor:</span> {{ $shiftingBooking->pickup_floor }} তলা
                                <span class="ms-2 badge {{ $shiftingBooking->pickup_has_lift ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-dark' }}">
                                    {{ $shiftingBooking->pickup_has_lift ? 'Lift Available' : 'No Lift (সিঁড়ি)' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100 bg-white">
                            <div class="badge bg-success mb-2">Delivery Location</div>
                            <h6 class="fw-bold mb-1">{{ $shiftingBooking->delivery_area }}</h6>
                            <p class="text-muted small mb-2">{{ $shiftingBooking->delivery_address }}</p>
                            <div class="small">
                                <span class="fw-semibold">Floor:</span> {{ $shiftingBooking->delivery_floor }} তলা
                                <span class="ms-2 badge {{ $shiftingBooking->delivery_has_lift ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-dark' }}">
                                    {{ $shiftingBooking->delivery_has_lift ? 'Lift Available' : 'No Lift (সিঁড়ি)' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mt-3 pt-2 border-top">
                    <div class="col-sm-6">
                        <label class="text-muted small d-block">Shifting Date</label>
                        <span class="fw-bold text-primary">{{ $shiftingBooking->shifting_date->format('l, d F Y') }}</span>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small d-block">Time Slot</label>
                        <span class="fw-semibold">{{ $shiftingBooking->preferred_time_slot }}</span>
                    </div>
                    <div class="col-sm-6 mt-2">
                        <label class="text-muted small d-block">Vehicle Requested</label>
                        <span class="fw-semibold">{{ $shiftingBooking->vehicle_type_label_bn }}</span>
                    </div>
                    <div class="col-sm-6 mt-2">
                        <label class="text-muted small d-block">Labours Needed</label>
                        <span class="fw-semibold">{{ $shiftingBooking->labour_count }} জন শ্রমিক</span>
                    </div>
                    @if($shiftingBooking->special_items_json)
                        <div class="col-12 mt-2 p-2 rounded bg-warning-subtle border border-warning border-opacity-25 small">
                            <strong><i class="bi bi-box-seam me-1"></i>বিশেষ মালামাল:</strong>
                            {{ implode(', ', $shiftingBooking->special_items_json) }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Services & Custom Requests --}}
            <div class="card shadow-sm border-0 mb-3 p-3">
                <h6 class="fw-bold border-bottom pb-2 mb-3 text-dark">
                    <i class="bi bi-tools text-secondary me-2"></i>Additional Services &amp; Requests
                </h6>

                @if($shiftingBooking->bookingServices->isNotEmpty())
                    <table class="table table-sm table-bordered align-middle mb-3">
                        <thead class="table-light">
                            <tr>
                                <th>Service</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shiftingBooking->bookingServices as $bs)
                                <tr>
                                    <td>{{ $bs->service_name }}</td>
                                    <td class="text-center">{{ $bs->quantity }}</td>
                                    <td class="text-end">
                                        @if($bs->unit_price > 0)
                                            ৳{{ number_format($bs->unit_price, 2) }}
                                        @else
                                            <span class="text-muted small">আলোচনা সাপেক্ষে</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                @if($shiftingBooking->custom_service_requests)
                    <div class="p-3 bg-light rounded border border-warning mb-2">
                        <label class="text-warning-emphasis fw-bold small d-block mb-1">
                            <i class="bi bi-chat-left-dots-fill me-1"></i>গ্রাহকের বিশেষ সার্ভিস রিকোয়েস্ট:
                        </label>
                        <div class="text-dark">{{ $shiftingBooking->custom_service_requests }}</div>
                    </div>
                @endif
            </div>

            {{-- CRM Customer Follow-Up Log (Image 4 requirement) --}}
            <div class="card shadow-sm border-0 mb-3 p-3">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-telephone-outbound text-primary me-2"></i>Customer CRM &amp; Follow-up History
                    </h6>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#followUpModal">
                        <i class="bi bi-plus-circle me-1"></i> Add Log
                    </button>
                </div>

                <div class="timeline">
                    @forelse($shiftingBooking->followUps as $fu)
                        <div class="border-start border-primary border-3 ps-3 pb-3 mb-2">
                            <div class="d-flex justify-content-between small text-muted">
                                <strong><i class="bi bi-person me-1"></i>{{ $fu->admin?->name ?? 'Admin Staff' }} ({{ ucfirst(str_replace('_', ' ', $fu->contact_type)) }})</strong>
                                <span>{{ $fu->created_at->format('d M, Y h:i A') }}</span>
                            </div>
                            <div class="mt-1 text-dark">{{ $fu->notes }}</div>
                            @if($fu->next_follow_up_date)
                                <div class="small text-danger mt-1">
                                    <i class="bi bi-alarm me-1"></i>Next Follow-up: {{ $fu->next_follow_up_date->format('d M, Y h:i A') }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-muted small py-2">No follow-up notes logged yet. Click "Add Log" to record phone calls or site visit notes.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column: Admin Dispatch, 10 Statuses, Staff & Financials --}}
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 p-3 sticky-top" style="top: 20px;">
                <h6 class="fw-bold border-bottom pb-2 mb-3 text-dark">
                    <i class="bi bi-sliders text-primary me-2"></i>Status Lifecycle &amp; Dispatch Controls
                </h6>

                <form method="POST" action="{{ route('admin.shifting-bookings.update', $shiftingBooking) }}">
                    @csrf
                    @method('PUT')

                    {{-- 10-Stage Status Dropdown (Image 1 specifications) --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Current Status (১০-টি স্টেজ)</label>
                        <select name="status" class="form-select form-select-lg fw-semibold">
                            <option value="new_request" {{ in_array($shiftingBooking->status, ['new_request', 'pending']) ? 'selected' : '' }}>🟡 New Request (নতুন বুকিং)</option>
                            <option value="contacted" {{ $shiftingBooking->status === 'contacted' ? 'selected' : '' }}>🔵 Contacted (গ্রাহকের সঙ্গে কথা হয়েছে)</option>
                            <option value="assessment_required" {{ $shiftingBooking->status === 'assessment_required' ? 'selected' : '' }}>⚪ Assessment Required (যাচাই প্রয়োজন)</option>
                            <option value="site_visit_scheduled" {{ $shiftingBooking->status === 'site_visit_scheduled' ? 'selected' : '' }}>📅 Site Visit Scheduled (ভিজিট নির্ধারিত)</option>
                            <option value="quotation_sent" {{ $shiftingBooking->status === 'quotation_sent' ? 'selected' : '' }}>📨 Quotation Sent (ভাড়া জানানো হয়েছে)</option>
                            <option value="customer_approved" {{ $shiftingBooking->status === 'customer_approved' ? 'selected' : '' }}>🤝 Customer Approved (গ্রাহক সম্মত)</option>
                            <option value="confirmed" {{ $shiftingBooking->status === 'confirmed' ? 'selected' : '' }}>🟣 Confirmed (বুকিং নিশ্চিত)</option>
                            <option value="in_progress" {{ $shiftingBooking->status === 'in_progress' ? 'selected' : '' }}>🚚 In Progress (কাজ চলছে)</option>
                            <option value="completed" {{ $shiftingBooking->status === 'completed' ? 'selected' : '' }}>🟢 Completed (কাজ সম্পন্ন)</option>
                            <option value="cancelled" {{ $shiftingBooking->status === 'cancelled' ? 'selected' : '' }}>🔴 Cancelled (বাতিল)</option>
                        </select>
                    </div>

                    {{-- Site Visit Date (optional) --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Site Visit Date &amp; Time (if scheduled)</label>
                        <input type="datetime-local" name="site_visit_date" class="form-control form-control-sm" value="{{ old('site_visit_date', $shiftingBooking->site_visit_date?->format('Y-m-d\TH:i')) }}">
                    </div>

                    {{-- Staff Assignment --}}
                    <div class="border rounded p-3 mb-3 bg-light">
                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-person-badge me-1"></i>Staff &amp; Vehicle Assignment</h6>
                        
                        <div class="mb-2">
                            <label class="form-label small mb-1">Supervisor Name</label>
                            <input type="text" name="assigned_supervisor_name" class="form-control form-control-sm" value="{{ old('assigned_supervisor_name', $shiftingBooking->assigned_supervisor_name) }}" placeholder="e.g. মোঃ রফিকুল ইসলাম">
                        </div>

                        <div class="mb-2">
                            <label class="form-label small mb-1">Supervisor Phone</label>
                            <input type="text" name="assigned_supervisor_phone" class="form-control form-control-sm" value="{{ old('assigned_supervisor_phone', $shiftingBooking->assigned_supervisor_phone) }}" placeholder="e.g. 017XXXXXXXX">
                        </div>

                        <div class="row g-2">
                            <div class="col-sm-6">
                                <label class="form-label small mb-1">Driver Name</label>
                                <input type="text" name="driver_name" class="form-control form-control-sm" value="{{ old('driver_name', $shiftingBooking->driver_name) }}" placeholder="e.g. আক্তার হোসেন">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small mb-1">Driver Phone</label>
                                <input type="text" name="driver_phone" class="form-control form-control-sm" value="{{ old('driver_phone', $shiftingBooking->driver_phone) }}" placeholder="e.g. 018XXXXXXXX">
                            </div>
                        </div>

                        <div class="mt-2">
                            <label class="form-label small mb-1">Truck Reg. Number</label>
                            <input type="text" name="driver_truck_number" class="form-control form-control-sm" value="{{ old('driver_truck_number', $shiftingBooking->driver_truck_number) }}" placeholder="e.g. ঢাকা মেট্রো-ন ১১-২২৩৩">
                        </div>
                    </div>

                    {{-- Financials & Payment --}}
                    <div class="border rounded p-3 mb-3 bg-light">
                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-cash-stack me-1"></i>Pricing &amp; Payment Collection</h6>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small mb-1">Estimated Cost</label>
                                <div class="fw-bold text-muted">৳{{ number_format($shiftingBooking->estimated_cost, 2) }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label small mb-1">Final Agreed Cost (৳)</label>
                                <input type="number" step="0.01" name="final_cost" class="form-control form-control-sm fw-bold text-primary" value="{{ old('final_cost', $shiftingBooking->final_cost) }}" placeholder="{{ $shiftingBooking->estimated_cost }}">
                            </div>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small mb-1">Advance Collected (৳)</label>
                                <input type="number" step="0.01" name="advance_amount" class="form-control form-control-sm text-success fw-bold" value="{{ old('advance_amount', $shiftingBooking->advance_amount) }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label small mb-1">Remaining Due (৳)</label>
                                <div class="fw-bold text-danger pt-1">৳{{ number_format($shiftingBooking->due_amount, 2) }}</div>
                            </div>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small mb-1">Payment Status</label>
                                <select name="payment_status" class="form-select form-select-sm">
                                    <option value="unpaid" {{ $shiftingBooking->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid (বাকি)</option>
                                    <option value="partial_advance" {{ $shiftingBooking->payment_status === 'partial_advance' ? 'selected' : '' }}>Partial Advance (অগ্রিম)</option>
                                    <option value="paid_in_full" {{ $shiftingBooking->payment_status === 'paid_in_full' ? 'selected' : '' }}>Paid in Full (পরিশোধিত)</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small mb-1">Method</label>
                                <input type="text" name="payment_method" class="form-control form-control-sm" value="{{ old('payment_method', $shiftingBooking->payment_method) }}" placeholder="bKash / Cash / Bank">
                            </div>
                        </div>

                        <div>
                            <label class="form-label small mb-1">Transaction ID / TrxID</label>
                            <input type="text" name="transaction_id" class="form-control form-control-sm" value="{{ old('transaction_id', $shiftingBooking->transaction_id) }}" placeholder="e.g. 9X29A88L">
                        </div>
                    </div>

                    {{-- Admin Notes --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Internal Admin Notes</label>
                        <textarea name="admin_notes" class="form-control" rows="2" placeholder="Private internal staff notes...">{{ old('admin_notes', $shiftingBooking->admin_notes) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                        <i class="bi bi-save me-1"></i> Update Booking &amp; Dispatch
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Quotation Builder (Image 4 requirement) --}}
    <div class="modal fade" id="quotationBuilderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('admin.shifting-bookings.store-quotation', $shiftingBooking) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-text text-primary me-2"></i>Build Formal Quotation for Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">কোটেশনের প্রতিটি লাইন আইটেম ও রেট লিখুন। গ্রাহক এটি ড্যাশবোর্ডে দেখতে ও অনুমোদন করতে পারবেন।</p>
                        
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered align-middle" id="quotationItemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Description</th>
                                        <th style="width: 100px;">Qty</th>
                                        <th style="width: 150px;">Rate (BDT)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" name="items[0][description]" class="form-control form-control-sm" value="{{ $shiftingBooking->display_property_type }} শিফটিং ও পরিবহন সেবা" required>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][quantity]" class="form-control form-control-sm text-center" value="1" min="1" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="items[0][rate]" class="form-control form-control-sm text-end" value="{{ $shiftingBooking->estimated_cost }}" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="items[1][description]" class="form-control form-control-sm" value="অভিজ্ঞ লেবার লোডিং ও আনলোডিং চার্জ ({{ $shiftingBooking->labour_count }} জন)">
                                        </td>
                                        <td>
                                            <input type="number" name="items[1][quantity]" class="form-control form-control-sm text-center" value="{{ $shiftingBooking->labour_count }}">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="items[1][rate]" class="form-control form-control-sm text-end" value="800">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Special Discount (BDT)</label>
                                <input type="number" step="0.01" name="discount_amount" class="form-control" value="{{ $shiftingBooking->discount_amount ?: 0 }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Advance Required (BDT)</label>
                                <input type="number" step="0.01" name="advance_required" class="form-control" value="1000">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Terms &amp; Conditions / Special Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="কোটেশনের বিশেষ শর্তাবলী..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-bold">Generate &amp; Send Quotation</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Invoice Builder (Image 4 requirement) --}}
    <div class="modal fade" id="invoiceBuilderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('admin.shifting-bookings.store-invoice', $shiftingBooking) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold"><i class="bi bi-receipt text-success me-2"></i>Generate Official Invoice</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">অফিসিয়াল ইনভয়েস বিল তৈরি করুন। গ্রাহক ড্যাশবোর্ড হতে এটি সরাসরি ডাউনলোড করতে পারবেন।</p>
                        
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Description</th>
                                        <th style="width: 100px;">Qty</th>
                                        <th style="width: 150px;">Rate (BDT)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" name="items[0][description]" class="form-control form-control-sm" value="{{ $shiftingBooking->display_property_type }} প্যাকেজ শিফটিং বিল" required>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][quantity]" class="form-control form-control-sm text-center" value="1" min="1" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="items[0][rate]" class="form-control form-control-sm text-end" value="{{ $shiftingBooking->final_cost ?: $shiftingBooking->estimated_cost }}" required>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Discount (BDT)</label>
                                <input type="number" step="0.01" name="discount_amount" class="form-control" value="{{ $shiftingBooking->discount_amount ?: 0 }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Advance Paid (BDT)</label>
                                <input type="number" step="0.01" name="advance_paid" class="form-control" value="{{ $shiftingBooking->advance_amount ?: 0 }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Payment Status</label>
                                <select name="payment_status" class="form-select">
                                    <option value="unpaid" {{ $shiftingBooking->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                    <option value="partial_advance" {{ $shiftingBooking->payment_status === 'partial_advance' ? 'selected' : '' }}>Partial Advance</option>
                                    <option value="paid_in_full" {{ $shiftingBooking->payment_status === 'paid_in_full' ? 'selected' : '' }}>Paid in Full</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Payment Method</label>
                                <input type="text" name="payment_method" class="form-control" value="{{ $shiftingBooking->payment_method ?: 'Cash / bKash' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Transaction ID</label>
                                <input type="text" name="transaction_id" class="form-control" value="{{ $shiftingBooking->transaction_id }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Invoice Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="নোট বা মন্তব্য..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success fw-bold">Generate Official Invoice</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Add Follow-up Log (Image 4 requirement) --}}
    <div class="modal fade" id="followUpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.shifting-bookings.store-follow-up', $shiftingBooking) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Log Customer Follow-Up</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Contact Channel <span class="text-danger">*</span></label>
                            <select name="contact_type" class="form-select" required>
                                <option value="phone_call">Phone Call (মোবাইলে কথা হয়েছে)</option>
                                <option value="whatsapp">WhatsApp Message</option>
                                <option value="site_visit">Site Visit (সরাসরি পরিদর্শন)</option>
                                <option value="email">Email</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Summary / Notes <span class="text-danger">*</span></label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="গ্রাহকের সাথে কী আলোচনা হয়েছে সংক্ষেপে লিখুন..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Next Follow-up Reminder (optional)</label>
                            <input type="datetime-local" name="next_follow_up_date" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save CRM Log</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
