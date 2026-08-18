<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Dodaje opcjonalne powiązanie wiadomości z ogłoszeniem, którego dotyczy
     * (np. pierwsza wiadomość wysłana z poziomu strony ogłoszenia "Napisz wiadomość").
     * Nie ustawiamy FK z kaskadowym usuwaniem - jeśli ogłoszenie zniknie, wiadomość
     * ma zostać, tylko traci odnośnik (SET NULL).
     */
    public function up()
    {
        Schema::table('user__messages', function (Blueprint $table) {
            $table->unsignedBigInteger('advertisement_id')->nullable()->after('conversation_id');
            $table->foreign('advertisement_id')
                ->references('id')->on('advertisement__advertisements')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('user__messages', function (Blueprint $table) {
            $table->dropForeign(['advertisement_id']);
            $table->dropColumn('advertisement_id');
        });
    }
};
