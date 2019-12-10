library(shiny)
library(ggplot2)

# Define UI for application that draws a histogram
ui <- fluidPage(
   
   # Application title
   titlePanel("Trends In Names From 1880-2017"),
   
   "Compare up to four names at a time.",
   "The four most popular names from 1880 are shown at load.",
   "Note that y axis varies across panels.",
   
   # Sidebar with a slider input for number of bins 
   sidebarLayout(
      sidebarPanel(
         textInput("name1",
                     "Name:",
                     value="Mary"),
         textInput("name2",
                   "Name:",
                   value="Anna"),
         textInput("name3",
                   "Name:",
                   value="Emma"),
         textInput("name4",
                   "Name:",
                   value="Elizabeth")
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
      ylab("Name Frequency Per 1000 Births")
  })
}

# Run the application 
shinyApp(ui = ui, server = server)
