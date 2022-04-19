<?php

namespace Overtrue\DoubleArrayTrie;

class Node
{
    public function __construct(
        // 节点字符的编码
        public ?int $code = null,

        // 节点所在树的深度
        public int $depth = 0,

        // 节点的子节点在字典中范围的左边界
        public int $left = 0,

        // 节点的子节点在字典中范围的右边界
        public int $right = 0,
    ) {
    }
}
