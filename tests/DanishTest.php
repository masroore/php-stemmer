<?php

namespace Kaiju\Snowball\Tests;

use Kaiju\Snowball\Stemmer\Danish;
use PHPUnit\Framework\TestCase;

class DanishTest extends TestCase
{
    /**
     * @dataProvider load
     */
    public function testStem($word, $stem): void
    {
        $o = new Danish();

        $snowballStem = $o->stem($word);

        self::assertEquals($stem, $snowballStem);
    }

    public function load(): CsvFileIterator
    {
        return new CsvFileIterator('tests/files/dk.txt');
    }
}
