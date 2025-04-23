<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LogoutTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test logout functionality.
     * @group logout
     */
    public function testLogout(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email) 
                ->type('password', 'password') 
                ->press('LOG IN')
                ->waitForLocation('/dashboard')
                ->assertPathIs('/dashboard');

            $browser
                ->click('div.sm\\:flex button.inline-flex') 
                ->waitForText('Log Out') 
                ->clickLink('Log Out') 
                ->waitForLocation('/') 
                ->assertPathIs('/')
                ->assertSee('Log in'); 
        });
    }
}
