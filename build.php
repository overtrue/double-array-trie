<?php
require_once __DIR__ . '/vendor/autoload.php';

use Overtrue\DoubleArrayTrie\Builder;

$builder = new Builder();
try {
    $trie = $builder->build(['一举', '一举一动', '一举成名', '一举成名天下知', '万能', '万能胶']);
    $trie->export()->toFile('trie.json');
} catch (\Overtrue\DoubleArrayTrie\Exception $e) {
}
