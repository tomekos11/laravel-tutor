<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $exists = DB::table('user__roles')->where('name', 'parent')->exists();

        if (!$exists) {
            DB::table('user__roles')->insert([
                'name'       => 'parent',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('user__roles')->where('name', 'parent')->delete();
    }
};
