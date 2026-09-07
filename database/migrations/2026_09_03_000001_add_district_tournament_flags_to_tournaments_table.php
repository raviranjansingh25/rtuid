<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tournaments', function (Blueprint $table) {
            if (!Schema::hasColumn('tournaments', 'is_district_tournament')) {
                $table->tinyInteger('is_district_tournament')->default(0)->after('draw_sheet_verified');
            }
            if (!Schema::hasColumn('tournaments', 'district_id')) {
                $table->string('district_id', 100)->nullable()->after('is_district_tournament');
            }
            if (!Schema::hasColumn('tournaments', 'district_apply_open')) {
                $table->tinyInteger('district_apply_open')->default(0)->after('district_id');
            }
            if (!Schema::hasColumn('tournaments', 'district_draw_sheet_open')) {
                $table->tinyInteger('district_draw_sheet_open')->default(0)->after('district_apply_open');
            }
            if (!Schema::hasColumn('tournaments', 'coach_apply_weight_open')) {
                $table->tinyInteger('coach_apply_weight_open')->default(0)->after('district_draw_sheet_open');
            }
            if (!Schema::hasColumn('tournaments', 'athlete_apply_weight_open')) {
                $table->tinyInteger('athlete_apply_weight_open')->default(0)->after('coach_apply_weight_open');
            }
        });

        if (Schema::hasColumn('tournaments', 'allow_coach_weight')) {
            DB::table('tournaments')->update([
                'coach_apply_weight_open' => DB::raw('allow_coach_weight'),
                'athlete_apply_weight_open' => DB::raw('allow_athlete_weight'),
            ]);
        }

        DB::table('tournaments')
            ->whereNotNull('created_by_subadmin')
            ->orWhere(function ($q) {
                $q->whereNotNull('district')->where('district', '<>', '');
            })
            ->update([
                'is_district_tournament' => 1,
                'district_id' => DB::raw('district'),
            ]);
    }

    public function down()
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $cols = [
                'is_district_tournament',
                'district_id',
                'district_apply_open',
                'district_draw_sheet_open',
                'coach_apply_weight_open',
                'athlete_apply_weight_open',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('tournaments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
