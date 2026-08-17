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
        Schema::table('lesson__user_lessons', function (Blueprint $table) {
            $table->boolean('absence_reported')->default(false)->after('comment');
            $table->text('absence_reason')->nullable()->after('absence_reported');
            $table->dateTime('absence_reported_at')->nullable()->after('absence_reason');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson__user_lessons', function (Blueprint $table) {
            $table->dropColumn(['absence_reported', 'absence_reason', 'absence_reported_at']);
        });
    }
};
