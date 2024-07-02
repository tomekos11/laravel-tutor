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

            $table->foreignId('answer_correctness_id');
            
            $table->boolean('is_correct');

            $table->timestamps();

            $table->foreign('answer_correctness_id')
                ->references('id')
                ->on('group__answers_correctness')
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
