<?php

use Overtrue\DoubleArrayTrie\Builder;
use Overtrue\DoubleArrayTrie\Matcher;
use PHPUnit\Framework\TestCase;

class MatcherTest extends TestCase
{
    public function test_it_can_match_a_word()
    {
        $builder = new Builder();

        $trie = $builder->build(['foo', 'bar', 'baz', '一举成名']);

        $matcher = new Matcher($trie);
        $this->assertTrue($matcher->match('foo'));
        $this->assertTrue($matcher->match('bar'));
        $this->assertTrue($matcher->match('baz'));
        $this->assertTrue($matcher->match('一举成名'));
        $this->assertFalse($matcher->match('fo'));
        $this->assertFalse($matcher->match('az'));
        $this->assertFalse($matcher->match('ba'));
        $this->assertFalse($matcher->match('举成'));
        $this->assertFalse($matcher->match('举成名'));
    }

    public function test_it_can_match_with_values()
    {
        $words = [
            '一举' => 'yi ju',
            '一举一动' => 'yi ju yi dong',
            '一举成名' => 'yi ju cheng ming',
            '一举成名天下知' => 'yi ju cheng ming tian xia zhi',
            '万能' => 'wan neng',
            '万能胶' => 'wan neng jiao'
        ];

        $builder = new Builder();
        $trie = $builder->build($words);
        $matcher = new Matcher($trie);

        $this->assertSame('yi ju', $matcher->match('一举'));
        $this->assertSame('yi ju yi dong', $matcher->match('一举一动'));
        $this->assertSame('yi ju cheng ming', $matcher->match('一举成名'));
        $this->assertSame('yi ju cheng ming tian xia zhi', $matcher->match('一举成名天下知'));
        $this->assertSame('wan neng', $matcher->match('万能'));
        $this->assertSame('wan neng jiao', $matcher->match('万能胶'));
        $this->assertFalse($matcher->match('万能胶布'));
    }

    public function test_it_can_prefix_match()
    {
        $words = [
            '一举' => 'yi ju',
            '一举一动' => 'yi ju yi dong',
            '一举成名' => 'yi ju cheng ming',
            '一举成名天下知' => 'yi ju cheng ming tian xia zhi',
            '万能' => 'wan neng',
            '万能胶' => 'wan neng jiao'
        ];

        $builder = new Builder();
        $trie = $builder->build($words);
        $matcher = new Matcher($trie);

        $this->assertSame([
            '一举' => 'yi ju',
            '一举一动' => 'yi ju yi dong',
        ], $matcher->prefixMatch('一举一动'));

        $this->assertSame([
            '一举' => 'yi ju',
            '一举一动' => 'yi ju yi dong',
        ], $matcher->prefixMatch('一举一动都很奇怪'));
    }
}
