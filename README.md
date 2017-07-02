## NUMBER 2 TEXT CONVERTER
[![Build Status](https://scrutinizer-ci.com/g/illusive-man/converter/badges/build.png?b=master)](https://scrutinizer-ci.com/g/illusive-man/converter/build-status/master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/illusive-man/converter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/illusive-man/converter/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/illusive-man/converter/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/illusive-man/converter/?branch=master) [![Latest Stable Version](https://poser.pugx.org/illusive-man/converter/v/stable)](https://packagist.org/packages/illusive-man/converter) [![License](https://poser.pugx.org/illusive-man/converter/license)](https://packagist.org/packages/illusive-man/converter)

Compact PHP Library that converts given numeric value to its written text representation (in Russian).
The secondary reason behind this converter was the fact that most of the previously created apps and tools (e.g. Number Words) of the same kind contain pretty big amounts of code (unnecessarily overcomplicated) and sometimes not working properly (Online converter tools like easycalculation.com or tools4noobs.com - both are working wrong for Russian). Actually, almost all so called "universal converters" are working wrong for complex languages like Russian. Also, well known "Number Words" script doesn't support PHP 7.0+. This one

But the main reason for creating this Library, maintaining it with Travis CI and Scrutinizer, using VCS like GitHub, is pure greed for knowledge and learning such a beautiful development language as PHP itself.

Currently, uses only Russian wording.

INSTALLATION

It's very easy. Go to your project root directory and run this command:

```
composer require illusive-man/converter
```

USAGE

Include your autoload.php file and type use statement for the class like this:
```php
require_once 'vendor/autoload.php';
use Converter\Core\Number2Text;
```
Instantiate the class. If you need currency shown uncomment second line:
```$php
$number = new Number2Text();
//$number->currency(true);
```


Guide -> work in progress. Will be completed very soon!