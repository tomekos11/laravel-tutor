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
        Schema::create('course__courses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('level_id');
            $table->foreignId('field_id');
            
            $table->timestamps();

            $table->foreign('level_id')
                ->references('id')
                ->on('course__levels')
                ->onDelete('cascade');
            
            $table->foreign('field_id')
                ->references('id')
                ->on('course__fields')
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
        Schema::dropIfExists('course__courses');
    }
};
