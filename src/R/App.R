if (!require("shiny")) install.packages("shiny")
if (!require("ggplot2")) install.packages("ggplot2")
if (!require("lubridate")) install.packages("lubridate")

library(shiny)
library(ggplot2)
library(lubridate)

source("tickets.R")
source("calculs.R")

#' Vérifier les dates
#'
#' Cette fonction vérifie si le mois de début est inférieur ou égal au mois de fin.
#'
#' @param start_date Date de début (format : "YYYY-MM").
#' @param end_date Date de fin (format : "YYYY-MM").
#'
#' @return Retourne TRUE si le mois de début est inférieur ou égal au mois de fin, FALSE sinon.
#'
#'
verifier_dates <- function(start_date, end_date) {
  # Vérifier que le mois de début est inférieur ou égal au mois de fin
  if (start_date > end_date) {
    # Afficher un message d'erreur
    shiny::showModal(
      shiny::modalDialog(
        title = "Erreur",
        "Le mois de début doit être inférieur ou égal au mois de fin.",
        easyClose = TRUE
      )
    )
    return(FALSE)
  } else {
    return(TRUE)
  }
}

#' Traiter l'affichage des résultats d'analyse de tickets
#'
#' Cette fonction traite et affiche les résultats d'analyse de tickets en fonction des paramètres fournis.
#'
#' @param tickets Liste des tickets à analyser.
#' @param output Objet Shiny qui permet d'afficher des résultats dans une application Shiny.
#' @param start_date Date de début de la période d'analyse.
#' @param end_date Date de fin de la période d'analyse.
#' @param selected_libelle Libellé des tickets à analyser.
#' @param affichage_type Type d'affichage ("proba" pour la probabilité, "nombre" pour les statistiques).
#'
#' @return Aucune valeur de retour. Les résultats sont affichés dans l'application Shiny.
#'
traiter_affichage <- function(tickets, output, start_date, end_date, selected_libelle, affichage_type) {
  mois_debut <- format(start_date, "%b")
  mois_fin <- format(end_date, "%b")

  if (affichage_type == "proba") {
    proba = calcule_proba(tickets, selected_libelle, start_date, end_date)
    print("Affichage des probabilités")

    output$ticket_texte <- renderPrint({
      cat(paste("Votre analyse porte sur les tickets possédant le label", selected_libelle,
                "compris entre", mois_debut, "et", mois_fin), "\n")
      cat(paste("Probabilité :", proba), "\n")
    })
  } else {
    resultat_nombre <- calcule_nombre(tickets, selected_libelle, start_date, end_date)
    nbTotal = sum(resultat_nombre$NombreTickets)
    moyenne = moy1(resultat_nombre$NombreTickets)
    sd = sd1(resultat_nombre$NombreTickets)
    loiN = calcule_loi_normale(moyenne, sd, resultat_nombre$NombreTickets)

    output$ticket_texte <- renderPrint({
      cat(paste("Votre analyse porte sur les tickets possédant le label", selected_libelle,
                "compris entre", mois_debut, "et", mois_fin), "\n")
      cat(paste("Nombre de tickets :", nbTotal), "\n")
      cat(paste("Moyenne :", moyenne), "\n")
      cat(paste("Écart type  :", sd), "\n")
      loiN <- calcule_loi_normale(moyenne, sd, resultat_nombre$NombreTickets)
      cat(paste("Le pourcentage de valeurs comprises entre moyenne-ecart type et moyenne + ecart type est  :", loiN[1]), "\n")
      cat(paste("Le pourcentage de valeurs comprises entre moyenne-2ecart type et moyenne + 2ecart type est  :", loiN[2]), "\n")
    })

    # Utiliser ggplot2 pour créer un graphique
    output$ticket_plot <- renderPlot({
      ggplot(resultat_nombre, aes(x = Mois, y = NombreTickets)) +
        geom_bar(stat = "identity") +
        geom_hline(aes(yintercept = moyenne, color = "Moyenne"), linetype = "dashed", size = 1.5) +
        geom_hline(aes(yintercept = moyenne + sd, color = "Écart Type + Moyenne"), linetype = "dotted", size = 1.5) +
        geom_hline(aes(yintercept = moyenne - sd, color = "Écart Type - Moyenne"), linetype = "dotted", size = 1.5) +
        geom_hline(aes(yintercept = moyenne + 2 * sd, color = "2 Écart Types + Moyenne"), linetype = "dotted", size = 1.5) +
        geom_hline(aes(yintercept = moyenne - 2 * sd, color = "2 Écart Types - Moyenne"), linetype = "dotted", size = 1.5) +
        labs(title = "Nombre de tickets par mois", x = "Mois", y = "Nombre de tickets") +
        theme_minimal() +
        scale_color_manual("Légende",
                           values = c("Moyenne" = "blue",
                                      "Écart Type + Moyenne" = "green",
                                      "Écart Type - Moyenne" = "green",
                                      "2 Écart Types + Moyenne" = "red",
                                      "2 Écart Types - Moyenne" = "red"))
    })
  }
}


# Définir l'interface utilisateur (UI)
ui <- fluidPage(
  titlePanel("Analyse des tickets"),
  sidebarLayout(
    sidebarPanel(
      selectInput("start_month", "Sélectionnez le mois de début :",
                  choices = month.abb),
      selectInput("end_month", "Sélectionnez le mois de fin :",
                  choices = month.abb),
      selectInput("selected_libelle", "Sélectionnez le libellé :",
                  choices = libelles_valides),
      radioButtons("affichage", "Choisissez l'affichage :",
                   choices = c("Probabilités" = "proba", "Nombres" = "nombre"),
                   selected = "proba"),
      actionButton("valider_btn", "Valider")
    ),
    mainPanel(
      verbatimTextOutput("ticket_texte"),
      plotOutput("ticket_plot"),
      verbatimTextOutput("infos_texte"),

    )
  )
)
# Définir le serveur
server <- function(input, output) {
  observe({

    start_month <- input$start_month
    end_month <- input$end_month
    selected_libelle <- input$selected_libelle
    affichage_probabilites <- input$affichage

    output$infos_texte <- renderPrint({

      # Affichage des informations simulées
      cat("Le jeu de données est fictif et le nombre de tickets s'élève à", length(tickets_2022), "\n")
    })

  })

  observeEvent(input$valider_btn,
  {
    start_date <- as.Date(paste("2022", match(input$start_month, month.abb), "01", sep = "-"))
    end_date <- as.Date(paste("2022", match(input$end_month, month.abb), "01", sep = "-"))
    end_date <- ceiling_date(end_date, "month") - days(1)

    # Appele la fonction pour vérifier les dates
    if (verifier_dates(start_date, end_date))
    {
      output$ticket_plot <- renderPlot(NULL)
      output$ticket_texte <- renderPrint(NULL)
      # Appeler la fonction pour traiter l'affichage
      traiter_affichage(tickets_2022,output,start_date, end_date, input$selected_libelle, input$affichage)
    }
  })
}
# Exécuter l'application Shiny
shinyApp(ui, server)