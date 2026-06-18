<?php

namespace App\Support;

use Intervention\Image\Facades\Image;
use Intervention\Image\Image as ImageInstance;

class SignatureImage
{
    public static function forIdOverlay(string $path, int $maxWidth, int $maxHeight): ImageInstance
    {
        $signature = Image::make($path);
        $signature->resize($maxWidth, $maxHeight, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        self::removeLightBackground($signature);

        return $signature;
    }

    private static function removeLightBackground(ImageInstance $image): void
    {
        $core = $image->getCore();

        if (! is_resource($core) && ! ($core instanceof \GdImage)) {
            return;
        }

        imagealphablending($core, false);
        imagesavealpha($core, true);

        $width = imagesx($core);
        $height = imagesy($core);

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $rgba = imagecolorat($core, $x, $y);
                $red = ($rgba >> 16) & 0xFF;
                $green = ($rgba >> 8) & 0xFF;
                $blue = $rgba & 0xFF;

                if ($red >= 235 && $green >= 235 && $blue >= 235) {
                    $transparent = imagecolorallocatealpha($core, 255, 255, 255, 127);
                    imagesetpixel($core, $x, $y, $transparent);
                }
            }
        }
    }
}
