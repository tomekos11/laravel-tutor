<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * Rozszerza istniejące tabele wiadomości/konwersacji o to, czego brakowało
     * do zbudowania czatu 1-1 oraz grupowego:
     * - conversations.type ("direct" | "group"),
     * - conversations.title / theme stają się opcjonalne (rozmowy 1-1 nie mają tytułu),
     * - messages.content jako TEXT zamiast VARCHAR(255),
     * - users_conversations.last_read_at do liczenia nieprzeczytanych wiadomości.
     *
     * Uwaga: zmiany typu/nullable kolumn robimy przez surowe ALTER TABLE (MySQL),
     * żeby nie wymagać instalacji doctrine/dbal tylko dla Blueprint::change().
     */
    public function up()
    {
        Schema::table('user__conversations', function (Blueprint $table) {
            $table->string('type')->default('direct')->after('owner_id');
        });

        DB::statement('ALTER TABLE user__conversations MODIFY title VARCHAR(255) NULL');
        DB::statement('ALTER TABLE user__conversations MODIFY theme VARCHAR(255) NULL');
        DB::statement('ALTER TABLE user__messages MODIFY content TEXT NOT NULL');

        Schema::table('user__users_conversations', function (Blueprint $table) {
            $table->timestamp('last_read_at')->nullable()->after('conversation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user__conversations', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        DB::statement("ALTER TABLE user__conversations MODIFY title VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE user__conversations MODIFY theme VARCHAR(255) NOT NULL");
        DB::statement('ALTER TABLE user__messages MODIFY content VARCHAR(255) NOT NULL');

        Schema::table('user__users_conversations', function (Blueprint $table) {
            $table->dropColumn('last_read_at');
        });
    }
};
