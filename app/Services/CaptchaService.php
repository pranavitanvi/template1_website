<?php

namespace App\Services;

class CaptchaService
{
    /**
     * Characters allowed in the captcha (excluding ambiguous 0, O, 1, I, l).
     */
    protected const CHARACTERS = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';

    /**
     * Generate a new captcha code and return the SVG image string.
     */
    public static function generateSvg(): string
    {
        $code = self::generateCode(5);
        session(['custom_captcha' => $code]);
        session()->save();

        return self::renderSvg($code);
    }

    /**
     * Verify the user's captcha input against session.
     */
    public static function verify(?string $input): bool
    {
        $sessionCode = session('custom_captcha');

        // Always forget the current captcha after an attempt to prevent replay
        session()->forget('custom_captcha');
        session()->save();

        if (empty($sessionCode) || empty($input)) {
            return false;
        }

        return strtoupper(trim($input)) === strtoupper(trim($sessionCode));
    }

    /**
     * Generate a random alphanumeric code.
     */
    protected static function generateCode(int $length = 5): string
    {
        $chars = self::CHARACTERS;
        $maxIndex = strlen($chars) - 1;
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, $maxIndex)];
        }

        return $code;
    }

    /**
     * Render the captcha as a vector SVG with security noise & luxury styling.
     */
    protected static function renderSvg(string $code): string
    {
        $width = 160;
        $height = 46;
        $chars = str_split($code);

        // Noise lines
        $lines = '';
        $lineColors = ['#c0a062', '#d8bc7e', '#a88c53', '#e0cd9d'];
        for ($i = 0; $i < 3; $i++) {
            $x1 = random_int(5, 30);
            $y1 = random_int(10, 36);
            $x2 = random_int(40, 80);
            $y2 = random_int(8, 38);
            $x3 = random_int(90, 130);
            $y3 = random_int(8, 38);
            $x4 = random_int(135, 155);
            $y4 = random_int(10, 36);
            $stroke = $lineColors[$i % count($lineColors)];
            $strokeWidth = random_int(12, 18) / 10;
            $lines .= "<path d=\"M{$x1},{$y1} Q{$x2},{$y2} {$x3},{$y3} T{$x4},{$y4}\" fill=\"none\" stroke=\"{$stroke}\" stroke-width=\"{$strokeWidth}\" opacity=\"0.55\" />\n";
        }

        // Noise dots
        $dots = '';
        for ($i = 0; $i < 24; $i++) {
            $cx = random_int(5, $width - 5);
            $cy = random_int(5, $height - 5);
            $r = random_int(8, 18) / 10;
            $color = (random_int(0, 1) === 1) ? '#c0a062' : '#8c7b64';
            $opacity = random_int(30, 60) / 100;
            $dots .= "<circle cx=\"{$cx}\" cy=\"{$cy}\" r=\"{$r}\" fill=\"{$color}\" opacity=\"{$opacity}\" />\n";
        }

        // Characters
        $textElements = '';
        $charColors = ['#1a1814', '#2c2214', '#4a3821', '#211c16', '#3b2e1b'];
        $fonts = ['Montserrat', 'Arial', 'Georgia', 'sans-serif'];

        $totalChars = count($chars);
        $spacing = ($width - 32) / $totalChars;

        foreach ($chars as $idx => $char) {
            $x = 18 + ($idx * $spacing) + random_int(-3, 3);
            $y = 31 + random_int(-3, 3);
            $rot = random_int(-16, 16);
            $color = $charColors[$idx % count($charColors)];
            $font = $fonts[$idx % count($fonts)];
            $fontSize = random_int(22, 25);

            $textElements .= "<text x=\"{$x}\" y=\"{$y}\" " .
                "transform=\"rotate({$rot}, {$x}, {$y})\" " .
                "font-family=\"{$font}, sans-serif\" " .
                "font-size=\"{$fontSize}px\" " .
                "font-weight=\"700\" " .
                "letter-spacing=\"1px\" " .
                "fill=\"{$color}\">{$char}</text>\n";
        }

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$width}" height="{$height}" viewBox="0 0 {$width} {$height}">
    <defs>
        <linearGradient id="captchaBg" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#faf7f2" />
            <stop offset="50%" stop-color="#f5efe6" />
            <stop offset="100%" stop-color="#ede4d6" />
        </linearGradient>
    </defs>
    <rect width="100%" height="100%" fill="url(#captchaBg)" rx="6" stroke="#e0d6c5" stroke-width="1" />
    {$lines}
    {$dots}
    {$textElements}
</svg>
SVG;
    }
}
