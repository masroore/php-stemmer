<?php

namespace Kaiju\Snowball\Tests;

use Kaiju\Snowball\Stemmer\German;
use PHPUnit\Framework\TestCase;

class GermanTest extends TestCase
{
    /**
     * @dataProvider load
     */
    public function testStem($word, $stem): void
    {
        $o = new German();

        $snowballStem = $o->stem($word);

        self::assertEquals($stem, $snowballStem);
    }

    public function load()
    {
        return new CsvFileIterator('tests/files/de.txt');
    }
}
