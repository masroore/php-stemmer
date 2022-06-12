<?php

namespace Kaiju\Snowball\Tests;

use Kaiju\Snowball\Stemmer\Spanish;
use PHPUnit\Framework\TestCase;

class SpanishTest extends TestCase
{
    /**
     * @dataProvider load
     */
    public function testStem($word, $stem): void
    {
        $o = new Spanish();

        $snowballStem = $o->stem($word);

        self::assertEquals($stem, $snowballStem);
    }

    public function load()
    {
        return new CsvFileIterator('tests/files/es.txt');
    }
}
