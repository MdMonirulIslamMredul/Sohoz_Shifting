# THE TOLET Clone — System Analysis & Master Development Plan
**Target Benchmark Platform:** [thetolet.com/bd](https://www.thetolet.com/bd)  
**Project:** `Tolet_Rent_flat` (Laravel 11/12 on Laragon)  
**Document Version:** 1.0.0  
**Prepared For:** Client Delivery & Architectural Reference

---

## 1. Executive Summary & Platform Overview

**THE TOLET** (`thetolet.com/bd`) is Bangladesh's leading broker-free property rental ecosystem. It eliminates traditional "broker/dalal" friction by offering an on-demand, tech-driven platform connecting tenants, property owners (landlords), and freelance rental scouts.

### Platform Triangle Model
```
┌────────────────────────────────────────────────────────────────────────┐
│                        THE TOLET PLATFORM                              │
├─────────────────────┬──────────────────────────┬───────────────────────┤
│ 1. PROPERTY PORTAL  │ 2. ORDER HOME CONCIERGE  │ 3. EARN MONEY NETWORK │
│ - Search & Filter   │ - Custom requirement form│ - Community job board │
│ - Categorized To-Let│ - Matched by scouts/team │ - Bounty per rental   │
│ - Verified Listings │ - Hassle-free search     │ - Field executive ops │
├─────────────────────┴──────────────────────────┴───────────────────────┤
│                   4. MONETIZATION & CREDIT ENGINE                      │
│ - Free tier listings  - Paid credits for extra ads & featured bumps    │
│ - Credit-based Landlord Phone Unlock (prevents spam, cuts out brokers) │
│ - bKash / Nagad / Rocket payment integration                           │
└────────────────────────────────────────────────────────────────────────┘
```

---

## 2. Core Feature Breakdown of `thetolet.com/bd`

### A. Rental Categories & Localized Classification
1. **Family Flat:** Full residential units (2–4 Bed, Drawing, Dining, Balcony, Titas/LPG).
2. **Bachelor Flat / Room / Seat:** Independent rooms or shared bed-spaces tailored for working professionals and university students.
3. **Sublet:** Single rooms within an occupied family flat with shared kitchen/utilities.
4. **Office / Commercial Space:** Floors, units, showrooms, and shared co-working setups.
5. **Hostel / Mess:** Dedicated hostels charging per seat per month.
6. **Shop:** Commercial retail spaces.

### B. The Contact Unlock & Credit Economy (Monetization Engine)
- **Landlord Privacy & Broker Protection:** Landlord phone numbers are masked by default.
- **Micro-Payment Model:** Tenants spend credits (or 20–50 BDT micro-payments) to reveal verified landlord phone numbers and direct WhatsApp links.
- **Paid Listing Tiers:** Landlords receive 1–2 free listings; subsequent ads or urgent "Featured Bumps" require credit packs purchased via bKash/Nagad.

### C. "Order Home" Concierge & "Earn Money" Job Feed
- **Order Home (For Tenants):** When a tenant cannot find a suitable property, they submit requirements (budget, area, move-in month, bed/bath count).
- **Job Feed (For Freelance Scouts):** These requests are published as tasks on the **"Earn Money"** board. Local scouts match the tenant with an unlisted flat and earn a fixed commission/bounty (e.g., 500 BDT per confirmed deal).

### D. 3-Tier Bangladesh Geo-Hierarchy
```
Division (e.g., Dhaka)
└── District (e.g., Dhaka)
    └── Area / Thana (e.g., Mirpur, Mohammadpur, Uttara, Dhanmondi, Vatara, Badda)
        └── Sub-Area / Neighborhood (e.g., Mirpur 11, Future Town Housing, Sector 3)
```

---

## 3. Database Schema Blueprint

### Table Specifications

#### 1. Locations
* `divisions`: `id`, `name`, `bn_name`, `slug`, `created_at`
* `districts`: `id`, `division_id`, `name`, `bn_name`, `slug`, `created_at`
* `areas`: `id`, `district_id`, `name`, `bn_name`, `slug`, `is_popular`, `created_at`

#### 2. Properties
* `properties`:
  * `id`, `uuid`, `property_code` (e.g. `TL-184952`)
  * `user_id` (Landlord / Poster)
  * `title`, `slug`, `description`
  * `category` (`family`, `bachelor`, `sublet`, `office`, `hostel`, `shop`)
  * `property_type` (`flat`, `room`, `seat`, `floor`, `commercial`)
  * `division_id`, `district_id`, `area_id`, `address`, `sub_area_landmark`, `google_map_pin`
  * `rent_amount` (decimal), `is_negotiable` (boolean)
  * `service_charge` (decimal), `advance_deposit` (decimal)
  * `available_from_month` (date), `is_immediately_available` (boolean)
  * `bedrooms`, `bathrooms`, `balconies`, `floor_level`, `total_floors`, `size_sqft`
  * `gas_type` (`pipeline_titas`, `cylinder_lpg`, `none`)
  * `lift`, `generator`, `parking`, `cctv_security`, `security_guard` (booleans)
  * `tenant_preference` (`any`, `family_only`, `job_holder_male`, `student_male`, `female_only`)
  * `contact_person_name`, `contact_number`, `whatsapp_number`
  * `status` (`pending`, `approved`, `rented_out`, `rejected`, `expired`)
  * `is_featured`, `featured_expires_at`, `view_count`, `unlock_count`
  * `timestamps`, `softDeletes`

#### 3. Property Images
* `property_images`: `id`, `property_id`, `image_path`, `thumbnail_path`, `is_primary`, `sort_order`, `timestamps`

#### 4. Order Homes & Job Feeds
* `order_homes`: `id`, `user_id`, `division_id`, `district_id`, `area_id`, `category`, `budget_min`, `budget_max`, `rooms_needed`, `move_in_month`, `special_requirements`, `status` (`pending`, `assigned`, `fulfilled`, `cancelled`), `timestamps`
* `job_feeds`: `id`, `order_home_id`, `bounty_amount` (default: 500 BDT), `assigned_agent_id`, `status` (`available`, `claimed`, `completed`, `paid`), `timestamps`

#### 5. Credits, Unlocks & Transactions
* `user_wallets`: `id`, `user_id`, `balance_credits`, `bonus_credits`, `timestamps`
* `credit_packages`: `id`, `package_name`, `credits_count`, `price_bdt`, `is_popular`, `is_active`
* `property_unlocks`: `id`, `user_id`, `property_id`, `credits_spent`, `unlocked_at`
* `payments`: `id`, `user_id`, `gateway` (`bkash`, `nagad`, `manual`), `transaction_id`, `amount_bdt`, `status` (`pending`, `approved`, `rejected`), `timestamps`
* `saved_properties`: `id`, `user_id`, `property_id`, `timestamps`

---

## 4. Master Development Plan (7-Phase Roadmap)

### **Phase 1: Database Architecture & Geo-Taxonomy**
- [ ] Create migrations for `divisions`, `districts`, `areas`.
- [ ] Seed Bangladesh geography (8 divisions, 64 districts, and core rental hubs: Mirpur, Mohammadpur, Uttara, Dhanmondi, Vatara, Banasree, Badda, Gulshan, etc.).
- [ ] Create `properties`, `property_images`, `property_unlocks`, and `saved_properties` tables.
- [ ] Create `order_homes`, `job_feeds`, `user_wallets`, and `credit_packages` tables.

### **Phase 2: Authentication & Multi-Role User System**
- [ ] Implement user roles using Spatie: `Admin`, `Landlord`, `Tenant`, `Agent`.
- [ ] Mobile-first authentication with phone number verification and profile settings.
- [ ] Wallet initialization upon registration with trial credits (e.g. 10 free credits).

### **Phase 3: Property Submission & Management Engine**
- [ ] Multi-step "Post Property" wizard matching `thetolet.com`:
  - Step 1: Location & Category.
  - Step 2: Rent details, utility breakdown, availability month.
  - Step 3: Bed, bath, balcony, floor, gas type, amenities badges.
  - Step 4: Multi-image file uploader with drag-and-drop, client-side preview & WebP compression.
  - Step 5: Verified contact numbers (phone & WhatsApp).
- [ ] Landlord Property Management Dashboard (Active, Rented, Draft, In-Review).
- [ ] One-click "Mark as Rented" toggle.

### **Phase 4: Frontend Portal & Search Experience**
- [ ] Modern responsive homepage with:
  - Sticky hero search bar with Division/Area + Category pill tabs.
  - Availability month filters (Immediate, November, December).
  - Quick budget slider & room count chips.
- [ ] Reusable Property Listing Card with:
  - Move-in month banner, category badge, and area highlight.
  - Bed / Bath / Rent / Gas specs.
  - Bookmark heart icon (AJAX/Fetch).
- [ ] Property Details page with:
  - High-res photo gallery with thumbnail switcher.
  - Complete amenities checklist with custom visual icons.
  - **"Unlock Landlord Contact"** modal with credit deduction prompt.
  - Direct WhatsApp & Click-to-Call buttons (once unlocked).
  - Safety tips & "Report Property" modal.

### **Phase 5: Monetization & Payment Gateways**
- [ ] Credit package store (Buy credits using bKash, Nagad, Rocket).
- [ ] Payment processing:
  - Direct automated checkout via bKash Tokenized API / SSLCommerz.
  - Manual bKash/Nagad merchant TrxID submission for manual admin approval.
- [ ] Contact number unlock logic:
  - Free preview -> click Unlock -> deduct credits -> permanently show number to this user.

### **Phase 6: "Order Home" Concierge & "Earn Money" Job Feed**
- [ ] Public **Order Home** submission form for seekers.
- [ ] **Job Feed** portal for registered freelance agents/scouts:
  - Feed list: e.g. "2 Room For Bachelor - Budget: 14,000 BDT - Area: Mirpur 11 - Earn 500 BDT".
  - Agent claim & submission workflow.
  - Payout disbursement module.

### **Phase 7: Admin Control Center & Programmatic SEO**
- [ ] Moderation dashboard: Approve/reject pending listings with rejection reasons.
- [ ] Transaction & payment validation queue.
- [ ] Programmatic SEO URL structure identical to `thetolet.com`:
  - `/property-listing` (Full filter directory)
  - `/property-division/{division}`
  - `/property-area/{division}/{district}/{area}`
  - `/property/category/{category}`
  - Structured RealEstateListing schema markup on all detail pages.
- [ ] Articles/Blog & Testimonials CMS.

---

## 5. Technology Stack & Architectural Decisions

| Layer | Recommended Choice | Rationale |
| :--- | :--- | :--- |
| **Framework** | **Laravel 11/12** | Already configured in your workspace; robust validation, queues, and security. |
| **Styling** | **TailwindCSS** | Clean, mobile-first utility styling matching `thetolet.com`'s modern card layout. |
| **Interactivity** | **Alpine.js / Vanilla JS** | Super lightweight, reactive filters, image galleries, and modals without heavy SPA overhead. |
| **Database** | **MySQL (Laragon)** | Relational data integrity for transactions, bookings, and location hierarchies. |
| **Image Processing** | **Intervention Image** | Automatic WebP conversion, thumbnail generation, and brand watermarking. |
| **Payments** | **bKash & Nagad Gateways** | Industry-standard payment methods in Bangladesh. |
