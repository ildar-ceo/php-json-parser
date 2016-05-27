<?

include ('../src/JsonStream.php');
include ('../src/JsonString.php');

use \vistoyn\json_parser\JsonString;


/* --- Initialization --- */
$content = '
{
	"a":[1,2,3,4,5],
	"b":["a1","a2","a3","a4","a5"],
	"c":[
		{
			"key1":"val1",
			"key2":"val2",
			"key3":"val3"
		},
		{
			"key4":"val4",
			"key5":"val5",
			"key6":"val6"
		}
	],
	"d":{
		"key1":"val1",
		"key2":"val2",
		"key3":"val3"
	},
	"e":{
		"a1": [1,2,3,4,5],
		"a2": [6,7,8],
		"a3": [9,10,11,{"zz":10}],
		"a4": "str",
		"a5": null
	},
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
	var_dump (json_decode($item, true));
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
	var_dump (json_decode($item, true));
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

$json->addRule(['c', '*'], function($item, $path){
	print ("---\n");
	print ("Item: ".implode('->',$path)."\n");
	var_dump (json_decode($item, true));
});
$json->parse();
print ("\n");
print ("\n");
print ("\n");
/*
Types:
---
Item: c->0
array(3) {
  ["key1"]=>
  string(4) "val1"
  ["key2"]=>
  string(4) "val2"
  ["key3"]=>
  string(4) "val3"
}
---
Item: c->1
array(3) {
  ["key4"]=>
  string(4) "val4"
  ["key5"]=>
  string(4) "val5"
  ["key6"]=>
  string(4) "val6"
}
*/



/* --- Example 4 --- */
print ("Example 4:\n");
$json->cleanRules();
$json->reset();

$json->addRule(['d', '*'], function($item, $path){
	print ("---\n");
	print ("Item: ".implode('->',$path)."\n");
	var_dump (json_decode($item, true));
});
$json->parse();
print ("\n");
print ("\n");
print ("\n");
/*
Types:
---
Item: d->key1
string(4) "val1"
---
Item: d->key2
string(4) "val2"
---
Item: d->key3
string(4) "val3"
*/



/* --- Example 5 --- */
print ("Example 5:\n");
$json->cleanRules();
$json->reset();

$json->addRule(['e', '*', '*'], function($item, $path){
	print ("---\n");
	print ("Item: ".implode('->',$path)."\n");
	var_dump (json_decode($item, true));
});
$json->parse();
print ("\n");
print ("\n");
print ("\n");
/*
Types:
---
Item: e->a1->0
int(1)
---
Item: e->a1->1
int(2)
---
Item: e->a1->2
int(3)
---
Item: e->a1->3
int(4)
---
Item: e->a1->4
int(5)
---
Item: e->a2->0
int(6)
---
Item: e->a2->1
int(7)
---
Item: e->a2->2
int(8)
---
Item: e->a3->0
int(9)
---
Item: e->a3->1
int(10)
---
Item: e->a3->2
int(11)
---
Item: e->a3->3
array(1) {
  ["zz"]=>
  int(10)
}
*/



/* --- Example 6 --- */
print ("Example 6:\n");
$json->cleanRules();
$json->reset();

$json->addRule(['e', '*'], function($item, $path){
	print ("---\n");
	print ("Item: ".implode('->',$path)."\n");
	var_dump (json_decode($item, true));
});
$json->parse();
print ("\n");
print ("\n");
print ("\n");
/*
Types:
---
Item: e->a1
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
Item: e->a2
array(3) {
  [0]=>
  int(6)
  [1]=>
  int(7)
  [2]=>
  int(8)
}
---
Item: e->a3
array(4) {
  [0]=>
  int(9)
  [1]=>
  int(10)
  [2]=>
  int(11)
  [3]=>
  array(1) {
    ["zz"]=>
    int(10)
  }
}
---
Item: e->a4
string(3) "str"
---
Item: e->a5
NULL
*/



/* --- Example 7 --- */
print ("Example 7:\n");
$json->cleanRules();
$json->reset();

$json->addRule(['*'], function($item, $path){
	print ("---\n");
	print ("Item: ".implode('->',$path)."\n");
	var_dump (json_decode($item, true));
});
$json->parse();
print ("\n");
print ("\n");
print ("\n");

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
---
Item: c
array(2) {
  [0]=>
  array(3) {
    ["key1"]=>
    string(4) "val1"
    ["key2"]=>
    string(4) "val2"
    ["key3"]=>
    string(4) "val3"
  }
  [1]=>
  array(3) {
    ["key4"]=>
    string(4) "val4"
    ["key5"]=>
    string(4) "val5"
    ["key6"]=>
    string(4) "val6"
  }
}
---
Item: d
array(3) {
  ["key1"]=>
  string(4) "val1"
  ["key2"]=>
  string(4) "val2"
  ["key3"]=>
  string(4) "val3"
}
---
Item: e
array(5) {
  ["a1"]=>
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
  ["a2"]=>
  array(3) {
    [0]=>
    int(6)
    [1]=>
    int(7)
    [2]=>
    int(8)
  }
  ["a3"]=>
  array(4) {
    [0]=>
    int(9)
    [1]=>
    int(10)
    [2]=>
    int(11)
    [3]=>
    array(1) {
      ["zz"]=>
      int(10)
    }
  }
  ["a4"]=>
  string(3) "str"
  ["a5"]=>
  NULL
}
*/