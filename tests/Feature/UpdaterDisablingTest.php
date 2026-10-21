<?php

namespace Tests\Feature;

use App\Actions\Updater\CheckForUpdate;
use App\Services\Updater\UpdateClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UpdaterDisablingTest extends TestCase
{
    public function test_default_configuration_has_updater_disabled_and_empty_feed_url(): void
    {
        $this->assertFalse(config('pos.updater.enabled'));
        $this->assertSame('', config('pos.updater.feed_url'));
        $this->assertStringNotContainsString('infinitietech.com', (string) config('pos.updater.feed_url'));
    }

    public function test_update_client_does_not_call_http_when_updater_is_disabled(): void
    {
        Http::fake();

        config([
            'pos.updater.enabled' => false,
            'pos.updater.feed_url' => 'https://updates.infinitietech.com/pos.json',
        ]);

        $client = app(UpdateClient::class);
        $result = $client->fetchFeed();

        $this->assertNull($result);
        Http::assertNothingSent();
    }

    public function test_update_client_does_not_call_http_when_feed_url_is_blank(): void
    {
        Http::fake();

        config([
            'pos.updater.enabled' => true,
            'pos.updater.feed_url' => '',
        ]);

        $client = app(UpdateClient::class);
        $result = $client->fetchFeed();

        $this->assertNull($result);
        Http::assertNothingSent();
    }

    public function test_check_for_update_action_returns_disabled_without_network_request(): void
    {
        Http::fake();

        config([
            'pos.updater.enabled' => false,
            'pos.updater.feed_url' => 'https://updates.infinitietech.com/pos.json',
        ]);

        $check = app(CheckForUpdate::class);
        $result = $check();

        $this->assertFalse($result['ok']);
        $this->assertFalse($result['available']);
        $this->assertNull($result['version']);
        $this->assertSame('updater_disabled', $result['reason']);
        Http::assertNothingSent();
    }
}
