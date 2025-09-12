<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('soba_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soba_id')->constrained('sobas')->cascadeOnDelete();
            $table->date('date');
            $table->integer('free_rooms')->default(0);
            $table->unique(['soba_id','date']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soba_inventories');
    }
};
