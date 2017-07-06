## NUMBER 2 TEXT CONVERTER (Russian only)
[![Latest Stable Version](https://poser.pugx.org/illusive-man/converter/v/stable)](https://packagist.org/packages/illusive-man/converter) 
[![Build Status](https://scrutinizer-ci.com/g/illusive-man/converter/badges/build.png?b=master)](https://scrutinizer-ci.com/g/illusive-man/converter/build-status/master) 
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/illusive-man/converter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/illusive-man/converter/?branch=master) 
[![Code Climate](https://codeclimate.com/github/illusive-man/converter/badges/gpa.svg)](https://codeclimate.com/github/illusive-man/converter) 
[![Code Coverage](https://scrutinizer-ci.com/g/illusive-man/converter/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/illusive-man/converter/?branch=master) 

Compact PHP Library that converts given numeric value to its written text representation (in Russian).
At first I wanted Number2Text to be Universal library, but considering amount of similar classes for other 
languages I stopped at using only Russian wording. Maybe I will extend it for English just for varity sake ;)

INFO

Number2Text is capable of lightning fast conversion for numbers from negative 1e+510 to positive 1e+510. Sure 
it has no practical application for numberes over quadrillion I think, but isn't it cool, is it?!

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
Instantiate the class. If you need currency shown, uncomment second line:
```php
$number = new Number2Text();
//$number->currency();
```

Well, the only thing left to do is a number we want to convert and method,
thal will do all the magic.
```php
$number = '1051650555165450046516654000654640690000959555987960054106514';
echo $test->convert($number);
```
NOTE: Since we're working with numbers way beyound bigger than even 64-bit 
php_max_int() ones, always pass a number as string!

AFTERWORDS

The secondary reason behind this converter was the fact that most of the previously created apps and tools 
(e.g. Number Words and some based on it) of the same kind are unnecessarily overcomplicated  and 
sometimes not working properly (Online converter tools like easycalculation.com or tools4noobs.com - both
 are working wrong for Russian). 
 
 Actually, most of "universal converters" are working wrong for
 complex languages like Russian. Also, big chunk of those translators doesn't support PHP 7.0+. This one does.
 Plus the Library is very very fast (0.0001s for big numbers like 1e+500 with random digits) even with enormous
 numbers and consists only of two files.
 
But the main reason for creating this Library, maintaining it with Travis CI and Scrutinizer, using VCS like GitHub, 
is pure greed for knowledge and learning such a beautiful development language - PHP. I deliberately made it of one 
file Class (actually two, but second is just the Data array) so feel free to modify it or maybe make a pull request
 if you can refactor it to become even more compat and/or fast. 
 
 Enjoy!