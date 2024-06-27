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
        Schema::create('user__users', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');

            $table->string('username')->unique();
            $table->string('password');

            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->date('birthday');

            $table->string('image');

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
        Schema::dropIfExists('user__users');
    }
};
