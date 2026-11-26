<?php

use App\Http\Controllers\Admin\CampaignCategoryController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\CampaignUpdateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DonationController;
use App\Http\Controllers\Admin\DonorController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\HeroBannerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StaticPageController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\PublicDonationController;
use App\Http\Controllers\Api\MidtransWebhookController;
use App\Support\AdminSite;
use App\Support\PublicSite;
use Illuminate\Support\Facades\Route;

Route::post('/api/midtrans/notification', [MidtransWebhookController::class, 'handle'])->name('api.midtrans.notification');

Route::get('/', function () {
    return view('pages.home', [
        'site' => PublicSite::site('Beranda', 'Halaman utama platform donasi dengan hero campaign, filter program, dan blok trust publik.'),
        'navigation' => PublicSite::navigation(),
        'aboutPage' => PublicSite::page('about'),
        'heroSlides' => PublicSite::heroSlides(),
        'filters' => PublicSite::filters(),
        'campaigns' => PublicSite::campaigns()->take(9),
        'socialLinks' => PublicSite::socials(),
        'impact' => PublicSite::impact(),
        'latestDonations' => PublicSite::latestDonations(),
        'faqs' => PublicSite::faqItems(),
        'testimonials' => PublicSite::testimonials(),
    ]);
})->name('home');

Route::get('/campaign', function () {
    return view('pages.campaign.index', [
        'site' => PublicSite::site('Campaign', 'Daftar campaign publik lengkap dengan filter status, kategori, dan panel donasi terbaru.'),
        'navigation' => PublicSite::navigation(),
        'filters' => PublicSite::filters(),
        'campaigns' => PublicSite::campaigns(),
        'socialLinks' => PublicSite::socials(),
        'latestDonations' => PublicSite::latestDonations(),
    ]);
})->name('campaign.index');

Route::get('/campaign/{slug}/donate', [PublicDonationController::class, 'create'])->name('donation.create');
Route::post('/campaign/{slug}/donate', [PublicDonationController::class, 'store'])->name('donation.store');
Route::get('/donation/check', [PublicDonationController::class, 'lookup'])->name('donation.lookup');
Route::get('/donation/{reference}', [PublicDonationController::class, 'show'])->name('donation.show');
Route::get('/donation/{reference}/invoice', [PublicDonationController::class, 'invoice'])->name('donation.invoice');

Route::get('/campaign/{slug}', function (string $slug) {
    $campaign = PublicSite::campaign($slug);

    abort_unless($campaign, 404);

    return view('pages.campaign.show', [
        'site' => PublicSite::site($campaign['title'], $campaign['summary']),
        'navigation' => PublicSite::navigation(),
        'campaign' => $campaign,
        'socialLinks' => PublicSite::socials(),
        'latestDonations' => PublicSite::latestDonations($slug),
        'prayerMessages' => PublicSite::prayerMessages($slug),
        'relatedCampaigns' => PublicSite::relatedCampaigns($slug),
    ]);
})->name('campaign.show');

Route::get('/pages/{slug}', function (string $slug) {
    $page = PublicSite::page($slug);

    abort_unless($page, 404);

    return view('pages.static.show', [
        'site' => PublicSite::site($page['title'], $page['intro']),
        'navigation' => PublicSite::navigation(),
        'page' => $page,
        'socialLinks' => PublicSite::socials(),
    ]);
})->name('pages.show');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'create'])->name('login');
        Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns');
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');

    Route::get('/categories', [CampaignCategoryController::class, 'index'])->name('categories');
    Route::get('/categories/create', [CampaignCategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CampaignCategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CampaignCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CampaignCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CampaignCategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/updates', [CampaignUpdateController::class, 'index'])->name('updates');
    Route::get('/updates/create', [CampaignUpdateController::class, 'create'])->name('updates.create');
    Route::post('/updates', [CampaignUpdateController::class, 'store'])->name('updates.store');
    Route::get('/updates/{update}/edit', [CampaignUpdateController::class, 'edit'])->name('updates.edit');
    Route::put('/updates/{update}', [CampaignUpdateController::class, 'update'])->name('updates.update');
    Route::delete('/updates/{update}', [CampaignUpdateController::class, 'destroy'])->name('updates.destroy');

    Route::get('/donations', [DonationController::class, 'index'])->name('donations');
    Route::post('/donations/manual', [DonationController::class, 'storeManual'])->name('donations.manual.store');
    Route::get('/donations/export', [DonationController::class, 'export'])->name('donations.export');
    Route::post('/ai-analyst/ask', [\App\Http\Controllers\Admin\AiAnalystController::class, 'ask'])
        ->middleware('throttle:15,1')
        ->name('ai-analyst.ask');
    Route::get('/donations/{donation}', [DonationController::class, 'show'])->name('donations.show');
    Route::patch('/donations/{donation}/status', [DonationController::class, 'updateStatus'])->name('donations.status');
    Route::get('/donors', [DonorController::class, 'index'])->name('donors');
    
    Route::get('/wa-reply', [\App\Http\Controllers\Admin\WaReplyController::class, 'index'])->name('wa-reply.index');
    Route::get('/wa-templates', [\App\Http\Controllers\Admin\WaReplyController::class, 'templates'])->name('wa-templates.index');
    Route::post('/wa-templates', [\App\Http\Controllers\Admin\WaReplyController::class, 'storeTemplate'])->name('wa-templates.store');
    Route::patch('/wa-templates/{template}', [\App\Http\Controllers\Admin\WaReplyController::class, 'updateTemplate'])->name('wa-templates.update');
    Route::delete('/wa-templates/{template}', [\App\Http\Controllers\Admin\WaReplyController::class, 'destroyTemplate'])->name('wa-templates.destroy');

    Route::get('/banners', [HeroBannerController::class, 'index'])->name('banners');
    Route::get('/banners/create', [HeroBannerController::class, 'create'])->name('banners.create');
    Route::post('/banners', [HeroBannerController::class, 'store'])->name('banners.store');
    Route::get('/banners/{banner}/edit', [HeroBannerController::class, 'edit'])->name('banners.edit');
    Route::put('/banners/{banner}', [HeroBannerController::class, 'update'])->name('banners.update');
    Route::delete('/banners/{banner}', [HeroBannerController::class, 'destroy'])->name('banners.destroy');

    Route::get('/faqs', [FaqController::class, 'index'])->name('faqs');
    Route::get('/faqs/create', [FaqController::class, 'create'])->name('faqs.create');
    Route::post('/faqs', [FaqController::class, 'store'])->name('faqs.store');
    Route::get('/faqs/{faq}/edit', [FaqController::class, 'edit'])->name('faqs.edit');
    Route::put('/faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');

    Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials');
    Route::get('/testimonials/create', [TestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
    Route::get('/testimonials/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('testimonials.edit');
    Route::put('/testimonials/{testimonial}', [TestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

    Route::get('/pages', [StaticPageController::class, 'index'])->name('pages');
    Route::get('/pages/create', [StaticPageController::class, 'create'])->name('pages.create');
    Route::post('/pages', [StaticPageController::class, 'store'])->name('pages.store');
    Route::get('/pages/{page}/edit', [StaticPageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{page}', [StaticPageController::class, 'update'])->name('pages.update');
    Route::delete('/pages/{page}', [StaticPageController::class, 'destroy'])->name('pages.destroy');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::put('/settings/save', [SettingController::class, 'save'])->name('settings.save');
    Route::get('/settings/create', [SettingController::class, 'create'])->name('settings.create');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::get('/settings/{setting}/edit', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings/{setting}', [SettingController::class, 'update'])->name('settings.update');
    Route::delete('/settings/{setting}', [SettingController::class, 'destroy'])->name('settings.destroy');
        Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    });
});
