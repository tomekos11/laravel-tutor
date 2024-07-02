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
        Schema::create('user__conversations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('owner_id');

            $table->string('title');
            $table->string('theme');

            $table->timestamps();

            $table->foreign('owner_id')
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
        Schema::dropIfExists('user__conversations');
    }
};
