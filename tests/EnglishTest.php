<?php

namespace Kaiju\Snowball\Tests;

use Kaiju\Snowball\Stemmer\English;
use PHPUnit\Framework\TestCase;

class EnglishTest extends TestCase
{
    /**
     * @dataProvider load
     */
    public function testStem($word, $stem): void
    {
        $o = new English();

        $snowballStem = $o->stem($word);

        self::assertEquals($stem, $snowballStem);
    }

    public function load()
    {
        return new CsvFileIterator('tests/files/en.txt');
    }
}
