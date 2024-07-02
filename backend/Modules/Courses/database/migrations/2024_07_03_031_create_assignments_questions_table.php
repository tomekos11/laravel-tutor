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
        Schema::create('course__assignments_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('assignment_date_id');
            $table->foreignId('question_id');

            $table->timestamps();

            $table->foreign('assignment_date_id')
                ->references('id')
                ->on('group__assignments_dates')
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
        Schema::dropIfExists('course__assignments_questions');
    }
};
