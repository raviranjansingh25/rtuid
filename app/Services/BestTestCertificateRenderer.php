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
     * Prefer project font (works on live Linux). Fallback to OS fonts.
     */
    public static function resolveFontPath(): string
    {
        $candidates = [
            public_path('certificates/fonts/arialbd.ttf'),
            public_path('certificates/fonts/arial.ttf'),
            public_path('certificates/fonts/DejaVuSans-Bold.ttf'),
            public_path('certificates/fonts/DejaVuSans.ttf'),
            base_path('public/certificates/fonts/arialbd.ttf'),
            base_path('public/certificates/fonts/arial.ttf'),
            'C:\\Windows\\Fonts\\arialbd.ttf',
            'C:\\Windows\\Fonts\\arial.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf',
            '/usr/share/fonts/truetype/freefont/FreeSansBold.ttf',
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
        // Project serves static files under /public/... (same as admin assets)
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
        $name = strtoupper(trim((string) ($row->user->father_name ?? '')));
        // Keep short so it does not overlap printed "of"
        if (mb_strlen($name) > 18) {
            $name = rtrim(mb_substr($name, 0, 17)) . '.';
        }

        return $name;
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
        $black = imagecolorallocate($img, 20, 20, 20);
        $font = self::resolveFontPath();

        $data = self::payload($batch, $row);
        $size = max(13, (int) round($w * 0.0165));

        // Dotted lines on blank (1152h): 724,765,808,849,891,925 (skip title underline 665)
        // imagettftext Y is baseline — keep a few px above the dots
        $fields = [
            ['text' => $data['no'],        'x' => 0.19, 'y' => 724 + 6],
            ['text' => $data['date'],      'x' => 0.71, 'y' => 724 + 6],
            ['text' => $data['name'],      'x' => 0.465, 'y' => 765 + 6],
            ['text' => $data['father'],    'x' => 0.29, 'y' => 808 + 6],
            ['text' => $data['district'],  'x' => 0.585, 'y' => 808 + 6],
            ['text' => $data['exam_date'], 'x' => 0.53, 'y' => 849 + 6],
            ['text' => $data['place'],     'x' => 0.74, 'y' => 849 + 6], // clear of "at"
            ['text' => $data['belt'],      'x' => 0.40, 'y' => 891 + 6],
            ['text' => $data['grade'],     'x' => 0.76, 'y' => 925 + 4],
        ];

        foreach ($fields as $field) {
            if ($field['text'] === '') {
                continue;
            }
            imagettftext(
                $img,
                $size,
                0,
                (int) round($w * $field['x']),
                (int) $field['y'],
                $black,
                $font,
                $field['text']
            );
        }

        ob_start();
        imagejpeg($img, null, 92);
        $binary = ob_get_clean();
        imagedestroy($img);

        return $binary;
    }
}
