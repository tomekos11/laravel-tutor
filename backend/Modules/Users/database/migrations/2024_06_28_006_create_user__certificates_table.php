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
        Schema::create('user__certificates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');

            $table->string('name');
            $table->string('img')->nullable();
            $table->string('link')->nullable();
            
            $table->string('issued_by');
            $table->string('issue_identifier');
            $table->date('issue_date');
            
            $table->timestamps();

            $table->foreign('user_id')
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
        Schema::dropIfExists('user__certificates');
    }
};
