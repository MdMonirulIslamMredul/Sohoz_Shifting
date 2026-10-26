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
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.shifting-bookings.print-challan', $shiftingBooking) }}" target="_blank" class="btn btn-outline-secondary">
                <i class="bi bi-printer me-1"></i> Print Challan
            </a>
            <a href="{{ route('admin.shifting-bookings.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">
        {{-- Left Column: Booking Details --}}
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
                        <div class="col-12 mt-2">
                            <label class="text-muted small d-block">Email Address</label>
                            <span>{{ $shiftingBooking->customer_email }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Property & Route Card --}}
            <div class="card shadow-sm border-0 mb-3 p-3">
                <h6 class="fw-bold border-bottom pb-2 mb-3 text-dark">
                    <i class="bi bi-geo-alt-fill text-danger me-2"></i>Route &amp; Property Details
                </h6>

                <div class="mb-3 p-2 bg-light rounded border">
                    <label class="text-muted small d-block">Property Type (বাসা / অফিসের ধরন)</label>
                    <div class="fs-6 fw-bold text-dark">
                        {{ $shiftingBooking->display_property_type }}
                    </div>
                    @if($shiftingBooking->custom_property_type)
                        <div class="badge bg-warning text-dark mt-1">
                            <i class="bi bi-pencil-square me-1"></i>গ্রাহকের ম্যানুয়াল ইনপুট: {{ $shiftingBooking->custom_property_type }}
                        </div>
                    @endif
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100 bg-white">
                            <div class="badge bg-primary mb-2">Pickup Location (যাত্রা শুরু)</div>
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
                            <div class="badge bg-success mb-2">Delivery Location (গন্তব্য)</div>
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
                        <span class="fw-semibold">{{ $shiftingBooking->labour_count }} জন দক্ষ শ্রমিক</span>
                    </div>
                </div>
            </div>

            {{-- Services & Custom Requests --}}
            <div class="card shadow-sm border-0 mb-3 p-3">
                <h6 class="fw-bold border-bottom pb-2 mb-3 text-dark">
                    <i class="bi bi-tools text-secondary me-2"></i>Additional Services &amp; Custom Requests
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
                @else
                    <p class="text-muted small mb-3">No standard add-on services selected.</p>
                @endif

                @if($shiftingBooking->custom_service_requests)
                    <div class="p-3 bg-light rounded border border-warning">
                        <label class="text-warning-emphasis fw-bold small d-block mb-1">
                            <i class="bi bi-chat-left-dots-fill me-1"></i>গ্রাহকের বিশেষ ম্যানুয়াল সার্ভিস রিকোয়েস্ট (Customer's Custom Service Request):
                        </label>
                        <div class="text-dark">{{ $shiftingBooking->custom_service_requests }}</div>
                    </div>
                @endif

                @if($shiftingBooking->special_notes)
                    <div class="mt-2 p-2 bg-light rounded small">
                        <span class="fw-bold text-muted">Special Instructions:</span> {{ $shiftingBooking->special_notes }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Right Column: Admin Dispatch & Financial Management --}}
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 p-3 sticky-top" style="top: 20px;">
                <h6 class="fw-bold border-bottom pb-2 mb-3 text-dark">
                    <i class="bi bi-sliders text-primary me-2"></i>Operations &amp; Dispatch Controls
                </h6>

                <form method="POST" action="{{ route('admin.shifting-bookings.update', $shiftingBooking) }}">
                    @csrf
                    @method('PUT')

                    {{-- Status Dropdown --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Booking Status</label>
                        <select name="status" class="form-select form-select-lg fw-semibold">
                            <option value="pending" {{ $shiftingBooking->status === 'pending' ? 'selected' : '' }}>🟡 নতুন রিকোয়েস্ট (Pending)</option>
                            <option value="contacted" {{ $shiftingBooking->status === 'contacted' ? 'selected' : '' }}>🔵 যোগাযোগ করা হয়েছে (Contacted)</option>
                            <option value="confirmed" {{ $shiftingBooking->status === 'confirmed' ? 'selected' : '' }}>🟣 বুকিং নিশ্চিত (Confirmed)</option>
                            <option value="team_assigned" {{ $shiftingBooking->status === 'team_assigned' ? 'selected' : '' }}>🚙 টিম নিয়োজিত (Team Assigned)</option>
                            <option value="in_progress" {{ $shiftingBooking->status === 'in_progress' ? 'selected' : '' }}>🚚 শিফটিং চলছে (In Progress)</option>
                            <option value="completed" {{ $shiftingBooking->status === 'completed' ? 'selected' : '' }}>🟢 সম্পন্ন (Completed)</option>
                            <option value="cancelled" {{ $shiftingBooking->status === 'cancelled' ? 'selected' : '' }}>🔴 বাতিল (Cancelled)</option>
                        </select>
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
                            <label class="form-label small mb-1">Truck / Vehicle Reg. No.</label>
                            <input type="text" name="driver_truck_number" class="form-control form-control-sm" value="{{ old('driver_truck_number', $shiftingBooking->driver_truck_number) }}" placeholder="e.g. ঢাকা মেট্রো-ন ১১-২২৩৩">
                        </div>
                    </div>

                    {{-- Financials & Payment --}}
                    <div class="border rounded p-3 mb-3 bg-light">
                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-cash-stack me-1"></i>Pricing &amp; Advance Collection</h6>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small mb-1">Estimated Cost</label>
                                <div class="fw-bold text-muted">৳{{ number_format($shiftingBooking->estimated_cost, 2) }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label small mb-1">Final Agreed Cost (৳)</label>
                                <input type="number" step="0.01" name="final_cost" class="form-control form-control-sm fw-bold text-success" value="{{ old('final_cost', $shiftingBooking->final_cost ?: $shiftingBooking->estimated_cost) }}">
                            </div>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small mb-1">Advance Paid (৳)</label>
                                <input type="number" step="0.01" name="advance_amount" class="form-control form-control-sm text-primary fw-bold" value="{{ old('advance_amount', $shiftingBooking->advance_amount) }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label small mb-1">Remaining Due</label>
                                <div class="fw-bold text-danger">৳{{ number_format($shiftingBooking->due_amount, 2) }}</div>
                            </div>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-sm-6">
                                <label class="form-label small mb-1">Payment Status</label>
                                <select name="payment_status" class="form-select form-select-sm">
                                    <option value="unpaid" {{ $shiftingBooking->payment_status === 'unpaid' ? 'selected' : '' }}>বাকি (Unpaid)</option>
                                    <option value="partial_advance" {{ $shiftingBooking->payment_status === 'partial_advance' ? 'selected' : '' }}>অগ্রিম গ্রহণ করা হয়েছে (Partial)</option>
                                    <option value="paid_in_full" {{ $shiftingBooking->payment_status === 'paid_in_full' ? 'selected' : '' }}>সম্পূর্ণ পরিশোধিত (Paid in Full)</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small mb-1">Payment Method</label>
                                <input type="text" name="payment_method" class="form-control form-control-sm" value="{{ old('payment_method', $shiftingBooking->payment_method) }}" placeholder="bKash / Nagad / Cash">
                            </div>
                        </div>

                        <div>
                            <label class="form-label small mb-1">Transaction ID (TrxID)</label>
                            <input type="text" name="transaction_id" class="form-control form-control-sm" value="{{ old('transaction_id', $shiftingBooking->transaction_id) }}" placeholder="e.g. 9K72LM89">
                        </div>
                    </div>

                    {{-- Admin Internal Notes --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Admin Internal Notes</label>
                        <textarea name="admin_notes" class="form-control form-control-sm" rows="3" placeholder="Private internal notes for management...">{{ old('admin_notes', $shiftingBooking->admin_notes) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-save me-1"></i> Update &amp; Save Dispatch
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
