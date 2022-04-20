<?php


use Overtrue\DoubleArrayTrie\DoubleArrayTrie;
use Overtrue\DoubleArrayTrie\Exporter;
use PHPUnit\Framework\TestCase;

class DoubleArrayTrieTest extends TestCase
{
    public function test_it_can_get_base_value()
    {
        $trie = new DoubleArrayTrie(base: [134 => 1134, 135 => 1135], check: [134 => 2134, 135 => 2135]);

        $this->assertSame(1, $trie->getBaseValue(0));
        $this->assertSame(1134, $trie->getBaseValue(134));
        $this->assertSame(1135, $trie->getBaseValue(135));
    }

    public function test_it_can_get_check_value()
    {
        $trie = new DoubleArrayTrie(base: [134 => 1134, 135 => 1135], check: [134 => 2134, 135 => 2135]);

        $this->assertSame(0, $trie->getCheckValue(0));
        $this->assertSame(2134, $trie->getCheckValue(134));
        $this->assertSame(2135, $trie->getCheckValue(135));
    }

    public function test_it_can_check_position()
    {
        $trie = new DoubleArrayTrie(base: [134 => 1134, 135 => 1135], check: [134 => 2134, 135 => 2135]);

        $this->assertTrue($trie->hasPosition(0));
        $this->assertFalse($trie->hasPosition(1));
        $this->assertFalse($trie->hasPosition(2));
        $this->assertTrue($trie->hasPosition(134));
        $this->assertTrue($trie->hasPosition(135));
    }

    public function test_it_can_store_values()
    {
        $trie = new DoubleArrayTrie();
        $this->assertFalse($trie->hasValues());

        $trie = new DoubleArrayTrie(base: [134 => 1134, 135 => 1135], check: [134 => 2134, 135 => 2135], values: [134 => 'v134', 135 => 'v135']);

        $this->assertTrue($trie->hasValues());
        $this->assertNull($trie->getValue(1));
        $this->assertNull($trie->getValue(2));
        $this->assertSame('v134', $trie->getValue(134));
        $this->assertSame('v135', $trie->getValue(135));
    }

    public function test_it_can_be_export()
    {
        $trie = new DoubleArrayTrie();
        $this->assertInstanceOf(Exporter::class, $trie->export());
    }
}
