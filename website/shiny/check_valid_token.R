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

