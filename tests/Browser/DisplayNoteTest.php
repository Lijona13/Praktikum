<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Note;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DisplayNoteTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test displaying notes in the index page
     * @group display-notes
     */
    public function testDisplayNotes(): void
    {
        $user = User::factory()->create([
            'email' => 'display_user1@example.com', 
            'password' => bcrypt('password'),
        ]);

        $note = Note::create([
            'judul' => 'Detailed Note Title For View', 
            'isi' => 'This is a detailed note content with full description',
            'penulis_id' => $user->id,
        ]);

        $this->browse(function (Browser $browser) use ($user, $note) { 
            $browser->visit('/login')
                ->type('email', $user->email) 
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/dashboard')
                ->visit('/notes')
                ->waitForText($note->judul)
                ->click("a[dusk='detail-{$note->id}']") 
                ->assertPathIs('/note/'.$note->id)
                ->assertSee($note->judul)
                ->assertSee($note->isi)
                ->assertSee('Author: ' . $user->name); 
        });
    }
} 