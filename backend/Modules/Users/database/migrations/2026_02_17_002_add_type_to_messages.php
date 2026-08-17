<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Dodaje typ wiadomości ("text" | "system"), żeby móc odróżnić zwykłe
     * wiadomości od zdarzeń systemowych (np. "X dołączył do czatu").
     */
    public function up()
    {
        Schema::table('user__messages', function (Blueprint $table) {
            $table->string('type')->default('text')->after('creator_id');
        });
    }

    public function down()
    {
        Schema::table('user__messages', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
