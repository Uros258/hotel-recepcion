<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->upsert([
            ['id'=>1,'naziv_role'=>'gost','created_at'=>now(),'updated_at'=>now()],
            ['id'=>2,'naziv_role'=>'recepcioner','created_at'=>now(),'updated_at'=>now()],
            ['id'=>3,'naziv_role'=>'menadzer','created_at'=>now(),'updated_at'=>now()],
            ['id'=>4,'naziv_role'=>'admin','created_at'=>now(),'updated_at'=>now()],
        ], ['id'], ['naziv_role','updated_at']);
    }
}
