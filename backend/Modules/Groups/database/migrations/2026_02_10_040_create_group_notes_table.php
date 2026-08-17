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
        Schema::create('group__notes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_id')
                ->constrained('group__groups')
                ->cascadeOnDelete();

            $table->foreignId('author_id')
                ->constrained('user__users')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
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
        Schema::dropIfExists('group__notes');
    }
};
