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
        Schema::create('course__answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('creator_id');
            $table->foreignId('question_id');

            $table->timestamps();

            $table->foreign('creator_id')
                ->references('id')
                ->on('user__users')
                ->onDelete('cascade');

            $table->foreign('question_id')
                ->references('id')
                ->on('course__questions')
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
        Schema::dropIfExists('course__answers');
    }
};
