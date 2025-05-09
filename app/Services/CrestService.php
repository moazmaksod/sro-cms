<?php

namespace App\Services;

class CrestService
{
    public static function generateGuildCrest(string $hex, int $width = 16, int $height = 16)
    {
        $palette = config('palette');

        $bytes = str_split(substr($hex, 2), 2);
        $img = imagecreatetruecolor($width, $height);
        imagesavealpha($img, true);

        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $transparent);
        imagealphablending($img, true);

        foreach ($bytes as $i => $byte) {
            $colorIndex = hexdec($byte);
            $colorHex = $palette[$colorIndex] ?? '#000000';
            [$r, $g, $b] = sscanf($colorHex, "#%02x%02x%02x");
            $color = imagecolorallocate($img, $r, $g, $b);

            $x = $i % $width;
            $y = intdiv($i, $width);
            if ($y < $height) {
                imagesetpixel($img, $x, $y, $color);
            }
        }

        imageflip($img, IMG_FLIP_VERTICAL);

        return $img;
    }
}
