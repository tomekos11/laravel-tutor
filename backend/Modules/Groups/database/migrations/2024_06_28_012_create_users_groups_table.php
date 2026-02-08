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
        Schema::create('group__users_groups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');
            $table->foreignId('group_id');
            $table->boolean('is_owner');
            
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('user__users')
                ->onDelete('cascade');

            $table->foreign('group_id')
                ->references('id')
                ->on('group__groups')
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
        Schema::dropIfExists('group__users_groups');
    }
};
