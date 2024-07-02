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
        Schema::create('group__groups_permissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_id');
            $table->foreignId('permission_id');
            
            $table->timestamps();

            $table->foreign('group_id')
                ->references('id')
                ->on('group__groups')
                ->onDelete('cascade');

            $table->foreign('permission_id')
                ->references('id')
                ->on('group__permissions')
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
        Schema::dropIfExists('group__groups_permissions');
    }
};
