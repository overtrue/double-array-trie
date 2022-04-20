<?php


use Overtrue\DoubleArrayTrie\DoubleArrayTrie;
use Overtrue\DoubleArrayTrie\Factory;
use Overtrue\DoubleArrayTrie\Matcher;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function test_it_can_load_trie_from_path()
    {
        // json
        $trie = Factory::loadFromFile(__DIR__.'/fixtures/trie.json');

        $this->assertInstanceOf(DoubleArrayTrie::class, $trie);
        $this->assertSame('yi ju cheng ming', (new Matcher($trie))->match('一举成名'));

        // PHP
        $trie = Factory::loadFromFile(__DIR__.'/fixtures/trie.php');

        $this->assertInstanceOf(DoubleArrayTrie::class, $trie);
        $this->assertSame('yi ju cheng ming', (new Matcher($trie))->match('一举成名'));

        $trie = Factory::loadFromFile(__DIR__.'/fixtures/trie.dat');

        $this->assertInstanceOf(DoubleArrayTrie::class, $trie);
        $this->assertSame('yi ju cheng ming', (new Matcher($trie))->match('一举成名'));
    }

    public function test_it_will_throws_exception_when_file_not_found()
    {
        $path = __DIR__.'/fixtures/trie-not-found.dat';
        $this->expectExceptionMessage('File not found: '.$path);

        Factory::loadFromFile($path);
    }
}
