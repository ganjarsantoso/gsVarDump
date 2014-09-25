<?php
require_once('gs.vardump.php');

$dump = new gsVarDump();

$dump->useSkin('themes/default.css');

$array = array(
			'A' => 'a', 
			'B' => 
				array(
					'b1', 'b2', 'b3', array(
										'b4_1', 
										'b4_2'
										), 
					'b5', 'b6'
				), 
			'C' => 'c', 
			'D' => array(
						'D1' => 'd1', 
						'D2' => 'd2'
					)
		);
		
echo $dump->vardump($array);
gs_vardump($array, 1);