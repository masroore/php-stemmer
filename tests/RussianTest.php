<?php

namespace Kaiju\Snowball\Tests;

use Kaiju\Snowball\Stemmer\Russian;
use PHPUnit\Framework\TestCase;

class RussianTest extends TestCase
{
    /**
     * @dataProvider load
     */
    public function testStem($word, $stem): void
    {
        $o = new Russian();

        $snowballStem = $o->stem($word);

        self::assertEquals($stem, $snowballStem);
    }

    public function load()
    {
        return new CsvFileIterator('tests/files/ru.txt');
    }
}
