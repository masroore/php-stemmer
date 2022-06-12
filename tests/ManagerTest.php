<?php

namespace Kaiju\Snowball\Tests;

use Kaiju\Snowball\StemmerManager;
use PHPUnit\Framework\TestCase;

class ManagerTest extends TestCase
{
    public function testManager(): void
    {
        $stemmerManager = new StemmerManager();

        self::assertEquals('anticonstitutionnel', $stemmerManager->stem('anticonstitutionnelement', 'fr'));
    }
}
