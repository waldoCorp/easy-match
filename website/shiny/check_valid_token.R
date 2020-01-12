# Function to check whether a user has a valid token
# to access the shiny server
#
# params: 
# uuid - the user id, wrapped in ' ' 
# token_table - which table to check for the token
#
# Usage:
# this is SQL query string, assumes valid connection to db
# called in dbGetQuery
# dbGetQuery(con, check_valid_token(uuid, "data_tokens")

check_valid_token <- function(uuid, token, token_table) {
  paste("
  SELECT count(*)  
  FROM", token_table, " 
  WHERE uuid =" , uuid,
	"AND token =", token,
        "AND expires >= CURRENT_TIMESTAMP;")
}

