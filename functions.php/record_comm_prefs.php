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
 * Function to set a user's communcation preferences
 *
 * @param $uuid : UUID of user making the request
 * @param $preferences : Associative array containing choices
 * Expected form is:
 * 		array(
 * 		allComm=>true/null,
 * 		partnerComm=>true/null,
 *		promotionComm=>true/null,
 * 		noComm=>true/null )
 *
 * Array can contain any/some of these choices (none is not a choice)
 *
 * @return No return, silent execution
**/

function record_comm_prefs($uuid, $preferences) {

    // Require table variables:
    require __DIR__ . '/table_variables.php';


    // connect to database
    require_once __DIR__ . '/db_connect.php';
    $db = db_connect();

    // Parse preferences:
    $all_comm = false;
    if( !is_null($preferences['allComm']) ) {
      $all_comm = true;
    }

    $no_comm = false;
    if( !is_null($preferences['noComm']) ) {
      $no_comm = true;
    }

    $promo_comm = false;
    if( !is_null($preferences['promotionComm']) ) {
      $promo_comm = true;
    }

    $func_comm = false;
    if( !is_null($preferences['partnerComm']) ) {
      $func_comm = true;
    }



    $sql = "INSERT INTO $communication_preferences_table
            (uuid, all_comm, none, functional, promotional)
            VALUES (:uuid, :all_comm, :no_comm, :promo_comm, :func_comm)

            -- If the name selected is already here, update to new true/false:
	    ON CONFLICT (uuid)
            DO UPDATE SET
            all_comm = EXCLUDED.all_comm,
            none = EXCLUDED.none,
            functional = EXCLUDED.functional,
            promotional = EXCLUDED.promotional;";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':uuid',$uuid);
    $stmt->bindValue(':all_comm',$all_comm, PDO::PARAM_BOOL);
    $stmt->bindValue(':no_comm',$no_comm, PDO::PARAM_BOOL);
    $stmt->bindValue(':promo_comm',$promo_comm, PDO::PARAM_BOOL);
    $stmt->bindValue(':func_comm',$func_comm, PDO::PARAM_BOOL);
    $stmt->execute();
}
