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
        Schema::create('user__parent_children', function (Blueprint $table) {
            $table->id();

            $table->foreignId('parent_id');
            $table->foreignId('child_id');

            // 'approved'  - the link is active, the parent can view the child's account.
            // 'pending'   - waiting for the child to confirm a link request initiated by the parent.
            $table->enum('status', ['pending', 'approved'])->default('approved');

            // Who created the link request: the parent (created the child account or
            // requested to link an existing one) or the child (requested a parent link).
            $table->enum('requested_by', ['parent', 'child'])->default('parent');

            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('user__users')
                ->onDelete('cascade');

            $table->foreign('child_id')
                ->references('id')
                ->on('user__users')
                ->onDelete('cascade');

            $table->unique(['parent_id', 'child_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user__parent_children');
    }
};
