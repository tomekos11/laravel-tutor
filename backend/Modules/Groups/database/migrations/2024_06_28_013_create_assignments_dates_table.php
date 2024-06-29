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
        Schema::create('group__assignments_dates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_group_id');
            $table->timestamp('open_date');
            $table->timestamp('finish_date');
            
            $table->timestamps();

            $table->foreign('user_group_id')
                ->references('id')
                ->on('group__users_groups')
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
        Schema::dropIfExists('group__assignments_dates');
    }
};
