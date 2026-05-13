<?php

namespace App\Services;

/**
 * Standalone Arabic Reshaper for PHP.
 * Handles character joining (Glyphs) and RTL reordering.
 */
class ArabicReshaper
{
    private static $mapping = [
        // Char => [Isolated, Final, Medial, Initial]
        'ا' => ["\u{FE8D}", "\u{FE8E}", "\u{FE8E}", "\u{FE8D}"],
        'أ' => ["\u{FE83}", "\u{FE84}", "\u{FE84}", "\u{FE83}"],
        'إ' => ["\u{FE87}", "\u{FE88}", "\u{FE88}", "\u{FE87}"],
        'آ' => ["\u{FE81}", "\u{FE82}", "\u{FE82}", "\u{FE81}"],
        'ب' => ["\u{FE8F}", "\u{FE90}", "\u{FE92}", "\u{FE91}"],
        'ت' => ["\u{FE95}", "\u{FE96}", "\u{FE98}", "\u{FE97}"],
        'ث' => ["\u{FE99}", "\u{FE9A}", "\u{FE9C}", "\u{FE9B}"],
        'ج' => ["\u{FE9D}", "\u{FE9E}", "\u{FEA0}", "\u{FE9F}"],
        'ح' => ["\u{FEA1}", "\u{FEA2}", "\u{FEA4}", "\u{FEA3}"],
        'خ' => ["\u{FEA5}", "\u{FEA6}", "\u{FEA8}", "\u{FEA7}"],
        'د' => ["\u{FEA9}", "\u{FEAA}", "\u{FEAA}", "\u{FEA9}"],
        'ذ' => ["\u{FEAB}", "\u{FEAC}", "\u{FEAC}", "\u{FEAB}"],
        'ر' => ["\u{FEAD}", "\u{FEAE}", "\u{FEAE}", "\u{FEAD}"],
        'ز' => ["\u{FEAF}", "\u{FEB0}", "\u{FEB0}", "\u{FEAF}"],
        'س' => ["\u{FEB1}", "\u{FEB2}", "\u{FEB4}", "\u{FEB3}"],
        'ش' => ["\u{FEB5}", "\u{FEB6}", "\u{FEB8}", "\u{FEB7}"],
        'ص' => ["\u{FEB9}", "\u{FEBA}", "\u{FEBC}", "\u{FEBB}"],
        'ض' => ["\u{FEBD}", "\u{FEBE}", "\u{FEC0}", "\u{FEBF}"],
        'ط' => ["\u{FEC1}", "\u{FEC2}", "\u{FEC4}", "\u{FEC3}"],
        'ظ' => ["\u{FEC5}", "\u{FEC6}", "\u{FEC8}", "\u{FEC7}"],
        'ع' => ["\u{FEC9}", "\u{FECA}", "\u{FECC}", "\u{FECB}"],
        'غ' => ["\u{FECD}", "\u{FECE}", "\u{FED0}", "\u{FECF}"],
        'ف' => ["\u{FED1}", "\u{FED2}", "\u{FED4}", "\u{FED3}"],
        'ق' => ["\u{FED5}", "\u{FED6}", "\u{FED8}", "\u{FED7}"],
        'ك' => ["\u{FED9}", "\u{FEDA}", "\u{FEDC}", "\u{FEDB}"],
        'ل' => ["\u{FEDD}", "\u{FEDE}", "\u{FEE0}", "\u{FEDF}"],
        'م' => ["\u{FEE1}", "\u{FEE2}", "\u{FEE4}", "\u{FEE3}"],
        'ن' => ["\u{FEE5}", "\u{FEE6}", "\u{FEE8}", "\u{FEE7}"],
        'ه' => ["\u{FEE9}", "\u{FEEA}", "\u{FEEC}", "\u{FEEB}"],
        'و' => ["\u{FEED}", "\u{FEEE}", "\u{FEEE}", "\u{FEED}"],
        'ي' => ["\u{FEF1}", "\u{FEF2}", "\u{FEF4}", "\u{FEF3}"],
        'ى' => ["\u{FEEF}", "\u{FEF0}", "\u{FEF0}", "\u{FEEF}"],
        'ة' => ["\u{FE93}", "\u{FE94}", "\u{FE94}", "\u{FE93}"],
        'ئ' => ["\u{FE89}", "\u{FE8A}", "\u{FE8C}", "\u{FE8B}"],
        'ؤ' => ["\u{FE85}", "\u{FE86}", "\u{FE86}", "\u{FE85}"],
        'ء' => ["\u{FE80}", "\u{FE80}", "\u{FE80}", "\u{FE80}"],
    ];

    private static $nonConnectingBefore = ['ا', 'أ', 'إ', 'آ', 'د', 'ذ', 'ر', 'ز', 'و', 'ى', 'ة', 'ؤ', 'ء'];

    public function reshape($text)
    {
        if (empty($text)) return '';

        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        $result = [];
        $count = count($chars);

        for ($i = 0; $i < $count; $i++) {
            $char = $chars[$i];
            
            if (!isset(self::$mapping[$char])) {
                $result[] = $char;
                continue;
            }

            $prev = ($i > 0) ? $chars[$i - 1] : null;
            $next = ($i < $count - 1) ? $chars[$i + 1] : null;

            $connectPrev = $prev && isset(self::$mapping[$prev]) && !in_array($prev, self::$nonConnectingBefore);
            $connectNext = $next && isset(self::$mapping[$next]);

            if ($connectPrev && $connectNext) {
                $result[] = self::$mapping[$char][2]; // Medial
            } elseif ($connectPrev) {
                $result[] = self::$mapping[$char][1]; // Final
            } elseif ($connectNext) {
                $result[] = self::$mapping[$char][3]; // Initial
            } else {
                $result[] = self::$mapping[$char][0]; // Isolated
            }
        }

        // Reorder for RTL (Reverse words while keeping English segments)
        return $this->reorderRTL(implode('', $result));
    }

    protected function reorderRTL($text)
    {
        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        return implode('', array_reverse($chars));
    }
}
