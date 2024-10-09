<?php

// Update doi table only adding things that we don't already have

$headings = array();

$row_count = 0;

$filename = "data/doi-2024-10-08.txt";

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
			$headings[0] = preg_replace('/\xEF\xBB\xBF/', '', $headings[0]);	
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
			
						
			$keys = array();
			$values = array();
			
			foreach ($obj as $k => $v)
			{
				$keys[] = '"' . $k . '"'; // must be double quotes
			
				if (is_array($v))
				{
					$values[] = "'" . str_replace("'", "''", json_encode(array_values($v))) . "'";
				}
				elseif(is_object($v))
				{
					$values[] = "'" . str_replace("'", "''", json_encode($v)) . "'";
				}
				elseif (preg_match('/^POINT/', $v))
				{
					$values[] = "ST_GeomFromText('" . $v . "', 4326)";
				}
				else
				{				
					$values[] = "'" . str_replace("'", "''", $v) . "'";
				}					
			}
			
			$sql = 'INSERT OR IGNORE INTO doi (' . join(",", $keys) . ') VALUES (' . join(",", $values) . ');';					
			$sql .= "\n";			
			
			echo $sql;
			
			
		}
	}	
	$row_count++;	
	
	if ($row_count > 5)
	{
		//exit();
	}
	
}	

