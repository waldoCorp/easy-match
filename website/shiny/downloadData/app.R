library(shiny)
library(DBI)
library(RPostgres)

# Define UI for data download app ----
ui <- fluidPage(
  
  HTML(' <input type="text" id="uuid" name="uuid" style="display: none;">'), 
  includeScript("../get_uuid.js"), 
  
  # App title ----
  titlePanel("Downloading Data"),
  
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
      
      tableOutput("table")
      
    )
    
  )
)


server <- function(input, output) {
  
  source("../conn.R")
  source("sql.R")
  
  # Reactive value for selected dataset ----
  datasetInput <- reactive({
    switch(input$dataset,
           "Account Data" = dbGetQuery(con, get_user(uuid)),
           "Friend List"  = dbGetQuery(con, get_partners(uuid)),
           "Name Selections"   = dbGetQuery(con, get_selections(uuid)), 
           "Name Matches" = dbGetQuery(con, get_matches(uuid)))
  })
  
  # Table of selected dataset ----
  output$table <- renderTable({
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
