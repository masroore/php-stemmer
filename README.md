# php-stemmer

PHP native implementation of Snowball stemmer
https://snowballstem.org/

Accept only UTF-8

* [Languages](#languages)
* [Installation](#installation)
* [Usage](#usage)

Languages
------------
Available : 
- Catalan (by Orestes Sanchez Benavente orestes@estotienearreglo.es)
- Danish
- Dutch
- English
- Finnish (by [Mikko Saari](https://github.com/msaari/))
- French
- German
- Italian
- Norwegian
- Portuguese
- Romanian
- Russian
- Spanish
- Swedish

Installation
------------

> **Requires [PHP 8.0+](https://php.net/releases/)**

You can install the package via composer:

```bash
composer require wamania/php-stemmer
```

Usage
-----

```php
use Wamania\Snowball\StemmerFactory;

// use ISO_639 (2 or 3 letters) or language name in english
$stemmer = StemmerFactory::create('fr');
$stemmer = StemmerFactory::create ('spanish');

// then 
$stem = $stemmer->stem('automóvil');
```

Or the manager
```php
use Wamania\Snowball\StemmerManager;

$manager = new StemmerManager();
$stem = $manager->stem('automóvil', 'es');
```