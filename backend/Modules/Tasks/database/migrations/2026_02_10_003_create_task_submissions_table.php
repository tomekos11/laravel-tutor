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
        Schema::create('task__submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_task_id')
                ->constrained('task__group_tasks')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('user__users')
                ->cascadeOnDelete();

            $table->string('status')->default('pending');
            $table->text('content')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->tinyInteger('grade')->nullable();
            $table->text('feedback')->nullable();

            $table->timestamps();

            $table->unique(['group_task_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task__submissions');
    }
};
