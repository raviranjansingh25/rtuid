<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            if (!Schema::hasColumn('admins', 'can_apply_tournament')) {
                $table->tinyInteger('can_apply_tournament')->default(0)->after('apply_tournament');
            }
        });

        if (Schema::hasColumn('admins', 'apply_tournament')) {
            DB::table('admins')->update([
                'can_apply_tournament' => DB::raw('apply_tournament'),
            ]);
        }
    }

    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            if (Schema::hasColumn('admins', 'can_apply_tournament')) {
                $table->dropColumn('can_apply_tournament');
            }
        });
    }
};
