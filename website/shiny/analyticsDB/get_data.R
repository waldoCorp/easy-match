
con <-  DBI::dbConnect(
  RPostgres::Postgres(), 
  dbname = 'namedb', 
  user = 'namebot', 
  host = '127.0.0.1',
  port = 9000)


# Get data tables --------------------------------------------------------------

selections <- RPostgres::dbReadTable(con, "selections") %>%
  mutate(month = paste(lubridate::year(date_selected), 
                       lubridate::month(date_selected), 
                       sep = "-"))
partners <- RPostgres::dbReadTable(con, "partners")

# Above lines pull selections from db into memory, in theory it would be better
# to run all the aggregation querys in the db itself in sql. These lines do so
# but dbplyr seems like its not very fully developed and a lot of the code below
# breaks
# library(dbplyr)
# selections <- tbl(con, "selections")
# partners <- tbl(con, "partners")  

name_popularity <- selections %>% 
  group_by(name) %>% 
  summarize(times_seen = n(), 
            times_liked = sum(as.integer(selected)),
            popularity = mean(as.integer(selected)))

user_selections <- selections %>% 
  group_by(uuid) %>% 
  summarize(names_ranked = n(), 
            names_liked = sum(as.integer(selected)))

# Month Aggregated Selection Data

byMonth_selections <- selections %>% 
  group_by(month) %>% 
  summarize(views = n(), 
            likes = sum(as.integer(selected)))

byMonth_newUsers <- selections %>% 
  group_by(uuid) %>% 
  filter(row_number() == 1) %>% 
  ungroup() %>% 
  count(month) %>% 
  rename(new_users = n)

byMonth_activeUsers <- selections %>% 
  distinct(month, uuid) %>% 
  count(month) %>% 
  rename(active_users = n)

byMonth <- 
  full_join(byMonth_selections, 
            byMonth_newUsers) %>% 
  full_join(byMonth_activeUsers)

# Match data

get_match <- function(uuid) {
  
  my_selected <- selections %>% filter(uuid == as.character(!!uuid), selected == TRUE)
  my_partners <- partners %>% filter(uuid == as.character(!!uuid)) %>% pull(partner_uuid)

  partner_selected <- selections %>%
    filter(uuid %in% my_partners, selected == TRUE) %>%
    select(partner = uuid, name, date_selected_2 = date_selected)

  return(my_selected %>%
           inner_join(partner_selected,
                      by = 'name') %>%
           select(uuid, partner, name))
}

matches <- selections %>% distinct(uuid) %>% pull(uuid) %>%  
  map_dfr(get_match) 

