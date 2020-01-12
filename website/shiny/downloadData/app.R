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
  
  source("../conn.R")
  source("sql.R")
  
  queryString <- getQueryString()
  uuid <- queryString["uuid"]
  token <- queryString["token"]
  
  # Reactive value for selected dataset ----
  datasetInput <- reactive({
    switch(input$dataset,
           
           "Account Data" = dbGetQuery(con,            get_user(paste0("'",uuid,"'"))),
           "Friend List"  = dbGetQuery(con,        get_partners(paste0("'",uuid,"'"))),
           "Name Selections"   = dbGetQuery(con, get_selections(paste0("'",uuid,"'"))),
           "Name Matches" = dbGetQuery(con,         get_matches(paste0("'",uuid,"'"))))
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
