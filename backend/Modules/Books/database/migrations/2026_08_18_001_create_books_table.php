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
        Schema::create('book__books', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('author')->nullable();
            $table->string('language')->nullable();
            $table->string('subject')->nullable();
            $table->string('publisher')->nullable();
            $table->string('isbn')->nullable();
            $table->year('publication_year')->nullable();
            $table->string('edition')->nullable();
            $table->string('cover_path')->nullable();
            $table->text('description')->nullable();

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
        Schema::dropIfExists('book__books');
    }
};
