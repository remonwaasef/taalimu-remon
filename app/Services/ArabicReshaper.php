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
        __('services.string_1') => ["\u{FE8D}", "\u{FE8E}", "\u{FE8E}", "\u{FE8D}"],
        __('services.string_2') => ["\u{FE83}", "\u{FE84}", "\u{FE84}", "\u{FE83}"],
        __('services.string_3') => ["\u{FE87}", "\u{FE88}", "\u{FE88}", "\u{FE87}"],
        __('services.string_4') => ["\u{FE81}", "\u{FE82}", "\u{FE82}", "\u{FE81}"],
        __('services.string_5') => ["\u{FE8F}", "\u{FE90}", "\u{FE92}", "\u{FE91}"],
        __('services.string_6') => ["\u{FE95}", "\u{FE96}", "\u{FE98}", "\u{FE97}"],
        __('services.string_7') => ["\u{FE99}", "\u{FE9A}", "\u{FE9C}", "\u{FE9B}"],
        __('services.string_8') => ["\u{FE9D}", "\u{FE9E}", "\u{FEA0}", "\u{FE9F}"],
        __('services.string_9') => ["\u{FEA1}", "\u{FEA2}", "\u{FEA4}", "\u{FEA3}"],
        __('services.string_10') => ["\u{FEA5}", "\u{FEA6}", "\u{FEA8}", "\u{FEA7}"],
        __('services.string_11') => ["\u{FEA9}", "\u{FEAA}", "\u{FEAA}", "\u{FEA9}"],
        __('services.string_12') => ["\u{FEAB}", "\u{FEAC}", "\u{FEAC}", "\u{FEAB}"],
        __('services.string_13') => ["\u{FEAD}", "\u{FEAE}", "\u{FEAE}", "\u{FEAD}"],
        __('services.string_14') => ["\u{FEAF}", "\u{FEB0}", "\u{FEB0}", "\u{FEAF}"],
        __('services.string_15') => ["\u{FEB1}", "\u{FEB2}", "\u{FEB4}", "\u{FEB3}"],
        __('services.string_16') => ["\u{FEB5}", "\u{FEB6}", "\u{FEB8}", "\u{FEB7}"],
        __('services.string_17') => ["\u{FEB9}", "\u{FEBA}", "\u{FEBC}", "\u{FEBB}"],
        __('services.string_18') => ["\u{FEBD}", "\u{FEBE}", "\u{FEC0}", "\u{FEBF}"],
        __('services.string_19') => ["\u{FEC1}", "\u{FEC2}", "\u{FEC4}", "\u{FEC3}"],
        __('services.string_20') => ["\u{FEC5}", "\u{FEC6}", "\u{FEC8}", "\u{FEC7}"],
        __('services.string_21') => ["\u{FEC9}", "\u{FECA}", "\u{FECC}", "\u{FECB}"],
        __('services.string_22') => ["\u{FECD}", "\u{FECE}", "\u{FED0}", "\u{FECF}"],
        __('services.string_23') => ["\u{FED1}", "\u{FED2}", "\u{FED4}", "\u{FED3}"],
        __('services.string_24') => ["\u{FED5}", "\u{FED6}", "\u{FED8}", "\u{FED7}"],
        __('services.string_25') => ["\u{FED9}", "\u{FEDA}", "\u{FEDC}", "\u{FEDB}"],
        __('services.string_26') => ["\u{FEDD}", "\u{FEDE}", "\u{FEE0}", "\u{FEDF}"],
        __('services.string_27') => ["\u{FEE1}", "\u{FEE2}", "\u{FEE4}", "\u{FEE3}"],
        __('services.string_28') => ["\u{FEE5}", "\u{FEE6}", "\u{FEE8}", "\u{FEE7}"],
        __('services.string_29') => ["\u{FEE9}", "\u{FEEA}", "\u{FEEC}", "\u{FEEB}"],
        __('services.string_30') => ["\u{FEED}", "\u{FEEE}", "\u{FEEE}", "\u{FEED}"],
        __('services.string_31') => ["\u{FEF1}", "\u{FEF2}", "\u{FEF4}", "\u{FEF3}"],
        __('services.string_32') => ["\u{FEEF}", "\u{FEF0}", "\u{FEF0}", "\u{FEEF}"],
        __('services.string_33') => ["\u{FE93}", "\u{FE94}", "\u{FE94}", "\u{FE93}"],
        __('services.string_34') => ["\u{FE89}", "\u{FE8A}", "\u{FE8C}", "\u{FE8B}"],
        __('services.string_35') => ["\u{FE85}", "\u{FE86}", "\u{FE86}", "\u{FE85}"],
        __('services.string_36') => ["\u{FE80}", "\u{FE80}", "\u{FE80}", "\u{FE80}"],
    ];

    private static $nonConnectingBefore = [__('services.string_37'), __('services.string_38'), __('services.string_39'), __('services.string_40'), __('services.string_41'), __('services.string_42'), __('services.string_43'), __('services.string_44'), __('services.string_45'), __('services.string_46'), __('services.string_47'), __('services.string_48'), __('services.string_49')];

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
        // Simple reversal for demonstration - in production, need Bidi algorithm
        // But for pure Arabic text, simple reverse works for PDF alignment.
        
        // Split by whitespace and reverse words for basic RTL handling if needed
        // However, usually, DomPDF with DejaVu Sans and utf8Glyphs only needs REVERSED string.
        
        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        return implode('', array_reverse($chars));
    }
}
