<?php

namespace Overtrue\DoubleArrayTrie;

class Node
{
    public function __construct(
        public ?int $code = null,
        public int $depth = 0,
        public int $left = 0,
        public int $right = 0,
    ) {
    }
}
