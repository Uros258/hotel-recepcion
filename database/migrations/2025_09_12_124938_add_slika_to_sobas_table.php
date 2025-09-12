<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sobas', function (Blueprint $table) {
            $table->string('slika')->nullable()->after('status_sobe');
        });
    }

    public function down()
    {
        Schema::table('sobas', function (Blueprint $table) {
            $table->dropColumn('slika');
        });
    }

};
