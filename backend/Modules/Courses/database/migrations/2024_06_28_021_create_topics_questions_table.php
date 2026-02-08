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
        Schema::create('course__topics_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('topic_id');
            $table->foreignId('question_id');

            $table->timestamps();

            $table->foreign('topic_id')
                ->references('id')
                ->on('course__topics')
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
        Schema::dropIfExists('course__topics_questions');
    }
};
