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
 * Function to get a list of names for a user to upvote/downvote
 * Takes user's email address and table names as arguments
 *
 * Example usage:
 * <code>
 * require_once './functions.php/get_names.php'
 * <code>
 *
 * Use of function:
 * $names = get_names($uuid,$n);
 *
 * On success returns a flat array of names
 *
 * @param string $uuid : UUID of user making the request
 * @param integer $n : Number of names to return
 * @return array : Returns an array of names
 *
**/

function get_names($uuid,$n) {
        // Require table variables:
        require __DIR__ . '/table_variables.php';

        // Connect to database
        require_once __DIR__ . '/db_connect.php';
        $db = db_connect();

	// Get preferences:
        require_once __DIR__ . '/get_preferences.php';
	$preferences = get_preferences($uuid);

	// Build filtering statements:

	// Gender filters -----------
	$gender_text = '';
	if( !empty($preferences['gender']) ) {

          switch ($preferences['gender']) {
	    case 'boy':
	      $gender_text = 'm = true';
	      break;
	    case 'girl':
	      $gender_text = 'f = true';
	      break;
	    case 'neutral20':
	      $gender_text = 'neutral20 = true';
	      break;
          }
        }

	// First Letter filter -----------
	$first_let_text = '';
	if( !empty($preferences['first_letter']) ) {
	  if( empty($gender_text) ) {
	    $first_let_text = " first_letter = '".$preferences['first_letter']."'";
	  } else {
	    $first_let_text = " AND first_letter = '".$preferences['first_letter']."'";
	  }
	}

	// Last Letter filter -----------
	$last_let_text = '';
	if( !empty($preferences['last_letter']) ) {
	  if( empty($gender_text) && empty($first_let_text) ) {
	    $last_let_text = "UPPER(last_letter) = '".$preferences['last_letter']."'";
	  } else {
	    $last_let_text = " AND UPPER(last_letter) = '".$preferences['last_letter']."'";
	  }
	}

	// Popularity filter -----------
	$pop_text = ''; // Now there is no default filter
	if( !empty($preferences['popularity']) ) {
	  switch ($preferences['popularity']) {
	    case 'popular':
	      $pop_text = ' (rank_m_2010 <= 250 OR rank_f_2010 <= 250)';
	      break;
	    case 'unusual':
	      $pop_text = '((rank_m_2010 >= 500 OR rank_m_2010 IS NULL) AND (rank_f_2010 >= 500 OR rank_f_2010 IS NULL)) ';
	      break;
	  }

    if( !empty($gender_text) || !empty($first_let_text) || !empty($last_let_text) ) {
      $pop_text = ' AND ' . $pop_text;
    }
	}

	// Put all filters together:
	$filter_text = $gender_text.$first_let_text.$last_let_text.$pop_text;

	$sql = "
	SELECT rs.name AS name,
	COALESCE(ps.match, false) AS match

	  FROM (
		-- Get partner selections
    		SELECT DISTINCT name, true AS priority, true AS match
    		FROM $selections_table s
    		LEFT JOIN $partners_table p ON s.uuid = p.partner_uuid
    		WHERE (p.uuid = :uuid AND confirmed = true)
		AND s.selected = true
	  ) AS ps

	  RIGHT JOIN (
	  -- get all the names from the db, apply filters and right join to drop partner selections that dont match
    		SELECT name, pweight_2010
    		FROM $names_table n
		WHERE $filter_text
	  ) AS rs ON ps.name = rs.name

	  LEFT JOIN (
	  -- remove anything already seen
		SELECT name
		FROM $selections_table
    		WHERE uuid = :uuid
	  ) AS ss ON rs.name = ss.name

	WHERE ss.name IS NULL
	-- randomizes the returned list, but keeps partner names on top --
	ORDER BY priority, random()^(pweight_2010)
	LIMIT :n;";

	$stmt = $db->prepare($sql);
	$stmt->bindValue(':uuid',$uuid);
	$stmt->bindValue(':n',$n,PDO::PARAM_INT);
	$stmt->execute();

        // Get names from specified table
	$names = $stmt->fetchAll();

return $names;

}
