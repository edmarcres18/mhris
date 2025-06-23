<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class NotificationTest extends DuskTestCase
{
    /** @test */
    public function it_shows_notification_dropdown()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                   ->visit('/')
                   ->click('#notification-dropdown')
                   ->waitFor('#notification-menu')
                   ->assertVisible('#notification-list')
                   ->assertSee('Notifications');
        });
    }

    /** @test */
    public function it_updates_notification_count()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                   ->visit('/')
                   ->waitFor('#notification-count')
                   ->assertSee('0');

            // Trigger a new notification
            // Add your notification creation logic here

            $browser->refresh()
                   ->waitFor('#notification-count')
                   ->assertSee('1');
        });
    }
}
