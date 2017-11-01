<?php

/**
 * @file
 * Checks a directory for any folders over a month in age.
 */

$dir = $argv[1];
$monthAgo = strtotime("-1 month");

// in_array is case sensitive, add exclusions in lower case
$notThese = array(".","..","development","drupalfileshare");

$contents = scandir($dir);
foreach ($contents as $item) {
  $fullitem = $dir . '/' . $item;
  if (is_dir($fullitem) && filectime($fullitem) <= $monthAgo && !in_array(strtolower($item), $notThese)) {
    echo($fullitem . "\n");
  }
}