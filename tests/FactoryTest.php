<?php

namespace Kaiju\Snowball\Tests;

use Kaiju\Snowball\StemmerFactory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testFactory(): void
    {
        $isoCodes = [
            'ca' => 'Kaiju\\Snowball\\Stemmer\\Catalan',
            'cat' => 'Kaiju\\Snowball\\Stemmer\\Catalan',
            'catalan' => 'Kaiju\\Snowball\\Stemmer\\Catalan',
            'da' => 'Kaiju\\Snowball\\Stemmer\\Danish',
            'dan' => 'Kaiju\\Snowball\\Stemmer\\Danish',
            'danish' => 'Kaiju\\Snowball\\Stemmer\\Danish',
            'nl' => 'Kaiju\\Snowball\\Stemmer\\Dutch',
            'dut' => 'Kaiju\\Snowball\\Stemmer\\Dutch',
            'nld' => 'Kaiju\\Snowball\\Stemmer\\Dutch',
            'dutch' => 'Kaiju\\Snowball\\Stemmer\\Dutch',
            'en' => 'Kaiju\\Snowball\\Stemmer\\English',
            'eng' => 'Kaiju\\Snowball\\Stemmer\\English',
            'english' => 'Kaiju\\Snowball\\Stemmer\\English',
            'fr' => 'Kaiju\\Snowball\\Stemmer\\French',
            'fre' => 'Kaiju\\Snowball\\Stemmer\\French',
            'fra' => 'Kaiju\\Snowball\\Stemmer\\French',
            'french' => 'Kaiju\\Snowball\\Stemmer\\French',
            'de' => 'Kaiju\\Snowball\\Stemmer\\German',
            'deu' => 'Kaiju\\Snowball\\Stemmer\\German',
            'ger' => 'Kaiju\\Snowball\\Stemmer\\German',
            'german' => 'Kaiju\\Snowball\\Stemmer\\German',
            'it' => 'Kaiju\\Snowball\\Stemmer\\Italian',
            'ita' => 'Kaiju\\Snowball\\Stemmer\\Italian',
            'italian' => 'Kaiju\\Snowball\\Stemmer\\Italian',
            'no' => 'Kaiju\\Snowball\\Stemmer\\Norwegian',
            'nor' => 'Kaiju\\Snowball\\Stemmer\\Norwegian',
            'norwegian' => 'Kaiju\\Snowball\\Stemmer\\Norwegian',
            'pt' => 'Kaiju\\Snowball\\Stemmer\\Portuguese',
            'por' => 'Kaiju\\Snowball\\Stemmer\\Portuguese',
            'portuguese' => 'Kaiju\\Snowball\\Stemmer\\Portuguese',
            'ro' => 'Kaiju\\Snowball\\Stemmer\\Romanian',
            'rum' => 'Kaiju\\Snowball\\Stemmer\\Romanian',
            'ron' => 'Kaiju\\Snowball\\Stemmer\\Romanian',
            'romanian' => 'Kaiju\\Snowball\\Stemmer\\Romanian',
            'ru' => 'Kaiju\\Snowball\\Stemmer\\Russian',
            'rus' => 'Kaiju\\Snowball\\Stemmer\\Russian',
            'russian' => 'Kaiju\\Snowball\\Stemmer\\Russian',
            'es' => 'Kaiju\\Snowball\\Stemmer\\Spanish',
            'spa' => 'Kaiju\\Snowball\\Stemmer\\Spanish',
            'spanish' => 'Kaiju\\Snowball\\Stemmer\\Spanish',
            'sv' => 'Kaiju\\Snowball\\Stemmer\\Swedish',
            'swe' => 'Kaiju\\Snowball\\Stemmer\\Swedish',
            'swedish' => 'Kaiju\\Snowball\\Stemmer\\Swedish',
        ];

        foreach ($isoCodes as $isoCode => $classname) {
            $stemmer = StemmerFactory::create($isoCode);

            self::assertInstanceOf($classname, $stemmer);
        }
    }
}
