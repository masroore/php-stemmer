<?php

declare(strict_types=1);

namespace Kaiju\Snowball;

class StemmerManager
{
    private array $stemmers;

    public function __construct()
    {
        $this->stemmers = [];
    }

    public function stem(string $word, string $isoCode): string
    {
        if (!isset($this->stemmers[$isoCode])) {
            $this->stemmers[$isoCode] = StemmerFactory::create($isoCode);
        }

        return $this->stemmers[$isoCode]->stem($word);
    }
}
