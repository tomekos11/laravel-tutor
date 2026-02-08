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
        Schema::create('user__messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('conversation_id');
            $table->foreignId('creator_id');

            $table->string('content');
            $table->string('img')->nullable();;

            $table->timestamps();

            $table->foreign('conversation_id')
                ->references('id')
                ->on('user__conversations')
                ->onDelete('cascade');

            $table->foreign('creator_id')
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
        Schema::dropIfExists('user__messages');
    }
};
