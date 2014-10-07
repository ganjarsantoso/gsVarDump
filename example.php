<?php
require_once('gs.vardump.php');

$dump = new gsVarDump();
$dump->useSkin('themes/default.css');

$f = fopen(__FILE__,'r');
fclose($f);

$g = fopen(__FILE__,'r');

$h = new stdClass(); 
$h->H1 = 'h1';  
$h->H2 = 'h2';  


$array = array(
			'boolean' => true, 
			'integer' => 234,
			'float' => 5.89,
			'string' => 'Hello World',
			'array' => array(
							'b1', 'b2', 'b3', array(),'b4', array('b5' => 'b51', 'b6' => 678)
						), 
			'object' => $h, 
			'null' => null,
			'resource' => $g,
			'unknown' => $f
		);


echo $dump->vardump($array,0);

// or you can directly call vardump() function
// vardump($array);


fclose($g); 
