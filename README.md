Converter for numeric values to their written text representation.

The reason behind this converter was the fact that most of previously created apps and tools (e.g. Number Words) of the same kind 
contain pretty big amounts of code (unnecessarily overcomplicated) and sometimes not working properly (Online converter tools like 

https://www.easycalculation.com/convert-number-to-text.php
or 
https://www.tools4noobs.com/online_tools/number_spell_words/ 

-- both are working wrong for Russian. Actually almost all so called "universal converters" are working wrong for langages like Russian.

This converter requires PHP 7.0+ to work properly, but I think that someday I will remove type hinting (introduced in PHP7) I used for purely for development purposes, so the converter could work with earlier PHP versions. If someone would ask for it of course.

Currently only Russian language is supported since this converter was initially created in VBA for accounting software in Russia.

`TODO: Implement this converter as a Class. (Any help would be appreciated!)`
