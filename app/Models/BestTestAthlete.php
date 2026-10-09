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

    /** Progression order: low index = lower belt, high index = higher belt */
    public static function beltRankOrder(): array
    {
        return array_keys(self::beltOptions());
    }

    public static function beltRank(string $beltType): int
    {
        $index = array_search($beltType, self::beltRankOrder(), true);

        return $index === false ? -1 : (int) $index;
    }

    public static function highestBeltForUser(int $userId): ?string
    {
        $rows = self::where('user_id', $userId)->pluck('belt_type');
        $highest = null;
        $highestRank = -1;

        foreach ($rows as $beltType) {
            $rank = self::beltRank((string) $beltType);
            if ($rank > $highestRank) {
                $highestRank = $rank;
                $highest = (string) $beltType;
            }
        }

        return $highest;
    }

    /**
     * Belts available for next apply:
     * - no previous belt => all options
     * - has belt (e.g. 4th Geup) => only higher belts (3rd, 2nd, 1st, Poom/Dan)
     */
    public static function availableBeltOptionsForUser(int $userId): array
    {
        $all = self::beltOptions();
        $highest = self::highestBeltForUser($userId);

        if (!$highest) {
            return $all;
        }

        $minRank = self::beltRank($highest);
        $available = [];

        foreach ($all as $key => $label) {
            if (self::beltRank($key) > $minRank) {
                $available[$key] = $label;
            }
        }

        return $available;
    }

    public static function isBeltAllowedForUser(int $userId, string $beltType): bool
    {
        $options = self::availableBeltOptionsForUser($userId);

        return array_key_exists($beltType, $options);
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
