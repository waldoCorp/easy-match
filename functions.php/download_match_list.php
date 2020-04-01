<?php
/**
 *    Copyright (c) 2020 Ben Cerjan, Lief Esbenshade
 *
 *    This file is part of Easy Match.
 *
 *    Easy Match is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Affero General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    Easy Match is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with Easy Match.  If not, see <https://www.gnu.org/licenses/>.
**/

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
  $delimiter = "\n";
  header('Content-Type: application/csv');
  header('Content-Disposition: attachement; filename="'.$filename.'";');
  $f = fopen('php://output', 'w'); // Open "output" stream as writable

  fputcsv($f, $names_array, $delimiter);
}
