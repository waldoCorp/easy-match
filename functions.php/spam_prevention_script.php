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

/*
 * Script to include spam prevention stuff on ajax pages / wherever needed
 * TEST COMMENT
*/
/*
if( !isset($_SESSION['spam_count']) ) {
	$_SESSION['spam_count'] = 0;
}*/

$_SESSION['spam_count'] = 1 + $_SESSION['spam_count'];

$time_diff = time() - $_SESSION['last_request'];

// Minimum time (in sec) between requests to add to spam counter:
$min_time = 5;

// Maximum number of tries within $min_time:
$max_requests = 5;

// Here, we check the above conditions:

if( $_SESSION['spam_count'] >= $max_requests && $time_diff <= $min_time ) {
//if( $_SESSION['spam_count'] >= 10 ) {
	// Stop here if too much spam:
	// header('last_location'); ????
	//$_SESSION['blocked'] = true;
	exit();

} elseif( $time_diff > $min_time ) {
	$_SESSION['spam_count'] = 1;
}


$_SESSION['last_request'] = time();
