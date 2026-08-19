<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up()
    {
        Schema::table('advertisement__advertisements', function (Blueprint $table) {
            // Promień dojazdu w km - używany, gdy korepetytor dojeżdża "do ucznia"
            // (address przechowuje wtedy np. "Rzeszów +30km").
            $table->unsignedInteger('travel_radius_km')->nullable()->after('address');

            // Opcjonalna stawka za każdy km dojazdu do ucznia.
            $table->decimal('price_per_km', 8, 2)->nullable()->after('travel_radius_km');
        });
    }

    public function down()
    {
        Schema::table('advertisement__advertisements', function (Blueprint $table) {
            $table->dropColumn(['travel_radius_km', 'price_per_km']);
        });
    }
};
