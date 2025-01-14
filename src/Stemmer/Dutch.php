<?php

namespace Kaiju\Snowball\Stemmer;

use Exception;
use voku\helper\UTF8;

/**
 * @see http://snowball.tartarus.org/algorithms/dutch/stemmer.html
 *
 * @author wamania
 */
class Dutch extends Stem
{
    /**
     * All dutch vowels.
     */
    protected static array $vowels = ['a', 'e', 'i', 'o', 'u', 'y', 'è'];

    /**
     * {@inheritdoc}
     */
    public function stem(string $word): string
    {
        // we do ALL in UTF-8
        if (!UTF8::is_utf8($word)) {
            throw new Exception('Word must be in UTF-8');
        }

        $this->word = UTF8::strtolower($word);

        // First, remove all umlaut and acute accents.
        $this->word = UTF8::str_replace(
            ['ä', 'ë', 'ï', 'ö', 'ü', 'á', 'é', 'í', 'ó', 'ú'],
            ['a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u'],
            $this->word
        );

        $this->plainVowels = implode('', self::$vowels);

        // Put initial y, y after a vowel, and i between vowels into upper case.
        $this->word = preg_replace('#^y#u', 'Y', $this->word);
        $this->word = preg_replace('#([' . $this->plainVowels . '])y#u', '$1Y', $this->word);
        $this->word = preg_replace('#([' . $this->plainVowels . '])i([' . $this->plainVowels . '])#u', '$1I$2', $this->word);

        // R1 and R2 (see the note on R1 and R2) are then defined as in German.
        // R1 and R2 are first set up in the standard way
        $this->r1();
        $this->r2();

        // but then R1 is adjusted so that the region before it contains at least 3 letters.
        if ($this->r1Index < 3) {
            $this->r1Index = 3;
            $this->r1 = UTF8::substr($this->word, 3);
        }

        // Do each of steps 1, 2 3 and 4.
        $this->step1();
        $removedE = $this->step2();
        $this->step3a();
        $this->step3b($removedE);
        $this->step4();
        $this->finish();

        return $this->word;
    }

    /**
     * Define a valid s-ending as a non-vowel other than j.
     *
     * @return bool
     */
    private function hasValidSEnding($word)
    {
        $lastLetter = UTF8::substr($word, -1, 1);

        return !in_array($lastLetter, array_merge(self::$vowels, ['j']));
    }

    /**
     * Define a valid en-ending as a non-vowel, and not gem.
     *
     * @return bool
     */
    private function hasValidEnEnding($word)
    {
        $lastLetter = UTF8::substr($word, -1, 1);
        if (in_array($lastLetter, self::$vowels)) {
            return false;
        }

        $threeLastLetters = UTF8::substr($word, -3, 3);
        if ($threeLastLetters == 'gem') {
            return false;
        }

        return true;
    }

    /**
     *  Define undoubling the ending as removing the last letter if the word ends kk, dd or tt.
     */
    private function unDoubling(): void
    {
        if ($this->search(['kk', 'dd', 'tt']) !== false) {
            $this->word = UTF8::substr($this->word, 0, -1);
        }
    }

    /**
     * Step 1
     * Search for the longest among the following suffixes, and perform the action indicated.
     */
    private function step1()
    {
        // heden
        //      replace with heid if in R1
        if (($position = $this->search(['heden'])) !== false) {
            if ($this->inR1($position)) {
                $this->word = preg_replace('#(heden)$#u', 'heid', $this->word);
            }

            return true;
        }

        // en   ene
        //      delete if in R1 and preceded by a valid en-ending, and then undouble the ending
        if (($position = $this->search(['ene', 'en'])) !== false) {
            if ($this->inR1($position)) {
                $word = UTF8::substr($this->word, 0, $position);
                if ($this->hasValidEnEnding($word)) {
                    $this->word = $word;
                    $this->unDoubling();
                }
            }

            return true;
        }

        // s   se
        //      delete if in R1 and preceded by a valid s-ending
        if (($position = $this->search(['se', 's'])) !== false) {
            if ($this->inR1($position)) {
                $word = UTF8::substr($this->word, 0, $position);
                if ($this->hasValidSEnding($word)) {
                    $this->word = $word;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Step 2
     * Delete suffix e if in R1 and preceded by a non-vowel, and then undouble the ending.
     */
    private function step2()
    {
        if (($position = $this->search(['e'])) !== false) {
            if ($this->inR1($position)) {
                $letter = UTF8::substr($this->word, -2, 1);
                if (!in_array($letter, self::$vowels)) {
                    $this->word = UTF8::substr($this->word, 0, $position);
                    $this->unDoubling();

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Step 3a: heid
     * delete heid if in R2 and not preceded by c, and treat a preceding en as in step 1(b).
     */
    private function step3a(): void
    {
        if (($position = $this->search(['heid'])) !== false) {
            if ($this->inR2($position)) {
                $letter = UTF8::substr($this->word, -5, 1);
                if ($letter !== 'c') {
                    $this->word = UTF8::substr($this->word, 0, $position);

                    if (($position = $this->search(['en'])) !== false) {
                        if ($this->inR1($position)) {
                            $word = UTF8::substr($this->word, 0, $position);
                            if ($this->hasValidEnEnding($word)) {
                                $this->word = $word;
                                $this->unDoubling();
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Step 3b: d-suffixe
     * Search for the longest among the following suffixes, and perform the action indicated.
     */
    private function step3b($removedE)
    {
        // end   ing
        //      delete if in R2
        //      if preceded by ig, delete if in R2 and not preceded by e, otherwise undouble the ending
        if (($position = $this->search(['end', 'ing'])) !== false) {
            if ($this->inR2($position)) {
                $this->word = UTF8::substr($this->word, 0, $position);

                if (($position2 = $this->searchIfInR2(['ig'])) !== false) {
                    $letter = UTF8::substr($this->word, -3, 1);
                    if ($letter !== 'e') {
                        $this->word = UTF8::substr($this->word, 0, $position2);
                    }
                } else {
                    $this->unDoubling();
                }
            }

            return true;
        }

        // ig
        //      delete if in R2 and not preceded by e
        if (($position = $this->search(['ig'])) !== false) {
            if ($this->inR2($position)) {
                $letter = UTF8::substr($this->word, -3, 1);
                if ($letter !== 'e') {
                    $this->word = UTF8::substr($this->word, 0, $position);
                }
            }

            return true;
        }

        // lijk
        //      delete if in R2, and then repeat step 2
        if (($position = $this->search(['lijk'])) !== false) {
            if ($this->inR2($position)) {
                $this->word = UTF8::substr($this->word, 0, $position);
                $this->step2();
            }

            return true;
        }

        // baar
        //      delete if in R2
        if (($position = $this->search(['baar'])) !== false) {
            if ($this->inR2($position)) {
                $this->word = UTF8::substr($this->word, 0, $position);
            }

            return true;
        }

        // bar
        //      delete if in R2 and if step 2 actually removed an e
        if (($position = $this->search(['bar'])) !== false) {
            if ($this->inR2($position) && $removedE) {
                $this->word = UTF8::substr($this->word, 0, $position);
            }

            return true;
        }

        return false;
    }

    /**
     * Step 4: undouble vowel
     * If the words ends CVD, where C is a non-vowel, D is a non-vowel other than I, and V is double a, e, o or u,
     * remove one of the vowels from V (for example, maan -> man, brood -> brod).
     */
    private function step4()
    {
        // D is a non-vowel other than I
        $d = UTF8::substr($this->word, -1, 1);
        if (in_array($d, array_merge(self::$vowels, ['I']))) {
            return false;
        }

        // V is double a, e, o or u
        $v = UTF8::substr($this->word, -3, 2);
        if (!in_array($v, ['aa', 'ee', 'oo', 'uu'])) {
            return false;
        }
        $singleV = UTF8::substr($v, 0, 1);

        // C is a non-vowel
        $c = UTF8::substr($this->word, -4, 1);
        if (in_array($c, self::$vowels)) {
            return false;
        }

        $this->word = UTF8::substr($this->word, 0, -4);
        $this->word .= $c . $singleV . $d;
    }

    /**
     * Finally
     * Turn I and Y back into lower case.
     */
    private function finish(): void
    {
        $this->word = UTF8::str_replace(['I', 'Y'], ['i', 'y'], $this->word);
    }
}
