<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tournament;
use App\Models\ApplyTournament;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UpdateTournament extends Command
{
    protected $signature = 'update:tournament';

    protected $description = 'Finalize ended tournaments and clear apply weights the day after end date';

    public function handle()
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        $endedToday = Tournament::where('end_date', $currentDate)->pluck('id')->toArray();
        if (!empty($endedToday)) {
            ApplyTournament::whereIn('turnament_id', $endedToday)->update(['final' => 1]);
            Log::info('Tournament final lock count: ' . count($endedToday));
        }

        $endedYesterday = Tournament::where('end_date', $yesterday)->pluck('id')->toArray();
        if (!empty($endedYesterday)) {
            $applyRows = ApplyTournament::whereIn('turnament_id', $endedYesterday)->get();
            foreach ($applyRows as $apply) {
                $apply->actul_waight = null;
                $apply->save();

                if (!empty($apply->user_id)) {
                    User::where('id', $apply->user_id)->update(['weight' => null]);
                }
            }
            Log::info('Cleared apply tournament weights for tournaments ended on: ' . $yesterday);
        }

        return Command::SUCCESS;
    }
}
