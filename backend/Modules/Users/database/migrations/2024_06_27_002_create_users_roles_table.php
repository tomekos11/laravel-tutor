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
        Schema::create('user__users_roles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');
            $table->foreignId('role_id');

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('user__users')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('user__roles')
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
        Schema::dropIfExists('user__users_roles');
    }
};
