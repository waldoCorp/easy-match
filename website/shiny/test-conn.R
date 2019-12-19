con <-  DBI::dbConnect(
  RPostgres::Postgres(), 
  dbname = 'namedbTest', 
  user = 'namebot', 
  host = '127.0.0.1',
  port = 5432)
