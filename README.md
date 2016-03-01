# php-stemmer

PHP5 native implementation of Snowball stemmer
http://snowball.tartarus.org/

Accept only UTF-8

* [Languages](#languages)
* [Installation](#installation)
* [Usage](#usage)

Languages
------------
Available : 
- English
- French
- German
- Italian
- Spanish
- Portuguese
- Romanian

Next :
- Dutch

Soon :
 - Swedish 
 - Norwegian
 - Danish 
 - Russian 
 - Finnish 

Installation
------------

Require [`wamania/php-stemmer`](https://packagist.org/packages/wamania/php-stemmer)
into your `composer.json` file:


``` json
{
    "require": {
        "wamania/php-stemmer": "dev-master"
    }
}
```

Usage
-----

In your controller :

``` php
use Wamania\Snowball\French;

$stemmer = new French();
$stem = $stemmer->stem('anticonstitutionnellement');
```