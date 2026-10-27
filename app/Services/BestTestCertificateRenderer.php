<?php

namespace App\Services;

use App\Models\BestTestAthlete;
use App\Models\BestTestBatch;
use App\Models\Tags;

class BestTestCertificateRenderer
{
    public static function blankPath(): string
    {
        $png4k = public_path('certificates/color_belt_certificate_4k.png');
        if (is_file($png4k)) {
            return $png4k;
        }

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
        $path = self::blankPath();
        $v = is_file($path) ? filemtime($path) : time();
        $name = basename($path);

        return url('/public/certificates/' . $name) . '?v=' . $v;
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

    /**
     * Split a long venue so "at" line + next dotted line show the full name.
     *
     * @return array{0:string,1:string}
     */
    public static function placeLines(BestTestBatch $batch): array
    {
        $place = self::place($batch);
        if ($place === '') {
            return ['', ''];
        }

        try {
            $bold = self::resolveFontPath(true);
            $regular = self::resolveFontPath(false);
            $w = 724;
            $blank = self::blankPath();
            if (is_file($blank)) {
                $info = @getimagesize($blank);
                if (!empty($info[0])) {
                    $w = (int) $info[0];
                }
            }
            $size = max(11, (int) round($w * 0.016));
            $gap = max(8, (int) round($w * 0.006));
            $left = (int) round($w * 0.112);
            $right = (int) round($w * 0.886);
            $x = $left
                + self::textWidth('qualified in Color Belt Promotion Test Held on', $size, $regular)
                + $gap
                + max((int) round($w * 0.145), self::textWidth(self::examDate($batch), $size, $bold) + $gap * 2)
                + $gap
                + self::textWidth('at', $size, $regular)
                + $gap;
            $maxW = max(20, $right - $x);

            return self::wrapToWidth($place, $size, $bold, $maxW);
        } catch (\Throwable $e) {
            if (mb_strlen($place) <= 14) {
                return [$place, ''];
            }
            $words = preg_split('/\s+/', $place);
            $line1 = array_shift($words);
            while ($words && mb_strlen($line1 . ' ' . $words[0]) <= 14) {
                $line1 .= ' ' . array_shift($words);
            }

            return [$line1, trim(implode(' ', $words))];
        }
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
        $placeLines = self::placeLines($batch);

        return [
            'no' => self::certificateNo($batch, $row),
            'date' => self::issueDate($batch),
            'name' => self::athleteName($row),
            'father' => self::fatherName($row),
            'district' => self::districtName($row, $batch),
            'exam_date' => self::examDate($batch),
            'place' => self::place($batch),
            'place1' => $placeLines[0],
            'place2' => $placeLines[1],
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
        @ini_set('memory_limit', '512M');
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
        $size = max(11, (int) round($w * 0.016));
        $minSize = max(9, (int) round($w * 0.011));
        $gap = max(8, (int) round($w * 0.006));
        $left = (int) round($w * 0.112);
        $right = (int) round($w * 0.886);

        $y1 = (int) round($h * 0.600);
        $y2 = (int) round($h * 0.634);
        $y3 = (int) round($h * 0.668);
        $y4 = (int) round($h * 0.702);
        $y5 = (int) round($h * 0.736);
        $y6 = (int) round($h * 0.770);

        $x = $left;
        $x = self::drawText($img, $x, $y1, 'No.', $size, $regular, $ink) + $gap;
        self::drawText($img, $x, $y1, $data['no'], $size, $bold, $ink);
        $dateSlot = (int) round($w * 0.36);
        $x = $right - $dateSlot;
        $x = self::drawText($img, $x, $y1, 'Date:', $size, $regular, $ink) + $gap;
        self::fillDotted($img, $x, $right, $y1, $data['date'], $size, $minSize, $regular, $bold, $ink);

        $x = $left;
        $x = self::drawText($img, $x, $y2, 'This is to certify that Mr./Ms.', $size, $regular, $ink) + $gap;
        self::fillDotted($img, $x, $right, $y2, $data['name'], $size, $minSize, $regular, $bold, $ink);

        $x = $left;
        $x = self::drawText($img, $x, $y3, 'Son / Daughter', $size, $regular, $ink) + $gap;
        $ofW = self::textWidth('of', $size, $regular);
        $hasW = self::textWidth('District, has', $size, $regular);
        $remain = max(40, $right - $x - $ofW - $hasW - ($gap * 3));
        $fatherEnd = $x + (int) round($remain * 0.55);
        self::fillDotted($img, $x, $fatherEnd, $y3, $data['father'], $size, $minSize, $regular, $bold, $ink);
        $x = $fatherEnd + $gap;
        $x = self::drawText($img, $x, $y3, 'of', $size, $regular, $ink) + $gap;
        $districtEnd = $right - $hasW - $gap;
        self::fillDotted($img, $x, $districtEnd, $y3, $data['district'], $size, $minSize, $regular, $bold, $ink);
        self::drawText($img, $districtEnd + $gap, $y3, 'District, has', $size, $regular, $ink);

        $x = $left;
        $x = self::drawText($img, $x, $y4, 'qualified in Color Belt Promotion Test Held on', $size, $regular, $ink) + $gap;
        $dateEnd = $x + max((int) round($w * 0.145), self::textWidth($data['exam_date'], $size, $bold) + $gap * 2);
        $atW = self::textWidth('at', $size, $regular);
        if ($dateEnd + $gap + $atW + $gap + 40 > $right) {
            $dateEnd = $right - $atW - $gap * 2 - 40;
        }
        self::fillDotted($img, $x, $dateEnd, $y4, $data['exam_date'], $size, $minSize, $regular, $bold, $ink);
        $x = $dateEnd + $gap;
        $x = self::drawText($img, $x, $y4, 'at', $size, $regular, $ink) + $gap;
        $placeLines = self::wrapToWidth($data['place'], $size, $bold, max(20, $right - $x));
        self::fillDotted($img, $x, $right, $y4, $placeLines[0], $size, $minSize, $regular, $bold, $ink);

        $wearW = self::textWidth('and is permitted to wear', $size, $regular);
        $beltDot = self::textWidth('belt.', $size, $regular);
        $x = $left;
        if ($placeLines[1] !== '') {
            $beltMin = self::textWidth($data['belt'], $size, $bold) + $gap;
            $maxPlace2 = $right - $left - $wearW - $beltMin - $beltDot - ($gap * 4);
            $need = self::textWidth($placeLines[1], $size, $bold) + $gap * 2;
            $place2End = $x + min(max($need, $gap * 4), max(40, $maxPlace2));
            self::fillDotted($img, $x, $place2End, $y5, $placeLines[1], $size, $minSize, $regular, $bold, $ink);
            $x = $place2End + $gap;
        }
        $x = self::drawText($img, $x, $y5, 'and is permitted to wear', $size, $regular, $ink) + $gap;
        $beltEnd = $right - $beltDot - $gap;
        self::fillDotted($img, $x, $beltEnd, $y5, $data['belt'], $size, $minSize, $regular, $bold, $ink);
        self::drawText($img, $beltEnd + $gap, $y5, 'belt.', $size, $regular, $ink);

        self::drawText($img, $left, $y6, 'He/She is not permitted to teach taekwondo', $size, $regular, $ink);
        $gradeSlot = (int) round($w * 0.26);
        $x = $right - $gradeSlot;
        $x = self::drawText($img, $x, $y6, 'Grade:', $size, $regular, $ink) + $gap;
        self::fillDotted($img, $x, $right, $y6, $data['grade'], $size, $minSize, $regular, $bold, $ink);

        ob_start();
        imagejpeg($img, null, 96);
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
     * Dotted field from $x to $end so the right side of each line is filled.
     */
    protected static function fillDotted($img, int $x, int $end, int $y, string $value, int $size, int $minSize, string $regular, string $bold, $color): void
    {
        if ($end <= $x + 2) {
            return;
        }
        $value = trim($value);
        $s = $size;
        $avail = $end - $x;
        while ($s > $minSize && $value !== '' && (self::textWidth($value, $s, $bold) + 4) > $avail) {
            $s--;
        }
        if ($value !== '') {
            $vw = self::textWidth($value, $s, $bold);
            $tx = $x + (int) floor(max(0, $avail - $vw) * 0.22);
            if ($tx + $vw > $end) {
                $tx = max($x, $end - $vw);
            }
            self::drawText($img, $tx, $y, $value, $s, $bold, $color);
        }
        self::drawDotUnderline($img, $x, $end, $y, $s, $color);
    }

    /**
     * @return array{0:string,1:string}
     */
    protected static function wrapToWidth(string $text, int $size, string $font, int $maxW): array
    {
        $text = trim($text);
        if ($text === '' || self::textWidth($text, $size, $font) <= $maxW) {
            return [$text, ''];
        }

        $words = preg_split('/\s+/', $text);
        $line1 = '';
        $used = 0;
        foreach ($words as $i => $word) {
            $try = $line1 === '' ? $word : ($line1 . ' ' . $word);
            if (self::textWidth($try, $size, $font) <= $maxW) {
                $line1 = $try;
                $used = $i + 1;
            } else {
                break;
            }
        }

        if ($line1 === '') {
            $word = $words[0];
            $line1 = $word;
            while ($line1 !== '' && self::textWidth($line1, $size, $font) > $maxW) {
                $line1 = mb_substr($line1, 0, mb_strlen($line1) - 1);
            }
            $rest = trim(mb_substr($word, mb_strlen($line1)) . ' ' . implode(' ', array_slice($words, 1)));

            return [$line1, $rest];
        }

        return [$line1, trim(implode(' ', array_slice($words, $used)))];
    }

    protected static function drawDotUnderline($img, int $x, int $end, int $baseline, int $size, $color): void
    {
        if ($end - $x < 2) {
            return;
        }
        $uy = $baseline + (int) max(2, round($size * 0.16));
        $d = max(2, (int) round($size * 0.11));
        $step = max($d + 2, (int) round($size * 0.30));
        for ($i = $x; $i + $d <= $end; $i += $step) {
            imagefilledellipse($img, $i + (int) floor($d / 2), $uy, $d, $d, $color);
        }
    }
}
