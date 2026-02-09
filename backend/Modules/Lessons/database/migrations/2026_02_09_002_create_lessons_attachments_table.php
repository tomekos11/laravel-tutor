<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up()
    {
        Schema::create('lesson__lesson_attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lesson_id')
                ->constrained('lesson__lessons')
                ->onDelete('cascade');

            $table->string('filename');           // nazwa oryginalna
            $table->string('path');               // ścieżka względna w storage/public
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable(); // bytes

            $table->string('title')->nullable(); 
            $table->text('description')->nullable();

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('lesson__lesson_attachments');
    }
};
