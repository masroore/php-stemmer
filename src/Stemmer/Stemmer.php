<?php

declare(strict_types=1);

namespace Kaiju\Snowball\Stemmer;

/**
 * @author Luís Cobucci <lcobucci@gmail.com>
 */
interface Stemmer
{
    /**
     * Main function to get the STEM of a word.
     *
     * @param string $word A valid UTF-8 word
     */
    public function stem(string $word): string;
}
