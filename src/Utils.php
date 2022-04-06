<?php

namespace Overtrue\DoubleArrayTrie;

class Utils
{
    public static function str2codes(string $text): array
    {
        $codes = [];
        $len = \mb_strlen($text);

        for ($i = 0; $i < $len; $i++) {
            $codes[] = \mb_ord(\mb_substr($text, $i, 1));
        }

        return $codes;
    }

    public static function codes2str(array $codes): string
    {
        $str = '';
        foreach ($codes as $code) {
            $str .= \mb_chr($code);
        }

        return $str;
    }
}
