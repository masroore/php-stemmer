<?php

declare(strict_types=1);

namespace Kaiju\Snowball\Stemmer;

use voku\helper\UTF8;

abstract class Stem implements Stemmer
{
    protected static array $vowels = ['a', 'e', 'i', 'o', 'u', 'y'];

    /**
     * helper, contains stringified list of vowels.
     */
    protected string $plainVowels;

    /**
     * The word we are stemming.
     */
    protected string $word;

    /**
     * The original word, use to check if word has been modified.
     */
    protected string $originalWord;

    /**
     * RV value.
     */
    protected string $rv;

    /**
     * RV index (based on the beginning of the word).
     */
    protected int $rvIndex;

    /**
     * R1 value.
     */
    protected string $r1;

    /**
     * R1 index (based on the beginning of the word).
     */
    protected int $r1Index;

    /**
     * R2 value.
     */
    protected string $r2;

    /**
     * R2 index (based on the beginning of the word).
     */
    protected int $r2Index;

    protected function inRv(int $position): bool
    {
        return $position >= $this->rvIndex;
    }

    protected function inR1(int $position): bool
    {
        return $position >= $this->r1Index;
    }

    protected function inR2(int $position): bool
    {
        return $position >= $this->r2Index;
    }

    protected function searchIfInRv(array $suffixes): bool|int
    {
        return $this->search($suffixes, $this->rvIndex);
    }

    protected function searchIfInR1(array $suffixes): bool|int
    {
        return $this->search($suffixes, $this->r1Index);
    }

    protected function searchIfInR2(array $suffixes): bool|int
    {
        return $this->search($suffixes, $this->r2Index);
    }

    protected function search(array $suffixes, int $offset = 0): bool|int
    {
        $length = UTF8::strlen($this->word);
        if ($offset > $length) {
            return false;
        }
        foreach ($suffixes as $suffixe) {
            if ((($position = UTF8::strrpos($this->word, $suffixe, $offset)) !== false) && ((Utf8::strlen($suffixe) + $position) == $length)) {
                return $position;
            }
        }

        return false;
    }

    /**
     * R1 is the region after the first non-vowel following a vowel, or the end of the word if there is no such non-vowel.
     */
    protected function r1(): void
    {
        [$this->r1Index, $this->r1] = $this->rx($this->word);
    }

    /**
     * R2 is the region after the first non-vowel following a vowel in R1, or the end of the word if there is no such non-vowel.
     */
    protected function r2(): void
    {
        [$index, $value] = $this->rx($this->r1);

        $this->r2 = $value;
        $this->r2Index = $this->r1Index + $index;
    }

    /**
     * Common function for R1 and R2
     * Search the region after the first non-vowel following a vowel in $word, or the end of the word if there is no such non-vowel.
     * R1 : $in = $this->word
     * R2 : $in = R1.
     */
    protected function rx(string $in): array
    {
        $length = UTF8::strlen($in);

        // defaults
        $value = '';
        $index = $length;

        // we search all vowels
        $vowels = [];
        for ($i = 0; $i < $length; ++$i) {
            $letter = UTF8::substr($in, $i, 1);
            if (in_array($letter, static::$vowels)) {
                $vowels[] = $i;
            }
        }

        // search the non-vowel following a vowel
        foreach ($vowels as $position) {
            $after = $position + 1;
            $letter = UTF8::substr($in, $after, 1);

            if (!in_array($letter, static::$vowels)) {
                $index = $after + 1;
                $value = UTF8::substr($in, ($after + 1));

                break;
            }
        }

        return [$index, $value];
    }

    /**
     * Used by spanish, italian, portuguese, etc (but not by french).
     *
     * If the second letter is a consonant, RV is the region after the next following vowel,
     * or if the first two letters are vowels, RV is the region after the next consonant,
     * and otherwise (consonant-vowel case) RV is the region after the third letter.
     * But RV is the end of the word if these positions cannot be found.
     */
    protected function rv(): bool
    {
        $length = UTF8::strlen($this->word);

        $this->rv = '';
        $this->rvIndex = $length;

        if ($length < 3) {
            return true;
        }

        $first = UTF8::substr($this->word, 0, 1);
        $second = UTF8::substr($this->word, 1, 1);

        // If the second letter is a consonant, RV is the region after the next following vowel,
        if (!in_array($second, static::$vowels, true)) {
            for ($i = 2; $i < $length; ++$i) {
                $letter = UTF8::substr($this->word, $i, 1);
                if (in_array($letter, static::$vowels, true)) {
                    $this->rvIndex = $i + 1;
                    $this->rv = UTF8::substr($this->word, ($i + 1));

                    return true;
                }
            }
        }

        // or if the first two letters are vowels, RV is the region after the next consonant,
        if (in_array($first, static::$vowels, true) && in_array($second, static::$vowels, true)) {
            for ($i = 2; $i < $length; ++$i) {
                $letter = UTF8::substr($this->word, $i, 1);
                if (!in_array($letter, static::$vowels, true)) {
                    $this->rvIndex = $i + 1;
                    $this->rv = UTF8::substr($this->word, ($i + 1));

                    return true;
                }
            }
        }

        // and otherwise (consonant-vowel case) RV is the region after the third letter.
        if (!in_array($first, static::$vowels, true) && in_array($second, static::$vowels, true)) {
            $this->rv = UTF8::substr($this->word, 3);
            $this->rvIndex = 3;

            return true;
        }

        return false;
    }
}
