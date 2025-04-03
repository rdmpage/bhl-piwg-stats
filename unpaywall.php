<?php

error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/utils.php');

//----------------------------------------------------------------------------------------
function get($url)
{
	$opts = array(
	  CURLOPT_URL =>$url,
	  CURLOPT_FOLLOWLOCATION => TRUE,
	  CURLOPT_RETURNTRANSFER => TRUE
	);
	
	$ch = curl_init();
	curl_setopt_array($ch, $opts);
	$data = curl_exec($ch);
	$info = curl_getinfo($ch); 
	curl_close($ch);

	return $data;
}

//----------------------------------------------------------------------------------------
function doi_to_pdf($doi)
{
	$pdfs = array();

	$url = 'https://api.oadoi.org/v2/' . urlencode('"' . strtolower($doi) . '"') . '?email=unpaywall@impactstory.org';
	
	$json = get($url);
	
	$obj = json_decode($json);
	
	print_r($obj);
	
	if ($obj)
	{
		if (isset($obj->is_oa))
		{
			if ($obj->is_oa)
			{
				foreach ($obj->oa_locations as $location)
				{
					if (isset($location->url_for_pdf) && $location->url_for_pdf != "")
					{
						$pdfs[] = $location->url_for_pdf;
					}
				}
		
			}		
		}
	}
	
	return $pdfs;			
}

//----------------------------------------------------------------------------------------
function doi_to_oa($doi)
{
	$is_oa = false;

	$url = 'https://api.oadoi.org/v2/' . urlencode('"' . strtolower($doi) . '"') . '?email=unpaywall@impactstory.org';
	
	$json = get($url);
	
	$obj = json_decode($json);
	
	//print_r($obj);
	
	if ($obj)
	{
		if (isset($obj->is_oa))
		{
			$is_oa = $obj->is_oa;
		}
		
		if (isset($obj->best_oa_location))
		{
			if (is_array($obj->best_oa_location))
			{
				if (preg_match('/biodiversitylibrary.org/', $obj->best_oa_location[0]->url))
				{
					//echo 'UPDATE doi SET unpaywall=1 WHERE doi="' . $doi . '";' . "\n";			
					echo 'REPLACE INTO unpaywall(doi,unpaywall) VALUES("' . $doi . '",1);' . "\n";		
				}
			}
			else
			{
				if (preg_match('/biodiversitylibrary.org/', $obj->best_oa_location->url))
				{
					echo 'REPLACE INTO unpaywall(doi,unpaywall) VALUES("' . $doi . '",1);' . "\n";		
				}
			}			
		}
	}
	
	return $is_oa;			
}

//----------------------------------------------------------------------------------------

$filename = 'nonbhldoi.txt';

$file = @fopen($filename, "r") or die("couldn't open $filename");

$row_count = 1;

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$doi = trim(fgets($file_handle));
	
	echo "-- $doi\n";
	
	$have_doi = false;
	$go = true;
	
	// 1. Do we have this already?
	
	$sql = 'SELECT * from unpaywall WHERE doi="' . $doi . '" LIMIT 1';
	
	$data = do_query($sql);
	
	if (count($data) == 1)
	{
		// yes...
		$have_doi = true;
		
		echo "-- have $doi\n";
		
		if ($data[0]->unpaywall == 1)
		{
			// ... and BHL is best hit, so we don't need to do anything
			$go = false; // we already have this and BHL is the best destination
			
			echo "-- BHL is best\n";
		}
		else
		{
			echo "-- BHL not best\n";
		}
	}	
	
	if ($go)
	{
		if (!$have_doi)
		{
			// update database with DOI and set to 0 by default
			echo "-- don't have $doi\n";
			echo 'REPLACE INTO unpaywall(doi,unpaywall) VALUES("' . $doi . '",0);' . "\n";
		}	

		echo "-- query Unpaywall\n";
		doi_to_oa($doi);
		
		// Give server a break every 10 items
		if (($row_count++ % 5) == 0)
		{
			$rand = rand(1000000, 3000000);
			echo "\n-- ...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
			usleep($rand);
		}		
	}
	

	

}


?>
