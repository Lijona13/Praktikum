<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Note;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DeleteNoteTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test deleting a note
     * @group delete-note
     */
    public function testDeleteNote(): void
    {

        $user = User::factory()->create([
            'email' => 'delete_user@example.com', 
            'password' => bcrypt('password'),
        ]);

        $note = Note::create([
            'judul' => 'Note To Be Deleted', 
            'isi' => 'This note content should disappear after deletion.',
            'penulis_id' => $user->id,
        ]);

        $this->browse(function (Browser $browser) use ($user, $note) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('LOG IN')
                ->assertPathIs('/dashboard')
                ->visit('/notes')
                ->assertSee($note->judul)
                ->click('#delete-'.$note->id)
                ->waitForText('Note has been deleted', 5)
                ->assertSee('Note has been deleted')
                ->assertDontSee($note->judul);
        });
    }
} 