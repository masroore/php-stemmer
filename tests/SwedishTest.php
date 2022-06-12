<?php

namespace Kaiju\Snowball\Tests;

use Kaiju\Snowball\Stemmer\Swedish;
use PHPUnit\Framework\TestCase;

class SwedishTest extends TestCase
{
    /**
     * @dataProvider load
     */
    public function testStem($word, $stem): void
    {
        $o = new Swedish();

        $snowballStem = $o->stem($word);

        self::assertEquals($stem, $snowballStem);
    }

    public function load()
    {
        return new CsvFileIterator('tests/files/sw.txt');
    }
}
