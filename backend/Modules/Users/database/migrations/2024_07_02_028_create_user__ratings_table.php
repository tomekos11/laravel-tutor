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
        Schema::create('user__ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tutor_id');
            $table->foreignId('reviewer_id');

            $table->boolean('tutor_made_this_review');
            $table->unsignedInteger('rating');
            $table->string('description');

            $table->timestamps();

            $table->foreign('tutor_id')
                ->references('id')
                ->on('user__users')
                ->onDelete('cascade');

            $table->foreign('reviewer_id')
                ->references('id')
                ->on('user__users')
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
        Schema::dropIfExists('user__ratings');
    }
};
