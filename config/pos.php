<?php

return [
    // A separately approved pre-business host adapter must also be bound.
    // Core-only installs keep their existing wizard when this is disabled.
    'fresh_saas_installer' => [
        'enabled' => (bool) env('POS_FRESH_SAAS_INSTALLER', true),
        // Host preparation supplies a private, sealed source directory.
        // Empty values keep the optional pre-business adapter unavailable.
        'source_directory' => env('POS_FRESH_SAAS_SOURCE_DIRECTORY', ''),
        // Publisher trust is shipped with Hyper-POS core. Customers never enter or configure it.
        'publisher_trust_file' => base_path('resources/security/hyper-pos-saas-publisher.sha256'),
        // Private APP_KEY-authenticated FI02 host identity and bridge configuration.
        'runtime_configuration' => env('POS_FRESH_SAAS_RUNTIME_CONFIGURATION', ''),
        'vps' => [
            'ready' => (bool) env('POS_FRESH_SAAS_VPS_READY', false),
        ],
        'shared' => [
            'ready' => (bool) env('POS_FRESH_SAAS_SHARED_READY', false),
            'management_defaults' => [
                'country_code' => env('POS_FRESH_SAAS_COUNTRY', 'US'),
                'currency_code' => env('POS_FRESH_SAAS_CURRENCY', 'USD'),
                'timezone' => env('POS_FRESH_SAAS_TIMEZONE', 'UTC'),
                'locale' => env('POS_FRESH_SAAS_LOCALE', 'en'),
            ],
            'database' => [
                'host' => env('POS_FRESH_SAAS_SHARED_DB_HOST', ''),
                'port' => (int) env('POS_FRESH_SAAS_SHARED_DB_PORT', 3306),
                'database' => env('POS_FRESH_SAAS_SHARED_DB_DATABASE', ''),
                'username' => env('POS_FRESH_SAAS_SHARED_DB_USERNAME', ''),
                'password' => env('POS_FRESH_SAAS_SHARED_DB_PASSWORD', ''),
            ],
        ],
        // Staging-only fresh SaaS rehearsal option. Production customers must
        // validate their Hyper-POS purchase code through the ordinary step.
        'core_purchase_launch_bypass' => (bool) env('POS_FRESH_SAAS_CORE_PURCHASE_LAUNCH_BYPASS', false),
        // Explicit fresh-installer rehearsal switches. Both default off.
        'test_purchase_bypass' => (bool) env('POS_FRESH_SAAS_TEST_PURCHASE_BYPASS', false),
        'test_https_bypass' => (bool) env('POS_FRESH_SAAS_TEST_HTTPS_BYPASS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Application version
    |--------------------------------------------------------------------------
    |
    | The canonical version of this install. Everything that needs to know
    | "what version am I?" reads `config('pos.version')` — the updater's
    | feed comparison, the backup manifest, the footer, support diagnostics.
    |
    | This ships inside config/pos.php (which the updater REPLACES on every
    | update), so a new release naturally carries its own new version number.
    | Bump it here when cutting a release. Format: semver (MAJOR.MINOR.PATCH).
    |
    */

    'version' => '1.1.4',

    /*
    |--------------------------------------------------------------------------
    | Product name
    |--------------------------------------------------------------------------
    |
    | The product's own display name — used where we must brand as the product
    | itself rather than the customer's company, most notably the installer
    | (which runs before any company exists, so `config('app.name')` is still
    | the framework default "Laravel" there). One place to change when the
    | final name is locked. The running app shows the company name instead, via
    | ApplyCompanySettings.
    |
    */

    'name' => 'POS',

    /*
    |--------------------------------------------------------------------------
    | Documentation URL
    |--------------------------------------------------------------------------
    |
    | Where every "Documentation" link points (admin footer, landing page nav
    | and footer). This is the hosted manual rather than the copy bundled at
    | /documentation, so customers always read the latest version even if their
    | install is behind. Override via .env for forks or to point back at the
    | bundled docs.
    |
    */

    'documentation_url' => env('POS_DOCUMENTATION_URL', 'https://hyper-pos.eshopweb.store/documentation/'),

    /*
    |--------------------------------------------------------------------------
    | Developer / maintenance routes
    |--------------------------------------------------------------------------
    |
    | The web equivalents of the artisan commands — /migrate, /seed,
    | /clear-cache, /phpinfo and the demo baseline routes. They
    | exist because this product targets shared hosting, where SSH is often
    | unavailable and the only way to run a migration is over HTTP.
    |
    | They are DANGEROUS by nature (/migrate?fresh=1 drops every table), so
    | `routes/dev.php` is only loaded into the router when `enabled` is true.
    | On a customer install the key is absent, the file is never read, and the
    | routes do not exist at all — not even as a 404-guarded closure. Never
    | ship a .env with POS_DEV_ROUTES=true.
    |
    | The destructive subset (fresh migrate, seed, demo reset/snapshot) needs a
    | second factor on top of the flag: either `?token=` matching POS_DEV_TOKEN,
    | or a signed-in super admin. The token path exists because the very first
    | migrate runs against an empty database where no user can exist yet.
    |
    */

    'dev' => [

        // Master switch. Loads routes/dev.php when true. Leave unset (false)
        // on anything a customer can reach.
        'routes_enabled' => env('POS_DEV_ROUTES', false),

        // Shared secret for the destructive routes, passed as ?token=. Leave
        // empty to require a signed-in super admin instead (which is not an
        // option on a freshly-migrated, userless database).
        'token' => env('POS_DEV_TOKEN', ''),

    ],

    /*
    |--------------------------------------------------------------------------
    | Demo mode
    |--------------------------------------------------------------------------
    |
    | When enabled, the login page shows a small notice with a link out to the
    | configured marketing site — so visitors who land on the live demo know
    | they're looking at a demo and can find their way back to the product
    | website. The toggle is environment-driven (.env) so the same code can
    | run as either the live demo or a real install without code changes.
    |
    */

    'demo' => [
        'enabled' => false,
        'website_url' => '',
        'analytics_id' => '',
        'purchase_url' => '',
        'credentials' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Remote image fetching
    |--------------------------------------------------------------------------
    |
    | Bulk product images can arrive two ways: the customer drops a folder of
    | files, or they give us URLs (from an import's `Image` column, or pasted on
    | the bulk-images screen) and the SERVER downloads them.
    |
    | That download is the only place in the app where a URL a customer typed
    | becomes an outbound request from their server, so it is a trust boundary:
    | App\Services\Images\RemoteImageFetcher refuses anything that isn't HTTPS to
    | a public address (SafeRemoteUrl), re-checks every redirect hop, caps the
    | response size, and identifies the file by its magic bytes rather than its
    | URL or Content-Type.
    |
    | The limits below are deliberately tight — a shared host gives us one PHP
    | request to work in, and the browser drives the run one small batch at a
    | time, so a single slow URL must never eat the whole request.
    |
    */

    'images' => [

        // Largest image we will pull down, in KB. Mirrors the 4 MB cap on the
        // file-upload path so both routes to an image behave the same.
        'max_kb' => (int) env('POS_IMAGE_FETCH_MAX_KB', 4096),

        // Seconds allowed for one image, and for the connection alone. Kept
        // short on purpose: a batch of 5 must finish well inside a typical
        // 30-second max_execution_time even if every URL is dead.
        'timeout' => (int) env('POS_IMAGE_FETCH_TIMEOUT', 10),
        'connect_timeout' => (int) env('POS_IMAGE_FETCH_CONNECT_TIMEOUT', 5),

        // Redirect hops to follow. Guzzle's own redirect handling is turned OFF
        // and each hop is re-checked against SafeRemoteUrl instead — otherwise a
        // public URL could bounce the fetch to 127.0.0.1 and walk straight past
        // the check we made on the URL we were given.
        'max_redirects' => (int) env('POS_IMAGE_FETCH_MAX_REDIRECTS', 3),

        // Some CDNs and shop platforms refuse an empty or unknown User-Agent.
        'user_agent' => env('POS_IMAGE_FETCH_UA', 'Mozilla/5.0 (compatible; POS-ImageFetcher/1.0)'),

    ],

    /*
    |--------------------------------------------------------------------------
    | Licensing
    |--------------------------------------------------------------------------
    |
    | See docs/features/installer.md §3 + §6. The license is validated once at
    | install (Step 3) against our license server, then re-checked
    | periodically using read-only domain status checks. Confirmed invalid
    | bindings block normal operation; outages preserve the last known status.
    | The license KEY lives in .env (LICENSE_KEY); the
    | validated DETAILS (buyer, type, support window) are cached in the DB.
    |
    | `public_key` verifies the offline-activation blob (Ed25519) for customers
    | behind firewalls who can't reach the license server during install.
    |
    */

    'license' => [

        // Master switch for license enforcement. When false the installer skips
        // the purchase-code step entirely and the background re-check no-ops —
        // useful for free / internal builds or offline development.
        //
        // ON: the CodeCanyon item is live (see item_id below), so the installer
        // asks for a purchase code and validates it against the validator
        // server. Set POS_LICENSE_REQUIRED=false in .env to disable on a
        // fork / internal build.
        'required' => env('POS_LICENSE_REQUIRED', true),

        // Explicit operator bypass for servers unable to reach the validator.
        // Honored in every environment, including production. Off by default.
        // Installer accepts a nonempty code; ongoing checks/enforcement are skipped.
        'dev_bypass' => env('POS_LICENSE_DEV_BYPASS', false),

        // Periodic read-only binding checks and middleware enforcement.
        //'recheck' => env('POS_LICENSE_RECHECK', true),
        'recheck_minutes' => 15,

        // The activated license key + its install fingerprint, written to .env
        // by the installer. Surfaced through config so the background re-check
        // reads them even when the config is cached (env() outside config
        // returns null once `config:cache` runs).
        'key' => env('LICENSE_KEY', ''),
        'fingerprint' => env('LICENSE_FINGERPRINT', ''),

        // Validator v1: authenticated JSON POST to validate and bind this install.
        // Revalidating the same domain does not consume another seat.

        // file disini sudah  dibuang!!!

        // The product / item identifier the validator binds the key to.
        // The legacy CI codebase called this `WEB_CODE`; in this build
        // it's `POS_LICENSE_ITEM_ID` in `.env` and reads from here at
        // runtime. Required by the validator — without it every check
        // comes back invalid.
        // 'item_id' => env('POS_LICENSE_ITEM_ID', '64055507'),

        // Seconds to wait on the license server before giving up. Offline
        // activation has been removed in this build; an unreachable server
        // surfaces a "couldn't reach license server" error and the
        // customer must retry once connectivity is restored.
        'timeout' => 15,

    ],

    /*
    |--------------------------------------------------------------------------
    | Demo Mode & Promo Landing Configuration
    |--------------------------------------------------------------------------
    */
    'demo' => [
        'enabled' => (bool) env('POS_DEMO_MODE', true),
        'website_url' => env('APP_URL', 'https://yukelola.id'),
        'support_whatsapp' => env('POS_SUPPORT_WHATSAPP', 'https://wa.me/6281234567890?text=Halo%20Admin%20YUKelola,%20saya%20tertarik%20mencoba%20aplikasi%20YUKelola'),
        'purchase_url' => env('POS_PURCHASE_URL', '#pricing'),
        'extended_url' => env('POS_EXTENDED_URL', '#pricing'),
        'credentials' => [
            [
                'role' => 'admin',
                'name' => 'Demo Super Admin YUKelola',
                'email' => 'admin@yukelola.id',
                'password' => 'password',
            ],
            [
                'role' => 'cashier',
                'name' => 'Demo Kasir YUKelola',
                'email' => 'kasir@yukelola.id',
                'password' => 'password',
            ],
        ],
    ],

];
