<?php

namespace App\Helpers;

class TextAbbreviator
{
    /**
     * Words or characters to skip during abbreviation.
     *
     * @var array
     */
    protected static $skipWords = ['the', 'and', 'a', 'an', ',', '.', 'etc'];

    /**
     * Abbreviate a given text by extracting the first letter of each word.
     *
     * @param string $text
     * @return string
     */
    public static function abbreviate($text)
    {
        $words = explode(' ', $text);
        $abbreviation = '';

        foreach ($words as $word) {
            $word = strtolower($word);

            if (!in_array($word, self::$skipWords)) {
                // Only abbreviate words that are not in the skip list
                $abbreviation .= substr($word, 0, 1);
            }
        }

        return strtoupper($abbreviation);
    }
}
