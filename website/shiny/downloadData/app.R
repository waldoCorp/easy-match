library(shiny)
library(DBI)
library(RPostgres)

# Define UI for data download app ----
ui <- fluidPage(
  
  # App title ----
  titlePanel("View and Download Your Data"),
  
  # Sidebar layout with input and output definitions ----
  sidebarLayout(
    
    # Sidebar panel for inputs ----
    sidebarPanel(
      
      # Input: Choose dataset ----
      selectInput("dataset", "Choose a dataset:",
                  choices = c(
                    "Account Data", 
                    "Friend List", 
                    "Name Selections", 
                    "Name Matches")),
      
      # Button
      downloadButton("downloadData", "Download")
      
    ),
    
    # Main panel for displaying outputs ----
    mainPanel(
      
      DT::dataTableOutput("table")      
    )
    
  )
)


server <- function(input, output) {
  
  source("../test-conn.R")
  source("sql.R")
  source("../token_to_uuid.R")
  uuid <- NULL
  
  # Convert query string token to uuid  
  uuid <- reactive({
    queryString <- getQueryString()
    token <- queryString[["token"]]
    uuid <- token_to_uuid(token, "data_tokens", con) # note that this deletes the token from the db
    uuid    
  })    
  
  # Reactive value for selected dataset ----
  datasetInput <- reactive({
    
    # displayed dataset
    switch(input$dataset,
           "Account Data" = dbGetQuery(con,            get_user(uuid())),
           "Friend List"  = dbGetQuery(con,        get_partners(uuid())),
           "Name Selections"   = dbGetQuery(con, get_selections(uuid())),
           "Name Matches" = dbGetQuery(con,         get_matches(uuid())))
  })
  
  # Table of selected dataset ----
  output$table <- DT::renderDataTable({
    datasetInput()
  })
  
  # Downloadable csv of selected dataset ----
  output$downloadData <- downloadHandler(
    filename = function() {
      paste(input$dataset, ".csv", sep = "")
    },
    content = function(file) {
      write.csv(datasetInput(), file, row.names = FALSE)
    }
  )
  
}

# Create Shiny app ----
shinyApp(ui, server)
