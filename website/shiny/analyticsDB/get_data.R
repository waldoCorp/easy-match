
# Get data tables --------------------------------------------------------------

users <- 
  "SELECT last_login, 
    create_date,
    CURRENT_DATE 
  FROM USERS" %>% 
  dbGetQuery(con, .)

partners <- 
  "SELECT COUNT(*) AS n
  FROM partners
  GROUP BY uuid;" %>% 
  dbGetQuery(con, .)

name_popularity <- 
  "SELECT l.name, ratio_mf_2010,
    COUNT(*) as times_seen, 
    SUM(selected::int) as times_liked, 
    AVG(selected::int) as popularity
   FROM selections AS l
   LEFT JOIN names as r on l.name = r.name
   GROUP BY l.name, ratio_mf_2010;" %>% 
  dbGetQuery(con, .)

user_selections <- 
  "SELECT uuid, 
    COUNT(*) as names_ranked, 
    SUM(selected::int) as names_liked
  FROM selections
  GROUP BY uuid;" %>% 
  dbGetQuery(con, .)

# Month Aggregated Selection Data
byMonth_selections <- 
  paste(
  "SELECT TO_CHAR(date_selected, 'YYYY-MM') AS month,
    COUNT(*) as views,
    SUM(selected::int) as likes
  FROM selections as s
  GROUP BY MONTH") %>% 
  dbGetQuery(con, .)

byMonth_newUsers <- 
  paste(
  "SELECT TO_CHAR(create_date, 'YYYY-MM') AS month,
          COUNT(*) as new_users
  FROM users
  GROUP BY month") %>% 
  dbGetQuery(con, .) %>% 
  mutate(new_users = as.integer(new_users))

byMonth_activeUsers <- 
  paste(
  "SELECT TO_CHAR(date_selected, 'YYYY-MM') AS month, COUNT(DISTINCT uuid) as active_users
    FROM selections
    GROUP BY month") %>% 
  dbGetQuery(con, .) %>% 
  mutate(active_users = as.integer(active_users))

byMonth <- 
  full_join(byMonth_selections, 
            byMonth_newUsers) %>% 
  full_join(byMonth_activeUsers) %>% 
  replace(is.na(.), 0) %>% 
  arrange(month)

rm(byMonth_selections, byMonth_activeUsers, byMonth_newUsers)

# Match data
matches <- 
  "SELECT s.uuid, partner_uuid, s.name
  FROM selections AS s
  INNER JOIN partners AS p on s.uuid = p.uuid
  INNER JOIN selections AS s2 on p.partner_uuid = s2.uuid AND s.name = s2.name
  WHERE s.selected AND s2.selected AND p.confirmed" %>% 
    dbGetQuery(con, .)

name_matches <-
  "SELECT s.name, count(s.name)/2 as times_matched
  FROM selections AS s
  INNER JOIN partners AS p on s.uuid = p.uuid
  INNER JOIN selections AS s2 on p.partner_uuid = s2.uuid AND s.name = s2.name
  WHERE s.selected AND s2.selected AND p.confirmed
  GROUP BY s.name" %>% 
  dbGetQuery(con, .)


# Put all the datasets together into a list
# Clean up data types - sql outputs as int64 which ggplot does not like
data <- list("byMonth" = byMonth, 
             "matches" = matches, 
             "name_matches" = name_matches,
             "name_popularity" = name_popularity, 
             "partners" = partners, 
             "user_selections" = user_selections,
             "users" = users)
for (i in 1: (length(data) - 1)) {
  data[[i]]<- data[[i]] %>% mutate_if(function(x) {class(x) == "integer64"}, as.numeric)
}

rm(byMonth, matches, name_matches, name_popularity, partners, user_selections, users, i)
