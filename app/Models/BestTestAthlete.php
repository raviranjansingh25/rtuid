<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BestTestAthlete extends Model
{
    protected $table = 'best_test_athletes';

    protected $fillable = [
        'batch_id',
        'user_id',
        'belt_type',
        'grade',
        'certificate_ready',
        'status',
    ];

    public static function beltOptions(): array
    {
        return [
            '8th_geup_yellow' => '8th Geup – Yellow Belt Test',
            '7th_geup_green_yellow' => '7th Geup – Green-Yellow Belt Test (Yellow one)',
            '6th_geup_green' => '6th Geup – Green Belt Test',
            '5th_geup_blue_green' => '5th Geup – Blue-Green Belt Test (Green one)',
            '4th_geup_blue' => '4th Geup – Blue Belt Test',
            '3rd_geup_red_blue' => '3rd Geup – Red-Blue Belt Test (Blue one)',
            '2nd_geup_red' => '2nd Geup – Red Belt Test',
            '1st_geup_red_black' => '1st Geup – Red-Black Belt Test (Red one)',
            '1st_poom_dan' => '1st Poom / 1st Dan Promotion Test',
        ];
    }

    public function getBeltLabelAttribute(): string
    {
        $options = self::beltOptions();

        return $options[$this->belt_type] ?? $this->belt_type;
    }

    public function getBeltColorAttribute(): string
    {
        $map = [
            '8th_geup_yellow' => 'YELLOW',
            '7th_geup_green_yellow' => 'GREEN-YELLOW',
            '6th_geup_green' => 'GREEN',
            '5th_geup_blue_green' => 'BLUE-GREEN',
            '4th_geup_blue' => 'BLUE',
            '3rd_geup_red_blue' => 'RED-BLUE',
            '2nd_geup_red' => 'RED',
            '1st_geup_red_black' => 'RED-BLACK',
            '1st_poom_dan' => 'BLACK (1st Poom/Dan)',
        ];

        return $map[$this->belt_type] ?? strtoupper((string) $this->belt_type);
    }

    public function batch()
    {
        return $this->belongsTo(BestTestBatch::class, 'batch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
