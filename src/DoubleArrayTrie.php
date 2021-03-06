<?php

namespace Overtrue\DoubleArrayTrie;

use JetBrains\PhpStorm\Pure;

class DoubleArrayTrie
{
    public function __construct(
        public array $base = [],
        public array $check = [],
        public array $values = [],
    ) {
        $this->base[0] = 1;
    }

    public function getCheckValue(int $position): int
    {
        return $this->check[$position] ?? 0;
    }

    public function getBaseValue(int $position): int
    {
        return $this->base[$position] ?? 0;
    }

    public function getValue(int $position): ?string
    {
        return $this->values[$position] ?? null;
    }

    public function hasValues(): bool
    {
        return count($this->values) > 0;
    }

    public function hasPosition(int $position): bool
    {
        return array_key_exists($position, $this->base);
    }

    #[ArrayShape(['base' => "array", 'check' => "array", 'values' => "array"])]
    public function toArray(): array
    {
        return [
            'base' => $this->base, 'check' => $this->check, 'values' => $this->values
        ];
    }

    #[Pure]
    #[ArrayShape(['base' => "array", 'check' => "array", 'values' => "array"])]
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    #[Pure]
    public function export(): Exporter
    {
        return new Exporter($this);
    }
}
