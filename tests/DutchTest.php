<?php

namespace Kaiju\Snowball\Tests;

use Kaiju\Snowball\Stemmer\Dutch;
use PHPUnit\Framework\TestCase;

class DutchTest extends TestCase
{
    /**
     * @dataProvider load
     */
    public function testStem($word, $stem): void
    {
        $o = new Dutch();

        $snowballStem = $o->stem($word);

        self::assertEquals($stem, $snowballStem);
    }

    public function load()
    {
        return new CsvFileIterator('tests/files/nl.txt');
    }
}
