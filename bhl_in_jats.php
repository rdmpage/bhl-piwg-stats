<?php

require_once(dirname(__FILE__) . '/jats_mixed_to_csl.php');
require_once(dirname(__FILE__) . '/jats_to_csl.php');


$basedir = dirname(__FILE__) . '/xml';

$files = scandir($basedir);

// debugging
//$files=array('106733.xml');

$stats = array();

$stats['total'] = 0;

$stats['DOI'] = 0;
$stats['BHL'] = 0;
$stats['BHL_NO_DOI'] = 0;
$stats['pre1923'] = 0;

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
		
		foreach ($bibliography as $bib)
		{
			$stats['total']++;
		
			if (isset($bib->DOI))
			{
				$stats['DOI']++;
			}
		
			if (isset($bib->URL))
			{
				if (preg_match('/biodiversitylibrary/', $bib->URL))
				{
					$stats['BHL']++;
					
					if (!isset($bib->DOI))
					{
						$stats['BHL_NO_DOI']++;
					}
				}
			}
			
			if (isset($bib->issued))
			{
				if ($bib->issued->{'date-parts'}[0][0] < 1923)
				{
					$stats['pre1923']++;
				}
			}
			
		
		}
		
		
	}
}

print_r($stats);

?>
