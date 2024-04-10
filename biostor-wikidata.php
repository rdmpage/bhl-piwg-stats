<?php

// Compare counts from BioStor with Wikidata

/*

Wikidata query uses https://qlever.cs.uni-freiburg.de/wikidata/OOHaFq


Note that a number of journals in Wikidata lack links to BHL, even though journals are in
BHL, hence some absences from Wikidata are spurious.

*/

$biostor_data = array();
$wikidata_data = array();


$headings = array();
$row_count = 0;
$filename = 'biostor-issn.tsv';

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$line = trim(fgets($file_handle));
		
	$row = explode("\t",$line);
	
	$go = is_array($row) && count($row) > 1;
	
	if ($go)
	{
		if ($row_count == 0)
		{
			$headings = $row;		
		}
		else
		{
			$obj = new stdclass;
		
			foreach ($row as $k => $v)
			{
				if ($v != '')
				{
					$obj->{$headings[$k]} = $v;
				}
			}
		
			//print_r($obj);	
			
			$biostor_data[$obj->issn] = $obj->works;
		}
	}	
	$row_count++;	
	
}	

$headings = array();
$row_count = 0;
$filename = 'wikidata.tsv';

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$line = trim(fgets($file_handle));
		
	$row = explode("\t",$line);
	
	$go = is_array($row) && count($row) > 1;
	
	if ($go)
	{
		if ($row_count == 0)
		{
			$headings = $row;		
		}
		else
		{
			$obj = new stdclass;
		
			foreach ($row as $k => $v)
			{
				if ($v != '')
				{
					$obj->{$headings[$k]} = $v;
				}
			}
		
			//print_r($obj);	
			
			$wikidata_data[$obj->issn] = $obj->works;
		}
	}	
	$row_count++;	
	
}	

//print_r($biostor_data);
//print_r($wikidata_data);

$xy = array();

foreach ($biostor_data as $issn => $count)
{
	$xy[$issn] = array($count, 0);
}
foreach ($wikidata_data as $issn => $count)
{
	if (isset($xy[$issn]))
	{
		$xy[$issn][1] = $count;
	}
	else
	{
		//$xy[$issn] = array($count, 0);
	}
}

//print_r($xy);

foreach ($xy as $issn => $values)
{
	echo $issn . "\t" . join("\t", $values) . "\n";
}





?>
