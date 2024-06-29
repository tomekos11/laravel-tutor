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
        Schema::create('group__assignments_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('assignment_answer_correctness_id');
            
            $table->boolean('is_correct');

            $table->timestamps();

            $table->foreign('group__assignments_answers_correctness')
                ->references('id')
                ->on('group__assignments')
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
        Schema::dropIfExists('group__assignments_answers');
    }
};
