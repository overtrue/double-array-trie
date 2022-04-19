<?php

require_once __DIR__ . '/vendor/autoload.php';

use Overtrue\DoubleArrayTrie\DoubleArrayTrie;
use Overtrue\DoubleArrayTrie\Factory;
use Overtrue\DoubleArrayTrie\Matcher;

$trie = new Matcher(Factory::from('trie.json'));

$result = $trie->prefixSearch('一举');

var_dump($result);