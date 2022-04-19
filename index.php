<?php

require_once __DIR__ . '/vendor/autoload.php';

use Overtrue\DoubleArrayTrie\DoubleArrayTrie;

$trie = new DoubleArrayTrie();
$trie->build(['一举', '一举一动', '一举成名', '一举成名天下知', '万能', '万能胶']);


$result = $trie->prefixSearch('万能', function ($key, $value) {
    echo $key . ':' . $value . PHP_EOL;
});

var_dump($result);