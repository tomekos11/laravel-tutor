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
        Schema::create('task__attachments', function (Blueprint $table) {
            $table->id();

            // Polymorphic owner: either a task or a task comment.
            $table->string('attachable_type');
            $table->unsignedBigInteger('attachable_id');

            $table->foreignId('uploaded_by')
                ->constrained('user__users')
                ->cascadeOnDelete();

            $table->string('path');
            $table->string('url');
            $table->string('original_name')->nullable();
            $table->unsignedBigInteger('size')->default(0);

            $table->timestamps();

            $table->index(['attachable_type', 'attachable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task__attachments');
    }
};
