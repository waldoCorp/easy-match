# Function to take a token and return a uuid
#
# params: 
# token  - the token to check for, wrapped in ' ' 
# token_table - which table to check for the token
# con - the r connection to the db
#
# Details:
# This function is used to take a token which is passed in a queryString
# so that shiny can identify the site user. The token expires after a period
# of time, and this function deletes it once it is used. The token is sanitized
# using `sqlInterpolate` to guard against sql injection attacks. The function
# throws an error if no record of the token is found or if the token is expired.
#
# Return: a uuid, ready to be pasted into further querys. 

token_to_uuid <- function(token, token_table, con) {

sql <-
  paste("SELECT uuid, token, expires, CURRENT_TIMESTAMP as now  
  FROM", token_table, " 
  WHERE token = ?token ;")

query <- DBI::sqlInterpolate(con, sql, token = token)
result <- DBI::dbGetQuery(con, query)

if(nrow(result) == 0){stop("invalid token supplied")}


sql_del <- paste("DELETE FROM", token_table, "WHERE token = ?token ;")
query_del <- DBI::sqlInterpolate(con, sql_del, token = token)
DBI::dbSendStatement(con, query_del, token = token)

if(result$expires < result$now) {stop("expired token")}

return(paste0("'",result$uuid,"'"))
}

