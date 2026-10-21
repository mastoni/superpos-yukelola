<?php

namespace App\Providers;

use App\Actions\Commerce\ScheduleImmediateCommerceProcessing;
use App\Services\Commerce\CommerceProviderRegistry;
use App\Services\Commerce\Providers\ShopifyProvider;
use App\Services\Commerce\Providers\WooCommerceProvider;
use App\Services\WhatsApp\Providers\MetaCloudApiProvider;
use App\Services\WhatsApp\WhatsAppProviderRegistry;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(ScheduleImmediateCommerceProcessing::class);

        $this->app->singleton(CommerceProviderRegistry::class, fn ($app) => new CommerceProviderRegistry([
            $app->make(WooCommerceProvider::class),
            $app->make(ShopifyProvider::class),
        ]));

        $this->app->singleton(WhatsAppProviderRegistry::class, function ($app) {
            $registry = new WhatsAppProviderRegistry([
                $app->make(MetaCloudApiProvider::class),
            ]);

            // Custom add-ons can resolve the registry in their service
            // provider and register another WhatsAppProvider implementation.
            return $registry;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('hookSlot', fn (string $expression) => "<?php echo hook_slot({$expression}); ?>");

        // System-wide default pagination view — every `->links()` call
        // across the admin renders with the same chrome (caption +
        // numbered nav) instead of Laravel's stock Tailwind 3 markup.
        Paginator::defaultView('vendor.pagination.system');
        Paginator::defaultSimpleView('vendor.pagination.system');
    }
}
