<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('statuses')->upsert([
            ['id'=>1,'naziv_statusa'=>'Kreirana','created_at'=>now(),'updated_at'=>now()],
            ['id'=>2,'naziv_statusa'=>'Potvrđena','created_at'=>now(),'updated_at'=>now()],
            ['id'=>3,'naziv_statusa'=>'Prijavljen','created_at'=>now(),'updated_at'=>now()],
            ['id'=>4,'naziv_statusa'=>'Odjavljen','created_at'=>now(),'updated_at'=>now()],
            ['id'=>5,'naziv_statusa'=>'Otkazana','created_at'=>now(),'updated_at'=>now()],
        ], ['id'], ['naziv_statusa','updated_at']);
    }
}
