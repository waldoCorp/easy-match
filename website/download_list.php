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
require './login_script.php';
?>

<?php
// Region to set up PHP stuff
require_once $function_path . 'get_matching_names_list.php';
require_once $function_path . 'get_uuid.php';
require_once $function_path . 'download_match_list.php';

$uuid = $_SESSION['uuid'];

$partner_email = $_SESSION['partner_email'];
$partner_uuid = get_uuid($partner_email);

$names = get_matching_names_list($uuid,$partner_uuid);
download_match_list($names);

//header('Location: ./match_list.php');

?>
