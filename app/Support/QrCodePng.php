<?php

namespace App\Support;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use RuntimeException;

class QrCodePng
{
    public static function generate(string $content, int $size = 300, int $margin = 0): string
    {
        if (! extension_loaded('gd')) {
            throw new RuntimeException('The GD extension is required to generate QR code images.');
        }

        $qrCode = Encoder::encode($content, ErrorCorrectionLevel::L());
        $matrix = $qrCode->getMatrix();
        $matrixWidth = $matrix->getWidth();
        $matrixHeight = $matrix->getHeight();

        $totalModules = $matrixWidth + ($margin * 2);
        $pixelPerModule = $size / $totalModules;
        $imageSize = (int) round($size);

        $image = imagecreatetruecolor($imageSize, $imageSize);

        if ($image === false) {
            throw new RuntimeException('Unable to create QR code image.');
        }

        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        imagefill($image, 0, 0, $white);

        for ($y = 0; $y < $matrixHeight; $y++) {
            for ($x = 0; $x < $matrixWidth; $x++) {
                if ($matrix->get($x, $y) !== 1) {
                    continue;
                }

                $offset = $margin * $pixelPerModule;
                $x1 = (int) round($offset + ($x * $pixelPerModule));
                $y1 = (int) round($offset + ($y * $pixelPerModule));
                $x2 = (int) round($offset + (($x + 1) * $pixelPerModule)) - 1;
                $y2 = (int) round($offset + (($y + 1) * $pixelPerModule)) - 1;

                imagefilledrectangle($image, $x1, $y1, max($x1, $x2), max($y1, $y2), $black);
            }
        }

        ob_start();
        imagepng($image);
        $png = ob_get_clean() ?: '';
        imagedestroy($image);

        return $png;
    }
}
