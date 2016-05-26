# PHP json parser for big files

```php
require_once '/vendor/autoload.php';
use \vistoyn\json_parser\JsonString;

$json = new JsonString();
$content = '{
	"a":[1,2,3,4,5],
	"b":["a1","a2","a3","a4","a5"]
}';

$json = new JsonString();
$json->setContent($content);
```

## Example1 

```php
/*
Types:
1
2
3
4
5
*/
$json->addRule(['a', '*'], function($item){
	print($item."\n");
});
$json->parse();
```

## Example2
```php
/*
Types:
"a1"
"a2"
"a3"
"a4"
"a5"
*/
$json->addRule(['b', '*'], function($item){
	print($item."\n");
});
$json->parse();
```

## Example3
```php
/*
Types:
"a":[1,2,3,4,5],
"b":["a1","a2","a3","a4","a5"]
*/
$json->addRule(['*'], function($item){
	print($item."\n");
});
$json->parse();
```