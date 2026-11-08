<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShiftingBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'month'); // today, week, month, year, custom
        $query = ShiftingBooking::query();

        $startDate = null;
        $endDate = null;

        if ($period === 'today') {
            $startDate = Carbon::today();
            $endDate = Carbon::today()->endOfDay();
        } elseif ($period === 'week') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } elseif ($period === 'month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        } elseif ($period === 'year') {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();
        } elseif ($period === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalBookings = (clone $query)->count();
        $completedBookings = (clone $query)->where('status', 'completed')->count();
        $confirmedBookings = (clone $query)->whereIn('status', ['confirmed', 'in_progress', 'completed'])->count();
        $cancelledBookings = (clone $query)->where('status', 'cancelled')->count();

        $totalRevenue = (clone $query)->whereNotIn('status', ['cancelled'])->sum('final_cost') ?: (clone $query)->whereNotIn('status', ['cancelled'])->sum('estimated_cost');
        $advanceCollected = (clone $query)->whereNotIn('status', ['cancelled'])->sum('advance_amount');
        $dueOutstanding = (clone $query)->whereNotIn('status', ['cancelled'])->sum('due_amount');

        $conversionRate = $totalBookings > 0 ? round(($confirmedBookings / $totalBookings) * 100, 1) : 0;

        // Status breakdown
        $statusBreakdown = (clone $query)
            ->select('status', DB::raw('count(*) as count'), DB::raw('sum(estimated_cost) as total_val'))
            ->groupBy('status')
            ->get();

        // Top Routes
        $topRoutes = (clone $query)
            ->select('pickup_area', 'delivery_area', DB::raw('count(*) as total'))
            ->groupBy('pickup_area', 'delivery_area')
            ->orderByDesc('total')
            ->take(8)
            ->get();

        return view('admin.reports.index', compact(
            'period',
            'startDate',
            'endDate',
            'totalBookings',
            'completedBookings',
            'confirmedBookings',
            'cancelledBookings',
            'totalRevenue',
            'advanceCollected',
            'dueOutstanding',
            'conversionRate',
            'statusBreakdown',
            'topRoutes'
        ));
    }

    public function campaigns(Request $request)
    {
        $campaignData = ShiftingBooking::select(
            DB::raw('COALESCE(utm_source, "direct / organic") as source'),
            DB::raw('COALESCE(utm_campaign, "general") as campaign'),
            DB::raw('count(*) as total_leads'),
            DB::raw('sum(case when status in ("confirmed", "in_progress", "completed") then 1 else 0 end) as confirmed_count'),
            DB::raw('sum(case when status not in ("cancelled") then COALESCE(final_cost, estimated_cost) else 0 end) as total_revenue'),
            DB::raw('sum(case when status not in ("cancelled") then advance_amount else 0 end) as total_advance')
        )
        ->groupBy(DB::raw('COALESCE(utm_source, "direct / organic")'), DB::raw('COALESCE(utm_campaign, "general")'))
        ->orderByDesc('total_leads')
        ->paginate(15);

        return view('admin.reports.campaigns', compact('campaignData'));
    }
}

