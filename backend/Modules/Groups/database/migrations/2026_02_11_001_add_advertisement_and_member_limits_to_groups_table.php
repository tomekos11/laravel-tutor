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
        Schema::table('group__groups', function (Blueprint $table) {
            $table->foreignId('advertisement_id')
                ->nullable()
                ->after('course_id')
                ->constrained('advertisement__advertisements')
                ->nullOnDelete();

            $table->unsignedInteger('min_members')->nullable()->after('name');
            $table->unsignedInteger('max_members')->nullable()->after('min_members');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group__groups', function (Blueprint $table) {
            $table->dropConstrainedForeignId('advertisement_id');
            $table->dropColumn(['min_members', 'max_members']);
        });
    }
};
