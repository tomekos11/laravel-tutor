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
        Schema::create('task__ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_id')
                ->constrained('task__tasks')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('user__users')
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('rating');

            $table->timestamps();

            $table->unique(['task_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task__ratings');
    }
};
