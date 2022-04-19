<?php

namespace Overtrue\DoubleArrayTrie;

class DoubleArrayTrie
{
    protected array $base = [];
    protected array $check = [];
    protected array $used = [];
    protected array $value = [];
    protected array $words = [];
    protected int $nextCheckPosition = 0;

    public function build(array $words, array $values = [])
    {
        $hasValues = !empty($values);

        if ($hasValues) {
            if (count($words) !== count($values)) {
                throw new \Exception('The number of words and values must be equal.');
            }

            $tmp = array_combine($words, $values);

            $words = array_keys($tmp);
            $values = array_values($tmp);
        }

        $this->words = $words;
        $this->values = $values;

        $this->base[0] = 1;

        $root = new Node(right: \count($words));

        $this->insert($this->fetch($root));

        ksort($this->base);
        ksort($this->check);
    }

    public function getCheckValue(int $position)
    {
        return $this->check[$position] ?? 0;
    }

    public function getBaseValue(int $position)
    {
        return $this->base[$position] ?? 0;
    }

    public function fetch(Node $parent)
    {
        $siblings = [];
        $prevCode = 0;

        for ($i = $parent->left; $i < $parent->right; $i++) {
            if (\mb_strlen($this->words[$i]) < $parent->depth) {
                continue;
            }

            $tmp = $this->words[$i];
            $currentCode = 0;

            if (\mb_strlen($tmp) !== $parent->depth) {
                $currentCode = \mb_ord(\mb_substr($tmp, $parent->depth, 1)) + 1;
            }

            if ($prevCode > $currentCode) {
                throw new Exception('The words must be sorted.');
            }

            $siblingsCount = \count($siblings);

            if ($currentCode !== $prevCode || $siblingsCount === 0) {
                $tmpNode = new Node(code: $currentCode, depth: $parent->depth + 1, left: $i);

                if ($siblingsCount > 0) {
                    $siblings[$siblingsCount - 1]->right = $i;
                }

                $siblings[] = $tmpNode;
            }

            $prevCode = $currentCode;
        }

        if (\count($siblings) > 0) {
            $siblings[\count($siblings) - 1]->right = $parent->right;
        }

        return $siblings;
    }

    public function insert(array $siblings)
    {
        $begin = 0;
        $position = ($siblings[0]->code + 1 > $this->nextCheckPosition ? $siblings[0]->code + 1 : $this->nextCheckPosition) - 1;

        $nonZeroCount = 0;
        $first = 0;

        // 此循环体的目标是找出满足base[begin + a1...an]  == 0的n个空闲空间,a1...an是siblings中的n个节点
        outer: while (true) {
            $position ++;
            if ($this->getCheckValue($position) !== 0) {
                $nonZeroCount++;
                continue;
            } else if ($first === 0) {
                $this->nextCheckPosition = $position;
                $first = 1;
            }

            $begin = $position - $siblings[0]->code; // 当前位置离第一个兄弟节点的距离

            if ($this->used[$begin] ?? false) {
                continue;
            }

            for ($i = 1; $i < \count($siblings); $i++) {
                if (($this->getCheckValue($begin + $siblings[$i]->code)) !== 0) {
                    var_dump(111);
                    goto outer;
                }
            }

            break;
        }

        if ($nonZeroCount * 1.0 / ($position - $this->nextCheckPosition + 1) >= 0.95) {
            $this->nextCheckPosition = $position;
        }

        $this->used[$begin] = true;

        // 计算所有子节点的 check 值
        for ($i = 0; $i < \count($siblings); $i++) {
            $this->check[$begin + $siblings[$i]->code] = $begin;
        }

        // 计算所有子节点的 base 值
        for ($i = 0; $i < \count($siblings); $i++) {
            $newSiblings = $this->fetch($siblings[$i]);

            // 一个词的终止且不为其他词的前缀，其实就是叶子节点
            if (empty($newSiblings)) {
                $this->base[$begin + $siblings[$i]->code] = -$siblings[$i]->left - 1;
            } else {
                $this->base[$begin + $siblings[$i]->code] = $this->insert($newSiblings);
            }
        }

        return $begin;
    }

    public function prefixSearch(string $prefix): array
    {
        $result = [];
        $currentCode = 0;
        $codes = Utils::str2codes($prefix);

        $base = $this->base[$currentCode] ?? 0;

        for ($i = 0; $i < \count($codes); $i++) {
            $position = $base;
            $n = $this->getBaseValue($position);

            if ($base === ($this->getCheckValue($position)) && $n < 0) {
                $result[] = -$n - 1;
            }

            $position = $base + $codes[$i] + 1;

            if ($base == $this->getCheckValue($position)) {
                $base = $this->getBaseValue($position);
            } else {
                return $result;
            }
        }

        $position = $base;
        $n = $this->getBaseValue($position);

        if ($base == $this->getCheckValue($position) && $n < 0) {
            $result[] = -$n - 1;
        }

        return $result;
    }
}
