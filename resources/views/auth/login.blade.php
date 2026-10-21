<x-auth-layout :title="__('auth.title')">
    @php
        // Branding data is loaded by AuthLayout for its own template, but
        // Blade slots render in the calling scope and can't see component
        // properties. The duplicate `Company::current()` here is one extra
        // SELECT on a page that's hit by humans (not in a hot loop), so
        // we accept it instead of refactoring to pass props through a slot.
        //
        // Guard against the pre-install state — first hit after a fresh
        // download, before migrations run, there's no `company` table at
        // all. Mirrors AuthLayout::loadBranding().
        $company        = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('company')) {
                $company = \App\Models\Company::current();
            }
        } catch (\Throwable) {
            // Table not ready yet — fall through with the config fallback.
        }
        $appLogoUrl     = $company?->app_logo_url;
        $appLogoDarkUrl = $company?->app_logo_dark_url;
        $appName        = $company?->display_app_name ?: config('app.name');
    @endphp

    {{-- novalidate: validation is owned by the server (LoginRequest in
         app/Http/Requests/Auth/LoginRequest.php). The browser's HTML5
         popups would race with the server response and be inconsistent. --}}
    <form method="POST" action="{{ route('login.attempt') }}" class="auth-card"
          novalidate
          x-data="{
              submitting: false,
              errorMsg: '',
              async doLogin(evt) {
                  if (this.submitting) return;
                  this.submitting = true;
                  this.errorMsg = '';
                  try {
                      const { data } = await this.$http.post(evt.target.action, new FormData(evt.target));
                      window.location.href = data.redirect || '/admin';
                  } catch (e) {
                      this.submitting = false;
                      this.errorMsg = (e.errors && Object.values(e.errors).flat()[0]) || e.message || @js(__('auth.failed'));
                      if (e.errors) this.$http.applyValidationErrors(evt.target, e.errors);
                  }
              }
          }"
          @submit.prevent="doLogin($event)">
        @csrf

        {{-- Brand. Don't use Tailwind's `hidden` / `dark:block` for the
             light↔dark swap — those utilities are in `@layer utilities`
             with the same specificity, and one company's combination of
             both logos rendered BOTH images in light mode (the second
             escaping the card on the right). Distinct
             `auth-brand-logo-light` / `-dark` classes toggled via plain
             CSS in [auth/login.css] are bulletproof. --}}
        <div class="auth-brand">
            @if ($appLogoUrl || $appLogoDarkUrl)
                @if ($appLogoUrl)
                    <img src="{{ $appLogoUrl }}"
                         class="auth-brand-logo auth-brand-logo-light"
                         alt="{{ $appName }}">
                @endif
                @if ($appLogoDarkUrl)
                    <img src="{{ $appLogoDarkUrl }}"
                         class="auth-brand-logo auth-brand-logo-dark"
                         alt="{{ $appName }}">
                @endif
            @else
                <span class="auth-brand-mark">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($appName, 0, 1)) }}</span>
                <div class="auth-brand-text">
                    <div class="auth-brand-name">{{ $appName }}</div>
                    <div class="auth-brand-sub">{{ __('auth.brand_sub') }}</div>
                </div>
            @endif
        </div>

        {{-- Title --}}
        <h1 class="auth-title">{{ __('auth.title') }}</h1>
        <p class="auth-sub">{{ __('auth.sub', ['name' => $appName]) }}</p>

        {{-- Success status — surfaces "Password updated" after a
             password-reset round-trip lands the user back here. --}}
        @if (session('status'))
            <div class="alert alert-success mb-5">
                <span class="alert-icon"><x-icon name="check" class="w-[18px] h-[18px]" /></span>
                <div class="alert-body">
                    <div class="alert-title">{{ session('status') }}</div>
                </div>
            </div>
        @endif

        {{-- Error alert from redirect flash --}}
        @if (session('error'))
            <div class="alert alert-danger mb-5">
                <span class="alert-icon"><x-icon name="alert" class="w-[18px] h-[18px]" /></span>
                <div class="alert-body">
                    <div class="alert-title">{{ session('error') }}</div>
                </div>
            </div>
        @endif

        {{-- Gate C1: 1-Click Interactive Demo Login Options --}}
        @if (pos_is_demo())
            <div class="demo-access-panel">
                <div class="demo-access-header">
                    <div class="demo-access-badge-row">
                        <span class="demo-access-badge">
                            <span class="demo-access-pulse"></span>
                            {{ __('Mode Demo Interaktif') }}
                        </span>
                    </div>
                    <p class="demo-access-desc">{{ __('Coba fitur lengkap YUKelola tanpa perlu mengetik kredensial:') }}</p>
                </div>

                <div class="demo-access-grid">
                    <a href="{{ route('demo.login', ['role' => 'admin']) }}" class="demo-btn demo-btn-admin">
                        <div class="demo-btn-content">
                            <div class="demo-btn-icon">
                                <x-icon name="user" class="w-5 h-5" />
                            </div>
                            <div class="demo-btn-text">
                                <span class="demo-btn-title">{{ __('Coba sebagai Admin Demo') }}</span>
                                <span class="demo-btn-subtitle">{{ __('Dashboard, Laporan & Kelola Stok') }}</span>
                            </div>
                        </div>
                        <x-icon name="chevron-right" class="w-4 h-4 demo-btn-arrow" />
                    </a>

                    <a href="{{ route('demo.login', ['role' => 'cashier']) }}" class="demo-btn demo-btn-cashier">
                        <div class="demo-btn-content">
                            <div class="demo-btn-icon">
                                <x-icon name="cart" class="w-5 h-5" />
                            </div>
                            <div class="demo-btn-text">
                                <span class="demo-btn-title">{{ __('Coba sebagai Kasir Demo') }}</span>
                                <span class="demo-btn-subtitle">{{ __('POS Penjualan, Barcode & Kasir') }}</span>
                            </div>
                        </div>
                        <x-icon name="chevron-right" class="w-4 h-4 demo-btn-arrow" />
                    </a>
                </div>

                <div class="demo-access-divider">
                    <span>{{ __('atau masuk dengan akun terdaftar') }}</span>
                </div>
            </div>
        @endif

        {{-- Error alert — SSR fallback (shown on traditional POST / JS disabled) --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-5">
                <span class="alert-icon"><x-icon name="alert" class="w-[18px] h-[18px]" /></span>
                <div class="alert-body">
                    @if ($errors->count() === 1)
                        <div class="alert-title">{{ $errors->first() }}</div>
                    @else
                        <ul class="alert-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        @endif

        {{-- Error alert — AJAX response --}}
        <div class="alert alert-danger mb-5" x-show="errorMsg" x-cloak>
            <span class="alert-icon"><x-icon name="alert" class="w-[18px] h-[18px]" /></span>
            <div class="alert-body">
                <div class="alert-title" x-text="errorMsg"></div>
            </div>
        </div>

        {{-- Fields — spacing handled by .form-stack, NOT per-field margins.
             Use this pattern for every multi-field form going forward. --}}
        <div class="form-stack">
            {{-- Email — server-side validation only (no `required` / `type=email`). --}}
            <label class="field">
                <span class="field-label">{{ __('auth.email') }}</span>
                <input type="text" name="email" autofocus autocomplete="username"
                       value="{{ old('email') }}"
                       class="pos-input"
                       placeholder="admin@local">
            </label>

            {{-- Password (with show / hide toggle) --}}
            <label class="field" x-data="{ shown: false }">
                <span class="field-label">{{ __('auth.password') }}</span>
                <div class="pos-input-wrap has-suffix">
                    <input :type="shown ? 'text' : 'password'"
                           name="password" autocomplete="current-password"
                           class="pos-input mono"
                           placeholder="••••••••">
                    <button type="button"
                            class="pos-input-suffix"
                            @click="shown = !shown"
                            :aria-label="shown ? @js(__('auth.hide_password')) : @js(__('auth.show_password'))"
                            :aria-pressed="shown">
                        <span x-show="!shown"><x-icon name="eye" class="w-[18px] h-[18px]" /></span>
                        <span x-show="shown" x-cloak><x-icon name="eye-off" class="w-[18px] h-[18px]" /></span>
                    </button>
                </div>
            </label>
        </div>

        {{-- Remember me + forgot password --}}
        <div class="auth-actions-row">
            <label class="auth-remember">
                <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                <span>{{ __('auth.remember_me') }}</span>
            </label>

            <a href="{{ route('password.request') }}" class="auth-link">
                {{ __('auth.forgot') }}
            </a>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="pos-btn pos-btn-primary auth-submit"
                :disabled="submitting">
            <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
            </svg>
            <span x-text="submitting ? @js(__('auth.signing_in')) : @js(__('auth.sign_in'))"></span>
        </button>


    </form>
</x-auth-layout>
