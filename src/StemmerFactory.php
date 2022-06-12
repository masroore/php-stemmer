<?php

declare(strict_types=1);

namespace Kaiju\Snowball;

use Kaiju\Snowball\Stemmer\Catalan;
use Kaiju\Snowball\Stemmer\Danish;
use Kaiju\Snowball\Stemmer\Dutch;
use Kaiju\Snowball\Stemmer\English;
use Kaiju\Snowball\Stemmer\Finnish;
use Kaiju\Snowball\Stemmer\French;
use Kaiju\Snowball\Stemmer\German;
use Kaiju\Snowball\Stemmer\Italian;
use Kaiju\Snowball\Stemmer\Norwegian;
use Kaiju\Snowball\Stemmer\Portuguese;
use Kaiju\Snowball\Stemmer\Romanian;
use Kaiju\Snowball\Stemmer\Russian;
use Kaiju\Snowball\Stemmer\Spanish;
use Kaiju\Snowball\Stemmer\Stemmer;
use Kaiju\Snowball\Stemmer\Swedish;
use voku\helper\UTF8;

class StemmerFactory
{
    const LANGS = [
        Catalan::class => ['ca', 'cat', 'catalan'],
        Danish::class => ['da', 'dan', 'danish'],
        Dutch::class => ['nl', 'dut', 'nld', 'dutch'],
        English::class => ['en', 'eng', 'english'],
        Finnish::class => ['fi', 'fin', 'finnish'],
        French::class => ['fr', 'fre', 'fra', 'french'],
        German::class => ['de', 'deu', 'ger', 'german'],
        Italian::class => ['it', 'ita', 'italian'],
        Norwegian::class => ['no', 'nor', 'norwegian'],
        Portuguese::class => ['pt', 'por', 'portuguese'],
        Romanian::class => ['ro', 'rum', 'ron', 'romanian'],
        Russian::class => ['ru', 'rus', 'russian'],
        Spanish::class => ['es', 'spa', 'spanish'],
        Swedish::class => ['sv', 'swe', 'swedish'],
    ];

    public static function create(string $code): Stemmer
    {
        $code = UTF8::strtolower($code);

        foreach (self::LANGS as $classname => $isoCodes) {
            if (in_array($code, $isoCodes)) {
                return new $classname();
            }
        }

        throw new NotFoundException(sprintf('Stemmer not found for %s', $code));
    }
}
