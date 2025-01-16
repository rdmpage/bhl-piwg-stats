<?php

error_reporting(E_ALL);

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

$filename = 'pdoi.txt';
//$filename = 'muelleria_doi.txt';
//$filename = 'title.txt';
//$filename = 'part.txt';


$file = @fopen($filename, "r") or die("couldn't open $filename");

$row_count = 1;

$file_handle = fopen($filename, "r");
while (!feof($file_handle)) 
{
	$doi = trim(fgets($file_handle));
	echo "-- $doi\n";
	
	$url = 'https://opencitations.net/index/coci/api/v1/citations/' . $doi;
	
	$json = get($url);
	$obj = json_decode($json);
	if ($obj)
	{
		//print_r($obj);
	
		foreach ($obj as $item)
		{
			$keys = array();
			$values = array();
			
			$keys[] = 'oci';
			$values[] = "'" . $item->oci . "'";
						
			$keys[] = 'cited';
			$values[] = "'" . $item->cited . "'";
		
			$keys[] = 'citing';
			$values[] = "'" . $item->citing . "'";

			$keys[] = 'journal_sc';
			$values[] = "'" . $item->journal_sc . "'";
		
			$keys[] = 'creation';
			$values[] = "'" . $item->creation . "'";
			
			echo 'REPLACE INTO citation(' . join(",", $keys) . ') VALUES (' . join(',', $values) . ');' . "\n";

		
		}
	
	}
	
	// Give server a break every 10 items
	if (($row_count++ % 5) == 0)
	{
		$rand = rand(1000000, 3000000);
		echo "\n-- ...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
		usleep($rand);
	}
	

}


?>
