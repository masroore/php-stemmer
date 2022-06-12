<?php

namespace Wamania\Snowball\Tests;

class CsvFileVerboseIterator extends CsvFileIterator
{
    public function rewind(): void
    {
        parent::rewind();
        $this->_updateKey($this->current());
    }

    public function next(): void
    {
        parent::next();
        if ($this->valid()) {
            $this->_updateKey($this->current());
        }
    }

    protected function _updateKey($value): void
    {
        if ($value && count($value)) {
            $this->key = $value[0];
        } elseif (count($this->current)) {
            $this->key = $this->current[0];
        }
    }
}
