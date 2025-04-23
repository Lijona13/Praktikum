<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateNoteTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test creating a new note
     * @group create-note
     */
    public function testCreateNote(): void
    {

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/dashboard')               
                ->visit('/notes')
                ->assertSee('Create Note')
                ->clickLink('Create Note')
                ->assertPathIs('/create-note')
                ->type('title', 'Test Note Title')
                ->type('description', 'This is my test note content')
                ->click('.btn-submit-note')
                ->pause(2000) 
                ->assertPathIs('/notes')
                ->assertSee('Test Note Title')
                ->assertSee('This is my test note content');
        });
    }
} 