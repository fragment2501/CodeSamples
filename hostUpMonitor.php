<?php

/**
 * @file
 * Script hostUpMonitor.php.
 *
 * Takes a URL as an argument and requests an HTTP response,
 * parses the HTTP response and returns OK for a 200 response.
 * Command is hostUpMonitor.php http://urltotest.
 */

$theHost = $argv[1];
$now = strtotime('now');
$log = "./timekeep.log";
$fh = fopen($log, 'a+');

/**
 * Requests http response code from host.
 */
function get_http_response_code($theHost) {
  if (get_headers($theHost)) {
    $headers = get_headers($theHost);
    return substr($headers[0], 9, 3);
  }
  else {
    return 'no_response';
  }
}

// Reads the log time contents.
$whenDown = file_get_contents($log);

// Pull the response code.
$response = get_http_response_code($theHost);

// Compare the response code and take action.
if ($response != 200) {
  if ($whenDown == '') {
    // Non 200 response, no previous log.
    fwrite($fh, $now);
    echo("DOWN " . $response . " as of now.\n");
  }
  else {
    // Non 200 response, previous log.
    $dt = $now - $whenDown;
    echo("DOWN " . $response . " for " . floor($dt / 60) . " minute(s).\n");
  }
}
else {
  // 200 response, report up time, clear log.
  file_put_contents($log, '');
  echo("UP as of " . date('Y-m-d H:i', $now) . " (UTC)\n");
}
