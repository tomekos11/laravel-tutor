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
        Schema::create('task__group_tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_id')
                ->constrained('task__tasks')
                ->cascadeOnDelete();

            $table->foreignId('group_id')
                ->constrained('group__groups')
                ->cascadeOnDelete();

            $table->foreignId('assigned_by')
                ->constrained('user__users')
                ->cascadeOnDelete();

            $table->dateTime('due_date')->nullable();

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
        Schema::dropIfExists('task__group_tasks');
    }
};
