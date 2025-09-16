<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('sobas', function (Blueprint $t) {
            $t->unsignedInteger('ukupno_soba')->default(1)->after('cena');
        });
    }
    public function down(): void {
        Schema::table('sobas', function (Blueprint $t) {
            $t->dropColumn('ukupno_soba');
        });
    }
};
