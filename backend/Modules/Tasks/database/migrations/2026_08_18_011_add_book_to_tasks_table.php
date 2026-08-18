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
        Schema::table('task__tasks', function (Blueprint $table) {
            $table->foreignId('book_id')
                ->nullable()
                ->after('author_id')
                ->constrained('book__books')
                ->nullOnDelete();

            $table->string('book_task_number')->nullable()->after('book_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task__tasks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('book_id');
            $table->dropColumn('book_task_number');
        });
    }
};
