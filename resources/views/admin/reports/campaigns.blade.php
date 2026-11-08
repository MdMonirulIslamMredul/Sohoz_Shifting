@extends('admin.layouts.app')
@section('title', 'Marketing & Campaign Performance')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Reports
        </a>
        <h4 class="fw-bold mb-0">Marketing & Campaign Performance Attribution</h4>
        <p class="text-muted small mb-0">কোন বিজ্ঞাপন, ক্যাম্পেইন বা চ্যানেল (Facebook, Google, WhatsApp) হতে কতটি লিড ও রেভিনিউ এসেছে</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Channel / Traffic Source</th>
                        <th>Campaign Name</th>
                        <th class="text-center">Total Leads</th>
                        <th class="text-center">Confirmed Bookings</th>
                        <th class="text-center">Conversion %</th>
                        <th class="text-end">Advance Collected</th>
                        <th class="text-end pe-4">Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaignData as $row)
                        @php
                            $rate = $row->total_leads > 0 ? round(($row->confirmed_count / $row->total_leads) * 100, 1) : 0;
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-25 px-2 py-1 fs-6">
                                    <i class="bi bi-broadcast me-1"></i>{{ ucfirst($row->source) }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $row->campaign }}</strong>
                            </td>
                            <td class="text-center fw-bold">{{ $row->total_leads }}</td>
                            <td class="text-center fw-bold text-success">{{ $row->confirmed_count }}</td>
                            <td class="text-center">
                                <span class="badge {{ $rate >= 50 ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $rate }}%
                                </span>
                            </td>
                            <td class="text-end text-muted">৳{{ number_format($row->total_advance) }}</td>
                            <td class="text-end fw-bold text-dark pe-4">৳{{ number_format($row->total_revenue) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No marketing campaign tracking data captured yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($campaignData->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $campaignData->links() }}
        </div>
    @endif
</div>
@endsection

