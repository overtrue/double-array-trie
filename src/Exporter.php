<?php

namespace Overtrue\DoubleArrayTrie;

class Exporter
{
    public function __construct(public DoubleArrayTrie $trie)
    {
    }

    public function toFile(string $path): bool|int
    {
        return \file_put_contents($path, \json_encode($this->trie->toArray()));
    }

    public function toPHP(?string $path = null): string
    {
        $contents = "<?php return ".var_export($this->trie->toArray(), true);

        if ($path !== null) {
            \mkdir(\dirname($path), 0777, true);
            \file_put_contents($path, $contents);
        }

        return $contents;
    }
}
