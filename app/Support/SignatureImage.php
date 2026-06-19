<?php

namespace App\Support;

use Intervention\Image\Facades\Image;
use Intervention\Image\Image as ImageInstance;

class SignatureImage
{
    public static function resolve(?string $path): ?string
    {
        if (empty($path) || str_starts_with($path, 'data:')) {
            return null;
        }

        $path = ltrim($path, '/');

        foreach (self::candidatePaths($path) as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * @return list<string>
     */
    private static function candidatePaths(string $path): array
    {
        return array_values(array_unique([
            base_path($path),
            public_path($path),
            base_path('public/' . $path),
        ]));
    }

    public static function insertOnStudentCard(ImageInstance $canvas, ?string $storedPath): void
    {
        $absolutePath = self::resolve($storedPath);

        if ($absolutePath === null) {
            return;
        }

        $maxWidth = (int) ($canvas->width() * 0.5);
        $maxHeight = (int) ($canvas->height() * 0.08);

        $signature = self::forIdOverlay($absolutePath, $maxWidth, $maxHeight);

        $x = (int) (($canvas->width() - $signature->width()) / 2);
        $lineY = (int) ($canvas->height() * 0.927);
        $y = $lineY - $signature->height() + (int) ($signature->height() * 0.08);

        $canvas->insert($signature, 'top-left', $x, $y);
    }

    public static function insertOnCard(
        ImageInstance $canvas,
        ?string $storedPath,
        int $maxWidth = 1100,
        int $maxHeight = 280,
        string $position = 'top-left',
        ?int $x = null,
        ?int $y = null
    ): void {
        $absolutePath = self::resolve($storedPath);

        if ($absolutePath === null) {
            return;
        }

        $signature = self::forIdOverlay($absolutePath, $maxWidth, $maxHeight);

        if ($x === null) {
            $x = (int) (($canvas->width() - $signature->width()) / 2);
        }

        if ($y === null) {
            $y = (int) ($canvas->height() * 0.943);
        }

        $canvas->insert($signature, $position, $x, $y);
    }

    public static function forIdOverlay(string $path, int $maxWidth, int $maxHeight): ImageInstance
    {
        $signature = Image::make($path);

        self::trimWhitespace($signature);
        $signature->resize($maxWidth, $maxHeight, function ($constraint) {
            $constraint->aspectRatio();
        });

        self::removeLightBackground($signature);

        return $signature;
    }

    private static function trimWhitespace(ImageInstance $image): void
    {
        $core = $image->getCore();

        if (! self::isGdImage($core)) {
            return;
        }

        $width = imagesx($core);
        $height = imagesy($core);
        $minX = $width;
        $minY = $height;
        $maxX = 0;
        $maxY = 0;

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $rgba = imagecolorat($core, $x, $y);
                $red = ($rgba >> 16) & 0xFF;
                $green = ($rgba >> 8) & 0xFF;
                $blue = $rgba & 0xFF;

                if ($red < 235 || $green < 235 || $blue < 235) {
                    $minX = min($minX, $x);
                    $minY = min($minY, $y);
                    $maxX = max($maxX, $x);
                    $maxY = max($maxY, $y);
                }
            }
        }

        if ($maxX <= $minX || $maxY <= $minY) {
            return;
        }

        $image->crop($maxX - $minX + 1, $maxY - $minY + 1, $minX, $minY);
    }

    private static function removeLightBackground(ImageInstance $image): void
    {
        $core = $image->getCore();

        if ($core instanceof \Imagick) {
            $core->setImageAlphaChannel(\Imagick::ALPHACHANNEL_SET);
            $core->transparentPaintImage(
                new \ImagickPixel('white'),
                0,
                0.12 * \Imagick::getQuantumRange()['quantumRangeLong'],
                false
            );

            return;
        }

        if (! self::isGdImage($core)) {
            return;
        }

        imagealphablending($core, false);
        imagesavealpha($core, true);

        $transparent = imagecolorallocatealpha($core, 255, 255, 255, 127);
        $width = imagesx($core);
        $height = imagesy($core);

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $rgba = imagecolorat($core, $x, $y);
                $red = ($rgba >> 16) & 0xFF;
                $green = ($rgba >> 8) & 0xFF;
                $blue = $rgba & 0xFF;

                if ($red >= 235 && $green >= 235 && $blue >= 235) {
                    imagesetpixel($core, $x, $y, $transparent);
                }
            }
        }
    }

    private static function isGdImage(mixed $image): bool
    {
        return is_resource($image) || $image instanceof \GdImage;
    }
}
