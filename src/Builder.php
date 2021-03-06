<?php

namespace Overtrue\DoubleArrayTrie;

class Builder
{
    protected array $used = [];
    protected array $words = [];
    protected array $values = [];
    protected int $nextCheckPosition = 0;

    /**
     * @throws Exception
     */
    public function build(array $words): DoubleArrayTrie
    {
        $values = [];
        if (!\array_is_list($words)) {
            \ksort($words);

            [$words, $values] = [array_keys($words), array_values($words)];
        } else {
            \sort($words);
        }

        $trie = new DoubleArrayTrie();

        $this->words = $words;
        $this->values = $values;

        $root = new Node(right: \count($words));

        $this->insert($trie, $this->fetch($root));

        ksort($trie->base, \SORT_NUMERIC);
        ksort($trie->check, \SORT_NUMERIC);

        return $trie;
    }

    /**
     * @throws Exception
     */
    public function fetch(Node $parent): array
    {
        $siblings = [];
        $prevCode = 0;

        for ($i = $parent->left; $i < $parent->right; $i++) {
            if (\mb_strlen($this->words[$i]) < $parent->depth) {
                continue;
            }

            $tmp = $this->words[$i];
            $value = $this->values[$i] ?? null;
            $currentCode = 0;

            if (\mb_strlen($tmp) !== $parent->depth) {
                $currentCode = \mb_ord(\mb_substr($tmp, $parent->depth, 1)) + 1;
            }

            if ($prevCode > $currentCode) {
                throw new Exception('The words must be sorted.');
            }

            $siblingsCount = \count($siblings);

            if ($currentCode !== $prevCode || $siblingsCount === 0) {
                $tmpNode = new Node(code: $currentCode, value: $value, depth: $parent->depth + 1, left: $i);

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

    /**
     * @throws Exception
     */
    public function insert(DoubleArrayTrie $trie, array $siblings): int
    {
        $first = 0;
        $nonZeroCount = 0;
        $position = max($siblings[0]->code + 1, $this->nextCheckPosition) - 1;

        // ????????????????????????????????????base[begin + a1...an]  == 0???n???????????????,a1...an???siblings??????n?????????
        outer: while (true) {
            $position++;
            if ($trie->getCheckValue($position) !== 0) {
                $nonZeroCount++;
                continue;
            } elseif ($first === 0) {
                $this->nextCheckPosition = $position;
                $first = 1;
            }

            $begin = $position - $siblings[0]->code; // ?????????????????????????????????????????????

            if ($this->positionHasBeenUsed($begin)) {
                continue;
            }

            for ($i = 1; $i < \count($siblings); $i++) {
                if (($trie->getCheckValue($begin + $siblings[$i]->code)) !== 0) {
                    goto outer;
                }
            }

            break;
        }

        if ($nonZeroCount * 1.0 / ($position - $this->nextCheckPosition + 1) >= 0.95) {
            $this->nextCheckPosition = $position;
        }

        $this->markPositionAsUsed($begin);

        // ???????????????????????? check ???
        for ($i = 0; $i < \count($siblings); $i++) {
            $trie->check[$begin + $siblings[$i]->code] = $begin;
        }

        // ???????????????????????? base ???
        for ($i = 0; $i < \count($siblings); $i++) {
            $newSiblings = $this->fetch($siblings[$i]);

            // ????????????????????????????????????????????????????????????????????????
            if (empty($newSiblings)) {
                $trie->base[$begin + $siblings[$i]->code] = -$siblings[$i]->left - 1;
                empty($this->values) || $trie->values[$begin + $siblings[$i]->code] = $siblings[$i]->value;
            } else {
                $trie->base[$begin + $siblings[$i]->code] = $this->insert($trie, $newSiblings);
            }
        }

        return $begin;
    }

    protected function positionHasBeenUsed(mixed $begin): bool
    {
        return !!($this->used[$begin] ?? false);
    }

    protected function markPositionAsUsed(mixed $begin): static
    {
        $this->used[$begin] = true;

        return $this;
    }
}
