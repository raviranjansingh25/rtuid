<?php

namespace App\Services;

use App\Models\BestTestAthlete;
use App\Models\BestTestBatch;
use App\Models\Tags;

class BestTestCertificateRenderer
{
    public static function blankPath(): string
    {
        return public_path('certificates/color_belt_certificate_blank.jpg');
    }

    /**
     * Same Arial as the original Color Belt certificate (1st image).
     */
    public static function resolveFontPath(bool $bold = false): string
    {
        $candidates = $bold
            ? [
                public_path('certificates/fonts/arialbd.ttf'),
                base_path('public/certificates/fonts/arialbd.ttf'),
                'C:\\Windows\\Fonts\\arialbd.ttf',
                '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf',
                '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
                '/usr/share/fonts/truetype/freefont/FreeSansBold.ttf',
            ]
            : [
                public_path('certificates/fonts/arial.ttf'),
                base_path('public/certificates/fonts/arial.ttf'),
                'C:\\Windows\\Fonts\\arial.ttf',
                '/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf',
                '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
                '/usr/share/fonts/truetype/freefont/FreeSans.ttf',
            ];

        foreach ($candidates as $path) {
            if ($path && is_file($path) && is_readable($path)) {
                return $path;
            }
        }

        throw new \RuntimeException(
            'Certificate font not found. Upload arial.ttf to public/certificates/fonts/ on the server.'
        );
    }

    public static function blankUrl(): string
    {
        return url('/public/certificates/color_belt_certificate_blank.jpg');
    }

    public static function certificateNo(BestTestBatch $batch, BestTestAthlete $row): string
    {
        return (string) ($row->id ?: ($batch->id . '00'));
    }

    public static function athleteName(BestTestAthlete $row): string
    {
        $user = $row->user;
        return strtoupper(trim(($user->name ?? '') . ' ' . ($user->last_name ?? '')));
    }

    public static function fatherName(BestTestAthlete $row): string
    {
        return strtoupper(trim((string) ($row->user->father_name ?? '')));
    }

    public static function districtName(BestTestAthlete $row, BestTestBatch $batch): string
    {
        $districtId = $row->user->district ?? $batch->district;
        if (!empty($row->user->district_name) && !is_numeric($row->user->district_name)) {
            return strtoupper($row->user->district_name);
        }
        $tag = Tags::find($districtId);
        return strtoupper($tag->title ?? (string) $districtId);
    }

    public static function issueDate(BestTestBatch $batch): string
    {
        return $batch->exam_date ? $batch->exam_date->format('d-m-Y') : date('d-m-Y');
    }

    public static function examDate(BestTestBatch $batch): string
    {
        return $batch->exam_date ? $batch->exam_date->format('d-m-Y') : '';
    }

    public static function place(BestTestBatch $batch): string
    {
        return strtoupper(trim((string) ($batch->place ?? '')));
    }

    public static function beltColor(BestTestAthlete $row): string
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

        return $map[$row->belt_type] ?? strtoupper((string) $row->belt_type);
    }

    public static function grade(BestTestAthlete $row): string
    {
        return strtoupper(trim((string) ($row->grade ?? '')));
    }

    public static function payload(BestTestBatch $batch, BestTestAthlete $row): array
    {
        return [
            'no' => self::certificateNo($batch, $row),
            'date' => self::issueDate($batch),
            'name' => self::athleteName($row),
            'father' => self::fatherName($row),
            'district' => self::districtName($row, $batch),
            'exam_date' => self::examDate($batch),
            'place' => self::place($batch),
            'belt' => self::beltColor($row),
            'grade' => self::grade($row),
            'blank_url' => self::blankUrl(),
        ];
    }

    /**
     * Render filled certificate JPG binary.
     */
    public static function renderJpg(BestTestBatch $batch, BestTestAthlete $row): string
    {
        $blank = self::blankPath();
        if (!file_exists($blank)) {
            throw new \RuntimeException('Certificate blank template missing.');
        }

        $img = @imagecreatefromjpeg($blank);
        if (!$img) {
            throw new \RuntimeException('Unable to read certificate blank template.');
        }

        $w = imagesx($img);
        $h = imagesy($img);
        $black = imagecolorallocate($img, 40, 40, 40);
        $regular = self::resolveFontPath(false);
        $bold = self::resolveFontPath(true);
        $data = self::payload($batch, $row);

        $size = max(13, (int) round($w * 0.0185));
        $left = (int) round($w * 0.125);
        $right = (int) round($w * 0.87);
        $lineH = (int) round($h * 0.0305);
        $y = (int) round($h * 0.608);

        // Line 1: No. ....value....     Date: value
        self::drawMixed($img, $left, $y, array_merge(
            [['No. ', 'r']],
            self::dottedParts($data['no'], 5, 10)
        ), $size, $regular, $bold, $black);
        $dateParts = [['Date: ', 'r'], [$data['date'], 'b']];
        $dateW = self::measureMixed($dateParts, $size, $regular, $bold);
        self::drawMixed($img, $right - $dateW, $y, $dateParts, $size, $regular, $bold, $black);

        // Line 2: This is to certify that Mr./Ms. ....NAME
        $y += $lineH;
        self::drawMixed($img, $left, $y, array_merge(
            [['This is to certify that Mr./Ms. ', 'r']],
            self::dottedParts($data['name'], 6, 6)
        ), $size, $regular, $bold, $black);

        // Line 3: Son / Daughter ....FATHER.... of ....DISTRICT.... District
        $y += $lineH;
        self::drawMixed($img, $left, $y, array_merge(
            [['Son / Daughter ', 'r']],
            self::dottedParts($data['father'], 3, 4),
            [[' of ', 'r']],
            self::dottedParts($data['district'], 2, 3),
            [[' District', 'r']]
        ), $size, $regular, $bold, $black);

        // Line 4: qualified ... Held on DATE.... at ....PLACE
        $y += $lineH;
        self::drawMixed($img, $left, $y, array_merge(
            [['qualified in Color Belt Promotion Test Held on ', 'r']],
            self::dottedParts($data['exam_date'], 0, 3),
            [[' at ', 'r']],
            self::dottedParts($data['place'], 1, 2)
        ), $size, $regular, $bold, $black);

        // Line 5: is permitted to wear ....BELT.... belt
        $y += $lineH;
        self::drawMixed($img, $left, $y, array_merge(
            [['is permitted to wear ', 'r']],
            self::dottedParts($data['belt'], 6, 6),
            [[' belt', 'r']]
        ), $size, $regular, $bold, $black);

        // Line 6: He/She is not permitted...     Grade: VALUE
        $y += $lineH;
        self::drawMixed($img, $left, $y, [
            ['He/She is not permitted to teach taekwondo', 'r'],
        ], $size, $regular, $bold, $black);
        $gradeParts = [['Grade: ', 'r'], [$data['grade'], 'b']];
        $gradeW = self::measureMixed($gradeParts, $size, $regular, $bold);
        self::drawMixed($img, $right - $gradeW, $y, $gradeParts, $size, $regular, $bold, $black);

        ob_start();
        imagejpeg($img, null, 92);
        $binary = ob_get_clean();
        imagedestroy($img);

        return $binary;
    }

    protected static function dottedParts(string $value, int $leftDots = 4, int $rightDots = 8): array
    {
        $value = trim($value);
        $parts = [];
        if ($leftDots > 0) {
            $parts[] = [str_repeat('.', $leftDots), 'r'];
        }
        if ($value !== '') {
            $parts[] = [$value, 'b'];
        }
        if ($rightDots > 0) {
            $parts[] = [str_repeat('.', $rightDots), 'r'];
        }

        return $parts;
    }

    protected static function drawMixed($img, int $x, int $y, array $parts, int $size, string $regular, string $bold, $color): int
    {
        foreach ($parts as $part) {
            $text = (string) $part[0];
            if ($text === '') {
                continue;
            }
            $font = (($part[1] ?? 'r') === 'b') ? $bold : $regular;
            imagettftext($img, $size, 0, $x, $y, $color, $font, $text);
            $box = imagettfbbox($size, 0, $font, $text);
            $x += (int) abs($box[2] - $box[0]);
        }

        return $x;
    }

    protected static function measureMixed(array $parts, int $size, string $regular, string $bold): int
    {
        $w = 0;
        foreach ($parts as $part) {
            $text = (string) $part[0];
            if ($text === '') {
                continue;
            }
            $font = (($part[1] ?? 'r') === 'b') ? $bold : $regular;
            $box = imagettfbbox($size, 0, $font, $text);
            $w += (int) abs($box[2] - $box[0]);
        }

        return $w;
    }
}
