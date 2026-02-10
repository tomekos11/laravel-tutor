<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up()
    {
        Schema::create('advertisement__advertisement_levels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('advertisement_id')
                ->constrained('advertisement__advertisements')
                ->cascadeOnDelete();

            $table->foreignId('level_id')
                ->constrained('course__levels')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(
            ['advertisement_id', 'level_id'],
            'adv_level_unique' // dowolna krótka nazwa
            );
        });
    }

    public function down()
    {
        Schema::dropIfExists('advertisement__advertisement_levels');
    }
};
