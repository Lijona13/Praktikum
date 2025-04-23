<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Note;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EditNoteTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test editing a note
     * @group edit-note
     */
    public function testEditNote(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $note = Note::create([
            'judul' => 'Original Note Title',
            'isi' => 'Original note description',
            'penulis_id' => $user->id,
        ]);

        $this->browse(function (Browser $browser) use ($note) {
            $browser->visit('/login')
                ->type('email', 'test@example.com')
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/dashboard')
                ->visit('/notes')
                ->assertSee('Original Note Title')
                ->clickLink('Edit')
                ->type('title', 'Updated Note Title')
                ->type('description', 'This is my updated note content')
                ->click('.btn-submit-note')
                ->pause(2000)
                ->assertPathIs('/notes')
                ->assertSee('Note has been updated')
                ->assertSee('Updated Note Title')
                ->assertSee('This is my updated note content');
        });
    }
} 