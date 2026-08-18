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
        Schema::create('task__comments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_id')
                ->constrained('task__tasks')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('user__users')
                ->cascadeOnDelete();

            $table->text('content');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task__comments');
    }
};
