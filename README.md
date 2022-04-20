# DoubleArrayTrie

A PHP implementation of Double Array Trie.

[![Sponsor me](https://github.com/overtrue/overtrue/blob/master/sponsor-me-button-s.svg?raw=true)](https://github.com/sponsors/overtrue)

## Installing

```shell
$ composer require overtrue/double-array-trie -vvv
```

## Usage

### Build a DoubleArrayTrie

#### build with a string array

```php
use Overtrue\DoubleArrayTrie\Builder;

$builder = new Builder();

$trie = $builder->build(['foo', 'bar', 'baz']);

$trie->export()->toFile('trie.json');
$trie->export()->toFile('trie.php');
$trie->export()->toFile('trie.dat');
```

### build with a key-value array

```php
use Overtrue\DoubleArrayTrie\Builder;

$builder = new Builder();

$trie = $builder->build([ 
            '一举' => 'yi ju',
            '一举一动' => 'yi ju yi dong',
        ]);
```

### Load a DoubleArrayTrie

```php
use Overtrue\DoubleArrayTrie\Factory;

$trie = Factory::loadFromFile('trie.json');
$trie = Factory::loadFromFile('trie.php');
$trie = Factory::loadFromFile('trie.dat');
```

### Matching

```php
use Overtrue\DoubleArrayTrie\Matcher;

$trie = Factory::loadFromFile('trie.json');
$matcher = new Matcher($trie);
```

match a string no values:

```php
// ['foo', 'bar', 'baz']
$matcher->match('foo');
// true
$matcher->match('oo');
// false
```

match a string with values:

```php
// ['一举' => 'yi ju', '一举一动' => 'yi ju yi dong']
$matcher->match('一举');
// 'yi ju'
$matcher->match('一举一');
// false
```

## Credits

 - [darts-java: Double-ARray Trie System Java implementation.](https://github.com/komiya-atsushi/darts-java)
 - [DoubleArrayTrie: A PHP implementation of Double Array Trie.](https://linux.thai.net/~thep/datrie/)
 - [双数组Trie树(DoubleArrayTrie)Java实现](https://www.hankcs.com/program/java/%E5%8F%8C%E6%95%B0%E7%BB%84trie%E6%A0%91doublearraytriejava%E5%AE%9E%E7%8E%B0.html)

## :heart: Sponsor me

[![Sponsor me](https://github.com/overtrue/overtrue/blob/master/sponsor-me.svg?raw=true)](https://github.com/sponsors/overtrue)

如果你喜欢我的项目并想支持它，[点击这里 :heart:](https://github.com/sponsors/overtrue)

## Project supported by JetBrains

Many thanks to Jetbrains for kindly providing a license for me to work on this and other open-source projects.

[![](https://resources.jetbrains.com/storage/products/company/brand/logos/jb_beam.svg)](https://www.jetbrains.com/?from=https://github.com/overtrue)

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/vendor/package/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/vendor/package/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and
PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT
