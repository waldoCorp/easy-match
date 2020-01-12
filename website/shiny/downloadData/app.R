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
  source("../check_valid_token.R")
  
  # Reactive value for selected dataset ----
  datasetInput <- reactive({
	
    # Get query string info
    queryString <- getQueryString()
    uuid <- paste0("'", queryString["uuid"], "'")
    token <- paste0("'", queryString["token"], "'")

    # Check for valid token
    token_check <- dbGetQuery(con, check_valid_token(uuid, token, "data_tokens"))
 #   if(token_check$count != 1) {stop("You don't have permission to access this page, try accessing again from your account page or contact us at contact@waldocorp.com if the problem persists")}
     if(token_check$count != 1) {stop(paste("invalid access uuid =", uuid, "token =", token ))}  

    switch(input$dataset,
           
          "Account Data" = dbGetQuery(con,            get_user(uuid)),
          "Friend List"  = dbGetQuery(con,        get_partners(uuid)),
          "Name Selections"   = dbGetQuery(con, get_selections(uuid)),
          "Name Matches" = dbGetQuery(con,         get_matches(uuid)))
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
