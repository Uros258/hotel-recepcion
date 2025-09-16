<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Rezervacijas', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned();

            $table->bigInteger('status_id')->unsigned();

            $table->bigInteger('soba_id')->unsigned();

            $table->date('datum_od');

            $table->date('datum_do');

            $table->integer('broj_osoba');

            $table->text('napomena')->nullable();

            $table->timestamp('created_at')->nullable();

            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table
                ->foreign('status_id')
                ->references('id')
                ->on('statuses')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table
                ->foreign('soba_id')
                ->references('id')
                ->on('sobas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Rezervacija');
    }
};
