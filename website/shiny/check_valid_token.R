# Function to check whether a user has a valid token
# to access the shiny server
#
# params: 
# token  - the token to check for, wrapped in ' ' 
# token_table - which table to check for the token
#
# Usage:
# this is SQL query string, assumes valid connection to db
# called in dbGetQuery
# dbGetQuery(con, check_valid_token(uuid, "data_tokens")

token_to_uuid <- function(token, token_table, con) {

query <-
  paste("
  SELECT uuid, token, expires, CURRENT_TIMESTAMP as now  
  FROM", token_table, " 
  WHERE token =" , token, ";") 

result <- DBI::dbGetQuery(con, query)

if(nrow(result) == 0){stop("invalid token supplied")}

DBI::dbSendStatement(paste("DELETE FROM", token_table, 
	"WHERE token =", token, ";"))

if(result$expires < result$now) {stop("expired token")}

return(result$uuid)


}

