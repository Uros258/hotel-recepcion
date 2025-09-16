<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
    {
        Schema::table('sobas', function (Blueprint $table) {
            $table->text('opis')->nullable()->after('tip_sobe');
        });
    }

    public function down(): void
    {
        Schema::table('sobas', function (Blueprint $table) {
            $table->dropColumn('opis');
        });
    }

};
