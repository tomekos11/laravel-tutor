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
        Schema::table('lesson__lessons', function (Blueprint $table) {
            $table->string('status')->default('scheduled')->after('notes');

            $table->foreignId('cancelled_by')
                ->nullable()
                ->after('status')
                ->constrained('user__users')
                ->nullOnDelete();

            $table->text('cancelled_reason')->nullable()->after('cancelled_by');
            $table->dateTime('cancelled_at')->nullable()->after('cancelled_reason');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson__lessons', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cancelled_by');
            $table->dropColumn(['status', 'cancelled_reason', 'cancelled_at']);
        });
    }
};
