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
        Schema::table('task__comments', function (Blueprint $table) {
            $table->boolean('is_pinned')->default(false)->after('content');
            $table->unsignedTinyInteger('pinned_rating')->nullable()->after('is_pinned');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task__comments', function (Blueprint $table) {
            $table->dropColumn(['is_pinned', 'pinned_rating']);
        });
    }
};
