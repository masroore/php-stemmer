<?php

namespace Kaiju\Snowball\Tests;

use Kaiju\Snowball\Stemmer\Portuguese;
use PHPUnit\Framework\TestCase;

class PortugueseTest extends TestCase
{
    /**
     * @dataProvider load
     */
    public function testStem($word, $stem): void
    {
        $o = new Portuguese();

        $snowballStem = $o->stem($word);

        self::assertEquals($stem, $snowballStem);
    }

    public function load()
    {
        return new CsvFileIterator('tests/files/pt.txt');
    }
}
