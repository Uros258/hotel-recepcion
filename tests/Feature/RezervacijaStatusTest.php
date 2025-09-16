<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Soba;
use App\Models\Status;
use App\Models\Rezervacija;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RezervacijaStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function recepcioner_moze_da_promeni_status_rezervacije(): void
    {
        // Ubacujemo role u test bazu
        $gostRole = Role::create(['id' => 1, 'naziv_role' => 'gost']);
        $recepcionerRole = Role::create(['id' => 2, 'naziv_role' => 'recepcioner']);
        $menadzerRole = Role::create(['id' => 3, 'naziv_role' => 'menadzer']);

        // Pravimo recepcionera
        $recepcioner = User::factory()->create([
            'role_id' => $recepcionerRole->id,
        ]);

        // Pravimo sobu i rezervaciju
        $soba = Soba::factory()->create();
        $status = Status::factory()->create(['naziv_statusa' => 'Kreirana']);
        $rezervacija = Rezervacija::factory()->create([
            'soba_id' => $soba->id,
            'status_id' => $status->id,
        ]);

        // Novi status
        $noviStatus = Status::factory()->create(['naziv_statusa' => 'Potvrđena']);

        $odgovor = $this->actingAs($recepcioner)->post(
            route('rezervacijas.status', $rezervacija->id),
            ['status' => $noviStatus->naziv_statusa] // npr. "Potvrđena"
        );


        $odgovor->assertRedirect();

        // Proveravamo da je status promenjen u bazi
        $this->assertDatabaseHas('rezervacijas', [
            'id' => $rezervacija->id,
            'status_id' => $noviStatus->id,
        ]);
    }
}
