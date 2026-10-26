# Sohoz Shifting (সহজ শিফটিং) — Master Development & Implementation Plan

**Project:** `Sohoz_Shifting`  
**Platform:** House & Flat Shifting Booking Ecosystem  
**Target Market:** Dhaka Metropolitan Area & Inter-District Bangladesh Relocation  
**Technology Stack:** Laravel 13, PHP 8.3+, Tailwind CSS v4, MySQL (Laragon Environment)  
**Benchmarks & References:**
1. Client Prototype & Feature Demo: [`Claude Artifact / Sohoz Shifting`](https://claude.ai/artifact/L3HvqBdKUYARXXBR4ofQNw)
2. Client UI Screenshots: Top Navbar, Bengali Typography, Accordion FAQ, Dropdown Form Spec
3. Industry Competitor Benchmark: [`basachange.com.bd`](https://basachange.com.bd/)

---

## 1. Executive Summary & Project Objectives

**Sohoz Shifting** is a modern, mobile-first on-demand house and office shifting service platform. The platform eliminates the stress, manual bargaining, and uncertainty of house relocation in Bangladesh by offering:
- **Instant Transparent Cost Estimation:** Customers can calculate or request moving quotes based on apartment size, floor levels, lift availability, and addon services.
- **Frictionless Booking Flow:** Multi-step or streamlined booking without requiring complex upfront registration.
- **Bangla-First & Bilingual Experience:** Native Bangla typography (`Hind Siliguri` / `Noto Sans Bengali`) tailored for local trust, backed by English toggles.
- **Dispatch & Order Management Dashboard:** Back-office administrative portal to manage quotes, assign drivers/labor supervisors, track bKash/Nagad advances, and generate printable shifting work orders (Challans).

```
┌──────────────────────────────────────────────────────────────────────────────────┐
│                           SOHOZ SHIFTING ECOSYSTEM                               │
├─────────────────────────┬───────────────────────────┬────────────────────────────┤
│ 1. CUSTOMER FRONTEND    │ 2. DYNAMIC BOOKING ENGINE │ 3. OPERATIONS & ADMIN      │
│ - High-conversion Hero  │ - Room/Office Selection   │ - Booking Dispatch Board   │
│ - 8 Core Moving Services│ - Pickup/Drop & Floors    │ - Driver & Labor Assign    │
│ - FAQ & Trust Badges    │ - Handyman/AC Addons      │ - Advance/Due Payment Trx  │
│ - Booking Tracking Tool │ - Automated Quote Logic   │ - PDF Invoice & Challan    │
│ - WhatsApp Floating CTA │ - Unique Tracking Code    │ - Rate & Service Settings  │
└─────────────────────────┴───────────────────────────┴────────────────────────────┘
```

---

## 2. Analysis of Client Requirements & Reference Sources

### A. Client Mobile Screenshots Analysis
* **Header & Brand Identity:** 
  - Brand badge: `S H` icon in rounded white container.
  - Brand name: **Sohoz Shifting** in clean bold white on deep dark navy (`#0F172A`).
  - Action button: Golden/Amber rounded button **"বুকিং দিন"** (Book Now).
* **FAQ Section (সাধারণ জিজ্ঞাসা):**
  - Section subtitle: *"বুকিংয়ের আগে প্রয়োজনীয় কিছু তথ্য।"*
  - Expandable accordion items matching exact client specification:
    1. *শিফটিংয়ের খরচ কীভাবে নির্ধারিত হয়?* (How is shifting cost calculated?)
    2. *কত আগে বুকিং দিতে হবে?* (How far in advance to book?)
    3. *অনলাইনে বুকিং দিলেই কি বুকিং নিশ্চিত?* (Does online booking guarantee confirmation?)
    4. *ঢাকার বাইরে সার্ভিস পাওয়া যাবে?* (Is service available outside Dhaka?)
* **Conversion Bar / Floating CTA:**
  - High-impact bottom banner: *"বাসা বদলানোর তারিখ ঠিক করেছেন?"* with a direct WhatsApp Green action button (`● WhatsApp`).
* **Property Type Dropdown Options (বাসা / অফিসের ধরন):**
  - `নির্বাচন করুন` (Select)
  - `স্টুডিও / ছোট বাসা` (Studio / Small House)
  - `১ বেডরুম` (1 Bedroom)
  - `২ বেডরুম` (2 Bedroom)
  - `৩+ বেডরুম` (3+ Bedroom)
  - `অফিস` (Office)
  - `অন্যান্য` (Other)

### B. Claude Demo Architecture (`claude.ai/artifact/L3HvqBdKUYARXXBR4ofQNw`)
* **Hero Section:** Value proposition *"বাসা বদল হোক চাপমুক্ত, সহজে।"* + trust badges (অভিজ্ঞ টিম, নিরাপদ পরিবহন, সাশ্রয়ী মূল্য).
* **8 Core Service Offerings:**
  1. বাসা ও অফিস শিফটিং (Residential & Commercial Relocation)
  2. দক্ষ লেবার সার্ভিস (Skilled Moving Labor)
  3. ইলেকট্রিক্যাল সার্ভিস (Electrician Handyman)
  4. এসি খোলা ও ফিটিং (AC Disconnect & Re-installation)
  5. ফার্নিচার স্থানান্তর (Furniture Disassembly, Transport & Placement)
  6. খোলা ও পুনরায় সেটআপ (Bed/Wardrobe Assembly & Dismantling)
  7. প্যাকিং ও আনপ্যাকিং (Bubble Wrap, Carton, Stretch Film Packing)
  8. পিকআপ ভ্যান / ট্রাক (7ft / 9ft / 14ft Open & Covered Vans)
* **How It Works (৩টি সহজ ধাপ):**
  1. ফ্রি কোট ও বুকিং রিকোয়েস্ট (Submit Requirements)
  2. কল ও শিডিউল কনফার্মেশন (Confirmation & Advance)
  3. নিরাপদ স্থানান্তর (Hassle-free execution)
* **Real-time Booking Tracker:** Customers enter their mobile number or `SH-XXXXXX` code to view live stage.

### C. Industry Benchmark (`basachange.com.bd`)
* **Key Lessons to Incorporate:**
  - Verified Customer Reviews Slider (Google Reviews style with stars and reviewer feedback).
  - Prominent emergency / urgent moving hotline.
  - Transparent checklist for packing and delicate electronics handling.
  - Localized SEO Landing structures (e.g. Uttara, Mirpur, Dhanmondi, Gulshan, Banani, Chittagong).

---

## 3. System Architecture & Database Blueprint

### Database Schema Specification

#### 1. `shifting_bookings` (Primary Booking Table)
```sql
CREATE TABLE shifting_bookings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_code VARCHAR(20) UNIQUE NOT NULL, -- e.g. SH-2026-1042
    customer_name VARCHAR(120) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_email VARCHAR(120) NULL,
    customer_whatsapp VARCHAR(20) NULL,
    
    -- Shifting Classification (From Client Dropdown)
    property_type ENUM('studio_small', '1_bedroom', '2_bedroom', '3_plus_bedroom', 'office', 'others') NOT NULL,
    
    -- Origin / Pickup Details
    pickup_address TEXT NOT NULL,
    pickup_area VARCHAR(100) NOT NULL, -- e.g. Mirpur 10, Uttara Sector 4
    pickup_floor INT DEFAULT 1,
    pickup_has_lift BOOLEAN DEFAULT FALSE,
    
    -- Destination / Delivery Details
    delivery_address TEXT NOT NULL,
    delivery_area VARCHAR(100) NOT NULL,
    delivery_floor INT DEFAULT 1,
    delivery_has_lift BOOLEAN DEFAULT FALSE,
    
    -- Schedule
    shifting_date DATE NOT NULL,
    preferred_time_slot VARCHAR(50) DEFAULT 'Morning (8 AM - 12 PM)',
    
    -- Vehicle & Staffing Requirements
    vehicle_type ENUM('pickup_7ft', 'covered_9ft', 'covered_14ft', 'customer_managed') DEFAULT 'pickup_7ft',
    labour_count INT DEFAULT 2,
    
    -- Costing & Payment
    estimated_cost DECIMAL(10,2) DEFAULT 0.00,
    final_cost DECIMAL(10,2) NULL,
    advance_amount DECIMAL(10,2) DEFAULT 0.00,
    due_amount DECIMAL(10,2) DEFAULT 0.00,
    payment_status ENUM('unpaid', 'partial_advance', 'paid_in_full') DEFAULT 'unpaid',
    payment_method VARCHAR(50) NULL, -- bKash, Nagad, Cash
    transaction_id VARCHAR(100) NULL,
    
    -- Workflow Status
    status ENUM('pending', 'contacted', 'confirmed', 'team_assigned', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
    assigned_supervisor_name VARCHAR(100) NULL,
    assigned_supervisor_phone VARCHAR(20) NULL,
    driver_name VARCHAR(100) NULL,
    driver_phone VARCHAR(20) NULL,
    driver_truck_number VARCHAR(50) NULL,
    
    special_notes TEXT NULL,
    admin_notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### 2. `shifting_booking_addons` (Ancillary Services)
```sql
CREATE TABLE shifting_booking_addons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id BIGINT UNSIGNED NOT NULL,
    addon_type ENUM('ac_disassembly', 'ac_installation', 'electrician_work', 'carpenter_assembly', 'carton_boxes', 'bubble_wrap', 'fragile_handling') NOT NULL,
    quantity INT DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (booking_id) REFERENCES shifting_bookings(id) ON DELETE CASCADE
);
```

#### 3. `shifting_pricing_rules` (Dynamic Config for Calculator)
* `property_base_rates`: Base fee for Studio, 1 BHK, 2 BHK, 3+ BHK, Office.
* `floor_charge_without_lift`: Surcharge per floor when elevator is absent (e.g. 300 BDT/floor).
* `vehicle_rates`: Base hire charges for 7ft pickup vs 9ft/14ft covered van.
* `addon_service_rates`: AC repair/refit (e.g. 1500–2500 BDT), bubble wrap roll, carton cost.

---

## 4. Feature & UI Component Breakdown

### 1. Front-End User Experience (Customer Journey)
* **Header:**
  - Modern Sticky Glassmorphism Header.
  - Left: Rounded Logo `S H` + `Sohoz Shifting` (সহজ শিফটিং).
  - Center/Nav: `সেবা সমূহ` (Services), `কীভাবে কাজ করে` (How it works), `ট্র্যাকিং` (Track Booking), `FAQ`.
  - Right: High-contrast Gold/Yellow button `বুকিং দিন` (Book Now) linking directly to the booking engine.
* **Hero Section:**
  - Bangla Punchline: *"বাসা বদল হোক চাপমুক্ত, নিশ্চিন্ত ও সহজ"*
  - Subtitle: *"দক্ষ লেবার, নিরাপদ প্যাকিং এবং পিকআপ/কাভার্ড ভ্যান—ঢাকা সহ সারাদেশে বিশ্বস্ত শিফটিং সেবা।"*
  - Quick Booking Trigger Card with Instant Estimate Button.
  - Trust Indicators: ৫,০০০+ সফল শিফটিং, ৯৯.৪% নিরাপদ ডেলিভারি, অভিজ্ঞ টিম।
* **8 Core Service Grid:**
  - Interactive cards with SVG icons, short descriptions, and pricing highlights.
* **Smart Booking Wizard (Multi-Step / Instant Modal):**
  - **Step 1:** Property Type (with the exact dropdown items from Image 2) + Shifting Date.
  - **Step 2:** Origin & Destination (Area, Floor, Lift Yes/No).
  - **Step 3:** Addon selections (AC fitting, Carton boxes, Handyman).
  - **Step 4:** Contact details (Name, Mobile, WhatsApp) + Instant Dynamic Price Estimate.
  - Instant Confirmation Screen with Tracking Code (`SH-2026-XXXX`) + Clickable WhatsApp button to send booking summary directly to admin.
* **Booking Status Tracker:**
  - Simple input field: *"আপনার মোবাইল নাম্বার বা বুকিং কোড দিন"*
  - Visual status stepper: `রিকোয়েস্ট জমা হয়েছে` ➔ `কনফার্মড` ➔ `টিম প্রস্তুত` ➔ `শিফটিং চলছে` ➔ `সম্পন্ন`.
* **Verified Customer Testimonials:**
  - Google Reviews style slider showcasing real reviews, star ratings, and satisfied client stories.
* **FAQ Section:**
  - Accordion implementing the exact questions and clear, professional Bangla answers from Image 1.
* **Sticky Bottom Bar (Mobile View):**
  - Instant Call Now button (`কল করুন`) + Floating WhatsApp button (`● WhatsApp`).

---

### 2. Admin Portal & Operations Dispatch Dashboard
* **Dashboard KPI Cards:**
  - Today's Shifts, Pending Bookings, Active Shifts, Monthly Revenue, Pending Advance Payments.
* **Booking Dispatch Management Table:**
  - Search by Booking Code, Phone Number, Moving Date, or Status.
  - Change status with 1-click dropdown.
  - Assign Driver, Truck Reg. No., and Team Supervisor.
  - Record bKash / Nagad Transaction ID and Advance deposit.
* **Printable Shifting Challan & Invoice:**
  - Clean branded printable A4 format showing customer details, origin/destination floors, inventory notes, and payment summary for field staff and customer signoff.
* **Pricing & Settings Management:**
  - Modify base rates, vehicle prices, and addon charges dynamically from the admin panel without touching code.

---

## 5. Technology Stack & Integration Details

| Layer | Recommended Choice | Rationale |
| :--- | :--- | :--- |
| **Backend Framework** | Laravel 13 (PHP 8.3+) | Already configured in workspace with robust MVC, DB migrations, and artisan tools. |
| **Styling & Design** | Tailwind CSS v4 + Vanilla CSS | Ultra-fast rendering, zero bloat, mobile-first responsive utilities. |
| **Frontend Interactivity** | Alpine.js or Lightweight Vanilla JS | Perfect for dynamic cost calculator, floor/lift toggles, and multi-step wizard. |
| **Admin Permissions** | `spatie/laravel-permission` | Already installed in `composer.json` for Role-Based Access Control (Admin, Operator, Manager). |
| **Notifications** | Local SMS Gateway API + WhatsApp Deep Links | Instant SMS to customer on booking creation; 1-click WhatsApp message dispatch. |
| **Database** | MySQL (Laragon) | High performance for relational booking records and queries. |

---

## 6. Implementation Roadmap & Milestones

### Milestone Deliverables:
1. **Sprint 1 (Database & Models):** Create migration files for `shifting_bookings`, `shifting_booking_addons`, and pricing configurations.
2. **Sprint 2 (Frontend & Booking Wizard):** Build the high-converting landing page matching the client's prototype color scheme, responsive navbar, booking modal with Image 2 dropdown, and accordion FAQ from Image 1.
3. **Sprint 3 (Admin Dispatch & Challan):** Implement backend admin controllers to review submissions, assign moving staff, update payment status, and print PDF work orders.
4. **Sprint 4 (Tracking & Polish):** Deploy booking status lookup tool, WhatsApp automation link generation, and SEO meta tags for Dhaka shifting queries.
