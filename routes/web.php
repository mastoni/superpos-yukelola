<?php

use App\Actions\Installer\LinkPublicStorage;
use App\Http\Controllers\Admin\AccountMappingController;
use App\Http\Controllers\Admin\AgedReceivablesReportController;
use App\Http\Controllers\Admin\BackupSettingsController;
use App\Http\Controllers\Admin\BalanceSheetReportController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\BrandImportController;
use App\Http\Controllers\Admin\BrandingSettingsController;
use App\Http\Controllers\Admin\BulkBrandLogoController;
use App\Http\Controllers\Admin\BulkBrandLogoUrlController;
use App\Http\Controllers\Admin\BulkProductImageController;
use App\Http\Controllers\Admin\BulkProductImageUrlController;
use App\Http\Controllers\Admin\CashFlowReportController;
use App\Http\Controllers\Admin\CashierSettingsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CategoryImportController;
use App\Http\Controllers\Admin\ChannelOrderController;
use App\Http\Controllers\Admin\ChartOfAccountsController;
use App\Http\Controllers\Admin\CommerceConnectionController;
use App\Http\Controllers\Admin\CompanyProfileController;
use App\Http\Controllers\Admin\CurrencySettingsController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CustomerGroupController;
use App\Http\Controllers\Admin\CustomerImportController;
use App\Http\Controllers\Admin\CustomerLoyaltyController;
use App\Http\Controllers\Admin\CustomerPaymentController;
use App\Http\Controllers\Admin\CustomerStatementController;
use App\Http\Controllers\Admin\CustomScriptSettingsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DaySummaryController;
use App\Http\Controllers\Admin\DiscountsReportController;
use App\Http\Controllers\Admin\DrugScheduleController;
use App\Http\Controllers\Admin\EbookLeadExportController;
use App\Http\Controllers\Admin\EmailSettingsController;
use App\Http\Controllers\Admin\ExpenseCategoryController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\FiscalPeriodController;
use App\Http\Controllers\Admin\GeneralLedgerReportController;
use App\Http\Controllers\Admin\HardwareController;
use App\Http\Controllers\Admin\JournalEntryController;
use App\Http\Controllers\Admin\KioskOrderController;
use App\Http\Controllers\Admin\LabelController;
use App\Http\Controllers\Admin\LegalPagesSettingsController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\LocationSettingsController;
use App\Http\Controllers\Admin\LowStockReportController;
use App\Http\Controllers\Admin\LoyaltyPointsReportController;
use App\Http\Controllers\Admin\LoyaltySettingsController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\NumberFormatController;
use App\Http\Controllers\Admin\OpeningBalanceController;
use App\Http\Controllers\Admin\OversoldReportController;
use App\Http\Controllers\Admin\PaymentGatewaysSettingsController;
use App\Http\Controllers\Admin\PaymentMethodsSettingsController;
use App\Http\Controllers\Admin\PluginController;
use App\Http\Controllers\Admin\PricingSettingsController;
use App\Http\Controllers\Admin\PrintController;
use App\Http\Controllers\Admin\ProductBatchController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImportController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProfitAndLossReportController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\PurchaseReturnController;
use App\Http\Controllers\Admin\QuotationController;
use App\Http\Controllers\Admin\ReceiptSettingsController;
use App\Http\Controllers\Admin\RegionalSettingsController;
use App\Http\Controllers\Admin\ReportsHubController;
use App\Http\Controllers\Admin\RestoreController;
use App\Http\Controllers\Admin\ReturnReasonController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\SaleCreateController;
use App\Http\Controllers\Admin\SaleReturnGatewayRefundController;
use App\Http\Controllers\Admin\SalesByCashierReportController;
use App\Http\Controllers\Admin\SalesByCategoryReportController;
use App\Http\Controllers\Admin\SalesByPaymentMethodReportController;
use App\Http\Controllers\Admin\SalesByProductReportController;
use App\Http\Controllers\Admin\SalesSummaryReportController;
use App\Http\Controllers\Admin\SavedReportController;
use App\Http\Controllers\Admin\ScaleSettingsController;
use App\Http\Controllers\Admin\ScheduledReportController;
use App\Http\Controllers\Admin\SchedulerSettingsController;
use App\Http\Controllers\Admin\SecuritySettingsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\ShiftsByCashierReportController;
use App\Http\Controllers\Admin\ShiftVarianceReasonController;
use App\Http\Controllers\Admin\StockAdjustmentController;
use App\Http\Controllers\Admin\StockAdjustmentImportController;
use App\Http\Controllers\Admin\StockAdjustmentReasonController;
use App\Http\Controllers\Admin\StockLevelController;
use App\Http\Controllers\Admin\StockMovementController;
use App\Http\Controllers\Admin\StockTakeController;
use App\Http\Controllers\Admin\StockTakeImportController;
use App\Http\Controllers\Admin\StockTransferController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\StoreSwitchController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\SupplierPaymentController;
use App\Http\Controllers\Admin\SyncLogController;
use App\Http\Controllers\Admin\SystemHealthController;
use App\Http\Controllers\Admin\TaxClassificationController;
use App\Http\Controllers\Admin\TaxComponentController;
use App\Http\Controllers\Admin\TaxGroupController;
use App\Http\Controllers\Admin\TaxSettingsController;
use App\Http\Controllers\Admin\TerminalController;
use App\Http\Controllers\Admin\TopCustomersReportController;
use App\Http\Controllers\Admin\TopSuppliersReportController;
use App\Http\Controllers\Admin\TrialBalanceReportController;
use App\Http\Controllers\Admin\UnitCategoryController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UpdateController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WhatsAppLogController;
use App\Http\Controllers\Admin\WhatsAppSendController;
use App\Http\Controllers\Admin\WhatsAppSettingsController;
use App\Http\Controllers\Auth\DemoLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Cashier\CustomerDisplayController;
use App\Http\Controllers\Cashier\DiscountApprovalController;
use App\Http\Controllers\Cashier\GatewayController;
use App\Http\Controllers\Cashier\PaymentSessionController;
use App\Http\Controllers\Cashier\RefundController;
use App\Http\Controllers\CommerceWebhookController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\EbookDownloadController;
use App\Http\Controllers\EbookLeadPublicExportController;
use App\Http\Controllers\Kiosk\KioskCheckoutController;
use App\Http\Controllers\Kiosk\KioskController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LegalPageController;
use App\Http\Controllers\Pay\CustomerPayController;
use App\Http\Controllers\PublicQuotationController;
use App\Http\Controllers\PublicReceiptController;
use App\Http\Controllers\PwaManifestController;
use App\Http\Controllers\Webhooks\FlutterwaveWebhookController;
use App\Http\Controllers\Webhooks\MercadoPagoWebhookController;
use App\Http\Controllers\Webhooks\PaystackWebhookController;
use App\Http\Controllers\Webhooks\RazorpayWebhookController;
use App\Http\Controllers\Webhooks\StripeWebhookController;
use App\Http\Controllers\WhatsAppWebhookController;
use App\Http\Middleware\ReserveCashierTerminal;
use App\Models\Company;
use App\Support\InstallState;
use Illuminate\Support\Facades\Route;

// NOTE: the developer / maintenance routes (/migrate, /seed, /clear-cache,
// /phpinfo, /debug-refund, /simulate-gateway-refund,
// /demo-snapshot, /demo-reset) live in routes/dev.php, which bootstrap/app.php
// loads ONLY when POS_DEV_ROUTES=true. They must never be registered on a
// customer install — /migrate?fresh=1 is an unauthenticated database wipe.
// The old /diag/* Paystack + QR self-tests were deleted; one of them printed
// part of the merchant's live gateway secret key.

// Manual storage repair for customer installs, including shared hosting.
// Available without developer routes; only the signed-in owner can run it.
Route::get('/storage-link', function (LinkPublicStorage $link) {
    abort_unless(auth()->user()?->is_super_admin, 403);
    abort_if(pos_is_demo(), 403, __('settings.demo.action_locked'));

    $result = $link();

    return response(
        $result['ok']
            ? __('maintenance.storage_link_success')
            : __('maintenance.storage_link_failed'),
        $result['ok'] ? 200 : 500,
    )->header('Content-Type', 'text/plain; charset=UTF-8')
        ->header('Cache-Control', 'no-store, private');
})->middleware('auth')->name('maintenance.storage-link');

// Root: if in demo mode, show marketing & promo landing page.
// Otherwise, an authenticated user goes to the dashboard and guests to login.
Route::get('/', function () {
    if (pos_is_demo()) {
        return app(LandingController::class)->index();
    }

    return auth()->check()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('login');
})->middleware('ensure.installed')->name('landing');

// Direct promo / landing page route (always accessible)
Route::get('/promo', [LandingController::class, 'index'])
    ->middleware('ensure.installed')
    ->name('promo');

// 1-Click Interactive Demo Login for prospective users
Route::get('/demo-login/{role}', [DemoLoginController::class, 'login'])
    ->middleware('ensure.installed')
    ->name('demo.login');

// Direct APK Downloads for YUKelola Mobile
Route::get('/download/superpos.apk', function () {
    $path = public_path('download/superpos.apk');
    if (! file_exists($path)) {
        abort(404, 'File APK belum tersedia.');
    }

    return response()->download($path, 'YUKelola-v2.0-release.apk', [
        'Content-Type' => 'application/vnd.android.package-archive',
    ]);
})->name('download.apk');

Route::get('/download/superpos-armv7.apk', function () {
    $path = public_path('download/superpos-armv7.apk');
    if (! file_exists($path)) {
        abort(404, 'File APK belum tersedia.');
    }

    return response()->download($path, 'YUKelola-v2.0-armv7.apk', [
        'Content-Type' => 'application/vnd.android.package-archive',
    ]);
})->name('download.apk.armv7');

// Lead-gated ebook download for the public demo landing page. The controller
// refuses requests when demo mode is off, validates the visitor details, and
// only serves the private PDF after their lead has been recorded.
Route::post('/free-ebook/download', EbookDownloadController::class)
    ->middleware(['ensure.installed', 'throttle:10,1'])
    ->name('landing.ebook.download');

// Shareable marketing-team export. The 64-character token is the credential;
// invalid or unconfigured tokens deliberately look like a missing page.
Route::get('/marketing/ebook-leads/{token}/download', EbookLeadPublicExportController::class)
    ->middleware(['ensure.installed', 'throttle:10,1'])
    ->where('token', '[a-fA-F0-9]{64}')
    ->name('marketing.ebook-leads.download');

// User documentation — a static, self-contained HTML/Tailwind manual living
// in public/documentation/. Sub-pages (e.g. /documentation/cashier.html) and
// assets are served directly as static files by the web server; this route
// only needs to map the clean /documentation URL onto the index file. Public
// and always available, even before install, so customers can read the guide.
Route::get('/documentation', function () {
    return response()->file(public_path('documentation/index.html'), [
        'Cache-Control' => 'no-cache, must-revalidate',
    ]);
})->name('documentation');

// URL-cron fallback for hosts with no shell cron (or an external pinger like
// cron-job.org). Equivalent to the `schedule:run` crontab line. 404s unless the
// operator explicitly minted a token in Settings → Scheduler, so a default
// install exposes nothing. Throttled hard — it's unauthenticated by necessity,
// and the controller additionally refuses to run more than once a minute.
Route::get('/cron/{token}', CronController::class)
    ->middleware(['ensure.installed', 'throttle:20,1'])
    ->name('cron.run');

// Signed sales-channel webhooks. Provider HMAC is the authentication; the
// controller persists and acknowledges before any expensive processing.
Route::post('/webhooks/commerce/{provider}/{connection}', CommerceWebhookController::class)
    ->middleware(['ensure.installed', 'throttle:120,1'])
    ->where('provider', 'woocommerce|shopify')
    ->name('webhooks.commerce');

// PWA web app manifest. Rendered from the company row (name, brand colour,
// uploaded app logo) so an installed desktop/mobile app carries the store's own
// branding rather than the generic placeholder that the old static
// public/manifest.json shipped. Public + auth-free: the browser fetches the
// manifest without credentials, and the install prompt can fire on the login
// page. Safe before install — the controller rescues a missing company row.
Route::get('/manifest.json', PwaManifestController::class)->name('pwa.manifest');

// Branding feed for the static docs — lets the guide show the customer's own
// uploaded app logo + name in its header/footer. The DB is only touched once
// the app is installed (guarded by the install lock) so this stays instant and
// safe before install; the docs fall back to a bundled placeholder otherwise.
Route::get('/documentation-brand', function () {
    $name = config('app.name');
    if (! $name || strtolower($name) === 'laravel') {
        $name = 'POS';
    }
    $logo = $logoDark = null;
    try {
        if (InstallState::isLocked()) {
            $company = Company::query()->first();
            if ($company) {
                $logo = $company->app_logo_url ?: $company->logo_url;
                $logoDark = $company->app_logo_dark_url ?: $logo;
            }
        }
    } catch (Throwable) {
        // DB unavailable — fall through to the placeholder.
    }

    return response()->json([
        'name' => $name,
        'logo' => $logo,
        'logoDark' => $logoDark,
    ])->header('Cache-Control', 'no-store');
})->name('documentation.brand');

// Public legal pages — payment gateways (Razorpay/Paystack/etc.) want
// these URLs during onboarding, and customers should be able to read
// them without logging in. 404 when the merchant hasn't authored them.
Route::middleware('ensure.installed')->group(function () {
    Route::get('/privacy-policy', [LegalPageController::class, 'privacy'])->name('legal.privacy');
    Route::get('/terms', [LegalPageController::class, 'terms'])->name('legal.terms');

    // Confirm a parked email change (SEC #10). Unauthenticated on purpose —
    // the link is opened from the mail we sent to the NEW address, which may
    // well be read on a phone with no session, and the one-time token IS the
    // credential. Throttled because the token is guessable in principle.
    Route::get('/admin/profile/email/confirm/{user}/{token}',
        [ProfileController::class, 'confirmEmail'])
        ->middleware('throttle:10,1')
        ->name('admin.profile.email.confirm');
});

// Auth ─────────────────────────────────────────────────────────────
Route::middleware(['ensure.installed', 'guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'show'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'attempt'])
        ->name('login.attempt');

    // Password reset — Laravel's broker handles tokens + expiry; the
    // outgoing mail uses whatever SMTP the merchant configured in
    // Settings → Email (applied by ApplyCompanySettings middleware).
    Route::get('/password/forgot', [PasswordResetController::class, 'showLinkRequest'])
        ->name('password.request');
    Route::post('/password/forgot', [PasswordResetController::class, 'sendLink'])
        ->middleware('throttle:5,1')   // 5 send attempts per minute per IP — anti-spam
        ->name('password.email');
    Route::get('/password/reset/{token}', [PasswordResetController::class, 'showReset'])
        ->name('password.reset');
    Route::post('/password/reset', [PasswordResetController::class, 'reset'])
        ->name('password.update');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Admin shell (auth-gated). Dashboard view is a stub; widgets, KPIs,
// and charts will be built when the underlying features come online.
Route::middleware(['ensure.installed', 'auth', 'store.selected', 'set.locale'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/chart-range', [DashboardController::class, 'chartRange'])->name('dashboard.chart-range');
    Route::get('dashboard/catalog-range', [DashboardController::class, 'catalogRange'])->name('dashboard.catalog-range');
    Route::post('dashboard/dismiss-setup', [DashboardController::class, 'dismissSetup'])->name('dashboard.dismiss-setup');
    Route::post('dashboard/restore-setup', [DashboardController::class, 'restoreSetup'])->name('dashboard.restore-setup');

    // Private marketing leads captured by the landing-page ebook form.
    Route::get('marketing/ebook-leads.csv', EbookLeadExportController::class)
        ->name('marketing.ebook-leads.export');

    // End-of-day quick summary — topbar popover (JSON), see DaySummaryController.
    Route::get('day-summary', [DaySummaryController::class, 'index'])->name('day-summary');

    // Profile & preferences — the signed-in user's own account (topbar menu).
    // Self-service: no permission gate.
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    // "Sign out my other devices" — password re-entry, see SEC #40.
    Route::post('profile/sessions/revoke', [ProfileController::class, 'revokeOtherSessions'])->name('profile.sessions.revoke');

    // Notifications — the topbar bell dropdown (own notifications only).
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');

    // Categories — list with off-canvas edit/add drawer + drag-to-reorder.
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/export', [CategoryController::class, 'export'])->name('categories.export');
    // CSV / XLSX import wizard — literal `import` sub-paths declared BEFORE
    // the `categories/{category}` wildcards so they aren't captured by it.
    Route::get('categories/import', [CategoryImportController::class, 'index'])->name('categories.import');
    Route::get('categories/import/template', [CategoryImportController::class, 'template'])->name('categories.import.template');
    Route::post('categories/import/preview', [CategoryImportController::class, 'preview'])->name('categories.import.preview');
    Route::post('categories/import/commit', [CategoryImportController::class, 'commit'])->name('categories.import.commit');
    Route::get('categories/search', [CategoryController::class, 'search'])->name('categories.search');
    Route::get('categories/search-replacement', [CategoryController::class, 'searchForReplacement'])->name('categories.search-replacement');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
    Route::patch('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::get('categories/{category}/delete-info', [CategoryController::class, 'deleteInfo'])->name('categories.delete-info');
    Route::get('categories/{category}/deactivate-check', [CategoryController::class, 'deactivateCheck'])->name('categories.deactivate-check');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Brands — flat list with side editor (same shape as Categories minus the tree).
    // CSV / XLSX import wizard — two steps, mirroring categories/products.
    // Logos are named in the sheet and uploaded afterwards via bulk-logos.
    Route::get('brands/import', [BrandImportController::class, 'index'])->name('brands.import');
    Route::get('brands/import/template', [BrandImportController::class, 'template'])->name('brands.import.template');
    Route::post('brands/import/preview', [BrandImportController::class, 'preview'])->name('brands.import.preview');
    Route::post('brands/import/commit', [BrandImportController::class, 'commit'])->name('brands.import.commit');
    // Drop a folder of logo files; each matches the brand that declared it.
    Route::get('brands/bulk-logos', [BulkBrandLogoController::class, 'index'])->name('brands.bulk-logos');
    Route::post('brands/bulk-logos/match', [BulkBrandLogoController::class, 'match'])->name('brands.bulk-logos.match');
    Route::post('brands/bulk-logos/store', [BulkBrandLogoController::class, 'store'])->name('brands.bulk-logos.store');
    // …or give us links and the server downloads them. Second tab of the same
    // screen; mirrors products/bulk-images/url-*.
    Route::post('brands/bulk-logos/url-match', [BulkBrandLogoUrlController::class, 'match'])->name('brands.bulk-logos.url-match');
    Route::post('brands/bulk-logos/url-fetch', [BulkBrandLogoUrlController::class, 'fetch'])->name('brands.bulk-logos.url-fetch');

    Route::get('brands', [BrandController::class, 'index'])->name('brands.index');
    Route::get('brands/rows', [BrandController::class, 'rows'])->name('brands.rows');
    Route::get('brands/export', [BrandController::class, 'export'])->name('brands.export');
    Route::get('brands/search', [BrandController::class, 'search'])->name('brands.search');
    Route::get('brands/search-replacement', [BrandController::class, 'searchForReplacement'])->name('brands.search-replacement');
    Route::post('brands', [BrandController::class, 'store'])->name('brands.store');
    Route::patch('brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
    Route::get('brands/{brand}/delete-info', [BrandController::class, 'deleteInfo'])->name('brands.delete-info');
    Route::get('brands/{brand}/deactivate-check', [BrandController::class, 'deactivateCheck'])->name('brands.deactivate-check');
    Route::delete('brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');

    // Terminals — checkout stations + their hardware config (flat list, side editor).
    Route::get('terminals', [TerminalController::class, 'index'])->name('terminals.index');
    Route::get('terminals/rows', [TerminalController::class, 'rows'])->name('terminals.rows');
    Route::get('terminals/export', [TerminalController::class, 'export'])->name('terminals.export');
    // Dedicated add / edit pages (the editor moved off the index into its
    // own full-width page — the config grew too tall for a side panel).
    Route::get('terminals/create', [TerminalController::class, 'create'])->name('terminals.create');
    Route::get('terminals/{terminal}/edit', [TerminalController::class, 'edit'])->name('terminals.edit');
    Route::post('terminals', [TerminalController::class, 'store'])->name('terminals.store');
    // Async upload for a customer-display attract-media image — returns the
    // stored path + URL; the editor keeps the path and submits it on save.
    Route::post('terminals/cfd-media', [TerminalController::class, 'uploadCfdMedia'])->name('terminals.cfd-media');
    Route::patch('terminals/{terminal}', [TerminalController::class, 'update'])->name('terminals.update');
    Route::delete('terminals/{terminal}', [TerminalController::class, 'destroy'])->name('terminals.destroy');
    // Bind / unbind THIS browser (workstation) to a terminal via the
    // long-lived `pos_terminal_id` cookie that current_terminal() reads.
    Route::post('terminals/clear-selection', [TerminalController::class, 'clearSelection'])->name('terminals.clear-selection');
    Route::post('terminals/{terminal}/select', [TerminalController::class, 'select'])->name('terminals.select');

    // Self-ordering kiosk — staff orders queue (order-mode placements
    // awaiting counter payment). See docs/features/kiosk-self-ordering.md §5.
    Route::get('kiosk-orders', [KioskOrderController::class, 'index'])->name('kiosk-orders.index');
    Route::get('kiosk-orders/rows', [KioskOrderController::class, 'rows'])->name('kiosk-orders.rows');
    // Full kiosk-order history (any status), date-windowed — the two live
    // queues only ever show open work.
    Route::get('kiosk-orders/all-rows', [KioskOrderController::class, 'allRows'])->name('kiosk-orders.all-rows');
    Route::get('kiosk-orders/export', [KioskOrderController::class, 'export'])->name('kiosk-orders.export');
    // Take payment for a pending kiosk order without leaving this page: the
    // panel edits lines, splits tenders (cash/card/QR), then completes.
    Route::post('kiosk-orders/{sale}/pay', [KioskOrderController::class, 'pay'])->name('kiosk-orders.pay');
    Route::post('kiosk-orders/{sale}/reject', [KioskOrderController::class, 'reject'])->name('kiosk-orders.reject');
    // Already paid at the kiosk — staff hand the goods over and clear it.
    Route::post('kiosk-orders/{sale}/collect', [KioskOrderController::class, 'collect'])->name('kiosk-orders.collect');

    // Units — flat list with side editor + base/derived linkage.
    // NOTE: literal sub-paths (export, categories) must come BEFORE the
    // {unit} wildcard or Laravel routes them to UnitController first.
    Route::get('units', [UnitController::class, 'index'])->name('units.index');
    Route::get('units/rows', [UnitController::class, 'rows'])->name('units.rows');
    Route::get('units/export', [UnitController::class, 'export'])->name('units.export');
    Route::post('units', [UnitController::class, 'store'])->name('units.store');

    // Unit Categories — dedicated page for managing measurement categories.
    Route::get('units/categories/export', [UnitCategoryController::class, 'export'])->name('units.categories.export');
    Route::get('units/categories', [UnitCategoryController::class, 'index'])->name('units.categories.index');
    Route::post('units/categories', [UnitCategoryController::class, 'store'])->name('units.categories.store');
    Route::patch('units/categories/{unitCategory}', [UnitCategoryController::class, 'update'])->name('units.categories.update');
    Route::delete('units/categories/{unitCategory}', [UnitCategoryController::class, 'destroy'])->name('units.categories.destroy');
    Route::get('units/categories/{unitCategory}/deactivate-check', [UnitCategoryController::class, 'deactivateCheck'])->name('units.categories.deactivate-check');

    // Units wildcard routes (after all literal paths to avoid shadowing).
    Route::patch('units/{unit}', [UnitController::class, 'update'])->name('units.update');
    Route::get('units/{unit}/deactivate-check', [UnitController::class, 'deactivateCheck'])->name('units.deactivate-check');
    Route::delete('units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');

    // Products — full-page list + dedicated create/edit pages (too many
    // fields for the side-editor pattern used by Brand/Unit/Category).
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
    // JSON search for the remote product picker (kit components, etc.).
    Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
    // Next auto SKU — powers the form's "Generate" button so the admin
    // can see and edit the value before saving.
    Route::get('products/next-sku', [ProductController::class, 'nextSku'])->name('products.next-sku');
    Route::get('products/next-barcode', [ProductController::class, 'nextBarcode'])->name('products.next-barcode');
    // One page of server-paginated rows/cards as an HTML fragment — the
    // Products list fetches this on every page / search / filter / sort.
    Route::get('products/rows', [ProductController::class, 'rows'])->name('products.rows');
    // Bulk-assign / clear the pick-face shelf location at the active store,
    // over ticked rows OR the whole filtered set (see the controller).
    Route::post('products/bulk-location', [ProductController::class, 'bulkLocation'])->name('products.bulk-location');
    // Soft-delete an explicit, bounded selection while retaining products
    // referenced by historical sales.
    Route::post('products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulk-delete');
    // CSV / XLSX import wizard — two steps. `preview` stages the file
    // and returns suggested column mapping; `commit` runs the import.
    Route::get('products/import', [ProductImportController::class, 'index'])->name('products.import');
    Route::get('products/import/template', [ProductImportController::class, 'template'])->name('products.import.template');
    Route::post('products/import/preview', [ProductImportController::class, 'preview'])->name('products.import.preview');
    Route::post('products/import/commit', [ProductImportController::class, 'commit'])->name('products.import.commit');
    // Bulk product images — match declared filenames to products, then upload
    // in batches. Before the products/{product} wildcard.
    Route::get('products/bulk-images', [BulkProductImageController::class, 'index'])->name('products.bulk-images');
    Route::post('products/bulk-images/match', [BulkProductImageController::class, 'match'])->name('products.bulk-images.match');
    Route::post('products/bulk-images/store', [BulkProductImageController::class, 'store'])->name('products.bulk-images.store');
    // …or give us links and the server downloads them. Same screen, second
    // tab; the file-upload path above is untouched and neither disables the
    // other. `url-fetch` runs five URLs per call — the browser loops, so no
    // queue and no cron are involved.
    Route::post('products/bulk-images/url-match', [BulkProductImageUrlController::class, 'match'])->name('products.bulk-images.url-match');
    Route::post('products/bulk-images/url-fetch', [BulkProductImageUrlController::class, 'fetch'])->name('products.bulk-images.url-fetch');
    // Label printer — wizard + print-ready A4 sheet (kept before the
    // products/{product} wildcard so "labels" isn't read as an id).
    Route::get('products/labels', [LabelController::class, 'form'])->name('products.labels');
    Route::post('products/labels/sheet', [LabelController::class, 'sheet'])->name('products.labels.sheet');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::patch('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::post('products/{product}/toggle', [ProductController::class, 'toggle'])->name('products.toggle');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Drug Schedules — pharmacy compliance lookup, fed into the
    // Products → Compliance tab. Same side-editor pattern as Brands.
    Route::get('drug-schedules/export', [DrugScheduleController::class, 'export'])->name('drug-schedules.export');
    Route::get('drug-schedules/search-replacement', [DrugScheduleController::class, 'searchForReplacement'])->name('drug-schedules.search-replacement');
    Route::get('drug-schedules', [DrugScheduleController::class, 'index'])->name('drug-schedules.index');
    Route::post('drug-schedules', [DrugScheduleController::class, 'store'])->name('drug-schedules.store');
    Route::patch('drug-schedules/{drugSchedule}', [DrugScheduleController::class, 'update'])->name('drug-schedules.update');
    Route::get('drug-schedules/{drugSchedule}/delete-info', [DrugScheduleController::class, 'deleteInfo'])->name('drug-schedules.delete-info');
    Route::get('drug-schedules/{drugSchedule}/deactivate-check', [DrugScheduleController::class, 'deactivateCheck'])->name('drug-schedules.deactivate-check');
    Route::delete('drug-schedules/{drugSchedule}', [DrugScheduleController::class, 'destroy'])->name('drug-schedules.destroy');

    // ── Inventory ──
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('levels/export', [StockLevelController::class, 'export'])->name('levels.export');
        Route::get('levels', [StockLevelController::class, 'index'])->name('levels.index');
        Route::get('levels/rows', [StockLevelController::class, 'rows'])->name('levels.rows');
        Route::patch('levels/{stockLevel}/reorder-override', [StockLevelController::class, 'updateReorderOverride'])->name('levels.reorder');
        Route::get('movements', [StockMovementController::class, 'index'])->name('movements.index');
        Route::get('movements/rows', [StockMovementController::class, 'rows'])->name('movements.rows');
        Route::get('low-stock', [LowStockReportController::class, 'index'])->name('low-stock.index');
        Route::get('low-stock/export', [LowStockReportController::class, 'export'])->name('low-stock.export');
        Route::get('oversold', [OversoldReportController::class, 'index'])->name('oversold.index');
        Route::get('oversold/export', [OversoldReportController::class, 'export'])->name('oversold.export');

        // Product batches (Slice 3b) — read-only visibility for owners.
        // CRUD lives in ReceivePurchase + RecordStockMovement.
        Route::get('batches/export', [ProductBatchController::class, 'export'])->name('batches.export');
        Route::get('batches/rows', [ProductBatchController::class, 'rows'])->name('batches.rows');
        Route::get('batches', [ProductBatchController::class, 'index'])->name('batches.index');
        // Archive (soft-delete) a spent batch — the only write path here.
        // Wildcard LAST so `batches/export` + `batches/rows` still resolve.
        Route::delete('batches/{batch}', [ProductBatchController::class, 'destroy'])->name('batches.destroy');
        // Un-archive. Binds by raw id (the controller resolves withTrashed) —
        // implicit binding would 404 on the very rows this acts on.
        Route::post('batches/{batch}/restore', [ProductBatchController::class, 'restore'])->name('batches.restore');

        // Stock takes (cycle counts) — same permission family as
        // adjustments (`products.adjust_stock`); see StockTakePolicy.
        Route::get('stock-takes/export', [StockTakeController::class, 'export'])->name('stock-takes.export');
        Route::get('stock-takes/import', [StockTakeImportController::class, 'index'])->name('stock-takes.import');
        Route::get('stock-takes/import/template', [StockTakeImportController::class, 'template'])->name('stock-takes.import.template');
        Route::post('stock-takes/import/preview', [StockTakeImportController::class, 'preview'])->name('stock-takes.import.preview');
        Route::post('stock-takes/import/commit', [StockTakeImportController::class, 'commit'])->name('stock-takes.import.commit');
        Route::get('stock-takes', [StockTakeController::class, 'index'])->name('stock-takes.index');
        Route::get('stock-takes/create', [StockTakeController::class, 'create'])->name('stock-takes.create');
        Route::post('stock-takes', [StockTakeController::class, 'store'])->name('stock-takes.store');
        // Before the {stockTake} wildcard so "scan" isn't read as an id.
        Route::get('stock-takes/scan', [StockTakeController::class, 'scan'])->middleware('throttle:120,1')->name('stock-takes.scan');
        Route::get('stock-takes/{stockTake}', [StockTakeController::class, 'show'])->name('stock-takes.show');
        Route::get('stock-takes/{stockTake}/edit', [StockTakeController::class, 'edit'])->name('stock-takes.edit');
        // Printable count sheet — grouped by shelf location, in walk order.
        Route::get('stock-takes/{stockTake}/sheet', [StockTakeController::class, 'sheet'])->name('stock-takes.sheet');
        Route::patch('stock-takes/{stockTake}', [StockTakeController::class, 'update'])->name('stock-takes.update');
        Route::post('stock-takes/{stockTake}/post', [StockTakeController::class, 'post'])->name('stock-takes.post');
        Route::delete('stock-takes/{stockTake}', [StockTakeController::class, 'destroy'])->name('stock-takes.destroy');

        Route::get('adjustments/export', [StockAdjustmentController::class, 'export'])->name('adjustments.export');
        Route::get('adjustments/import', [StockAdjustmentImportController::class, 'index'])->name('adjustments.import');
        Route::get('adjustments/import/template', [StockAdjustmentImportController::class, 'template'])->name('adjustments.import.template');
        Route::post('adjustments/import/preview', [StockAdjustmentImportController::class, 'preview'])->name('adjustments.import.preview');
        Route::post('adjustments/import/commit', [StockAdjustmentImportController::class, 'commit'])->name('adjustments.import.commit');
        Route::get('adjustments', [StockAdjustmentController::class, 'index'])->name('adjustments.index');
        Route::get('adjustments/rows', [StockAdjustmentController::class, 'rows'])->name('adjustments.rows');
        Route::get('adjustments/search-products', [StockAdjustmentController::class, 'searchProducts'])->name('adjustments.search-products');
        Route::get('adjustments/scan', [StockAdjustmentController::class, 'scan'])->middleware('throttle:120,1')->name('adjustments.scan');
        Route::get('adjustments/batches', [StockAdjustmentController::class, 'batches'])->name('adjustments.batches');
        Route::post('adjustments/product-stock', [StockAdjustmentController::class, 'productStock'])->name('adjustments.product-stock');
        Route::get('adjustments/create', [StockAdjustmentController::class, 'create'])->name('adjustments.create');
        Route::post('adjustments', [StockAdjustmentController::class, 'store'])->name('adjustments.store');
        Route::get('adjustments/{stockAdjustment}', [StockAdjustmentController::class, 'show'])->name('adjustments.show');
        Route::get('adjustments/{stockAdjustment}/edit', [StockAdjustmentController::class, 'edit'])->name('adjustments.edit');
        Route::patch('adjustments/{stockAdjustment}', [StockAdjustmentController::class, 'update'])->name('adjustments.update');
        Route::post('adjustments/{stockAdjustment}/post', [StockAdjustmentController::class, 'post'])->name('adjustments.post');
        Route::delete('adjustments/{stockAdjustment}', [StockAdjustmentController::class, 'destroy'])->name('adjustments.destroy');

        Route::get('transfers/export', [StockTransferController::class, 'export'])->name('transfers.export');
        Route::get('transfers', [StockTransferController::class, 'index'])->name('transfers.index');
        Route::get('transfers/rows', [StockTransferController::class, 'rows'])->name('transfers.rows');
        Route::get('transfers/search-products', [StockTransferController::class, 'searchProducts'])->name('transfers.search-products');
        Route::get('transfers/scan', [StockTransferController::class, 'scan'])->middleware('throttle:120,1')->name('transfers.scan');
        Route::get('transfers/batches', [StockTransferController::class, 'batches'])->name('transfers.batches');
        Route::post('transfers/product-stock', [StockTransferController::class, 'productStock'])->name('transfers.product-stock');
        Route::get('transfers/create', [StockTransferController::class, 'create'])->name('transfers.create');
        Route::post('transfers', [StockTransferController::class, 'store'])->name('transfers.store');
        Route::get('transfers/{stockTransfer}', [StockTransferController::class, 'show'])->name('transfers.show');
        Route::get('transfers/{stockTransfer}/edit', [StockTransferController::class, 'edit'])->name('transfers.edit');
        Route::patch('transfers/{stockTransfer}', [StockTransferController::class, 'update'])->name('transfers.update');
        Route::post('transfers/{stockTransfer}/dispatch', [StockTransferController::class, 'dispatch'])->name('transfers.dispatch');
        Route::post('transfers/{stockTransfer}/receive', [StockTransferController::class, 'receive'])->name('transfers.receive');
        Route::post('transfers/{stockTransfer}/cancel', [StockTransferController::class, 'cancel'])->name('transfers.cancel');
        Route::delete('transfers/{stockTransfer}', [StockTransferController::class, 'destroy'])->name('transfers.destroy');

        Route::get('adjustment-reasons', [StockAdjustmentReasonController::class, 'index'])->name('adjustment-reasons.index');
        Route::get('adjustment-reasons/rows', [StockAdjustmentReasonController::class, 'rows'])->name('adjustment-reasons.rows');
        Route::post('adjustment-reasons', [StockAdjustmentReasonController::class, 'store'])->name('adjustment-reasons.store');
        Route::patch('adjustment-reasons/{stockAdjustmentReason}', [StockAdjustmentReasonController::class, 'update'])->name('adjustment-reasons.update');
        Route::delete('adjustment-reasons/{stockAdjustmentReason}', [StockAdjustmentReasonController::class, 'destroy'])->name('adjustment-reasons.destroy');
    });

    // ── Customers ──
    Route::get('customers/export', [CustomerController::class, 'export'])->name('customers.export');
    Route::get('customers/import', [CustomerImportController::class, 'index'])->name('customers.import');
    Route::get('customers/import/template', [CustomerImportController::class, 'template'])->name('customers.import.template');
    Route::post('customers/import/preview', [CustomerImportController::class, 'preview'])->name('customers.import.preview');
    Route::post('customers/import/commit', [CustomerImportController::class, 'commit'])->name('customers.import.commit');
    Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::get('customers/rows', [CustomerController::class, 'rows'])->name('customers.rows');
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::patch('customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::patch('customers/{customer}/toggle-active', [CustomerController::class, 'toggleActive'])->name('customers.toggle-active');
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    // Manual loyalty-point adjustment (Loyalty tab) — its own permission.
    Route::post('customers/{customer}/loyalty', [CustomerLoyaltyController::class, 'store'])->name('customers.loyalty.store');

    // Customer groups (sub-setting)
    Route::get('customer-groups/export', [CustomerGroupController::class, 'export'])->name('customer-groups.export');
    Route::get('customer-groups', [CustomerGroupController::class, 'index'])->name('customer-groups.index');
    Route::get('customer-groups/rows', [CustomerGroupController::class, 'rows'])->name('customer-groups.rows');
    Route::post('customer-groups', [CustomerGroupController::class, 'store'])->name('customer-groups.store');
    Route::patch('customer-groups/{customerGroup}', [CustomerGroupController::class, 'update'])->name('customer-groups.update');
    Route::delete('customer-groups/{customerGroup}', [CustomerGroupController::class, 'destroy'])->name('customer-groups.destroy');

    // ── Reports ──
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('', [ReportsHubController::class, '__invoke'])->name('index');
        Route::get('sales', [SalesSummaryReportController::class, 'index'])->name('sales.index');
        Route::get('sales/export', [SalesSummaryReportController::class, 'export'])->name('sales.export');
        Route::get('sales-by-product', [SalesByProductReportController::class, 'index'])->name('sales-by-product.index');
        Route::get('sales-by-product/export', [SalesByProductReportController::class, 'export'])->name('sales-by-product.export');
        Route::get('sales-by-cashier', [SalesByCashierReportController::class, 'index'])->name('sales-by-cashier.index');
        Route::get('sales-by-cashier/export', [SalesByCashierReportController::class, 'export'])->name('sales-by-cashier.export');
        Route::get('sales-by-payment-method', [SalesByPaymentMethodReportController::class, 'index'])->name('sales-by-payment-method.index');
        Route::get('sales-by-payment-method/export', [SalesByPaymentMethodReportController::class, 'export'])->name('sales-by-payment-method.export');
        Route::get('sales-by-category', [SalesByCategoryReportController::class, 'index'])->name('sales-by-category.index');
        Route::get('sales-by-category/export', [SalesByCategoryReportController::class, 'export'])->name('sales-by-category.export');
        Route::get('discounts', [DiscountsReportController::class, 'index'])->name('discounts.index');
        Route::get('discounts/export', [DiscountsReportController::class, 'export'])->name('discounts.export');
        Route::get('top-customers', [TopCustomersReportController::class, 'index'])->name('top-customers.index');
        Route::get('top-customers/export', [TopCustomersReportController::class, 'export'])->name('top-customers.export');
        Route::get('loyalty-points', [LoyaltyPointsReportController::class, 'index'])->name('loyalty-points.index');
        Route::get('loyalty-points/export', [LoyaltyPointsReportController::class, 'export'])->name('loyalty-points.export');
        Route::get('top-suppliers', [TopSuppliersReportController::class, 'index'])->name('top-suppliers.index');
        Route::get('top-suppliers/export', [TopSuppliersReportController::class, 'export'])->name('top-suppliers.export');
        Route::get('shifts-by-cashier', [ShiftsByCashierReportController::class, 'index'])->name('shifts-by-cashier.index');
        Route::get('shifts-by-cashier/export', [ShiftsByCashierReportController::class, 'export'])->name('shifts-by-cashier.export');
        Route::get('aged-receivables', [AgedReceivablesReportController::class, 'index'])->name('aged-receivables.index');
        Route::get('aged-receivables/export', [AgedReceivablesReportController::class, 'export'])->name('aged-receivables.export');

        // Accounting reports (Slice 3) — read-only aggregation of the ledger.
        Route::get('trial-balance', [TrialBalanceReportController::class, 'index'])->name('trial-balance.index');
        Route::get('trial-balance/export', [TrialBalanceReportController::class, 'export'])->name('trial-balance.export');
        Route::get('general-ledger', [GeneralLedgerReportController::class, 'index'])->name('general-ledger.index');
        Route::get('general-ledger/export', [GeneralLedgerReportController::class, 'export'])->name('general-ledger.export');
        Route::get('profit-and-loss', [ProfitAndLossReportController::class, 'index'])->name('profit-and-loss.index');
        Route::get('profit-and-loss/export', [ProfitAndLossReportController::class, 'export'])->name('profit-and-loss.export');
        Route::get('balance-sheet', [BalanceSheetReportController::class, 'index'])->name('balance-sheet.index');
        Route::get('balance-sheet/export', [BalanceSheetReportController::class, 'export'])->name('balance-sheet.export');
        Route::get('cash-flow', [CashFlowReportController::class, 'index'])->name('cash-flow.index');
        Route::get('cash-flow/export', [CashFlowReportController::class, 'export'])->name('cash-flow.export');

        // Saved reports (Slice 5a) — capture a report's current params under a name.
        Route::get('saved', [SavedReportController::class, 'index'])->name('saved.index');
        Route::post('saved', [SavedReportController::class, 'store'])->name('saved.store');
        Route::delete('saved/{savedReport}', [SavedReportController::class, 'destroy'])->name('saved.destroy');

        // Scheduled reports (Slice 5b) — recurring email delivery of a report.
        Route::get('schedules', [ScheduledReportController::class, 'index'])->name('schedules.index');
        Route::post('schedules', [ScheduledReportController::class, 'store'])->name('schedules.store');
        Route::post('schedules/{scheduledReport}/pause', [ScheduledReportController::class, 'pause'])->name('schedules.pause');
        Route::post('schedules/{scheduledReport}/resume', [ScheduledReportController::class, 'resume'])->name('schedules.resume');
        // Throttled (SEC #59): runNow synchronously builds a file and emails
        // every recipient, and the only guard was a client-side busy flag —
        // trivially bypassed by scripting it into a mail flood.
        Route::post('schedules/{scheduledReport}/run-now', [ScheduledReportController::class, 'runNow'])->middleware('throttle:5,1')->name('schedules.run-now');
        Route::delete('schedules/{scheduledReport}', [ScheduledReportController::class, 'destroy'])->name('schedules.destroy');
    });

    // ── Accounting (Slice 8) — journal / day book + manual entries ──
    Route::prefix('accounting')->name('accounting.')->group(function () {
        Route::get('journal', [JournalEntryController::class, 'index'])->name('journal.index');
        Route::get('journal/create', [JournalEntryController::class, 'create'])->name('journal.create');
        Route::post('journal', [JournalEntryController::class, 'store'])->name('journal.store');
        Route::get('journal/{entry}', [JournalEntryController::class, 'show'])->name('journal.show');
        Route::post('journal/{entry}/reverse', [JournalEntryController::class, 'reverse'])->name('journal.reverse');

        // Fiscal years / periods + year-end close (Slice 9).
        Route::get('periods', [FiscalPeriodController::class, 'index'])->name('periods.index');
        Route::post('periods/{period}/lock', [FiscalPeriodController::class, 'lockPeriod'])->name('periods.lock');
        Route::post('periods/{period}/unlock', [FiscalPeriodController::class, 'unlockPeriod'])->name('periods.unlock');
        Route::post('years/{year}/close', [FiscalPeriodController::class, 'closeYear'])->name('years.close');

        // Chart of accounts + business-event mappings (Slice 10).
        Route::get('chart-of-accounts', [ChartOfAccountsController::class, 'index'])->name('chart-of-accounts.index');
        Route::post('chart-of-accounts', [ChartOfAccountsController::class, 'store'])->name('chart-of-accounts.store');
        // Account groups CRUD (Slice 12) — declared before {account} so `groups` isn't captured as an account.
        Route::post('chart-of-accounts/groups', [ChartOfAccountsController::class, 'storeGroup'])->name('chart-of-accounts.groups.store');
        Route::patch('chart-of-accounts/groups/{group}', [ChartOfAccountsController::class, 'updateGroup'])->name('chart-of-accounts.groups.update');
        Route::delete('chart-of-accounts/groups/{group}', [ChartOfAccountsController::class, 'destroyGroup'])->name('chart-of-accounts.groups.destroy');
        Route::patch('chart-of-accounts/{account}', [ChartOfAccountsController::class, 'update'])->name('chart-of-accounts.update');
        Route::delete('chart-of-accounts/{account}', [ChartOfAccountsController::class, 'destroy'])->name('chart-of-accounts.destroy');

        Route::get('mappings', [AccountMappingController::class, 'index'])->name('mappings.index');
        Route::patch('mappings', [AccountMappingController::class, 'update'])->name('mappings.update');

        // Opening balances (migration) — Slice 11.
        Route::get('opening-balances', [OpeningBalanceController::class, 'index'])->name('opening-balances.index');
        Route::post('opening-balances', [OpeningBalanceController::class, 'store'])->name('opening-balances.store');
    });

    // ── Sync log (Slice 4) ──
    Route::get('sync-log', [SyncLogController::class, 'index'])->name('sync-log.index');
    Route::get('sync-log/rows', [SyncLogController::class, 'rows'])->name('sync-log.rows');
    Route::get('sync-log/{syncLog}', [SyncLogController::class, 'show'])->name('sync-log.show');
    // Replay a stuck entry from the back office (the queued payload lives in
    // the terminal's IndexedDB; `sync_logs.payload` is the only server-side
    // copy). Idempotent — CompleteSale dedupes on sales.local_uuid.
    Route::post('sync-log/{syncLog}/retry', [SyncLogController::class, 'retry'])->name('sync-log.retry');
    Route::post('sync-log/{syncLog}/resolve', [SyncLogController::class, 'resolve'])->name('sync-log.resolve');

    // ── Purchases ──
    Route::get('purchases/export', [PurchaseController::class, 'export'])->name('purchases.export');
    Route::get('purchases/rows', [PurchaseController::class, 'rows'])->name('purchases.rows');
    Route::get('purchases/scan', [PurchaseController::class, 'scan'])->middleware('throttle:120,1')->name('purchases.scan');
    Route::get('purchases/prices', [PurchaseController::class, 'prices'])->name('purchases.prices');
    Route::get('purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    Route::get('purchases/{purchase}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::get('purchases/{purchase}/attachment/{attachment}', [PurchaseController::class, 'downloadAttachment'])->name('purchases.attachment.download');
    Route::patch('purchases/{purchase}', [PurchaseController::class, 'update'])->name('purchases.update');
    Route::post('purchases/{purchase}/receive', [PurchaseController::class, 'receive'])->name('purchases.receive');
    Route::post('purchases/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel');
    Route::delete('purchases/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');

    // ── Purchase returns ──
    Route::get('purchase-returns', [PurchaseReturnController::class, 'index'])->name('purchase-returns.index');
    Route::get('purchase-returns/rows', [PurchaseReturnController::class, 'rows'])->name('purchase-returns.rows');
    Route::get('purchase-returns/{purchaseReturn}', [PurchaseReturnController::class, 'show'])->name('purchase-returns.show');
    Route::get('purchases/{purchase}/returns/create', [PurchaseReturnController::class, 'create'])->name('purchase-returns.create');
    Route::post('purchases/{purchase}/returns', [PurchaseReturnController::class, 'store'])->name('purchase-returns.store');

    // ── Supplier payments ──
    Route::get('supplier-payments', [SupplierPaymentController::class, 'index'])->name('supplier-payments.index');
    Route::get('supplier-payments/rows', [SupplierPaymentController::class, 'rows'])->name('supplier-payments.rows');
    Route::get('supplier-payments/create', [SupplierPaymentController::class, 'create'])->name('supplier-payments.create');
    Route::post('supplier-payments', [SupplierPaymentController::class, 'store'])->name('supplier-payments.store');
    Route::get('supplier-payments/{supplierPayment}', [SupplierPaymentController::class, 'show'])->name('supplier-payments.show');
    Route::post('supplier-payments/{supplierPayment}/void', [SupplierPaymentController::class, 'void'])->name('supplier-payments.void');
    Route::get('suppliers/{supplier}/open-purchases', [SupplierPaymentController::class, 'openPurchases'])->name('suppliers.open-purchases');

    // Customer payments (Slice 6b) — mirror of supplier-payments. One
    // payment can settle multiple open sales for a customer; the form's
    // Auto-allocate spreads a typed total across oldest sales FIFO.
    Route::get('customer-payments', [CustomerPaymentController::class, 'index'])->name('customer-payments.index');
    Route::get('customer-payments/rows', [CustomerPaymentController::class, 'rows'])->name('customer-payments.rows');
    Route::get('customer-payments/create', [CustomerPaymentController::class, 'create'])->name('customer-payments.create');
    Route::post('customer-payments', [CustomerPaymentController::class, 'store'])->name('customer-payments.store');
    Route::get('customer-payments/{customerPayment}', [CustomerPaymentController::class, 'show'])->name('customer-payments.show');
    Route::get('customers/{customer}/open-sales', [CustomerPaymentController::class, 'openSales'])->name('customers.open-sales');

    // Customer statement — print-ready event log + running balance.
    Route::get('customers/{customer}/statement', [CustomerStatementController::class, 'show'])->name('customers.statement.show');
    Route::get('customers/{customer}/statement/print', [CustomerStatementController::class, 'print'])->name('customers.statement.print');
    Route::post('customers/{customer}/statement/email', [CustomerStatementController::class, 'email'])->name('customers.statement.email');

    // ── Suppliers ──
    Route::get('suppliers/export', [SupplierController::class, 'export'])->name('suppliers.export');
    Route::get('suppliers/rows', [SupplierController::class, 'rows'])->name('suppliers.rows');
    Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::get('suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::patch('suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::patch('suppliers/{supplier}/toggle-active', [SupplierController::class, 'toggleActive'])->name('suppliers.toggle-active');
    Route::delete('suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    // Expenses — the drawer's cash-out side (Register & Cash Mgmt, Slice E).
    Route::get('expenses/export', [ExpenseController::class, 'export'])->name('expenses.export');
    Route::get('expenses/rows', [ExpenseController::class, 'rows'])->name('expenses.rows');
    Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::patch('expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Expense categories — manageable picklist for the expense form.
    Route::get('expense-categories', [ExpenseCategoryController::class, 'index'])->name('expense-categories.index');
    Route::post('expense-categories', [ExpenseCategoryController::class, 'store'])->name('expense-categories.store');
    Route::patch('expense-categories/{expenseCategory}', [ExpenseCategoryController::class, 'update'])->name('expense-categories.update');
    Route::delete('expense-categories/{expenseCategory}', [ExpenseCategoryController::class, 'destroy'])->name('expense-categories.destroy');

    // Stores — multi-store CRUD + active-store switcher. Switching sets
    // session('active_store_id'); the StoreScoped trait reads it.
    Route::get('stores/export', [StoreController::class, 'export'])->name('stores.export');
    Route::get('stores', [StoreController::class, 'index'])->name('stores.index');
    Route::get('stores/create', [StoreController::class, 'create'])->name('stores.create');
    Route::post('stores', [StoreController::class, 'store'])->name('stores.store');
    Route::get('stores/{store}/edit', [StoreController::class, 'edit'])->name('stores.edit');
    Route::patch('stores/{store}', [StoreController::class, 'update'])->name('stores.update');
    Route::patch('stores/{store}/toggle', [StoreController::class, 'toggle'])->name('stores.toggle');
    Route::patch('stores/{store}/default', [StoreController::class, 'setDefault'])->name('stores.default');
    Route::delete('stores/{store}', [StoreController::class, 'destroy'])->name('stores.destroy');
    Route::post('stores/{store}/switch', StoreSwitchController::class)->name('stores.switch');

    // Users — account management + per-store role assignment.
    Route::get('users/export', [UserController::class, 'export'])->name('users.export');
    Route::get('users/rows', [UserController::class, 'rows'])->name('users.rows');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    // Admin force-logout — ends every session a user holds (SEC #40).
    Route::post('users/{user}/force-logout', [UserController::class, 'forceLogout'])->name('users.force-logout');

    // Roles — permission builder. Gated by the `roles.manage` permission.
    Route::get('roles/export', [RoleController::class, 'export'])->name('roles.export');
    Route::get('roles/rows', [RoleController::class, 'rows'])->name('roles.rows');
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::patch('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Sales channels â€” provider-neutral WooCommerce / Shopify connections.
    Route::get('commerce/connections', [CommerceConnectionController::class, 'index'])->name('commerce.connections.index');
    Route::get('commerce/connections/create', [CommerceConnectionController::class, 'create'])->name('commerce.connections.create');
    Route::post('commerce/connections', [CommerceConnectionController::class, 'store'])->name('commerce.connections.store');
    Route::get('commerce/connections/{connection}/edit', [CommerceConnectionController::class, 'edit'])->name('commerce.connections.edit');
    Route::patch('commerce/connections/{connection}', [CommerceConnectionController::class, 'update'])->name('commerce.connections.update');
    Route::post('commerce/connections/{connection}/test', [CommerceConnectionController::class, 'test'])->name('commerce.connections.test');
    Route::post('commerce/connections/{connection}/sync', [CommerceConnectionController::class, 'sync'])->name('commerce.connections.sync');
    Route::put('commerce/connections/{connection}/mappings', [CommerceConnectionController::class, 'mappings'])->name('commerce.connections.mappings');
    Route::delete('commerce/connections/{connection}', [CommerceConnectionController::class, 'destroy'])->name('commerce.connections.destroy');
    Route::get('commerce/orders', [ChannelOrderController::class, 'index'])->name('commerce.orders.index');
    Route::get('commerce/orders/{order}', [ChannelOrderController::class, 'show'])->name('commerce.orders.show');
    Route::post('commerce/orders/{order}/retry-allocation', [ChannelOrderController::class, 'retryAllocation'])->name('commerce.orders.retry-allocation');
    Route::post('commerce/orders/{order}/allocations/{allocation}/fulfill', [ChannelOrderController::class, 'fulfill'])->name('commerce.orders.allocations.fulfill');
    Route::post('commerce/orders/{order}/allocations/{allocation}/retry-sync', [ChannelOrderController::class, 'retryFulfillmentSync'])->name('commerce.orders.allocations.retry-sync');

    // Settings — hub + per-group editors. Currency is the first group;
    // taxes / receipts / stores / users land here as they ship.
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('settings/plugins', [PluginController::class, 'index'])->name('settings.plugins.index');
    Route::post('settings/plugins', [PluginController::class, 'install'])->name('settings.plugins.install');
    Route::post('settings/plugins/{plugin}/enable', [PluginController::class, 'enable'])->name('settings.plugins.enable');
    Route::post('settings/plugins/{plugin}/disable', [PluginController::class, 'disable'])->name('settings.plugins.disable');
    Route::delete('settings/plugins/{plugin}', [PluginController::class, 'destroy'])->name('settings.plugins.destroy');
    Route::get('settings/company', [CompanyProfileController::class, 'edit'])->name('settings.company.edit');
    Route::patch('settings/company', [CompanyProfileController::class, 'update'])->name('settings.company.update');
    Route::get('settings/payment-gateways', [PaymentGatewaysSettingsController::class, 'edit'])->name('settings.payment-gateways.edit');
    Route::patch('settings/payment-gateways', [PaymentGatewaysSettingsController::class, 'update'])->name('settings.payment-gateways.update');

    // Manual payment methods (Cash, Card, UPI, Bank Transfer, Cheque, and
    // admin-created offline methods): list/create + per-row settings.
    Route::get('settings/payment-methods', [PaymentMethodsSettingsController::class, 'edit'])->name('settings.payment-methods.edit');
    Route::post('settings/payment-methods', [PaymentMethodsSettingsController::class, 'store'])->name('settings.payment-methods.store');
    Route::patch('settings/payment-methods/{method}/toggle', [PaymentMethodsSettingsController::class, 'toggle'])->name('settings.payment-methods.toggle');
    Route::patch('settings/payment-methods/{method}/upi', [PaymentMethodsSettingsController::class, 'updateUpi'])->name('settings.payment-methods.upi');
    Route::get('settings/branding', [BrandingSettingsController::class, 'edit'])->name('settings.branding.edit');
    Route::patch('settings/branding', [BrandingSettingsController::class, 'update'])->name('settings.branding.update');
    Route::get('settings/legal-pages', [LegalPagesSettingsController::class, 'edit'])->name('settings.legal-pages.edit');
    Route::patch('settings/legal-pages', [LegalPagesSettingsController::class, 'update'])->name('settings.legal-pages.update');
    Route::get('settings/regional', [RegionalSettingsController::class, 'edit'])->name('settings.regional.edit');
    Route::patch('settings/regional', [RegionalSettingsController::class, 'update'])->name('settings.regional.update');
    Route::get('settings/currency', [CurrencySettingsController::class, 'edit'])->name('settings.currency.edit');
    Route::patch('settings/currency', [CurrencySettingsController::class, 'update'])->name('settings.currency.update');

    // Settings → Tax (Slice 1 — components + groups CRUD). Resolver +
    // exemptions + reverse-charge + country seeders land in later slices.
    Route::get('settings/tax', [TaxSettingsController::class, 'index'])->name('settings.tax.index');
    Route::get('settings/tax/components/export', [TaxComponentController::class, 'export'])->name('settings.tax.components.export');
    Route::get('settings/tax/components/list', [TaxComponentController::class, 'list'])->name('settings.tax.components.list');
    Route::get('settings/tax/components', [TaxComponentController::class, 'index'])->name('settings.tax.components.index');
    Route::post('settings/tax/components', [TaxComponentController::class, 'store'])->name('settings.tax.components.store');
    Route::patch('settings/tax/components/{taxComponent}', [TaxComponentController::class, 'update'])->name('settings.tax.components.update');
    Route::delete('settings/tax/components/{taxComponent}', [TaxComponentController::class, 'destroy'])->name('settings.tax.components.destroy');
    Route::get('settings/tax/components/{taxComponent}/deactivate-check', [TaxComponentController::class, 'deactivateCheck'])->name('settings.tax.components.deactivate-check');
    Route::get('settings/tax/groups/export', [TaxGroupController::class, 'export'])->name('settings.tax.groups.export');
    Route::get('settings/tax/groups', [TaxGroupController::class, 'index'])->name('settings.tax.groups.index');
    Route::get('settings/tax/groups/search-replacement', [TaxGroupController::class, 'searchForReplacement'])->name('settings.tax.groups.search-replacement');
    Route::get('settings/tax/groups/{taxGroup}/delete-info', [TaxGroupController::class, 'deleteInfo'])->name('settings.tax.groups.delete-info');
    Route::get('settings/tax/groups/{taxGroup}/deactivate-check', [TaxGroupController::class, 'deactivateCheck'])->name('settings.tax.groups.deactivate-check');
    Route::post('settings/tax/groups', [TaxGroupController::class, 'store'])->name('settings.tax.groups.store');
    Route::patch('settings/tax/groups/{taxGroup}', [TaxGroupController::class, 'update'])->name('settings.tax.groups.update');
    Route::delete('settings/tax/groups/{taxGroup}', [TaxGroupController::class, 'destroy'])->name('settings.tax.groups.destroy');

    // Tax Classifications — literal paths must precede any future wildcards.
    Route::get('settings/tax/classifications/export', [TaxClassificationController::class, 'export'])->name('settings.tax.classifications.export');
    Route::get('settings/tax/classifications', [TaxClassificationController::class, 'index'])->name('settings.tax.classifications.index');
    Route::post('settings/tax/classifications', [TaxClassificationController::class, 'store'])->name('settings.tax.classifications.store');
    Route::patch('settings/tax/classifications/{taxClassification}', [TaxClassificationController::class, 'update'])->name('settings.tax.classifications.update');
    Route::delete('settings/tax/classifications/{taxClassification}', [TaxClassificationController::class, 'destroy'])->name('settings.tax.classifications.destroy');
    Route::get('settings/tax/classifications/{taxClassification}/deactivate-check', [TaxClassificationController::class, 'deactivateCheck'])->name('settings.tax.classifications.deactivate-check');

    Route::get('settings/receipt', [ReceiptSettingsController::class, 'edit'])->name('settings.receipt.edit');
    Route::patch('settings/receipt', [ReceiptSettingsController::class, 'update'])->name('settings.receipt.update');

    // Hardware diagnostics — peripheral status board + test actions.
    Route::get('settings/hardware', [HardwareController::class, 'index'])->name('settings.hardware');
    Route::post('settings/hardware/test-print', [HardwareController::class, 'testPrint'])->name('settings.hardware.test-print');

    // Scheduler (cron job) — setup guide + live "is my cron actually running?"
    // status. `status` is the poll target for the Test button; it's hit every
    // few seconds during a test, so it stays a tiny read-only JSON endpoint.
    Route::get('settings/scheduler', [SchedulerSettingsController::class, 'index'])->name('settings.scheduler.index');
    Route::get('settings/scheduler/status', [SchedulerSettingsController::class, 'status'])->name('settings.scheduler.status');
    Route::post('settings/scheduler/token', [SchedulerSettingsController::class, 'generateToken'])->name('settings.scheduler.token.generate');
    Route::delete('settings/scheduler/token', [SchedulerSettingsController::class, 'revokeToken'])->name('settings.scheduler.token.revoke');

    // System Health — read-only diagnostics board (config, DB, storage, jobs, PHP).
    Route::get('settings/system-health', [SystemHealthController::class, 'index'])->name('settings.system-health');
    Route::post('settings/system-health/clear-sample-data', [SystemHealthController::class, 'clearSampleData'])->name('settings.system-health.clear-sample-data');
    // Dry run without `apply`, writes with it — see SystemHealthController.
    Route::post('settings/system-health/backfill-costs', [SystemHealthController::class, 'backfillCosts'])->name('settings.system-health.backfill-costs');
    Route::get('settings/cashier', [CashierSettingsController::class, 'edit'])->name('settings.cashier.edit');
    Route::patch('settings/cashier', [CashierSettingsController::class, 'update'])->name('settings.cashier.update');

    // Weighing-scale barcode template (Settings → Scale).
    Route::get('settings/scale', [ScaleSettingsController::class, 'edit'])->name('settings.scale.edit');
    Route::patch('settings/scale', [ScaleSettingsController::class, 'update'])->name('settings.scale.update');

    // Settings → Security. Currently just the concurrent-login policy (SEC #40).
    Route::get('settings/security', [SecuritySettingsController::class, 'edit'])->name('settings.security.edit');
    Route::patch('settings/security', [SecuritySettingsController::class, 'update'])->name('settings.security.update');

    // Owner-trusted tracking snippets. Super-admin-only in the controller;
    // current-password confirmation is required for every save.
    Route::get('settings/custom-scripts', [CustomScriptSettingsController::class, 'edit'])->name('settings.custom-scripts.edit');
    Route::patch('settings/custom-scripts', [CustomScriptSettingsController::class, 'update'])->name('settings.custom-scripts.update');

    // Shelf-location fields (Settings → Stock locations) — which of
    // aisle / rack / shelf / bin this company uses, and their labels.
    Route::get('settings/locations', [LocationSettingsController::class, 'edit'])->name('settings.locations.edit');
    Route::patch('settings/locations', [LocationSettingsController::class, 'update'])->name('settings.locations.update');

    // Loyalty program (Settings → Loyalty) — earn/redeem rates, thresholds, expiry.
    Route::get('settings/loyalty', [LoyaltySettingsController::class, 'edit'])->name('settings.loyalty.edit');
    Route::patch('settings/loyalty', [LoyaltySettingsController::class, 'update'])->name('settings.loyalty.update');

    // License status (Settings → License) — view + on-demand re-check.
    Route::get('settings/license', [LicenseController::class, 'index'])->name('settings.license.index');
    Route::post('settings/license/recheck', [LicenseController::class, 'recheck'])->middleware('throttle:5,1')->name('settings.license.recheck');
    Route::post('settings/license/deregister', [LicenseController::class, 'deregister'])->middleware('throttle:5,1')->name('settings.license.deregister');
    Route::post('settings/license/activate', [LicenseController::class, 'activate'])->middleware('throttle:5,1')->name('settings.license.activate');

    // Pricing settings — auto-apply markup on purchase receive. Single
    // toggle today; more pricing knobs (rounding rules, default markup)
    // can land here later without breaking the URL surface.
    Route::get('settings/pricing', [PricingSettingsController::class, 'edit'])->name('settings.pricing.edit');
    Route::patch('settings/pricing', [PricingSettingsController::class, 'update'])->name('settings.pricing.update');
    Route::get('settings/numbering', [NumberFormatController::class, 'edit'])->name('settings.numbering.edit');
    Route::patch('settings/numbering', [NumberFormatController::class, 'update'])->name('settings.numbering.update');
    Route::get('settings/email', [EmailSettingsController::class, 'edit'])->name('settings.email.edit');
    Route::patch('settings/email', [EmailSettingsController::class, 'update'])->name('settings.email.update');
    Route::post('settings/email/test', [EmailSettingsController::class, 'sendTest'])->name('settings.email.test');

    // WhatsApp delivery. Provider credentials are encrypted and store-scoped;
    // all send routes still authorize the underlying business document.
    Route::get('settings/whatsapp', [WhatsAppSettingsController::class, 'edit'])->name('settings.whatsapp.edit');
    Route::get('settings/whatsapp/connections/{connection}/edit', [WhatsAppSettingsController::class, 'editConnection'])->name('settings.whatsapp.connections.edit');
    Route::post('settings/whatsapp/connections', [WhatsAppSettingsController::class, 'store'])->name('settings.whatsapp.connections.store');
    Route::patch('settings/whatsapp/connections/{connection}', [WhatsAppSettingsController::class, 'update'])->name('settings.whatsapp.connections.update');
    Route::post('settings/whatsapp/connections/{connection}/test', [WhatsAppSettingsController::class, 'test'])->name('settings.whatsapp.connections.test');
    Route::post('settings/whatsapp/connections/{connection}/test-message', [WhatsAppSettingsController::class, 'sendTest'])->name('settings.whatsapp.connections.test-message');
    Route::delete('settings/whatsapp/connections/{connection}', [WhatsAppSettingsController::class, 'destroy'])->name('settings.whatsapp.connections.destroy');
    Route::post('settings/whatsapp/templates', [WhatsAppSettingsController::class, 'saveTemplate'])->name('settings.whatsapp.templates.store');

    Route::get('whatsapp/logs', [WhatsAppLogController::class, 'index'])->name('whatsapp.logs.index');
    Route::post('whatsapp/logs/{whatsappLog}/retry', [WhatsAppLogController::class, 'retry'])->name('whatsapp.logs.retry');
    Route::post('whatsapp/statements/bulk', [WhatsAppSendController::class, 'bulkStatements'])->name('whatsapp.statements.bulk');
    Route::post('sales/{sale}/whatsapp', [WhatsAppSendController::class, 'sale'])->name('sales.whatsapp');
    Route::post('sales/returns/{saleReturn}/whatsapp', [WhatsAppSendController::class, 'saleReturn'])->name('sales.returns.whatsapp');
    Route::post('quotations/{quotation}/whatsapp', [WhatsAppSendController::class, 'quotation'])->name('quotations.whatsapp');
    Route::post('customers/{customer}/statement/whatsapp', [WhatsAppSendController::class, 'statement'])->name('customers.statement.whatsapp');
    Route::post('customer-payments/{customerPayment}/whatsapp', [WhatsAppSendController::class, 'customerPayment'])->name('customer-payments.whatsapp');
    Route::post('supplier-payments/{supplierPayment}/whatsapp', [WhatsAppSendController::class, 'supplierPayment'])->name('supplier-payments.whatsapp');
    Route::get('settings/backup', [BackupSettingsController::class, 'edit'])->name('settings.backup.edit');
    Route::patch('settings/backup', [BackupSettingsController::class, 'update'])->name('settings.backup.update');
    Route::post('settings/backup/run', [BackupSettingsController::class, 'runNow'])->name('settings.backup.run');
    Route::get('settings/backup/download/{filename}', [BackupSettingsController::class, 'download'])->name('settings.backup.download');
    Route::delete('settings/backup/{filename}', [BackupSettingsController::class, 'destroy'])->name('settings.backup.destroy');

    // Restore wizard — choose a backup → review → confirm → restore. Gated by
    // `backup.restore` in the controller (super-admin / Admin only by default).
    Route::get('settings/restore', [RestoreController::class, 'index'])->name('settings.restore.index');
    Route::post('settings/restore/validate', [RestoreController::class, 'validateSource'])->name('settings.restore.validate');
    Route::post('settings/restore', [RestoreController::class, 'store'])->name('settings.restore.store');

    // System Information & Version — read-only view of current installed version and system status
    Route::get('settings/updates', [UpdateController::class, 'index'])->name('settings.updates.index');

    // Sales — admin index + detail. The cashier flow itself lives at /cashier
    // (separate URL, separate route group) so the topbar's "Open POS" link
    // goes somewhere recognisable.
    Route::get('quotations', [QuotationController::class, 'index'])->name('quotations.index');
    Route::get('quotations/rows', [QuotationController::class, 'rows'])->name('quotations.rows');
    Route::get('quotations/create', [QuotationController::class, 'create'])->name('quotations.create');
    Route::get('quotations/preview', [QuotationController::class, 'preview'])->name('quotations.preview');
    Route::get('quotations/scan', [QuotationController::class, 'scan'])->middleware('throttle:120,1')->name('quotations.scan');
    Route::post('quotations', [QuotationController::class, 'store'])->name('quotations.store');
    Route::get('quotations/{quotation}', [QuotationController::class, 'show'])->name('quotations.show');
    Route::get('quotations/{quotation}/edit', [QuotationController::class, 'edit'])->name('quotations.edit');
    Route::patch('quotations/{quotation}', [QuotationController::class, 'update'])->name('quotations.update');
    Route::get('quotations/{quotation}/document', [QuotationController::class, 'document'])->name('quotations.document');
    Route::get('quotations/{quotation}/pdf', [QuotationController::class, 'pdf'])->name('quotations.pdf');
    Route::post('quotations/{quotation}/send', [QuotationController::class, 'send'])->name('quotations.send');
    Route::post('quotations/{quotation}/accept', [QuotationController::class, 'accept'])->name('quotations.accept');
    Route::post('quotations/{quotation}/decline', [QuotationController::class, 'decline'])->name('quotations.decline');
    Route::post('quotations/{quotation}/cancel', [QuotationController::class, 'cancel'])->name('quotations.cancel');
    Route::post('quotations/{quotation}/revise', [QuotationController::class, 'revise'])->name('quotations.revise');
    Route::get('quotations/{quotation}/convert', [QuotationController::class, 'convertForm'])->name('quotations.convert.form');
    Route::post('quotations/{quotation}/convert', [QuotationController::class, 'convert'])->name('quotations.convert');

    Route::get('sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('sales/rows', [SaleController::class, 'rows'])->name('sales.rows');
    // Back-office sale creation — raise a sale without opening the cashier.
    // These MUST stay above `sales/{sale}` or route-model binding swallows
    // the literal `create` / `quote` segments.
    Route::get('sales/create', [SaleCreateController::class, 'create'])->name('sales.create');
    Route::get('sales/quote', [SaleCreateController::class, 'quote'])->name('sales.quote');
    Route::get('sales/scan', [SaleCreateController::class, 'scan'])->middleware('throttle:120,1')->name('sales.scan');
    Route::post('sales', [SaleCreateController::class, 'store'])->name('sales.store');
    Route::get('sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
    // Print pipeline — dual-format payload for the print bridge + outcome log.
    Route::get('sales/{sale}/print-payload', [PrintController::class, 'salePayload'])->name('sales.print-payload');
    Route::post('print-logs', [PrintController::class, 'log'])->name('print-logs.store');
    Route::get('sales/{sale}/refund', [SaleController::class, 'refundForm'])->name('sales.refund.form');
    Route::post('sales/{sale}/refund', [SaleController::class, 'refundStore'])->name('sales.refund.store');
    Route::post('sales/{sale}/payments', [SaleController::class, 'recordPayment'])->name('sales.payments.store');
    Route::post('sales/{sale}/void', [SaleController::class, 'void'])->name('sales.void');
    Route::post('sales/returns/{saleReturn}/retry-reversal', [SaleReturnGatewayRefundController::class, 'retry'])->name('sales.returns.retry-reversal');

    // Return reasons — picklist used by the refund form. Single-page
    // master-detail CRUD; mirrors adjustment-reasons.
    Route::get('return-reasons', [ReturnReasonController::class, 'index'])->name('return-reasons.index');
    Route::post('return-reasons', [ReturnReasonController::class, 'store'])->name('return-reasons.store');
    Route::patch('return-reasons/{returnReason}', [ReturnReasonController::class, 'update'])->name('return-reasons.update');
    Route::delete('return-reasons/{returnReason}', [ReturnReasonController::class, 'destroy'])->name('return-reasons.destroy');

    // Shifts — open/close + Z-report. One open shift per user installation-wide;
    // sales completed during the shift auto-bind via sales.shift_id so the
    // Z-report can roll them up. Slice 1 — denomination helper, pay-in/out,
    // force-close, PDF/thermal Z-report land later.
    Route::get('shifts', [ShiftController::class, 'index'])->name('shifts.index');
    Route::get('shifts/rows', [ShiftController::class, 'rows'])->name('shifts.rows');
    Route::get('shifts/open', [ShiftController::class, 'openForm'])->name('shifts.open.form');
    Route::post('shifts', [ShiftController::class, 'store'])->name('shifts.store');
    Route::get('shifts/{shift}/x-report', [ShiftController::class, 'xReport'])->name('shifts.x-report');
    Route::get('shifts/{shift}/z-report/payload', [ShiftController::class, 'zReportPayload'])->name('shifts.z-report.payload');
    Route::get('shifts/{shift}', [ShiftController::class, 'show'])->name('shifts.show');
    Route::get('shifts/{shift}/close', [ShiftController::class, 'closeForm'])->name('shifts.close.form');
    Route::post('shifts/{shift}/close', [ShiftController::class, 'close'])->name('shifts.close');

    // Cash drawer movements against an open shift. Single endpoint —
    // the `type` field in the body decides pay-in / pay-out / drawer-
    // open-no-sale; the request validator branches its rules + perm.
    Route::post('shifts/{shift}/cash-drawer', [ShiftController::class, 'recordCashDrawerEntry'])->name('shifts.cash-drawer.record');

    // Live "cash in drawer" for the caller's own open shift. Read by every
    // form that spends drawer cash so it can cap the amount client-side —
    // the hard limit still lives in RecordCashDrawerEntry.
    Route::get('cash-drawer/available', [ShiftController::class, 'cashDrawerAvailable'])->name('cash-drawer.available');

    // Shift variance reasons — picklist used by the close-shift form.
    // Same inline master-detail CRUD as return-reasons / adjustment-reasons.
    Route::get('shift-variance-reasons', [ShiftVarianceReasonController::class, 'index'])->name('shift-variance-reasons.index');
    Route::post('shift-variance-reasons', [ShiftVarianceReasonController::class, 'store'])->name('shift-variance-reasons.store');
    Route::patch('shift-variance-reasons/{shiftVarianceReason}', [ShiftVarianceReasonController::class, 'update'])->name('shift-variance-reasons.update');
    Route::delete('shift-variance-reasons/{shiftVarianceReason}', [ShiftVarianceReasonController::class, 'destroy'])->name('shift-variance-reasons.destroy');
});

// Cashier surface. Same auth gate as the admin but no `admin/` URL prefix —
// `/cashier` is the operator-facing URL. The cashier UI itself reuses the
// admin layout in Slice 1; a dedicated cashier chrome lands when the rest
// of the cashier features (shift, held drawer, ⌘K) are wired up.
Route::middleware(['ensure.installed', 'auth', 'store.selected', 'set.locale', ReserveCashierTerminal::class])->name('cashier.')->group(function () {
    Route::get('cashier', [SaleController::class, 'cashier'])->name('index');

    // Customer-Facing Display (CFD) — the second screen turned toward the
    // shopper. Opened from the cashier as a second window; mirrors the cart
    // live over a same-machine BroadcastChannel (no server round-trip).
    // See docs/features/customer-display.md.
    Route::get('cashier/display', [CustomerDisplayController::class, 'show'])->name('display');
    Route::get('cashier/display/upi', [CustomerDisplayController::class, 'upi'])->middleware('throttle:240,1')->name('display.upi');
    // Tier 2 (separate-device) relay: the cashier pushes its snapshot to a
    // per-terminal cache; the tablet polls the state endpoint. No Pusher.
    Route::post('cashier/display/push', [CustomerDisplayController::class, 'push'])->middleware('throttle:240,1')->name('display.push');   // SEC #50
    Route::get('cashier/display/{terminal}/state', [CustomerDisplayController::class, 'state'])->middleware('throttle:240,1')->name('display.state'); // SEC #50

    // Offline-first Slice 1: catalog snapshot + heartbeat.
    Route::get('cashier/sync', [SaleController::class, 'sync'])->name('sync');
    Route::get('cashier/heartbeat', [SaleController::class, 'heartbeat'])->name('heartbeat');
    Route::get('cashier/search', [SaleController::class, 'search'])->name('search');
    Route::post('cashier/complete', [SaleController::class, 'complete'])->name('complete');

    // Open a shift WITHOUT leaving /cashier — the shift gate posts here
    // when the store enforces shifts (Slice A). Reuses the OpenShift action.
    Route::post('cashier/shift', [App\Http\Controllers\Cashier\ShiftController::class, 'open'])->name('shift.open');

    // Bind this workstation to a terminal from the cashier (Slice B).
    Route::post('cashier/terminal', [App\Http\Controllers\Cashier\TerminalController::class, 'select'])->name('terminal.select');

    // Manager approval for an over-threshold discount (Discounts Slice 2).
    Route::post('cashier/discount/approve', [DiscountApprovalController::class, 'approve'])->name('discount.approve');

    Route::get('cashier/batches', [SaleController::class, 'batches'])->name('batches');
    Route::get('cashier/customers/search', [SaleController::class, 'customerSearch'])->name('customers.search');
    Route::post('cashier/customers', [SaleController::class, 'quickAddCustomer'])->name('customers.store');

    Route::post('cashier/hold', [SaleController::class, 'holdCart'])->name('hold');
    Route::get('cashier/holds', [SaleController::class, 'heldList'])->name('holds.list');
    Route::post('cashier/holds/{sale}/resume', [SaleController::class, 'resumeHeld'])->name('holds.resume');
    Route::delete('cashier/holds/{sale}', [SaleController::class, 'voidHeld'])->name('holds.void');

    Route::get('cashier/reprint-last', [SaleController::class, 'reprintLast'])->name('reprint-last');
    Route::get('cashier/recents', [SaleController::class, 'recentsList'])->name('recents');

    // Refund / return from the cashier surface. Reuses RecordSaleReturn
    // under the hood; UI lives in the cashier modal so the operator
    // doesn't have to leave /cashier to process a return at the counter.
    // Payment gateways (Slice 1 — Stripe). One controller dispatches
    // by `payment_methods.provider`; add Razorpay/Paystack/etc. by
    // implementing the gateway class + extending `gatewayFor()`.
    Route::post('cashier/gateways/start', [GatewayController::class, 'start'])->name('gateways.start');
    Route::get('cashier/gateways/status', [GatewayController::class, 'status'])->name('gateways.status');

    // POS payment sessions — chooser flow (cashier shows QR pointing
    // at /pay/pos/{uuid}; customer picks provider there). The cashier
    // creates a session row, polls status, and can cancel.
    Route::post('cashier/pos-sessions', [PaymentSessionController::class, 'create'])->name('pos-sessions.create');
    // Server-priced static-UPI intent — the deep link is built server-side
    // from the configured VPA and a PriceCart total (SEC #78).
    Route::post('cashier/pos-sessions/upi', [PaymentSessionController::class, 'upiIntent'])->name('pos-sessions.upi');
    Route::get('cashier/pos-sessions/{uuid}/status', [PaymentSessionController::class, 'status'])->name('pos-sessions.status');
    Route::post('cashier/pos-sessions/{uuid}/cancel', [PaymentSessionController::class, 'cancel'])->name('pos-sessions.cancel');

    Route::get('cashier/refund/lookup', [RefundController::class, 'lookup'])->name('refund.lookup');
    Route::get('cashier/refund/{sale}', [RefundController::class, 'show'])->name('refund.show');
    Route::post('cashier/refund/{sale}', [RefundController::class, 'store'])->name('refund.store');
});

// Self-ordering kiosk surface. Same auth gate as the cashier — a kiosk is
// opened from an authenticated staff session and bound to a `type=kiosk`
// terminal, then locked down for the customer. It reuses the cashier's
// catalog sync (`/cashier/sync` → IndexedDB), so Slice 1 adds no new data
// endpoints. See docs/features/kiosk-self-ordering.md.
Route::middleware(['ensure.installed', 'auth', 'store.selected', 'set.locale'])->name('kiosk.')->group(function () {
    Route::get('kiosk', [KioskController::class, 'show'])->name('index');
    Route::post('kiosk/quote', [KioskController::class, 'quote'])->name('quote');
    // Order-mode submission → a `placed` Sale in the staff queue (Slice 2).
    Route::post('kiosk/place', [KioskController::class, 'place'])->name('place');
    // Checkout-mode: pay at the kiosk via the QR-chooser, then finalise the
    // sale with origin=kiosk (Slice 3). Status is polled on the shared
    // /cashier/pos-sessions/{uuid}/status endpoint.
    Route::post('kiosk/checkout/start', [KioskCheckoutController::class, 'start'])->name('checkout.start');
    Route::post('kiosk/checkout/complete', [KioskCheckoutController::class, 'complete'])->name('checkout.complete');
    // Supervisor exit-kiosk-mode (skeleton in Slice 1: confirm → leave;
    // PIN hardening lands in Slice 5).
    Route::post('kiosk/exit', [KioskController::class, 'exit'])->middleware('throttle:5,1')->name('exit');   // SEC #55
});

// Public webhook receivers — no auth, no CSRF (the provider signs the
// payload). Each provider gets its own route + controller because the
// signature header + raw-body handling differs.
Route::post('webhooks/stripe', StripeWebhookController::class)->name('webhooks.stripe');
Route::post('webhooks/razorpay', RazorpayWebhookController::class)->name('webhooks.razorpay');
Route::post('webhooks/paystack', PaystackWebhookController::class)->name('webhooks.paystack');
Route::post('webhooks/flutterwave', FlutterwaveWebhookController::class)->name('webhooks.flutterwave');
Route::post('webhooks/mercado_pago', MercadoPagoWebhookController::class)->name('webhooks.mercado_pago');
Route::get('webhooks/whatsapp/{connection}', [WhatsAppWebhookController::class, 'verify'])
    ->middleware('throttle:60,1')->name('webhooks.whatsapp.verify');
Route::post('webhooks/whatsapp/{connection}', [WhatsAppWebhookController::class, 'receive'])
    ->middleware('throttle:240,1')->name('webhooks.whatsapp.receive');

// Customer-facing payment chooser — public route the cashier QR
// points at. Customer arrives, picks a gateway, pays, returns. No
// auth: the un-guessable session UUID is the token.
Route::get('pay/pos/{uuid}', [CustomerPayController::class, 'show'])->name('pay.pos.show');
Route::get('pay/pos/{uuid}/status', [CustomerPayController::class, 'status'])->name('pay.pos.status');
Route::post('pay/pos/{uuid}/select', [CustomerPayController::class, 'select'])->name('pay.pos.select');
Route::get('pay/pos/{uuid}/return', [CustomerPayController::class, 'return'])->name('pay.pos.return');

// Public, no-login receipt viewer — the CFD thank-you QR, WhatsApp, and
// email/SMS receipts all point here. The opaque 48-char token IS the auth;
// throttled to blunt token-guessing. See docs/features/whatsapp-receipts.md §5.
Route::get('r/{token}', [PublicReceiptController::class, 'show'])
    ->middleware('throttle:60,1')
    ->name('receipt.public');

Route::get('q/{token}', [PublicQuotationController::class, 'show'])
    ->middleware('throttle:120,1')
    ->name('quotation.public');
Route::post('q/{token}/accept', [PublicQuotationController::class, 'accept'])
    ->middleware('throttle:20,1')
    ->name('quotation.public.accept');
Route::post('q/{token}/decline', [PublicQuotationController::class, 'decline'])
    ->middleware('throttle:20,1')
    ->name('quotation.public.decline');
