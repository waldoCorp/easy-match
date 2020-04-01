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
 * Function to get image and thumbnail URLs for hosting by Amazon.
 * Takes a tablename as input and tries to parse for all UUID's there.
 * (SHOULD BE MODIFIED TO GET METADATA AS WELL!!!!)
 *
 * Example usage:
 * <code>
 * require_once './functions.php/get_all_urls.php'
 * <code>
 *
 * Use of function:
 * $url_array = get_all_urls($table);
 *
 * On success returns a nested array containing URL's to both
 * the original image and the thumbnails in the specified table
 * @author Ben Cerjan
 * @param string $table : Table to retrieve uuid's from
 * @return array : Returns an array of arrays with URLs to the
 * uuid's in the table. They are always listed original image first,
 * followed by the thumbnail url.
 *
**/

function get_all_urls($table) {
        // Connect to database
        require_once __DIR__ . '/db_connect.php';
        $db = db_connect();

        // Pull bucket info as well:
        require '/some/place/file.php';

        // Pull UUID's from specified table
        if ($table) {
                        try {
                                $sql = "SELECT * FROM $table";
                                $stmt = $db->prepare($sql);
                                $stmt->execute();
                                // Get rows:
                                $uuid = $stmt->fetchAll();
                        } catch(PDOException $e) {
                                die('ERROR: ' . $e->getMessage() . "\n.");
                        }

        }

	// Now that we have all of the uuid's, we need to make them into paired
	// sets of URL's -- so that we can match thumbnails and full-size images

	// Convert to URL's:
	$urls = array();

	foreach ($uuid as $key=>$id) {
        	$urls[] = array ( $key => array ( $bucket_url . "/" . $id['uuid'],
                	                        $bucket_url . "/" . $id['thumb_uuid']
                        	              )
                      		);
	}


return $urls;

}
