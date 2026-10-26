<?php

namespace App\Services;

use App\Models\BestTestAthlete;
use App\Models\BestTestBatch;
use App\Models\Tags;

class BestTestCertificateRenderer
{
    public static function blankPath(): string
    {
        $jpg = public_path('certificates/color_belt_certificate_blank.jpg');
        if (is_file($jpg)) {
            return $jpg;
        }

        return public_path('certificates/color_belt_certificate_blank.png');
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
        if (is_file(public_path('certificates/color_belt_certificate_blank.jpg'))) {
            return url('/public/certificates/color_belt_certificate_blank.jpg');
        }

        return url('/public/certificates/color_belt_certificate_blank.png');
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
        $name = strtoupper(trim((string) ($row->user->father_name ?? '')));
        if (mb_strlen($name) > 20) {
            $name = rtrim(mb_substr($name, 0, 19)) . '.';
        }

        return $name;
    }

    public static function districtName(BestTestAthlete $row, BestTestBatch $batch): string
    {
        $districtId = $row->user->district ?? $batch->district;
        if (!empty($row->user->district_name) && !is_numeric($row->user->district_name)) {
            $name = strtoupper($row->user->district_name);
        } else {
            $tag = Tags::find($districtId);
            $name = strtoupper($tag->title ?? (string) $districtId);
        }
        if (mb_strlen($name) > 16) {
            $name = rtrim(mb_substr($name, 0, 15)) . '.';
        }

        return $name;
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
        $place = strtoupper(trim((string) ($batch->place ?? '')));
        if (mb_strlen($place) > 14) {
            $place = rtrim(mb_substr($place, 0, 13)) . '.';
        }

        return $place;
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
            '1st_poom_dan' => 'BLACK 1ST POOM',
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
     * Render filled certificate PNG binary. Original blank colors are copied as-is.
     */
    public static function renderJpg(BestTestBatch $batch, BestTestAthlete $row): string
    {
        $blank = self::blankPath();
        if (!file_exists($blank)) {
            throw new \RuntimeException('Certificate blank template missing.');
        }

        $ext = strtolower(pathinfo($blank, PATHINFO_EXTENSION));
        $src = ($ext === 'png') ? @imagecreatefrompng($blank) : @imagecreatefromjpeg($blank);
        if (!$src) {
            throw new \RuntimeException('Unable to read certificate blank template.');
        }

        $w = imagesx($src);
        $h = imagesy($src);
        $img = imagecreatetruecolor($w, $h);
        imagecopy($img, $src, 0, 0, 0, 0, $w, $h);
        imagedestroy($src);

        $ink = imagecolorallocate($img, 51, 51, 51);
        $regular = self::resolveFontPath(false);
        $bold = self::resolveFontPath(true);
        $data = self::payload($batch, $row);
        $size = max(11, (int) round($w * 0.0166));
        $left = (int) round($w * 0.125);
        $right = (int) round($w * 0.868);
        $mid = (int) round($w * 0.62);
        $lineH = (int) round($h * 0.034);
        $y = (int) round($h * 0.605);

        $x = self::drawText($img, $left, $y, 'No. ', $size, $regular, $ink);
        self::fillDots($img, $x, $y, $mid - 16, $data['no'], $size, $regular, $bold, $ink);
        $x = self::drawText($img, $mid + 8, $y, 'Date: ', $size, $regular, $ink);
        self::fillDots($img, $x, $y, $right, $data['date'], $size, $regular, $bold, $ink);

        $y += $lineH;
        $x = self::drawText($img, $left, $y, 'This is to certify that Mr./Ms. ', $size, $regular, $ink);
        self::fillDots($img, $x, $y, $right, $data['name'], $size, $regular, $bold, $ink);

        $y += $lineH;
        $x = self::drawText($img, $left, $y, 'Son / Daughter ', $size, $regular, $ink);
        $ofW = self::textWidth(' of ', $size, $regular);
        $distLblW = self::textWidth(' District, has', $size, $regular);
        $split = (int) round(($x + ($right - $distLblW - $ofW)) / 2);
        self::fillDots($img, $x, $y, $split, $data['father'], $size, $regular, $bold, $ink);
        $x = self::drawText($img, $split, $y, ' of ', $size, $regular, $ink);
        self::fillDots($img, $x, $y, $right - $distLblW, $data['district'], $size, $regular, $bold, $ink);
        self::drawText($img, $right - $distLblW, $y, ' District, has', $size, $regular, $ink);

        $y += $lineH;
        $x = self::drawText($img, $left, $y, 'qualified in Color Belt Promotion Test Held on ', $size, $regular, $ink);
        $atW = self::textWidth(' at ', $size, $regular);
        $split = $x + (int) round(($right - $x - $atW) * 0.50);
        self::fillDots($img, $x, $y, $split, $data['exam_date'], $size, $regular, $bold, $ink);
        $x = self::drawText($img, $split, $y, ' at ', $size, $regular, $ink);
        self::fillDots($img, $x, $y, $right, $data['place'], $size, $regular, $bold, $ink);

        $y += $lineH;
        $x = self::drawText($img, $left, $y, 'is permitted to wear ', $size, $regular, $ink);
        $beltW = self::textWidth(' belt.', $size, $regular);
        self::fillDots($img, $x, $y, $right - $beltW, $data['belt'], $size, $regular, $bold, $ink);
        self::drawText($img, $right - $beltW, $y, ' belt.', $size, $regular, $ink);

        $y += $lineH;
        self::drawText($img, $left, $y, 'He/She is not permitted to teach taekwondo', $size, $regular, $ink);
        $x = self::drawText($img, $mid + 8, $y, 'Grade: ', $size, $regular, $ink);
        self::fillDots($img, $x, $y, $right, $data['grade'], $size, $regular, $bold, $ink);

        ob_start();
        imagepng($img, null, 6);
        $binary = ob_get_clean();
        imagedestroy($img);

        return $binary;
    }

    protected static function drawText($img, int $x, int $y, string $text, int $size, string $font, $color): int
    {
        if ($text === '') {
            return $x;
        }
        imagettftext($img, $size, 0, $x, $y, $color, $font, $text);

        return $x + self::textWidth($text, $size, $font);
    }

    protected static function textWidth(string $text, int $size, string $font): int
    {
        if ($text === '') {
            return 0;
        }
        $box = imagettfbbox($size, 0, $font, $text);

        return (int) abs($box[2] - $box[0]);
    }

    /**
     * Fill a span with dots and sit the value in the field (filled-form look).
     */
    protected static function fillDots($img, int $x, int $y, int $end, string $value, int $size, string $regular, string $bold, $color): int
    {
        $value = trim($value);
        $avail = max(0, $end - $x);
        $valW = self::textWidth($value, $size, $bold);
        $dotW = max(1, self::textWidth('.', $size, $regular));
        $dotsSpace = max(0, $avail - $valW);
        $totalDots = max(0, (int) floor($dotsSpace / $dotW) - 1);
        $leftDots = (int) floor($totalDots * 0.28);
        $rightDots = $totalDots - $leftDots;

        if ($leftDots > 0) {
            $x = self::drawText($img, $x, $y, str_repeat('.', $leftDots), $size, $regular, $color);
        }
        if ($value !== '') {
            $x = self::drawText($img, $x, $y, $value, $size, $bold, $color);
        }
        if ($rightDots > 0) {
            $x = self::drawText($img, $x, $y, str_repeat('.', $rightDots), $size, $regular, $color);
        }

        return $end;
    }
}
