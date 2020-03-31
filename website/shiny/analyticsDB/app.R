library(shiny)
library(tidyverse)
library(DBI)
library(RPostgres)
library(survival)
library(survminer)
library(lubridate)

# Define UI --------------------------------------------------------------------

ui <- fluidPage(
  
  titlePanel("Site Analytics Dashboard"),
  
  navlistPanel(
    
    "Stats by Name",
    tabPanel("Name Lookup", DT::dataTableOutput("table_namepop_gender")),
    tabPanel("View & Like Histograms", plotOutput("plot_name_seen"),
                                       plotOutput("plot_name_like")),
    tabPanel("Popularity", plotOutput("plot_name_popularity")),

    "Stats by User",
    tabPanel("View & Like Histograms", plotOutput("hist_view"),
                                       plotOutput("hist_like")),
    tabPanel("Names Viewed vs Liked", plotOutput("scatter_rank_like")),

    "Partners/Matches",
    tabPanel("Number of Partners", plotOutput("plot_num_partners")),
    tabPanel("Matches and Views", plotOutput("plot_num_matches"),
             plotOutput("plot_match_view_hist"),
             plotOutput("plot_match_view_scatter")),
    
    "Usage",
    tabPanel("# Views/Likes Over Time", plotOutput("likerank_time_trend")),
    tabPanel("# Users Over Time", plotOutput("user_time_trend")),
    tabPanel('User Survival Curve', plotOutput("user_survival"))
    
  )
)


# Server -----------------------------------------------------------------------

# Define server logic required to draw a histogram
server <- function(input, output) {
  
  source("../con.R")
  source("get_data.R")
  
  # Output for Names Tab -------------------------------------------------------
  
  output$plot_name_seen <- renderPlot({
    
    ggplot(data[["name_popularity"]], 
           aes(times_seen)) +
      geom_histogram(bins = 10) +
      labs(title = "Histogram of Name Views",
           y = "Count of Names", 
           x = "Number of Times Viewed",
           caption = "Excludes names that have never been viewed") +
      theme_minimal()
  })
  
  output$plot_name_like <- renderPlot({
    
    ggplot(data[["name_popularity"]], 
           aes(times_liked)) +
      geom_histogram(bins = 10) +
      labs(title = "Histogram of Name Likes",
           y = "Count of Names", 
           x = "Number of Times Liked",
          caption = "Excludes names that have never been viewed") +
      theme_minimal()
  })
  
  output$plot_name_popularity <- renderPlot({
    
    data[["name_popularity"]] %>% 
      mutate(gender = case_when(
        ratio_mf_2010 < 0.2 ~ "Female", 
        ratio_mf_2010 > 0.8 ~ "Male", 
        TRUE ~ "Neutral"
      )) %>% 
      ggplot(., 
             aes(popularity, fill = gender, color = gender, alpha = .5)) +
      geom_density() +
      labs(title = "Name Popularity by Gender",
           # y = "Count of Names", 
           x = "Popularity Ratio (Likes / Views)", 
           alpha = "") +
      theme_minimal() + 
      guides(alpha = FALSE)
  })
    
  output$table_namepop_gender <- DT::renderDataTable(
    data[["name_popularity"]] %>% 
      mutate(gender = case_when(
        ratio_mf_2010 < 0.2 ~ "Female", 
        ratio_mf_2010 > 0.8 ~ "Male", 
        TRUE ~ "Neutral"
        ), 
        popularity = round(popularity, 2)
      ) %>% 
      select(name, gender, times_seen, times_liked, popularity) %>% 
      arrange(desc(times_seen)), 
    filter = 'top'
  )
  
  
  # Output for Rankings/Likes Tab ----------------------------------------------
  
  output$hist_view <- renderPlot({
    
    ggplot(data[["user_selections"]], 
           aes(names_ranked)) +
      geom_histogram(bins = 10) +
      labs(title = "Number of Names Viewed by Site Users",
           y = "Count of Users", 
           x = "Number of Names Viewed") +
      theme_minimal()
  })
  
  output$hist_like <- renderPlot({
    
    ggplot(data[["user_selections"]], 
           aes(names_liked)) +
      geom_histogram(bins = 10) +
      labs(title = "Number of Names Liked by Site Users",
           y = "Count of Users", 
           x = "Number of Names Liked") +
      theme_minimal()
  })
  
  output$scatter_rank_like <- renderPlot({
    
    ggplot(data[["user_selections"]], 
           aes(x=names_ranked, y = names_liked)) +
      geom_point() +
      geom_smooth(method = "lm") + 
      theme_minimal() +
      labs(y = "Names Liked", 
           x = "Names Viewed", 
           title = "Relationship between Names Viewed and Liked")
  })
  
  # Output for Partners/Matches ------------------------------------------------
  
  output$plot_num_partners <- renderPlot({
    
    ggplot(data[["partners"]], 
           aes(n)) +
      geom_histogram(bins = 4) +
      labs(title = "Distribution of Number of Partners Per User",
           y = "Count of Users", 
           x = "Number of Partners") +
      theme_minimal()
  })
  
  output$plot_num_matches <- renderPlot({
    
    data[["matches"]] %>% 
      count(uuid) %>% 
      ggplot(., 
             aes(n)) +
      geom_histogram(bins = 10) +
      labs(title = "Distribution of Matchs", 
           y = "Number of Users", 
           x = "Number of Matchs") +
      theme_minimal()
  })
  
  output$plot_match_view_hist <- renderPlot({
    
    data[["matches"]] %>% 
      count(uuid) %>% 
      left_join(data[["user_selections"]]) %>% 
      mutate(view_match = n / names_ranked) %>% 
      ggplot(., 
             aes(view_match)) +
      geom_histogram(bins = 5) +
      labs(title = "Distribution of Match to View Ratios", 
           y = "Number of Users", 
           x = "Match to View Ratio") +
      theme_minimal()
  })
  
  output$plot_match_view_scatter <- renderPlot({
    
    data[["matches"]] %>% 
      count(uuid) %>% 
      left_join(data[["user_selections"]])  %>% 
      ggplot(., 
             aes(x = names_ranked, y = n)) +
      geom_point() +
      geom_smooth(method = "lm") +
      labs(title = "Users View and Match Counts", 
           y = "Number of Matches", 
           x = "Number of Views") +
      theme_minimal()
  })
  
  # Output for Usage Tab -------------------------------------------------------
  output$user_time_trend <- renderPlot({
    
    data[["byMonth"]] %>%
    pivot_longer(cols = c(new_users, active_users)) %>% 
      ggplot(., 
             aes(x=month, y = value, group = name, color = name)) +
      geom_line() +
      theme_minimal() +
      labs(y = "Number of Users", 
           x = "Time", 
           title = "New/Active Users by Month")
  })
  
  output$likerank_time_trend <- renderPlot({
    
    data[["byMonth"]] %>% 
      mutate_if(is.numeric, cumsum) %>% 
      pivot_longer(cols = c(views, likes)) %>% 
      ggplot(., 
             aes(x=month, y = value, group = name, color = name)) +
      geom_line() +
      theme_minimal() +
      labs(y = "Number of Names", 
           x = "Month", 
           title = "Cumulative Names Viewed and Liked Over Time")
  })
  
  output$user_survival <- renderPlot({
    
    survDat <- mutate(data[["users"]], 
                      duration = (interval(create_date, last_login))/days(1) , 
                      time_since = (interval(last_login, current_date))/days(1),
                      lost = as.numeric(time_since >= 30))
    
    ggsurvplot(
      survfit(Surv(duration, lost) ~ 1, 
              data =  survDat)) +
      labs(title = "User Tenure With Site", 
           x = "Days Using Site")
  })
}

# Run the application 
shinyApp(ui = ui, server = server)
