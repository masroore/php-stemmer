<?php

namespace Kaiju\Snowball\Tests;

use Kaiju\Snowball\Stemmer\Finnish;
use PHPUnit\Framework\TestCase;

class FinnishTest extends TestCase
{
    /**
     * @dataProvider load
     */
    public function testStem($word, $stem): void
    {
        $o = new Finnish();

        $snowballStem = $o->stem($word);

        self::assertEquals($stem, $snowballStem);
    }

    public function load()
    {
        return new CsvFileIterator('tests/files/fi.txt');
    }
}
