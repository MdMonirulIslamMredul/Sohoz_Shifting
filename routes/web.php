<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\AboutController as AdminAboutController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\CounterController as AdminCounterController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\HomepageCarouselController;
use App\Http\Controllers\Admin\ContactSocialController;
use App\Http\Controllers\Admin\PageContentController;
use App\Http\Controllers\Admin\ProductCategoryController as AdminProductCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductSubcategoryController as AdminProductSubcategoryController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamMemberController as AdminTeamMemberController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Frontend\UserDashboardController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\TeamController;
use App\Http\Controllers\Admin\PartnerController as AdminPartnerController;
use App\Http\Controllers\Admin\PropertyTypeController as AdminPropertyTypeController;
use App\Http\Controllers\Admin\ShiftingBookingController as AdminShiftingBookingController;
use App\Http\Controllers\Admin\SystemToolController;
use App\Http\Controllers\Frontend\PartnerController;
use App\Http\Controllers\Frontend\ShiftingBookingController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/booking', [ShiftingBookingController::class, 'create'])->name('bookings.create');
Route::post('/booking', [ShiftingBookingController::class, 'store'])->name('bookings.store');
Route::get('/booking/confirmation/{code}', [ShiftingBookingController::class, 'confirmation'])->name('bookings.confirmation');
Route::get('/booking/track', [ShiftingBookingController::class, 'track'])->name('bookings.track');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/team', [TeamController::class, 'index'])->name('team.index');
Route::get('/partners', [PartnerController::class, 'index'])->name('partners.index');
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{blog:slug}', [BlogController::class, 'show'])->name('blogs.show');
Route::get('/gallery/photos', [GalleryController::class, 'photos'])->name('gallery.photos');
Route::get('/gallery/videos', [GalleryController::class, 'videos'])->name('gallery.videos');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.submit');
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/profile', [AdminAuthController::class, 'showProfile'])->name('profile');
        Route::get('/password', [AdminAuthController::class, 'showPasswordForm'])->name('password.edit');
        Route::post('/password', [AdminAuthController::class, 'updatePassword'])->name('password.update');

        Route::get('/page-content', fn () => redirect()->route('admin.about.edit'))->name('page-content.edit');
        Route::post('/page-content', fn () => redirect()->route('admin.about.edit'))->name('page-content.update');
        Route::get('/contact-social', [ContactSocialController::class, 'edit'])->name('contact-social.edit')->middleware('permission:Web_Settings');
        Route::post('/contact-social', [ContactSocialController::class, 'update'])->name('contact-social.update')->middleware('permission:Web_Settings');

        Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->middleware('permission:manage users');
        Route::resource('roles', RoleController::class)->except(['show'])->middleware('permission:manage roles');
        Route::resource('permissions', PermissionController::class)->except(['show'])->middleware('permission:manage permissions');

        Route::resource('homepage-carousel-images', HomepageCarouselController::class)->only([
            'index',
            'store',
            'update',
            'destroy',
        ])->middleware('permission:Web_Settings');
        Route::resource('counters', AdminCounterController::class)->only([
            'index',
            'store',
            'update',
            'destroy',
        ])->middleware('permission:Web_Settings');
        Route::resource('testimonials', AdminTestimonialController::class)->only([
            'index',
            'store',
            'update',
            'destroy',
        ])->middleware('permission:Web_Settings');
        Route::resource('faqs', AdminFaqController::class)->only([
            'index',
            'store',
            'update',
            'destroy',
        ])->middleware('permission:Web_Settings');

        Route::resource('team-members', AdminTeamMemberController::class)->except(['show'])->middleware('permission:Web_Settings');
        Route::resource('partners', AdminPartnerController::class)->except(['show'])->middleware('permission:Web_Settings');
        Route::resource('blogs', AdminBlogController::class)->except(['show'])->middleware('permission:Web_Settings');
        Route::resource('galleries', AdminGalleryController::class)->except(['show'])->middleware('permission:Web_Settings');
        Route::get('/about', [AdminAboutController::class, 'edit'])->name('about.edit')->middleware('permission:Web_Settings');
        Route::post('/about', [AdminAboutController::class, 'update'])->name('about.update')->middleware('permission:Web_Settings');
        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit')->middleware('permission:Web_Settings');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update')->middleware('permission:Web_Settings');


        Route::resource('products', AdminProductController::class)->except(['show'])->middleware('permission:manage products');
        Route::resource('product-categories', AdminProductCategoryController::class)->except(['show'])->middleware('permission:manage products');
        Route::resource('product-subcategories', AdminProductSubcategoryController::class)->except(['show'])->middleware('permission:manage products');
        Route::resource('services', AdminServiceController::class)->except(['show'])->middleware('permission:manage services');
        Route::resource('property-types', AdminPropertyTypeController::class)->except(['show']);
        Route::get('shifting-bookings/{shifting_booking}/print-challan', [AdminShiftingBookingController::class, 'printChallan'])->name('shifting-bookings.print-challan');
        Route::resource('shifting-bookings', AdminShiftingBookingController::class);

        Route::prefix('system-tools')->name('system-tools.')->middleware('permission:Web_Settings')->group(function () {
            Route::get('/', [SystemToolController::class, 'index'])->name('index');
            Route::post('/clear-cache', [SystemToolController::class, 'clearCache'])->name('clear-cache');
            Route::post('/optimize', [SystemToolController::class, 'optimize'])->name('optimize');
            Route::post('/run-migrations', [SystemToolController::class, 'runMigrations'])->name('run-migrations');
            Route::post('/link-storage', [SystemToolController::class, 'linkStorage'])->name('link-storage');
        });

        // Contact Messages
        Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
        Route::patch('/contacts/{contact}/toggle-read', [AdminContactController::class, 'toggleRead'])->name('contacts.toggle-read');
        Route::patch('/contacts/{contact}/toggle-connected', [AdminContactController::class, 'toggleConnected'])->name('contacts.toggle-connected');
        Route::put('/contacts/{contact}/notes', [AdminContactController::class, 'updateNotes'])->name('contacts.update-notes');
        Route::post('/contacts/mark-all-read', [AdminContactController::class, 'markAllRead'])->name('contacts.mark-all-read');
        Route::delete('/contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');
    });
});

// Emergency Web Setup Route (Runs migrations, storage link & cache clear via browser on hosting without terminal)
Route::get('/system/setup', [SystemToolController::class, 'emergencySetup'])->name('system.setup');

// Automatic Fallback for Uploaded Files (solves 404s when symlink is missing on shared hosting without terminal)
Route::get('/storage/{path}', function (string $path) {
    $cleanPath = str_replace(['..', "\0"], '', $path);
    $cleanPath = ltrim($cleanPath, '/\\');

    $disk = Storage::disk('public');
    if (! $disk->exists($cleanPath)) {
        abort(404);
    }

    $fullPath = $disk->path($cleanPath);
    $mimeType = File::mimeType($fullPath) ?: 'application/octet-stream';

    return response()->file($fullPath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*')->name('storage.fallback');

