<?php

require_once(dirname(__FILE__) . '/jats_mixed_to_csl.php');
require_once(dirname(__FILE__) . '/jats_to_csl.php');


$basedir = dirname(__FILE__) . '/xml';

$files = scandir($basedir);

foreach ($files as $filename)
{
	if (preg_match('/xml$/', $filename))
	{	
		//echo $filename . "\n";
	
		$xml = file_get_contents($basedir . '/' . $filename);
		
		if (0)
		{
			$bibliography = jats_mixed_to_csl($xml);
		}
		else
		{
			$bibliography = jats_to_csl($xml);
		}

		//print_r($bibliography);
		
		foreach ($bibliography as $item)
		{
			echo  json_encode($item, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
			echo "\n";

		}		
		
	}
}



?>
