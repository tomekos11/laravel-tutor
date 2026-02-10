<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up()
    {
        Schema::create('advertisement__advertisements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('user__users')
                ->cascadeOnDelete();

            $table->decimal('price', 10, 2);
            $table->text('description');
            
            $table->foreignId('field_id')
                ->constrained('course__fields')
                ->cascadeOnDelete();

            $table->string('address'); 

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('advertisement__advertisements');
    }
};
