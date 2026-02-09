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
        Schema::create('lesson__lessons', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_id')
                ->constrained('group__groups')
                ->restrictOnDelete();
            
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->dateTime('real_end_time')->nullable(); // do liczenia czasu trwania zajec, moze byc rozna od end_time, jesli zajecia sie przedluzyly

            $table->boolean('remote')->default(true);
            $table->boolean('shared')->default(false); // czy globalnie udostepnic w swoim harmonogramie
            $table->text('notes')->nullable(); // komentarz do zajec
            
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
        Schema::dropIfExists('lesson__lessons');
    }
};
