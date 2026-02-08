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
        Schema::create('group__answers_correctness', function (Blueprint $table) {
            $table->id();

            $table->foreignId('assignment_id');
            $table->foreignId('question_id');

            $table->boolean('is_correct');
            $table->timestamps();

            $table->foreign('assignment_id')
                ->references('id')
                ->on('group__assignments')
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
        Schema::dropIfExists('group__answers_correctness');
    }
};
