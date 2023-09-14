<?php

require_once(dirname(__FILE__) . '/utils.php');

function data_to_table($data)
{
	$header = array();
	foreach ($data[0] as $k => $v)
	{
		$header[] = $k;
	}
	echo "|" . join(" | ", $header) . "|\n";
	
	for ($i = 0; $i < count($header) - 1; $i++)
	{
		echo "|--|";
	}
	echo "--|\n";
	
	
	foreach ($data as $item)
	{
		$row = array();
		foreach ($item as $k => $v)
		{
			if ($k == "doi")
			{
				$v = "[" . $v . "](https://doi.org/" . $v . ")";
			}
			$row[] = $v;
		}
	
		echo "|" . join(" | ", $row) . "|\n";

	}
	
	echo "\n";
}


echo "# Impact of new-style BHL DOIs \"10.5962/p.\"";

{
	echo "\n## DOI prefixes in BHL\n";
	echo "Typically different publishers have their own unique DOI prefix, but this can change as publishers merge, or acquire new content from other publishers. These are the prefixes for content hosted by BHL. Note that **10.5962** is the BHL prefix.\n";

	$sql = "SELECT SUBSTR(doi,1,INSTR(doi,'/') -1) AS prefix, COUNT(doi) as count FROM doi GROUP BY prefix ORDER BY count DESC;";

	$data = do_query($sql);

	// print_r($data);

	data_to_table($data);
}

{
	echo "\n## Non BHL DOIs in BHL\n";
	echo "DOIs minted by other organisations, may be commercial publishers, repositories, or BHL members.\n";

	$sql = "SELECT COUNT(doi) as count FROM doi WHERE doi NOT LIKE '10.5962/%'";

	$data = do_query($sql);

	// print_r($data);

	data_to_table($data);
}

{
	echo "\n## New style BHL DOIs\n";
	echo "Total number of DOIs minted as part of PIWG activities.\n";
	
	$sql = "SELECT SUBSTR(doi,1,10) AS prefix, COUNT(doi) as count FROM doi WHERE doi LIKE '10.5962/p.%'";

	$data = do_query($sql);

	// print_r($data);

	data_to_table($data);
}

{
	echo "\n## New style BHL DOIs minted each year\n";
	echo "Activity for each year.\n";
	

	$sql = "SELECT SUBSTR(CreationDate,1,4) AS year , COUNT(doi) as count FROM doi WHERE doi LIKE '10.5962/p.%' GROUP BY year";

	$data = do_query($sql);

	// print_r($data);

	data_to_table($data);
}


{
	echo "\n## When does Unpaywall say BHL is best?\n";
	echo "[Unpaywall](https://unpaywall.org/) has a database of open access versions of articles, which includes content in BHL. Here we count the number of non-BHL DOIs where BHL is the \"best\" source. This is a measure of how much BHL is enabling access to paywalled articles, and depends on BHL adding external DOIs to its content.\n";

	$sql = "SELECT COUNT(doi) as count FROM doi WHERE doi NOT LIKE '10.5962%' AND unpaywall=1";

	$data = do_query($sql);

	// print_r($data);

	data_to_table($data);
}

{
	echo "\n## Are people citing these new DOIs?\n";
	echo "[OpenCitations](http://opencitations.net) is building a database of citations sourced from CrossRef and elsewhere. Citations are pairs of DOIs. We can count the number of citations for works in BHL with new style DOIs\n";

	$sql = "SELECT COUNT(cited) as count FROM citation";

	$data = do_query($sql);

	// print_r($data);

	data_to_table($data);
}

{
	echo "\n## Top ten cited DOIs in OpenCitations\n";
	echo "These are the most cited articles with new BHL DOIs.\n";

	$sql = "SELECT cited AS doi, COUNT(cited) as count FROM citation GROUP BY doi ORDER BY count DESC LIMIT 10";

	$data = do_query($sql);

	// print_r($data);

	data_to_table($data);
}

{
	echo "\n## If the new DOis were a researcher hat would be their _h_-index?\n";
	echo "> The _h_-index is defined as the maximum value of _h_ such that the given author/journal has published at least _h_ papers that have each been cited at least _h_ times. [Wikipedia](https://en.wikipedia.org/wiki/H-index)\n";

	$sql = "SELECT cited AS doi, COUNT(cited) as count FROM citation GROUP BY doi ORDER BY count DESC LIMIT 100";

	$data = do_query($sql);
	
	$h = 0;
	
	$row_number = 1;
	
	foreach ($data as $item)
	{
		if ($item->count == $row_number)	
		{
			$h = $row_number;
		}
		$row_number++;
	}
	
	echo "\n_h_-index: **" . $h . "**\n";
}

{
	echo "\n## How relevant are articles with new-style BHL DOIs?\n";
	echo "If articles with new style DOis are relevant to current researchers then we would expect to see them cited in recently published papers. This table lists the number of citations of new DOI in each decade, showing that recently papers do indeed cite BHL content. Note that citation links are continually updated, so that these newly minted BHL DOIs are enabling citation links to be created between works have been published long before BHL began.\n";

	$sql = "SELECT SUBSTR(creation,1,4)/10 * 10 AS decade, COUNT(cited) as count FROM citation GROUP BY decade ORDER BY decade DESC";

	$data = do_query($sql);

	// print_r($data);

	data_to_table($data);
	
	
}




?>

