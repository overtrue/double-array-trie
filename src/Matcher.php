<?php

namespace Overtrue\DoubleArrayTrie;

class Matcher
{
    public bool $hasValues = false;

    public function __construct(protected DoubleArrayTrie $trie)
    {
        $this->hasValues = $trie->hasValues();
    }

    public function exists(string $word): bool
    {
        return !!$this->match($word);
    }

    public function prefixMatch(string $string): array
    {
        $result = [];
        $currentCode = 0;
        $codes = Utils::str2codes($string);
        $base = $this->trie->getBaseValue($currentCode);
        $word = '';

        foreach ($codes as $code) {
            $position = $base;
            $nextState = $this->trie->getBaseValue($position);

            if ($base === $this->trie->getCheckValue($position) && $nextState < 0) {
                $result[$word] = $this->hasValues ? $this->trie->getValue($position) : -$nextState - 1;
            }

            $word .= \mb_chr($code);

            $position = $base + $code + 1;

            if ($base == $this->trie->getCheckValue($position)) {
                $base = $this->trie->getBaseValue($position);
            } else {
                return $result;
            }
        }

        $position = $base;
        $nextState = $this->trie->getBaseValue($position);

        if ($base == $this->trie->getCheckValue($position) && $nextState < 0) {
            $result[$word] = $this->hasValues ? $this->trie->getValue($position) : -$nextState - 1;
        }

        return $this->hasValues ? $result : array_keys($result);
    }

    public function match(string $word): mixed
    {
        $codes = Utils::str2codes($word);
        $currentCode = 0;
        $base = $this->trie->getBaseValue($currentCode);

        $word = '';
        foreach ($codes as $code) {
            $position = $base + $code + 1;

            if ($base == $this->trie->getCheckValue($position)) {
                $word .= \mb_chr($code);
                $base = $this->trie->getBaseValue($position);
            } else {
                return $this->hasValues ? $this->trie->getValue(-$position - 1) ?: false : !!$word;
            }
        }

        $position = $base;
        $nextState = $this->trie->getBaseValue($position);

        if ($base == $this->trie->getCheckValue($position) && $nextState < 0) {
            return $this->hasValues ? $this->trie->getValue($position) ?: false : !!$word;
        }

        return false;
    }
}
