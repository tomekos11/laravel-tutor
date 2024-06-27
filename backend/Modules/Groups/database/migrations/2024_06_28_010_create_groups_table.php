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
        Schema::create('group__groups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id');
            $table->string('name');
            
            $table->timestamps();

            $table->foreign('course_id')
                ->references('id')
                ->on('group__courses')
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
        Schema::dropIfExists('group__groups');
    }
};
