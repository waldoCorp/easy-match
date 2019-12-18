
# Get data tables --------------------------------------------------------------

partners <- 
  "SELECT COUNT(*) AS n
  FROM partners
  GROUP BY uuid;" %>% 
  dbGetQuery(con, .)

name_popularity <- 
  "SELECT name,
    COUNT(*) as times_seen, 
    SUM(selected::int) as times_liked, 
    AVG(selected::int) as popularity
   FROM selections
   GROUP BY name;" %>% 
  dbGetQuery(con, .)

user_selections <- 
  "SELECT uuid, 
    COUNT(*) as names_ranked, 
    SUM(selected::int) as names_liked
  FROM selections
  GROUP BY uuid;" %>% 
  dbGetQuery(con, .)

# Month Aggregated Selection Data
month <- 
    "SELECT *, CONCAT(DATE_PART('year' , date_selected), '-',
                     DATE_PART('month', date_selected)) AS month
    FROM selections"

byMonth_selections <- 
  paste(
  "SELECT month,
    COUNT(*) as views,
    SUM(selected::int) as likes
  FROM (", month, ") as s
  GROUP BY MONTH") %>% 
  dbGetQuery(con, .)

byMonth_newUsers <- 
  paste(
  "SELECT month, count(*) AS new_users
  FROM 
  ( SELECT uuid, min(month) as month, min(date_selected)
    FROM (", month, ") AS a
    GROUP BY uuid) AS b
  GROUP BY month") %>% 
  dbGetQuery(con, .)

byMonth_activeUsers <- 
  paste(
  "SELECT month, COUNT(DISTINCT uuid) as active_users
    FROM (", month, ") AS a
    GROUP BY month") %>% 
  dbGetQuery(con, .)

byMonth <- 
  full_join(byMonth_selections, 
            byMonth_newUsers) %>% 
  full_join(byMonth_activeUsers)

rm(byMonth_selections, byMonth_activeUsers, byMonth_newUsers)

# Match data
matches <- 
  "SELECT s.uuid, partner_uuid, s.name
  FROM selections AS s
  INNER JOIN partners AS p on s.uuid = p.uuid
  INNER JOIN selections AS s2 on p.partner_uuid = s2.uuid AND s.name = s2.name
  WHERE s.selected AND s2.selected" %>% 
    dbGetQuery(con, .)

name_matches <-
  "SELECT s.name, count(s.name)/2 as times_matched
  FROM selections AS s
  INNER JOIN partners AS p on s.uuid = p.uuid
  INNER JOIN selections AS s2 on p.partner_uuid = s2.uuid AND s.name = s2.name
  WHERE s.selected AND s2.selected
  GROUP BY s.name" %>% 
  dbGetQuery(con, .)


# Put all the datasets together into a list
# Clean up data types - sql outputs as int64 which ggplot does not like
data <- list("byMonth" = byMonth, 
             "matches" = matches, 
             "name_matches" = name_matches,
             "name_popularity" = name_popularity, 
             "partners" = partners, 
             "user_selections" = user_selections)
for (i in 1:length(data)) {
  data[[i]]<- data[[i]] %>% mutate_if(function(x) {class(x) == "integer64"}, as.numeric)
}
