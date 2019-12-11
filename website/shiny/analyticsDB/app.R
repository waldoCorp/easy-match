library(shiny)
library(tidyverse)
library(DBI)
library(RPostgres)

# Define UI for application that draws a histogram
ui <- fluidPage(
  
  titlePanel("Site Analytics Dashboard"),
  
  navlistPanel(
    
    "Stats by Name",
    tabPanel("Name Lookup",
             textInput("name",
                       "Name:",
                       value="Waldo"),
             textOutput("single_name")),
    tabPanel("View Distribution", plotOutput("plot_name_seen")),
    tabPanel("Like Distribution", plotOutput("plot_name_like")),
    tabPanel("Popularity", plotOutput("plot_name_popularity")),
    tabPanel("Totals"),
    
    "Stats by User",
    tabPanel("Number of Names Ranked", plotOutput("hist_rank")),
    tabPanel("Number of Names Liked", plotOutput("hist_like")),
    tabPanel("Names Ranked vs Liked", plotOutput("scatter_rank_like")),
    tabPanel("Totals"),
    
    "Partners/Matches",
    tabPanel("Number of Partners", plotOutput("plot_num_partners")),
    tabPanel("Number of Matches", plotOutput("plot_num_matches")), 
    
    "Usage"
    
  )
)
# Define server logic required to draw a histogram
server <- function(input, output) {
  
  # Connect to database
  con <-  DBI::dbConnect(
    RPostgres::Postgres(), 
    dbname = 'namedb', 
    user = 'namebot', 
    host = '127.0.0.1',
    port = 9000)
  
  
  # Get data tables ------------------------------------------------------------
  selections <- RPostgres::dbReadTable(con, "selections")
  partners <- RPostgres::dbReadTable(con, "partners")
  
  name_popularity <- selections %>% 
    group_by(name) %>% 
    summarize(times_seen = n(), 
              times_liked = sum(selected),
              popularity = mean(selected))
  
  user_selections <- selections %>% 
    group_by(uuid) %>% 
    summarize(names_ranked = n(), 
              names_liked = sum(selected))
  
  
  get_match <- function(uuid) {
    
    my_selected <- selections %>% filter(uuid == !!uuid, selected == 1)
    my_partners <- partners %>% filter(uuid == !!uuid) %>% pull(partner_uuid)
    
    partner_selected <- selections %>% 
      filter(uuid %in% !!my_partners, selected == 1) %>% 
      select(partner = uuid, name)
    
    
    return(my_selected %>% 
             inner_join(partner_selected, 
                        by = 'name') %>% 
             select(uuid, partner, name))
  }
  
  # Output for Names Tab -------------------------------------------------------
  
  single_name <- reactive({
    selections[selections$name == input$name, "selected"]
  })
  output$single_name <- renderText(
    paste0(
      "Times Seen: ", length(single_name()), "\n",
      "Times Liked: ", sum(single_name()), "\n",
      "Like Ratio: ", round(sum(single_name())/length(single_name()),2), "\n"
    )
  )
  
  output$plot_name_seen <- renderPlot({
    ggplot(name_popularity, 
           aes(times_liked)) +
      geom_histogram() +
      labs(title = "Histogram of Name Likes",
           y = "Count of Names", 
           x = "Number of Times Liked") +
      theme_minimal()
  })
  
  output$plot_name_like <- renderPlot({
    ggplot(name_popularity, 
           aes(times_seen)) +
      geom_histogram() +
      labs(title = "Histogram of Name Views",
           y = "Count of Names", 
           x = "Number of Times Seen") +
      theme_minimal()
  })
  
  output$plot_name_popularity <- renderPlot({
    ggplot(name_popularity, 
           aes(popularity)) +
      geom_histogram() +
      labs(title = "Histogram of Name Popularity",
           y = "Count of Names", 
           x = "Likes / Views") +
      theme_minimal()
  })
  
  # Output for Rankings/Likes Tab ----------------------------------------------
  
  output$hist_rank <- renderPlot({
    ggplot(user_selections, 
           aes(names_ranked)) +
      geom_histogram() +
      labs(title = "Number of Names Ranked by Site Users",
           y = "Count of Users", 
           x = "Number of Names Ranked") +
      theme_minimal()
  })
  
  output$hist_like <- renderPlot({
    ggplot(user_selections, 
           aes(names_liked)) +
      geom_histogram() +
      labs(title = "Number of Names Liked by Site Users",
           y = "Count of Users", 
           x = "Number of Names Liked") +
      theme_minimal()
  })
  
  output$scatter_rank_like <- renderPlot({
    
    ggplot(user_selections, 
           aes(x=names_ranked, y = names_liked)) +
      geom_point() +
      theme_minimal() +
      labs(y = "Names Liked", 
           x = "Names Ranked", 
           title = "Relationship between Names Ranked and Liked")
  })
  
  # Output for Partners/Matches ------------------------------------------------
  
  output$plot_num_partners <- renderPlot({
    ggplot(partners %>% group_by(uuid) %>% count(), 
           aes(n)) +
      geom_bar() +
      labs(title = "Distribution of Number of Partners",
           y = "Count of Users", 
           x = "Number of Partners") +
      theme_minimal()
  })
  
  output$plot_num_matches <- renderPlot({
    matches <- unique(selections$uuid) %>% 
      map_dfr(get_match)

    ggplot(matches %>% count(uuid), 
           aes(n)) +
      geom_histogram() +
      labs(title = "Distribution of Number of Matches", 
           y = "Count of Users", 
           x = "Number of Matches") +
      theme_minimal()
  })
  
  # Output for Usage Tab -------------------------------------------------------
  
}

# Run the application 
shinyApp(ui = ui, server = server)