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
        Schema::create('user__password_resets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id');

            // Gdzie wysłano kod – e‑mail lub SMS (na razie tylko logowany, brak bramki SMS).
            $table->enum('channel', ['email', 'phone'])->default('email');

            // 6-cyfrowy kod weryfikacyjny wysyłany użytkownikowi.
            $table->string('code', 6);

            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('user__users')
                ->onDelete('cascade');

            $table->index(['user_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user__password_resets');
    }
};
