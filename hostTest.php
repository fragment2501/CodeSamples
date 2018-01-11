<?php

/**
 * @file
 * Script hostTest.php.
 *
 * Takes a URL as an argument and requests an HTTP response
 * parses the HTTP response and returns OK for a 200 response.
 * Command is hostTest.php http://urltotest.
 */

$theHost = $argv[1];

/**
 * Requests http response code from host.
 */
function get_http_response_code($theHost) {
  $headers = get_headers($theHost);
  return substr($headers[0], 9, 3);
}

$response = get_http_response_code($theHost);

if ($response != 200) {
  echo("DOWN " . $response . "\n");
}
else {
  echo("UP\n");
}
