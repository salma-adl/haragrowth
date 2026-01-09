<?php

use App\Http\Controllers\BlogCommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ImageSettingController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MetricController;
use App\Http\Controllers\ParameterSettingController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\TestimonialController;
use App\Models\Booking;
use App\Models\ContactInformation;

// Rute default
Route::get('/', function () {
    return redirect('/'); // Redirect ke dashboard admin
});

// Rute untuk Blog tanpa autentikasi
Route::get('/api/blogs', [BlogController::class, 'index']); // Mendapatkan semua blog
Route::get('/api/blogs/{id}', [BlogController::class, 'show']); // Mendapatkan blog berdasarkan ID
Route::post('/api/blogs', [BlogController::class, 'store']); // Menambahkan blog baru
Route::get('/api/blogs/adjacent/{id}', [BlogController::class, 'showAdjacentBlogs']); // Menambahkan blog baru
Route::get('/api/blogs/tag/{tagId}', [BlogController::class, 'getBlogsByTag']); // Menambahkan blog baru
Route::get('/api/blogs/category/{categoryId}', [BlogController::class, 'getBlogsByCategory']); // Menambahkan blog baru
Route::get('/api/blogs/latest/{total}', [BlogController::class, 'getLatestBlogs']); // Menambahkan blog baru
Route::get('/api/blogs/detail-image/{id}', [BlogController::class, 'showImage']); // Menambahkan blog baru

Route::get('/api/menus/{type}', [MenuController::class, 'index']); // Mendapatkan menu berdasarkan type

Route::get('/api/faqs/{name}', [FaqController::class, 'show']); // Mendapatkan menu berdasarkan type

Route::get('/api/metrics/{id}', [MetricController::class, 'show']); // Mendapatkan menu berdasarkan type

Route::get('/api/compliances/{id}', [ComplianceController::class, 'show']); // Mendapatkan menu berdasarkan type
Route::get('/api/compliances', [ComplianceController::class, 'index']); // Mendapatkan menu berdasarkan type

Route::get('/api/social-media', [SocialMediaController::class, 'index']); // Mendapatkan menu berdasarkan type

Route::get('/api/testimonial', [TestimonialController::class, 'index']); // Mendapatkan menu berdasarkan type

// Route::get('/api/contact-information', [ContactInformationController::class, 'index']); // Mendapatkan menu berdasarkan type

Route::get('/api/parameter-settings', [ParameterSettingController::class, 'index']); // Mendapatkan menu berdasarkan type
Route::get('/api/parameter-settings/type/{type}', [ParameterSettingController::class, 'indexType']); // Mendapatkan menu berdasarkan type
Route::get('/api/parameter-settings/{key}', [ParameterSettingController::class, 'show']); // Mendapatkan menu berdasarkan type

Route::get('/api/image-settings/type/{type}', [ImageSettingController::class, 'index']);
Route::get('/api/image-settings/{key}', [ImageSettingController::class, 'show']);

Route::get('/api/image-settings/industry/logo', [ImageSettingController::class, 'indexLogo']);

Route::post('/api/customer-support', [CustomerController::class, 'store']); // Menambahkan komentar blog baru

Route::get('/api/route-settings/{routeName}', [RouteController::class, 'index']);

Route::get('/api/industry', [IndustryController::class, 'index']);

// Route::post('/api/customer-booking', [BookingController::class, 'store']);

Route::get('/api/schedule-service', [ServiceController::class, 'index']);

Route::get('/', [CompanyProfileController::class, 'home']);

Route::get('/get-schedules/{service_id}', [CompanyProfileController::class, 'getSchedules']);

// Route::post('/buat-janji', [CompanyProfileController::class, 'submitJanji'])->name('buat.janji');

Route::post('/appointment', [BookingController::class, 'store'])->name('appointment.store');

Route::get('/psychologists/{serviceId}', [CompanyProfileController::class, 'getTherapis']);

Route::get('/therapist/{id}', [CompanyProfileController::class, 'getTherapisByid']);

Route::get('/book/{booking_code}', function ($booking_code) {
    $booking = Booking::where('booking_code', $booking_code)->first();

    if (!$booking) {
        abort(404, 'Booking not found');
    }

    return view('company-profile.book', compact('booking'));
});
