# Wikidata

## BHL page id but no DOI


```
SELECT * WHERE {  
  ?work wdt:P687 ?bhl.
  ?work wdt:P1476 ?title .
  OPTIONAL { ?work wdt:P356 ?doi. }
  FILTER (!BOUND(?doi))
}
```
