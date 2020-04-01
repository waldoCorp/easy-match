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
 * Function to get a list of current accepted partners
 *
 * Use of function:
 * require_once '../get_partners.php';
 *
 *
 * $partners = get_partners($uuid);
 *
 * @param $uuid : UUID of the to find partners for
 *
 * @return Array containing the uuid and email addresses of people who have
 * sent a partnership request.
 *
 * The format is array(uuid => email_address,...)
**/

function get_partners($uuid) {
    // Require table variables:
    require __DIR__ . '/table_variables.php';

    // connect to database
    require_once __DIR__ . '/db_connect.php';
    $db = db_connect();

    // Find partners for this user:
    $sql = "SELECT partner_uuid FROM $partners_table WHERE uuid = :uuid
            AND confirmed = true";


    $stmt = $db->prepare($sql);
    $stmt->bindValue(':uuid',$uuid);
    $stmt->execute();

    $p_uuids = $stmt->fetchAll();
    // Convert to flat array:
    $p_uuids = call_user_func_array('array_merge',$p_uuids);
    array_shift($p_uuids);

    require_once __DIR__ . '/get_email.php';
    require_once __DIR__ . '/get_username.php';

    $output = array();

    foreach( $p_uuids as $p_uuid ) {
      $output[$p_uuid] = array(
                            'email' => get_email($p_uuid),
                            'uname' => get_username($p_uuid)
                         );
    }

    return $output;
}
