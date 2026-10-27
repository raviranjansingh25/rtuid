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
            $font = self::resolveFontPath(true);
            $w = 724;
            $blank = self::blankPath();
            if (is_file($blank)) {
                $info = @getimagesize($blank);
                if (!empty($info[0])) {
                    $w = (int) $info[0];
                }
            }
            $size = max(11, (int) round($w * 0.0154));
            $maxW = (int) round($w * 0.148);

            return self::wrapToWidth($place, $size, $font, $maxW);
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
        $size = max(11, (int) round($w * 0.0154));

        // Same places and dotted lengths as the original printed form.
        $y1 = (int) round($h * 0.603);
        $y2 = (int) round($h * 0.637);
        $y3 = (int) round($h * 0.672);
        $y4 = (int) round($h * 0.706);
        $y5 = (int) round($h * 0.741);
        $y6 = (int) round($h * 0.775);

        self::drawText($img, (int) round($w * 0.128), $y1, 'No.', $size, $regular, $ink);
        self::drawText($img, (int) round($w * 0.195), $y1, $data['no'], $size, $bold, $ink);
        self::drawText($img, (int) round($w * 0.648), $y1, 'Date:', $size, $regular, $ink);
        self::dottedField($img, $w, 0.708, 0.858, $y1, $data['date'], $size, $regular, $bold, $ink);

        self::drawText($img, (int) round($w * 0.128), $y2, 'This is to certify that Mr./Ms.', $size, $regular, $ink);
        self::dottedField($img, $w, 0.372, 0.858, $y2, $data['name'], $size, $regular, $bold, $ink);

        self::drawText($img, (int) round($w * 0.128), $y3, 'Son / Daughter', $size, $regular, $ink);
        self::dottedField($img, $w, 0.270, 0.541, $y3, $data['father'], $size, $regular, $bold, $ink);
        self::drawText($img, (int) round($w * 0.545), $y3, 'of', $size, $regular, $ink);
        self::dottedField($img, $w, 0.565, 0.762, $y3, $data['district'], $size, $regular, $bold, $ink);
        self::drawText($img, (int) round($w * 0.768), $y3, 'District, has', $size, $regular, $ink);

        self::drawText($img, (int) round($w * 0.128), $y4, 'qualified in Color Belt Promotion Test Held on', $size, $regular, $ink);
        self::dottedField($img, $w, 0.532, 0.691, $y4, $data['exam_date'], $size, $regular, $bold, $ink);
        self::drawText($img, (int) round($w * 0.695), $y4, 'at', $size, $regular, $ink);
        $placeSize = $size;
        $placeMax1 = (int) round($w * 0.148);
        $placeMax2 = (int) round($w * 0.225);
        $placeLines = self::wrapToWidth($data['place'], $placeSize, $bold, $placeMax1);
        while ($placeSize > 9 && $placeLines[1] !== '' && self::textWidth($placeLines[1], $placeSize, $bold) > $placeMax2) {
            $placeSize--;
            $placeLines = self::wrapToWidth($data['place'], $placeSize, $bold, $placeMax1);
        }
        self::dottedField($img, $w, 0.708, 0.858, $y4, $placeLines[0], $placeSize, $regular, $bold, $ink);

        self::dottedField($img, $w, 0.142, 0.369, $y5, $placeLines[1], $placeSize, $regular, $bold, $ink);
        self::drawText($img, (int) round($w * 0.372), $y5, 'and is permitted to wear', $size, $regular, $ink);
        self::dottedField($img, $w, 0.593, 0.822, $y5, $data['belt'], $size, $regular, $bold, $ink);
        self::drawText($img, (int) round($w * 0.828), $y5, 'belt.', $size, $regular, $ink);

        self::drawText($img, (int) round($w * 0.128), $y6, 'He/She is not permitted to teach taekwondo', $size, $regular, $ink);
        self::drawText($img, (int) round($w * 0.725), $y6, 'Grade:', $size, $regular, $ink);
        self::dottedField($img, $w, 0.779, 0.858, $y6, $data['grade'], $size, $regular, $bold, $ink);

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
     * Dotted field of a fixed length (same as the original printed form).
     */
    protected static function dottedField($img, int $w, float $x0pct, float $x1pct, int $y, string $value, int $size, string $regular, string $bold, $color): void
    {
        $x0 = (int) round($w * $x0pct);
        $x1 = (int) round($w * $x1pct);
        $value = trim($value);
        $valW = ($value === '') ? 0 : self::textWidth($value, $size, $bold);
        $avail = max(0, $x1 - $x0);
        if ($valW > $avail) {
            $valW = $avail;
        }
        $remain = max(0, $avail - $valW);
        if ($value !== '') {
            $split = $x0 + (int) floor($remain * 0.18);
            self::drawDotsTo($img, $x0, $split, $y, $size, $regular, $color);
            self::drawText($img, $split, $y, $value, $size, $bold, $color);
            self::drawDotsTo($img, $split + $valW, $x1, $y, $size, $regular, $color);
        } else {
            self::drawDotsTo($img, $x0, $x1, $y, $size, $regular, $color);
        }
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

    protected static function drawDotsTo($img, int $x, int $end, int $y, int $size, string $font, $color): void
    {
        if ($end - $x < 2) {
            return;
        }
        $dotW = max(1, self::textWidth('.', $size, $font));
        $n = (int) floor(($end - $x) / $dotW);
        while ($n > 0 && ($x + self::textWidth(str_repeat('.', $n), $size, $font)) > $end) {
            $n--;
        }
        if ($n > 0) {
            self::drawText($img, $x, $y, str_repeat('.', $n), $size, $font, $color);
        }
    }
}
