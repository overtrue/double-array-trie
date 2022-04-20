<?php

use Overtrue\DoubleArrayTrie\Builder;
use Overtrue\DoubleArrayTrie\DoubleArrayTrie;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    public function test_it_can_build_words_list()
    {
        $builder = new Builder();

        $trie = $builder->build(['foo', 'bar', 'baz']);

        $this->assertInstanceOf(DoubleArrayTrie::class, $trie);
        $this->assertSame([0 => 1, 100 => 3, 101 => 2, 104 => 7, 116 => -1, 117 => 116, 118 => -2, 119 => 8, 120 => 121, 121 => -3, 125 => 118,], $trie->base);
        $this->assertSame([100 => 1, 101 => 3, 104 => 1, 116 => 116, 117 => 2, 118 => 118, 119 => 7, 120 => 8, 121 => 121, 125 => 2,], $trie->check);
    }

    public function test_it_can_build_words_with_values()
    {
        $builder = new Builder();

        $trie = $builder->build(['foo' => 'value1', 'bar' => 'value2', 'baz' => 'value3']);

        $this->assertInstanceOf(DoubleArrayTrie::class, $trie);
        $this->assertSame([0 => 1, 100 => 3, 101 => 2, 104 => 7, 116 => -1, 117 => 116, 118 => -2, 119 => 8, 120 => 121, 121 => -3, 125 => 118,], $trie->base);
        $this->assertSame([100 => 1, 101 => 3, 104 => 1, 116 => 116, 117 => 2, 118 => 118, 119 => 7, 120 => 8, 121 => 121, 125 => 2,], $trie->check);

        $this->assertSame([116 => 'value2', 118 => 'value3', 121 => 'value1'], $trie->values);
    }
}
