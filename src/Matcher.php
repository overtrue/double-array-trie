<?php

namespace Overtrue\DoubleArrayTrie;

class Matcher
{
    public function __construct(protected DoubleArrayTrie $trie)
    {
    }

    public function search(string $prefix): array
    {
        // todo
        return [];
    }

    public function prefixSearch(string $prefix): array
    {
        $result = [];
        $currentCode = 0;
        $codes = Utils::str2codes($prefix);
        $base = $this->trie->getBaseValue($currentCode);

        foreach ($codes as $code) {
            $position = $base;
            $n = $this->trie->getBaseValue($position);

            if ($base === $this->trie->getCheckValue($position) && $n < 0) {
                $result[] = mb_chr($code);
            }

            $position = $base + $code + 1;

            if ($base == $this->trie->getCheckValue($position)) {
                $base = $this->trie->getBaseValue($position);
            } else {
                return $result;
            }
        }

        $position = $base;
        $n = $this->trie->getBaseValue($position);

        if ($base == $this->trie->getCheckValue($position) && $n < 0) {
            $result[] = mb_chr($code);
        }

        return $result;
    }
}
