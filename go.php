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
	echo "[Unpaywall](https://unpaywall.org/) has a database of open access versions of articles, which includes content in BHL. Here we count the number of non-BHL DOIs where BHL is the \"best\" source (an example is [10.1002/fedr.19110090704](http://doi.org/10.1002/fedr.19110090704), best viewed in Chrome or Firefox with the Unpaywall extension). This is a measure of how much BHL is enabling access to paywalled articles, and depends on BHL adding external DOIs to its content.\n";

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
	echo "\n## If the new DOIs were a researcher what would be their _h_-index?\n";
	echo "> The _h_-index is defined as the maximum value of _h_ such that the given author/journal has published at least _h_ papers that have each been cited at least _h_ times. [Wikipedia](https://en.wikipedia.org/wiki/H-index). See Winker K, Withrow JJ (2013) Small collections make a big impact. Nature 493(7433):480–480. [https://doi.org/10.1038/493480b](https://doi.org/10.1038/493480b)\n";

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
	echo "If articles with new style DOIs are relevant to current researchers then we would expect to see them cited in recently published papers. This table lists the number of citations of new DOI in each decade, showing that recently papers do indeed cite BHL content. Note that citation links are continually updated, so that these newly minted BHL DOIs are enabling citation links to be created between works have been published long before BHL began.\n";

	$sql = "SELECT SUBSTR(creation,1,4)/10 * 10 AS decade, COUNT(cited) as count FROM citation GROUP BY decade ORDER BY decade DESC";

	$data = do_query($sql);

	// print_r($data);

	data_to_table($data);
	
	
}



{
	echo "\n## How many missed opportunities for BHL DOIs are there?\n";
	echo "One way to estimate the number of citation opportunities BHL is missing is to look at the lists of literature cited in a journal such as _ZooKeys_ and to ask questions such as:
- How many cited works have a link to BHL don't have a DOI?
- How many cited works pre-date 1923 but don't have a DOI?
\nReferences with links to BHL but no DOI are likely to be cases where BHL could add value by adding DOIs. References that pre-date 1923 have a good chance of being in BHL, and hence ideally would have DOIs.\nNote that the reason for using _ZooKeys_ is that the content of each article can be downloadsed as XML and hence we can extract the list of literature cited.";

}




?>

