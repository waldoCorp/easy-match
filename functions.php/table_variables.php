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
 * parametrize table names in case Ben goes crazy and wants to change up the database
 * For the record, Lief thinks this is unnecessary
 *
 * But you never know...
 */
$names_table = 'names';
$selections_table = 'selections';
$partners_table = 'partners';
$password_recovery_table = 'password_recovery';
$users_table = 'users';
$new_matches_table = 'new_matches';
$preferences_table = 'name_preferences';
$communication_preferences_table = 'communication_preferences';
$data_token_table = 'data_tokens';
$unsubscribe_token_table = 'unsubscribe_tokens';
