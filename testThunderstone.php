<?php

/**
 * @file
 * Script testThunderstone.php.
 *
 * Script will go through the searchterms, types and uses per site
 * site, and compile a list of the results in test_results.csv.
 */

$output_file = '../data/onetimeupdates/srcTest/test_results.csv';

// Set a header for the csv file.
file_put_contents($output_file, 'searchterm,searchuse,type,total,site', FILE_APPEND);

$sites = [
  'http://npsd1042_allwordsfix.nichdweb.woodbournesolutions.com'
];

$searchterms = [
  'child',
  'childbearing',
  'childbearing age',
  'children',
  'choice;',
  'sids vaccines'
];

$types = [
  'all',
  'general',
  'events',
  'orgunits',
  'publications'
];

$searchuses = [
  'exact',
  'any',
  'all'
];

foreach ($sites as $site) {
  foreach ($searchterms as $searchterm) {
    foreach ($types as $type) {
      foreach ($searchuses as $searchuse) {
        // Construct the url to "search"
        $url = $site . '/search/site?keys=' . rawurlencode($searchterm) . '&order=dd&cq_text=' . $type . '&matching=' . $searchuse;

        // Load the constructed url, and parse the results total.
        $dom_doc = new DOMDocument();
        libxml_use_internal_errors(TRUE);
        $dom_doc->loadHTMLFile($url);

        $results = $dom_doc->getElementById("thunderstone-search-api-searchresults");
        $p_results = $results->getElementsByTagName("p");

        $pattern = '~(.+)\s(\d+)\s(.+)~';

        // If no numeric catch, swap 0 for no results.
        preg_match($pattern, $p_results[0]->nodeValue, $matches);
        $total = '';
        if ($matches[2] == '') {
          $total = "0";
        }
        else {
          $total = $matches[2];
        }

        // Create a csv line and write to the output file.
        $build_a_line = str_replace('%20', ' ', $searchterm) . ',' . $searchuse . ',' . $type . ',' . $total . ',' . $site . "\n";
        file_put_contents($output_file, $build_a_line, FILE_APPEND);
      }
    }
  }
}
