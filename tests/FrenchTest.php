<?php

namespace Kaiju\Snowball\Tests;

use Kaiju\Snowball\Stemmer\French;
use PHPUnit\Framework\TestCase;

class FrenchTest extends TestCase
{
    /**
     * @dataProvider load
     */
    public function testStem($word, $stem): void
    {
        $o = new French();

        $snowballStem = $o->stem($word);

        self::assertEquals($stem, $snowballStem);
    }

    public function load()
    {
        return new CsvFileIterator('tests/files/fr.txt');
    }
}
