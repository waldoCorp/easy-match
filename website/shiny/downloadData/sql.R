
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
  SELECT name, date_selected AS date_viewed, selected AS liked 
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
