library(shiny)
library(ggplot2)

# Define UI for application that draws a histogram
ui <- fluidPage(
   
   # Application title
   titlePanel("Name Trends 1880-2017"),
   
  h4( "Here you can compare up to four names at a time. These charts are designed to compare trends, so please note that y axis may vary across names."),
  # "The four most popular names from 1880 are shown at load.",
   
   # Sidebar with a slider input for number of bins 
   sidebarLayout(
      sidebarPanel(
         textInput("name1",
                     "Name:",
                     value="Waldo"),
         textInput("name2",
                   "Name:",
                   value=""),
         textInput("name3",
                   "Name:",
                   value=""),
         textInput("name4",
                   "Name:",
                   value=""),

	 helpText("Here you can compare up to four names at a time. These charts are designed to compare trends, so please note that the y axis may vary across names.")

      ),
      
      # Show a plot of the generated distribution
      mainPanel(
         plotOutput("timePlot")
      )
   )
)

# Define server logic required to draw a histogram
server <- function(input, output) {
  df <- readRDS("name_data_decade.rds") 
  
  output$timePlot <- renderPlot({
    names <- c(input$name1, input$name2, input$name3, input$name4)
    
    ggplot(df[df$Name %in% names,], 
           aes(x=Decade, y=rp1000, color=Sex)) +
      facet_wrap(~Name, scales="free") +
      geom_line() +
      labs(y = "Name Frequency Per 1000 Births",
           color = "Gender") +
      theme_minimal() +
      theme(strip.text.x = element_text(size = 14))
  })
}

# Run the application 
shinyApp(ui = ui, server = server)
