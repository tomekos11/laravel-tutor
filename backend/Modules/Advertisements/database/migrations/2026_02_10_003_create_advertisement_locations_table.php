<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up()
    {
        Schema::create('advertisement__advertisement_locations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('advertisement_id')
                ->constrained('advertisement__advertisements')
                ->cascadeOnDelete();

            $table->foreignId('location_id')
                ->constrained('advertisement__locations')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['advertisement_id', 'location_id'], 'adv_loc_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('advertisement__advertisement_locations');
    }
};
