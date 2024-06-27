<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system__streaks', function (Blueprint $table) {
            $table->id();

            //pola relacyjne
            $table->foreignId('user_id');

            //pola
            $table->integer('count');
            $table->timestamps();

            //index
            $table->index('user_id');

            //relacja z streak-user
            $table->foreign('user_id')
                ->references('id')
                ->on('auth__users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system__streaks');
    }
};
