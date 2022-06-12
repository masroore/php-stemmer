<?php

namespace Kaiju\Snowball\Tests;

use Kaiju\Snowball\Stemmer\Italian;
use PHPUnit\Framework\TestCase;

class ItalianTest extends TestCase
{
    /**
     * @dataProvider load
     */
    public function testStem($word, $stem): void
    {
        $o = new Italian();

        $snowballStem = $o->stem($word);

        self::assertEquals($stem, $snowballStem);
    }

    public function load()
    {
        return new CsvFileIterator('tests/files/it.txt');
    }
}
