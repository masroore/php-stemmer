<?php

namespace Kaiju\Snowball\Tests;

use Kaiju\Snowball\Stemmer\Romanian;
use PHPUnit\Framework\TestCase;

class RomanianTest extends TestCase
{
    /**
     * @dataProvider load
     */
    public function testStem($word, $stem): void
    {
        $o = new Romanian();

        $snowballStem = $o->stem($word);

        self::assertEquals($stem, $snowballStem);
    }

    public function load()
    {
        return new CsvFileIterator('tests/files/ro.txt');
    }
}
