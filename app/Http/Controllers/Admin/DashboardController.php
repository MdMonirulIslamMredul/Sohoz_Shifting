<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Partner;
use App\Models\Product;
use App\Models\PropertyType;
use App\Models\Service;
use App\Models\ShiftingBooking;
use App\Models\TeamMember;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Shifting Booking Statistics
        $bookingStats = [
            'total' => ShiftingBooking::count(),
            'pending' => ShiftingBooking::where('status', 'pending')->count(),
            'confirmed' => ShiftingBooking::where('status', 'confirmed')->count(),
            'team_assigned' => ShiftingBooking::where('status', 'team_assigned')->count(),
            'in_progress' => ShiftingBooking::where('status', 'in_progress')->count(),
            'completed' => ShiftingBooking::where('status', 'completed')->count(),
            'cancelled' => ShiftingBooking::where('status', 'cancelled')->count(),
            'active_operations' => ShiftingBooking::whereIn('status', ['team_assigned', 'in_progress'])->count(),
            'total_estimated_value' => ShiftingBooking::whereNotIn('status', ['cancelled'])->sum('estimated_cost'),
            'total_advance_collected' => ShiftingBooking::whereNotIn('status', ['cancelled'])->sum('advance_amount'),
            'total_due' => ShiftingBooking::whereNotIn('status', ['cancelled'])->sum('due_amount'),
        ];

        // 2. Services Statistics & List
        $serviceStats = [
            'total' => Service::count(),
            'active' => Service::where('status', true)->count(),
            'addon_for_booking' => Service::where('is_addon_for_booking', true)->count(),
            'featured' => Service::where('is_featured', true)->count(),
        ];

        $servicesList = Service::withCount('bookingServices')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        // 3. Recent Bookings (with customer, route, service details)
        $recentBookings = ShiftingBooking::with(['propertyType', 'bookingServices.service'])
            ->latest()
            ->take(6)
            ->get();

        // 4. Recent Contacts
        $recentContacts = Contact::latest()->take(5)->get();

        // 5. General / Content Statistics
        $contentStats = [
            'property_types' => PropertyType::count(),
            'products' => Product::count(),
            'blogs' => Blog::count(),
            'team_members' => TeamMember::count(),
            'partners' => Partner::count(),
            'galleries' => Gallery::count(),
            'testimonials' => Testimonial::count(),
            'contacts' => Contact::count(),
            'unread_contacts' => Contact::unread()->count(),
        ];

        return view('admin.dashboard', compact(
            'bookingStats',
            'serviceStats',
            'servicesList',
            'recentBookings',
            'recentContacts',
            'contentStats'
        ));
    }
}
