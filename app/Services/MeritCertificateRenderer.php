<?php

namespace App\Services;

use App\Models\MeritCertificate;
use App\Models\MeritCertificateBatch;

class MeritCertificateRenderer
{
    public static function blankPath(int $template): string
    {
        $n = $template === 2 ? 2 : 1;
        return public_path('certificates/merit_certificate_blank_' . $n . '.jpg');
    }

    public static function blankUrl(int $template): string
    {
        $n = $template === 2 ? 2 : 1;
        return url('/public/certificates/merit_certificate_blank_' . $n . '.jpg');
    }

    public static function resolveFontPath(): string
    {
        return BestTestCertificateRenderer::resolveFontPath();
    }

    public static function recipientName(MeritCertificate $row): string
    {
        $person = $row->recipient();
        return strtoupper(trim((string) ($person->name ?? '')));
    }

    public static function payload(MeritCertificateBatch $batch, MeritCertificate $row): array
    {
        return [
            'serial' => (string) ($row->serial_no ?: $row->id),
            'name' => self::recipientName($row),
            'course' => strtoupper(trim((string) ($batch->course_title ?? ''))),
            'place' => strtoupper(trim((string) ($batch->place ?? ''))),
            'date_from' => trim((string) ($batch->date_from ?? '')),
            'date_on' => trim((string) ($batch->date_on ?? '')),
            'issue_date' => $batch->issue_date ? $batch->issue_date->format('d-m-Y') : date('d-m-Y'),
            'template' => (int) $batch->template,
            'blank_url' => self::blankUrl((int) $batch->template),
        ];
    }

    public static function renderJpg(MeritCertificateBatch $batch, MeritCertificate $row): string
    {
        $template = (int) $batch->template === 2 ? 2 : 1;
        $blank = self::blankPath($template);
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
        $size = max(12, (int) round($w * 0.018));
        $sizeSmall = max(11, (int) round($w * 0.0155));

        // Sr. No (top-right after "Sr. No:")
        imagettftext($img, $sizeSmall, 0, (int) round($w * 0.78), 72, $black, $font, $data['serial']);

        if ($template === 1) {
            self::drawCentered($img, $font, $size, $data['name'], $w, 402, $black, 0.12, 0.88);
            self::drawText($img, $font, $sizeSmall, $data['course'], (int) round($w * 0.55), 535, $black);
            self::drawText($img, $font, $sizeSmall, $data['place'], (int) round($w * 0.52), 600, $black);
            self::drawText($img, $font, $sizeSmall, $data['date_from'], (int) round($w * 0.28), 629, $black);
        } else {
            self::drawCentered($img, $font, $size, $data['name'], $w, 482, $black, 0.12, 0.88);
            self::drawText($img, $font, $sizeSmall, $data['course'], (int) round($w * 0.55), 615, $black);
            self::drawText($img, $font, $sizeSmall, $data['date_on'], (int) round($w * 0.28), 652, $black);
        }

        ob_start();
        imagejpeg($img, null, 92);
        $binary = ob_get_clean();
        imagedestroy($img);

        return $binary;
    }

    protected static function drawText($img, string $font, int $size, string $text, int $x, int $y, $color): void
    {
        if ($text === '') {
            return;
        }
        imagettftext($img, $size, 0, $x, $y, $color, $font, $text);
    }

    protected static function drawCentered($img, string $font, int $size, string $text, int $w, int $y, $color, float $left = 0.15, float $right = 0.85): void
    {
        if ($text === '') {
            return;
        }
        $box = imagettfbbox($size, 0, $font, $text);
        $textW = abs($box[2] - $box[0]);
        $areaL = (int) round($w * $left);
        $areaR = (int) round($w * $right);
        $x = (int) round($areaL + (($areaR - $areaL) - $textW) / 2);
        imagettftext($img, $size, 0, max($areaL, $x), $y, $color, $font, $text);
    }
}
