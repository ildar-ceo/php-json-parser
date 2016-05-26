# Json stream parser for big files

*Example:*

```php
$content = file_get_contents($file);

$json = new JsonStreamParserString();
$json->setContent($content);

$json->addRule(['*'], function($item){
	var_dump($item);
});
```
