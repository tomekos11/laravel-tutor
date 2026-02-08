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

            $table->string('username')->unique();
            $table->string('password');

            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->date('birthday')->nullable();

            $table->string('image')->nullable()->default(null);

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
