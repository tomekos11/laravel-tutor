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
        Schema::create('course__courses_topics', function (Blueprint $table) {
            $table->id();

            $table->foreignId('topic_id');
            $table->foreignId('course_id');

            $table->timestamps();

            $table->foreign('topic_id')
                ->references('id')
                ->on('course__topics')
                ->onDelete('cascade');

            $table->foreign('course_id')
                ->references('id')
                ->on('course__courses')
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
        Schema::dropIfExists('course__courses_topics');
    }
};
