<?php

namespace Kaiju\Snowball\Tests;

use Kaiju\Snowball\Stemmer\Norwegian;
use PHPUnit\Framework\TestCase;

class NorwegianTest extends TestCase
{
    /**
     * @dataProvider load
     */
    public function testStem($word, $stem): void
    {
        $o = new Norwegian();

        $snowballStem = $o->stem($word);

        self::assertEquals($stem, $snowballStem);
    }

    public function load()
    {
        return new CsvFileIterator('tests/files/no.txt');
    }
}
