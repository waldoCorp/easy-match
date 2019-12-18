library(shiny)
library(tidyverse)
library(DBI)
library(RPostgres)

# Define UI --------------------------------------------------------------------

ui <- fluidPage(
  
  titlePanel("Site Analytics Dashboard"),
  
  navlistPanel(
    
    "Stats by Name",
    tabPanel("Name Lookup",
             textInput("name",
                       "Name:",
                       value="Waldo"),
             tableOutput("single_name")),
    tabPanel("View Distribution", plotOutput("plot_name_seen")),
    tabPanel("Like Distribution", plotOutput("plot_name_like")),
    tabPanel("Popularity", plotOutput("plot_name_popularity")),

    "Stats by User",
    tabPanel("Number of Names Viewed", plotOutput("hist_view")),
    tabPanel("Number of Names Liked", plotOutput("hist_like")),
    tabPanel("Names Viewed vs Liked", plotOutput("scatter_rank_like")),

    "Partners/Matches",
    tabPanel("Number of Partners", plotOutput("plot_num_partners")),
    tabPanel("Number of Matches", plotOutput("plot_num_matches")), 
    
    "Usage",
    tabPanel("# Views/Likes Over Time", plotOutput("likerank_time_trend")),
    tabPanel("# Users Over Time", plotOutput("user_time_trend"))
    
  )
)


# Server -----------------------------------------------------------------------

# Define server logic required to draw a histogram
server <- function(input, output) {
  
  source("../conn.R")
  source("get_data.R")
  
  # Output for Names Tab -------------------------------------------------------
  
  single_name <- reactive({
    left_join(data.frame(name = as.character(input$name)),
    filter(name_popularity, name == as.character(input$name)))
  })

  output$single_name <- renderTable(
    
    single_name()
  )
  
  output$plot_name_seen <- renderPlot({
    
    ggplot(data[["name_popularity"]], 
           aes(times_liked)) +
      geom_histogram() +
      labs(title = "Histogram of Name Likes",
           y = "Count of Names", 
           x = "Number of Times Liked") +
      theme_minimal()
  })
  
  output$plot_name_like <- renderPlot({
    
    ggplot(data[["name_popularity"]], 
           aes(times_seen)) +
      geom_histogram() +
      labs(title = "Histogram of Name Views",
           y = "Count of Names", 
           x = "Number of Times Seen") +
      theme_minimal()
  })
  
  output$plot_name_popularity <- renderPlot({
    
    ggplot(data[["name_popularity"]], 
           aes(popularity)) +
      geom_histogram() +
      labs(title = "Histogram of Name Popularity",
           y = "Count of Names", 
           x = "Likes / Views") +
      theme_minimal()
  })
  
  
  # Output for Rankings/Likes Tab ----------------------------------------------
  
  output$hist_view <- renderPlot({
    
    ggplot(data[["user_selections"]], 
           aes(names_ranked)) +
      geom_histogram() +
      labs(title = "Number of Names Viewed by Site Users",
           y = "Count of Users", 
           x = "Number of Names Viewed") +
      theme_minimal()
  })
  
  output$hist_like <- renderPlot({
    
    ggplot(data[["user_selections"]], 
           aes(names_liked)) +
      geom_histogram() +
      labs(title = "Number of Names Liked by Site Users",
           y = "Count of Users", 
           x = "Number of Names Liked") +
      theme_minimal()
  })
  
  output$scatter_rank_like <- renderPlot({
    
    ggplot(data[["user_selections"]], 
           aes(x=names_ranked, y = names_liked)) +
      geom_point() +
      theme_minimal() +
      labs(y = "Names Liked", 
           x = "Names Ranked", 
           title = "Relationship between Names Ranked and Liked")
  })
  
  # Output for Partners/Matches ------------------------------------------------
  
  output$plot_num_partners <- renderPlot({
    
    ggplot(data[["partners"]], 
           aes(n)) +
      geom_histogram() +
      labs(title = "Distribution of Number of Partners Per User",
           y = "Count of Users", 
           x = "Number of Partners") +
      theme_minimal()
  })
  
  output$plot_num_matches <- renderPlot({
    
    ggplot(data[["matches"]] %>% count(uuid), 
           aes(n)) +
      geom_histogram() +
      labs(title = "Distribution of Number of Matches Per User", 
           y = "Count of Users", 
           x = "Number of Matches") +
      theme_minimal()
  })
  
  # Output for Usage Tab -------------------------------------------------------
  output$user_time_trend <- renderPlot({
    
    ggplot(data[["byMonth"]] %>% pivot_longer(cols = c(new_users, active_users)), 
           aes(x=month, y = value, group = name, color = name)) +
      geom_line() +
      theme_minimal() +
      labs(y = "Number of Users", 
           x = "Time", 
           title = "New/Active Users by Month")
  })
  
  output$likerank_time_trend <- renderPlot({
    
    ggplot(data[["byMonth"]] %>% pivot_longer(cols = c(views, likes)), 
           aes(x=month, y = value, group = name, color = name)) +
      geom_line() +
      theme_minimal() +
      labs(y = "Number of Names", 
           x = "Month", 
           title = "Names Viewed and Liked Over Time")
  })
}

# Run the application 
shinyApp(ui = ui, server = server)