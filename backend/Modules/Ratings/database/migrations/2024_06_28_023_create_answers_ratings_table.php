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
        Schema::create('course__answers_ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reviewer_id');
            $table->foreignId('answer_id');

            $table->integer('mark');
            $table->timestamps();

            $table->foreign('reviewer_id')
                ->references('id')
                ->on('user__users')
                ->onDelete('cascade');

            $table->foreign('answer_id')
                ->references('id')
                ->on('course__answers')
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
        Schema::dropIfExists('course__answers_ratings');
    }
};
