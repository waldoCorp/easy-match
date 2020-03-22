<?php
/**
 * Function to download CSV of matching list of names
 *
 * Use of function:
 * require_once '../download_match_list.php';
 *
 *
 * download_match_list($names_array);
 *
 * @param $names_array : Array of names (e.g. from get_matching_names_list())
 *
 * @return No "traditional" return, but does return a download link in the browser
**/

function download_match_list($names_array) {
  $filename = "match_list.csv";
  $delimiter = ";";
  header('Content-Type: application/csv');
  header('Content-Disposition: attachement; filename="'.$filename.'";');
  $f = fopen('php://output', 'w'); // Open "output" stream as writable

  fputcsv($f, $names_array, $delimiter);
}
