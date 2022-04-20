<?php


use Overtrue\DoubleArrayTrie\Builder;
use Overtrue\DoubleArrayTrie\Exporter;
use PHPUnit\Framework\TestCase;

class ExporterTest extends TestCase
{
    public function test_it_can_export_to_json_file()
    {
        $builder = new Builder();

        $trie = $builder->build(['foo', 'bar', 'baz']);

        $exporter = new Exporter($trie);
        $path = $this->tmpFile('/tmp/trie.json');
        $exporter->toFile($path);

        $this->assertFileExists($path);
        $this->assertJsonStringEqualsJsonFile($path, json_encode($trie->toArray()));
    }

    public function test_it_can_export_to_php_file()
    {
        $builder = new Builder();

        $trie = $builder->build(['foo', 'bar', 'baz']);

        $exporter = new Exporter($trie);
        $path = $this->tmpFile('/tmp/trie.php');
        $exporter->toPHP($path);

        $this->assertFileExists($path);

        $data = require $path;
        $this->assertSame($trie->toArray(), $data);
    }

    public function tmpFile(string $name): string
    {
        return sys_get_temp_dir() . '/' . $name;
    }
}
