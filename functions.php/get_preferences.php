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
 * Function to get the name preferences a user has selected:
 *
 * Example usage:
 * require_once '../get_preferences.php';
 *
 *
 * $preferences = get_preferences($uuid);
 *
 * @param $uuid : UUID of user whose preferences we are getting
 *
 * @return Nested array with the preferences the user has indicated
 *
**/

function get_preferences($uuid) {
    // Require table variables:
    require __DIR__ . '/table_variables.php';


    // connect to database
    require_once __DIR__ . '/db_connect.php';
    $db = db_connect();

    $sql = "SELECT gender, first_letter, last_letter, popularity
            FROM $preferences_table
	    WHERE uuid = :uuid;";


    $stmt = $db->prepare($sql);
    $stmt->bindValue(':uuid',$uuid);
    $stmt->execute();

    $result = $stmt->fetchAll();

    $output = null;

    // If we have an entry, prepare our return:
    if( !empty($result) ) {
      // Turn gender into nested array from string:

      $output = $result[0];
    }

    return $output;
}
