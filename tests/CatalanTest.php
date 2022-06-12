<?php

namespace Kaiju\Snowball\Tests;

use Kaiju\Snowball\Stemmer\Catalan;
use PHPUnit\Framework\TestCase;

class CatalanTest extends TestCase
{
    /**
     * @dataProvider load
     */
    public function testStem($word, $stem): void
    {
        $o = new Catalan();

        $snowballStem = $o->stem($word);

        self::assertEquals($stem, $snowballStem);
    }

    public function load(): CsvFileVerboseIterator
    {
        return new CsvFileVerboseIterator('tests/files/ca.txt');
    }
}
