<?php

namespace Overtrue\DoubleArrayTrie;

class Factory
{
    public static function from(string $path, string $type = 'json'): DoubleArrayTrie
    {
        if (!file_exists($path)) {
            throw new \InvalidArgumentException("File not found: $path");
        }

        if ($type === 'json' || \str_ends_with($path, '.json')) {
            return self::fromJson($path);
        }

        if ($type === 'php' || \str_ends_with($path, '.php')) {
            return self::fromPHP($path);
        }

        throw new \InvalidArgumentException("Unsupported file type: $type");
    }

    public static function fromJson(string $path): DoubleArrayTrie
    {
        $data = \json_decode(\file_get_contents($path) ?: '', true) ?: [];

        if (!\is_array($data)) {
            throw new \InvalidArgumentException("Invalid json file: $path");
        }

        return new DoubleArrayTrie($data['base'], $data['check'], $data['values']);
    }

    public static function fromPHP(string $path): DoubleArrayTrie
    {
        $data = require $path;

        if (!\is_array($data)) {
            throw new \InvalidArgumentException("Invalid php file: $path");
        }

        return new DoubleArrayTrie($data['base'], $data['check'], $data['values']);
    }
}
