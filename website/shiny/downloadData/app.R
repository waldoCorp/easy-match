#'  Copyright (c) 2020 Lief Esbenshade
#'
#'    This file is part of Easy Match.
#'
#'    Easy Match is free software: you can redistribute it and/or modify
#'    it under the terms of the GNU Affero General Public License as published by
#'    the Free Software Foundation, either version 3 of the License, or
#'    (at your option) any later version.
#'
#'    Easy Match is distributed in the hope that it will be useful,
#'    but WITHOUT ANY WARRANTY; without even the implied warranty of
#'    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#'    GNU Affero General Public License for more details.
#'
#'    You should have received a copy of the GNU Affero General Public License
#'    along with Easy Match.  If not, see <https://www.gnu.org/licenses/>.

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
  
  source("../con.R")
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
