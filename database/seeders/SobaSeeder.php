<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SobaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sobas')->upsert([
            ['id'=>1,'broj_sobe'=>101,'tip_sobe'=>'Single','cena'=>4500,'status_sobe'=>'slobodna','created_at'=>now(),'updated_at'=>now()],
            ['id'=>2,'broj_sobe'=>102,'tip_sobe'=>'Double','cena'=>6500,'status_sobe'=>'slobodna','created_at'=>now(),'updated_at'=>now()],
            ['id'=>3,'broj_sobe'=>201,'tip_sobe'=>'Suite','cena'=>12000,'status_sobe'=>'slobodna','created_at'=>now(),'updated_at'=>now()],
        ], ['id'], ['broj_sobe','tip_sobe','cena','status_sobe','updated_at']);
    }
}
