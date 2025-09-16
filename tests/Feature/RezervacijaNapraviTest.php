<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Soba;
use App\Models\Status;
use App\Models\Rezervacija;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class RezervacijaNapraviTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function gost_moze_da_napravi_rezervaciju(): void
    {
        Role::insert([
            ['id' => 1, 'naziv_role' => 'gost'],
            ['id' => 2, 'naziv_role' => 'recepcioner'],
            ['id' => 3, 'naziv_role' => 'menadzer'],
        ]);

        $gost = User::factory()->create(['role_id' => 1]);

        $soba = Soba::factory()->create();
        $status = Status::factory()->create(['naziv_statusa' => 'Kreirana']);

        $this->actingAs($gost);
        $odgovor = $this->post(route('rezervacijas.store'), [
            'soba_id'   => $soba->id,
            'datum_od'  => now()->addDay()->toDateString(),
            'datum_do'  => now()->addDays(2)->toDateString(),
            'broj_osoba'=> 2,
            'status_id' => $status->id,
        ]);

        $odgovor->assertRedirect(); 
        $this->assertDatabaseHas('rezervacijas', [
            'soba_id'   => $soba->id,
            'status_id' => $status->id,
        ]);
    }
}
