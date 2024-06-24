# Updating the data

The data used to generate these stats comes from various sources.

## DOIs in BHL

The file [doi.txt](https://www.biodiversitylibrary.org/data/doi.txt) is a TSV file of 

The data is imported into a SQLite database with the following schema:

```
CREATE TABLE "doi" (
    EntityType TEXT
  , EntityID INTEGER
  , DOI TEXT
  , CreationDate TEXT
  , unpaywall INTEGER
);

CREATE INDEX "doi_doi" ON doi(DOI COLLATE BINARY ASC);
```

Typically this schema will be created by simply importing the TSV file, but you then have to add the `unpaywall` column.

## OpenCitation

The SQL query `SELECT doi FROM doi WHERE doi LIKE '10.5962/p.%'` (which is run automatically by `go.php`) finds all PIWG DOIs. `go.php` writes these to `pdoi.txt`. The script `opencitation.php` will read this file and find citations (if any) for each DOI in that file. The results are written as SQL statements that insert data into a table with the following structure.

```
CREATE TABLE "citation" (
    oci TEXT PRIMARY KEY
  , cited TEXT
  , citing TEXT
  , creation TEXT
  , journal_sc TEXT
);

CREATE INDEX cited ON citation(cited COLLATE BINARY ASC);
```

## Unpaywall

The SQL query `SELECT doi FROM doi WHERE doi NOT LIKE '10.5962/%'` (which is run automatically by `go.php`) finds all DOIs in BHL that aren’t minted by BHL. `go.php` writes these to `nonbhldoi.txt`. The script `unpaywall.php` will read this file and determine whether BHL is the preferred destination for each DOI. The results are written as SQL statements.