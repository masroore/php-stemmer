<?php

namespace Wamania\Snowball\Stemmer;

use Exception;
use voku\helper\UTF8;

/**
 * @see http://snowball.tartarus.org/algorithms/german/stemmer.html
 *
 * @author wamania
 */
class German extends Stem
{
    /**
     * All German vowels.
     */
    protected static $vowels = ['a', 'e', 'i', 'o', 'u', 'y', 'ä', 'ö', 'ü'];

    protected static $sEndings = ['b', 'd', 'f', 'g', 'h', 'k', 'l', 'm', 'n', 'r', 't'];

    protected static $stEndings = ['b', 'd', 'f', 'g', 'h', 'k', 'l', 'm', 'n', 't'];

    /**
     * {@inheritdoc}
     */
    public function stem($word)
    {
        // we do ALL in UTF-8
        if (!UTF8::is_utf8($word)) {
            throw new Exception('Word must be in UTF-8');
        }

        $this->plainVowels = implode('', self::$vowels);

        $this->word = UTF8::strtolower($word);

        // First, replace ß by ss
        $this->word = UTF8::str_replace('ß', 'ss', $this->word);

        // put u and y between vowels into upper case
        $this->word = preg_replace('#([' . $this->plainVowels . '])y([' . $this->plainVowels . '])#u', '$1Y$2', $this->word);
        $this->word = preg_replace('#([' . $this->plainVowels . '])u([' . $this->plainVowels . '])#u', '$1U$2', $this->word);

        //  R1 and R2 are first set up in the standard way
        $this->r1();
        $this->r2();

        // but then R1 is adjusted so that the region before it contains at least 3 letters.
        if ($this->r1Index < 3) {
            $this->r1Index = 3;
            $this->r1 = UTF8::substr($this->word, 3);
        }

        $this->step1();
        $this->step2();
        $this->step3();
        $this->finish();

        return $this->word;
    }

    /**
     * Step 1.
     */
    private function step1()
    {
        // delete if in R1
        if (($position = $this->search(['em', 'ern', 'er'])) !== false) {
            if ($this->inR1($position)) {
                $this->word = UTF8::substr($this->word, 0, $position);
            }

            return true;
        }

        // delete if in R1
        if (($position = $this->search(['es', 'en', 'e'])) !== false) {
            if ($this->inR1($position)) {
                $this->word = UTF8::substr($this->word, 0, $position);

                // If an ending of group (b) is deleted, and the ending is preceded by niss, delete the final s
                if ($this->search(['niss']) !== false) {
                    $this->word = UTF8::substr($this->word, 0, -1);
                }
            }

            return true;
        }

        // s (preceded by a valid s-ending)
        if (($position = $this->search(['s'])) !== false) {
            if ($this->inR1($position)) {
                $before = $position - 1;
                $letter = UTF8::substr($this->word, $before, 1);

                if (in_array($letter, self::$sEndings)) {
                    $this->word = UTF8::substr($this->word, 0, $position);
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Step 2.
     */
    private function step2()
    {
        // en   er   est
        //      delete if in R1
        if (($position = $this->search(['en', 'er', 'est'])) !== false) {
            if ($this->inR1($position)) {
                $this->word = UTF8::substr($this->word, 0, $position);
            }

            return true;
        }

        // st (preceded by a valid st-ending, itself preceded by at least 3 letters)
        //      delete if in R1
        if (($position = $this->search(['st'])) !== false) {
            if ($this->inR1($position)) {
                $before = $position - 1;
                if ($before >= 3) {
                    $letter = UTF8::substr($this->word, $before, 1);

                    if (in_array($letter, self::$stEndings)) {
                        $this->word = UTF8::substr($this->word, 0, $position);
                    }
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Step 3: d-suffixes.
     */
    private function step3()
    {
        // end   ung
        //      delete if in R2
        //      if preceded by ig, delete if in R2 and not preceded by e
        if (($position = $this->search(['end', 'ung'])) !== false) {
            if ($this->inR2($position)) {
                $this->word = UTF8::substr($this->word, 0, $position);
            }

            if (($position2 = $this->search(['ig'])) !== false) {
                $before = $position2 - 1;
                $letter = UTF8::substr($this->word, $before, 1);

                if (($this->inR2($position2)) && ($letter != 'e')) {
                    $this->word = UTF8::substr($this->word, 0, $position2);
                }
            }

            return true;
        }

        // ig   ik   isch
        //      delete if in R2 and not preceded by e
        if (($position = $this->search(['ig', 'ik', 'isch'])) !== false) {
            $before = $position - 1;
            $letter = UTF8::substr($this->word, $before, 1);

            if (($this->inR2($position)) && ($letter != 'e')) {
                $this->word = UTF8::substr($this->word, 0, $position);
            }

            return true;
        }

        // lich   heit
        //      delete if in R2
        //      if preceded by er or en, delete if in R1
        if (($position = $this->search(['lich', 'heit'])) != false) {
            if ($this->inR2($position)) {
                $this->word = UTF8::substr($this->word, 0, $position);
            }

            if (($position2 = $this->search(['er', 'en'])) !== false) {
                if ($this->inR1($position2)) {
                    $this->word = UTF8::substr($this->word, 0, $position2);
                }
            }

            return true;
        }

        // keit
        //      delete if in R2
        //      if preceded by lich or ig, delete if in R2
        if (($position = $this->search(['keit'])) != false) {
            if ($this->inR2($position)) {
                $this->word = UTF8::substr($this->word, 0, $position);
            }

            if (($position2 = $this->search(['lich', 'ig'])) !== false) {
                if ($this->inR2($position2)) {
                    $this->word = UTF8::substr($this->word, 0, $position2);
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Finally.
     */
    private function finish(): void
    {
        // turn U and Y back into lower case, and remove the umlaut accent from a, o and u.
        $this->word = UTF8::str_replace(['U', 'Y', 'ä', 'ü', 'ö'], ['u', 'y', 'a', 'u', 'o'], $this->word);
    }
}
