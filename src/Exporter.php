<?php

namespace Overtrue\DoubleArrayTrie;

class Exporter
{
    public function __construct(public DoubleArrayTrie $trie)
    {
    }

    public function toFile(string $path): int|bool
    {
        if (\str_ends_with($path, '.php')) {
            return $this->toPHP($path);
        }

        return $this->writeFile($path, \json_encode($this->trie->toArray()));
    }

    public function toPHP(?string $path = null): string
    {
        $contents = "<?php return ".var_export($this->trie->toArray(), true).';';

        if ($path !== null) {
            $this->writeFile($path, $contents);
        }

        return $contents;
    }

    public function writeFile(string $path, string $contents): int|bool
    {
        if (!\file_exists(\dirname($path))) {
            \mkdir(\dirname($path), 0777, true);
        }

        return \file_put_contents($path, $contents);
    }
}
