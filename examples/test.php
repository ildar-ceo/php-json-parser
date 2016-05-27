<?

include ('../src/JsonStream.php');
include ('../src/JsonString.php');

use \vistoyn\json_parser\JsonString;


/* --- Initialization --- */
$content = '
{
	"a":[1,2,3,4,5],
	"b":["a1","a2","a3","a4","a5"]
}
';

$json = new JsonString();
$json->setContent($content);



/* --- Example 1 --- */
print ("Example 1:\n");
$json->cleanRules();
$json->reset();

$json->addRule(['a', '*'], function($item, $path){
	print ("---\n");
	print ("Item: ".implode('->',$path)."\n");
	var_dump (json_decode($item));
});
$json->parse();
print ("\n");
print ("\n");
print ("\n");

/*
Types:
---
Item: a->0
int(1)
---
Item: a->1
int(2)
---
Item: a->2
int(3)
---
Item: a->3
int(4)
---
Item: a->4
int(5)
*/




/* --- Example 2 --- */
print ("Example 2:\n");
$json->cleanRules();
$json->reset();

$json->addRule(['b', '*'], function($item, $path){
	print ("---\n");
	print ("Item: ".implode('->',$path)."\n");
	var_dump (json_decode($item));
});
$json->parse();
print ("\n");
print ("\n");
print ("\n");

/*
Types:
---
Item: b->0
string(2) "a1"
---
Item: b->1
string(2) "a2"
---
Item: b->2
string(2) "a3"
---
Item: b->3
string(2) "a4"
---
Item: b->4
string(2) "a5"
*/



/* --- Example 3 --- */
print ("Example 3:\n");
$json->cleanRules();
$json->reset();

$json->addRule(['*'], function($item, $path){
	print ("---\n");
	print ("Item: ".implode('->',$path)."\n");
	var_dump (json_decode($item));
});
$json->parse();

/*
Types:
---
Item: a
array(5) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  int(3)
  [3]=>
  int(4)
  [4]=>
  int(5)
}
---
Item: b
array(5) {
  [0]=>
  string(2) "a1"
  [1]=>
  string(2) "a2"
  [2]=>
  string(2) "a3"
  [3]=>
  string(2) "a4"
  [4]=>
  string(2) "a5"
}
*/