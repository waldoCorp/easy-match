#'  Copyright (c) 2020 Lief Esbenshade
#'
#'    This file is part of Easy Match.
#'
#'    Easy Match is free software: you can redistribute it and/or modify
#'    it under the terms of the GNU Affero General Public License as published by
#'    the Free Software Foundation, either version 3 of the License, or
#'    (at your option) any later version.
#'
#'    Easy Match is distributed in the hope that it will be useful,
#'    but WITHOUT ANY WARRANTY; without even the implied warranty of
#'    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#'    GNU Affero General Public License for more details.
#'
#'    You should have received a copy of the GNU Affero General Public License
#'    along with Easy Match.  If not, see <https://www.gnu.org/licenses/>.


# user
get_user <- function(uuid) {
  paste("
  SELECT email, create_date AS signup_date, last_login, username 
  FROM users
  WHERE uuid =", uuid, ";")
}

# Partners
get_partners <- function(uuid) {
  paste("
  SELECT email AS partner, 
  	proposer AS you_invited, 
  	pair_propose_date AS invitation_date, 
  	confirmed AS invitation_accepted, 
  	pair_confirm_date AS accepted_date 
  FROM (
  	SELECT a.*, email 
  	FROM partners AS a 
	  LEFT JOIN users on a.partner_uuid = users.uuid
	  ) as p 
  WHERE uuid = ", uuid, ";")
}

# Selections
get_selections <- function(uuid) {
  paste("
  SELECT name, date_selected AS date_viewed, selected AS liked, n_changes, date_changed as date_last_change 
  FROM selections
  WHERE uuid =" , uuid, ";")
}


# Matches 
get_matches <- function(uuid) {
  paste(
    "
  SELECT selections.name, users.email AS partner
  FROM selections
  INNER JOIN (
    SELECT uuid, name
    FROM selections
    INNER JOIN (
      SELECT DISTINCT partner_uuid 
      FROM partners
      WHERE confirmed AND uuid =", uuid, "
    ) AS partner on partner.partner_uuid = selections.uuid
    WHERE selected
  ) AS p on p.name = selections.name
  INNER JOIN users ON p.uuid = users.uuid
  WHERE selected AND selections.uuid =", uuid, "
  ;"
  )
}
