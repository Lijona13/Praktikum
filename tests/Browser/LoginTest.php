<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test for login.
     * @group login
     */
    public function testLogin(): void
    {
        // Create a user first
        User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $this->browse(callback: function (Browser $browser): void {   
            $browser->visit(url: '/')
                ->assertSee(text: 'Modul 3')
                ->clickLink(link: 'Log in')
                ->assertPathIs(path: '/login')
                ->type(field: 'email', value: 'admin@gmail.com')
                ->type(field: 'password', value: 'password')
                ->press(button: 'LOG IN')
                ->assertPathIs(path: '/dashboard');
        });

    }
}
