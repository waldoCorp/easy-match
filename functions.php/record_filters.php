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
 * Function to set a user's filter preferences
 *
 * @param $uuid : UUID of user making the request
 * @param $preferences : Associative array containing filter choices
 * Expected form is:
 * 		array(
 * 		gender=>""/"boys"/"girls"/"neutral20",
 * 		first_letter=>null/"A-Z",
 *		last_letter=>null/"A-Z",
 * 		popularity=>null/"popular/unusual" )
 *
 * Gender array can contain none/any/all of those entries indicating
 *
 * @return No return, silent execution
**/

function record_filters($uuid, $preferences) {

    // Require table variables:
    require __DIR__ . '/table_variables.php';


    // connect to database
    require_once __DIR__ . '/db_connect.php';
    $db = db_connect();

    // Parse input gender preferences:
    $gender_text = null;
    if( !is_null($preferences['gender']) ) {
      $gender_text = $preferences['gender'];
    }

    // First Letter filters:
    $first_let_text = null;
    if( !is_null($preferences['first_letter']) ) {
      $first_let_text = $preferences['first_letter'];
    }

    // Last Letter:
    $last_let_text = null;
    if( !is_null($preferences['last_letter']) ) {
      $last_let_text = $preferences['last_letter'];
    }

    // Popularity filter:
    $pop_text = null;
    if( !is_null($preferences['popularity']) ) {
      $pop_text = $preferences['popularity'];
    }

    $sql = "INSERT INTO $preferences_table
            (uuid, gender, first_letter, last_letter, popularity)
            VALUES (:uuid, :gender_text, :first_let_text, :last_let_text, :pop_text)

            -- If the name selected is already here, update to new true/false:
	    ON CONFLICT (uuid)
            DO UPDATE SET
            gender = EXCLUDED.gender,
            first_letter = EXCLUDED.first_letter,
            last_letter = EXCLUDED.last_letter,
            popularity = EXCLUDED.popularity;";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':uuid',$uuid);
    $stmt->bindValue(':gender_text',$gender_text);
    $stmt->bindValue(':first_let_text',$first_let_text);
    $stmt->bindValue(':last_let_text',$last_let_text);
    $stmt->bindValue(':pop_text',$pop_text);
    $stmt->execute();
}
