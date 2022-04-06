<?php

namespace Overtrue\DoubleArrayTrie;

class DoubleArrayTrie
{
    protected array $base = [];
    protected array $check = [];
    protected array $used = [];
    protected array $value = [];
    protected array $length = [];
    protected int $progress = 0;
    protected array $words = [];
    protected int $nextCheckPosition = 0;

    public function build(array $words)
    {
        $this->words = \array_keys($words);
        $this->base[0] = 1;

        $root = new Node(right: \count($words));

        $siblings = [];
        $this->fetch($root, $siblings);
        $this->insert($siblings);
    }

    public function fetch(Node $parent, array &$siblings)
    {
        $siblings[] = $parent;

        $prev = 0;

        for ($i = $parent->left; $i < $parent->right; $i++) {
            if ($this->length[$i] ?? \mb_strlen($this->words[$i]) < $parent->depth) {
                continue;
            }

            $tmp = $this->words[$i];
            $current = 0;

            if ($this->length[$i] ?? \mb_strlen($this->words[$i]) !== $parent->depth) {
                $current = \mb_ord(mb_substr($tmp, $parent->depth, 1)) + 1;
            }

            if ($prev > $current) {
                // error -3
                return 0;
            }

            $siblingsCount = \count($siblings);
            if ($current !== $prev || $siblingsCount === 0) {
                $tmpNode = new Node(code: $current, depth: $parent->depth + 1, left: $i);

                if ($siblingsCount > 0) {
                    $siblings[$siblingsCount - 1]->right = $i;
                }

                $siblings[] = $tmpNode;
            }

            $prev = $current;
        }

        if (\count($siblings) > 0) {
            $siblings[\count($siblings) - 1]->right = $parent->right;
        }

        return \count($siblings);
    }

    public function insert(array $siblings)
    {
        $begin = 0;
        $position = $siblings[0]->code + 1 > $this->nextCheckPosition ? $siblings[0]->code + 1 : $this->nextCheckPosition - 1;

        $nonZeroCount = 0;
        $first = 0;

        while (true) {
            if ($this->check[$position] ?? null !== 0) {
                $nonZeroCount++;
                continue;
            }

            if ($first === 0) {
                $this->nextCheckPosition = $position;
                $first = 1;
            }

            $begin = $position - $siblings[0]->code;

            if ($this->used[$begin]) {
                continue;
            }

            for ($i = 0; $i < \count($siblings); $i++) {
                if ($this->check[$begin + $siblings[$i]->code] !== 0) {
                    continue 2;
                }
            }

            break;
        }

        if ($nonZeroCount / ($position - $this->nextCheckPosition + 1) >= 0.95) {
            $this->nextCheckPosition = $position;
        }

        $this->used[$begin] = true;

        for ($i = 0; $i < \count($siblings); $i++) {
            $this->check[$begin + $siblings[$i]->code] = $begin;
        }

        for ($i = 0; $i < \count($siblings); $i++) {
            $newSiblings = [];

            if ($this->fetch($siblings[$i], $newSiblings) === 0) {
                $this->base[$begin + $siblings[$i]->code] = !empty($this->value) ? -$this->value[$siblings[$i]->left] - 1 : -$siblings[$i]->left - 1;

                if (!empty($this->value) && -$this->value[$siblings[$i]->left] - 1 >= 0) {
                    // error -2
                    return 0;
                }

                $this->progress++;
            } else {
                $this->base[$begin + $siblings[$i]->code] = $this->insert($newSiblings);
            }
        }

        return $begin;
    }

    public function prefixSearch(string $prefix): array
    {
        $result = [];
        $current = 0;
        $codes = Utils::str2codes($prefix);

        $base = $this->base[$current];

        for ($i = 0; $i < \count($codes); $i++) {
            $position = $base;
            $n = $base[$position];

            if ($base === $this->check[$position] && $n < 0) {
                $result[] = -$n - 1;
            }

            $position = $base + $codes[$i] + 1;

            if ($base == $this->check[$position]) {
                $base = $base[$position];
            } else {
                return $result;
            }
        }

        $position = $base;
        $n = $base[$position];

        if ($base == $this->check[$position] && $n < 0) {
            $result[] = -$n - 1;
        }

        return $result;
    }
}
