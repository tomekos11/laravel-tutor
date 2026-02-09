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
        Schema::create('lesson__user_lessons', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lesson_id')
                ->constrained('lesson__lessons')
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->constrained('user__users')
                ->cascadeOnDelete();
           
            $table->tinyInteger('grade')->nullable(); // ocena za zajecia
            $table->text('comment')->nullable(); // komentarz do zajec
            
            $table->timestamps();
            $table->unique(['lesson_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson__user_lessons');
    }
};
