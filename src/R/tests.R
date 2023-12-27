library(testthat)
source("calculs.R")

# Tests
test_that("Test de la fonction calcule_proba", {
  # Créer des tickets de test
  libelles_valides <- c(
    "all",
    "Problème matériel", "Problème logiciel", "Problème Réseau",
    "Problème de sécurité", "Problème d'accès et d'authentification",
    "Problème de Performances", "Problème de serveur", "Problème de sauvegarde"
  )

  tickets <- list(
    Ticket("Problème logiciel", "Utilisateur1", 2, "Ouvert", as.POSIXct("2022-01-01")),
    Ticket("Problème logiciel", "Utilisateur2", 3, "En cours de traitement", as.POSIXct("2022-02-01")),
    Ticket("Problème matériel", "Utilisateur3", 1, "Fermée", as.POSIXct("2022-03-01")),
    Ticket("Problème Réseau", "Utilisateur4", 4, "Ouvert", as.POSIXct("2022-04-01")),
    Ticket("Problème de sécurité", "Utilisateur5", 2, "Fermée", as.POSIXct("2022-05-01")),
    Ticket("Problème d'accès et d'authentification", "Utilisateur6", 3, "En cours de traitement", as.POSIXct("2022-06-01")),
    Ticket("Problème de Performances", "Utilisateur7", 1, "Fermée", as.POSIXct("2022-07-01")),
    Ticket("Problème de serveur", "Utilisateur8", 4, "Ouvert", as.POSIXct("2022-08-01")),
    Ticket("Problème de sauvegarde", "Utilisateur9", 2, "Fermée", as.POSIXct("2022-09-01")),
    Ticket("Problème matériel", "Utilisateur10", 3, "En cours de traitement", as.POSIXct("2022-10-01"))
  )

  # Test pour libellé "all"
  expect_equal(calcule_proba(tickets, "all", as.Date("2022-01-01"), as.Date("2022-12-31")), 1)

  # Test pour libellé inexistant
  expect_equal(calcule_proba(tickets, "manger une choucroute", as.Date("2022-01-01"), as.Date("2022-12-31")), 0)

  # Test pour libellé existant
  expect_equal(calcule_proba(tickets, "Problème logiciel", as.Date("2022-01-01"), as.Date("2022-12-31")), 2/10)
})

test_that("Test de la fonction calcule_nombre", {
  # Créer des tickets de test manuellement
  tickets <- list(
    Ticket("Problème logiciel", "Utilisateur1", 2, "Ouvert", as.POSIXct("2022-01-01")),
    Ticket("Problème matériel", "Utilisateur2", 3, "En cours de traitement", as.POSIXct("2022-01-31")),
    Ticket("Problème matériel", "Utilisateur3", 1, "Fermée", as.POSIXct("2022-02-01")),
    Ticket("Problème logiciel", "Utilisateur4", 4, "Ouvert", as.POSIXct("2022-02-28")),
    Ticket("Problème de sécurité", "Utilisateur5", 2, "Fermée", as.POSIXct("2022-03-01")),
    Ticket("Problème d'accès et d'authentification", "Utilisateur6", 3, "En cours de traitement", as.POSIXct("2022-03-31")),
    Ticket("Problème de Performances", "Utilisateur7", 1, "Fermée", as.POSIXct("2022-04-01")),
    Ticket("Problème de serveur", "Utilisateur8", 4, "Ouvert", as.POSIXct("2022-04-30")),
    Ticket("Problème de sauvegarde", "Utilisateur9", 2, "Fermée", as.POSIXct("2022-05-01")),
    Ticket("Problème matériel", "Utilisateur10", 3, "En cours de traitement", as.POSIXct("2022-05-31")),
    Ticket("Problème logiciel", "Utilisateur11", 2, "Ouvert", as.POSIXct("2022-06-01")),
    Ticket("Problème logiciel", "Utilisateur12", 3, "En cours de traitement", as.POSIXct("2022-06-30")),
    Ticket("Problème logiciel", "Utilisateur13", 2, "Ouvert", as.POSIXct("2022-07-01")),
    Ticket("Problème logiciel", "Utilisateur14", 3, "En cours de traitement", as.POSIXct("2022-07-31")),
    Ticket("Problème matériel", "Utilisateur15", 1, "Fermée", as.POSIXct("2022-08-01")),
    Ticket("Problème Réseau", "Utilisateur16", 4, "Ouvert", as.POSIXct("2022-08-31")),
    Ticket("Problème de sécurité", "Utilisateur17", 2, "Fermée", as.POSIXct("2022-09-01")),
    Ticket("Problème d'accès et d'authentification", "Utilisateur18", 3, "En cours de traitement", as.POSIXct("2022-09-30")),
    Ticket("Problème de Performances", "Utilisateur19", 1, "Fermée", as.POSIXct("2022-10-01")),
    Ticket("Problème de serveur", "Utilisateur20", 4, "Ouvert", as.POSIXct("2022-10-31")),
    Ticket("Problème de sauvegarde", "Utilisateur21", 2, "Fermée", as.POSIXct("2022-11-01")),
    Ticket("Problème matériel", "Utilisateur22", 3, "En cours de traitement", as.POSIXct("2022-11-30")),
    Ticket("Problème logiciel", "Utilisateur23", 2, "Ouvert", as.POSIXct("2022-12-01")),
    Ticket("Problème logiciel", "Utilisateur24", 3, "En cours de traitement", as.POSIXct("2022-12-31"))
  )

  resultat_attendu <- data.frame(Mois = c("2022-01"), NombreTickets = c(2.0))
  resultat_reel <- calcule_nombre(tickets, "all", as.Date("2022-01-01"), as.Date("2022-01-31"))

  print( resultat_reel)
  # Test d'égalité avec expect_equal
  if (!identical(resultat_reel, resultat_attendu)) {
    cat("Les résultats ne sont pas identiques :\n")
    stop("Test échoué.")
  } else {
    cat("Test réussi.")
  }

  resultat_attendu <- data.frame(Mois = c("2022-01","2022-02"), NombreTickets = c(2.0,2))
  resultat_reel <- calcule_nombre(tickets, "all", as.Date("2022-01-01"), as.Date("2022-02-28"))

  if (!identical(resultat_reel, resultat_attendu)) {
    cat("Les résultats ne sont pas identiques :\n")
    stop("Test échoué.")
  } else {
    cat("Test réussi.")
  }

  resultat_attendu <- data.frame(Mois = c("2022-01"), NombreTickets = c(0))
  resultat_reel <- calcule_nombre(tickets, "manger une choucroute", as.Date("2022-01-01"), as.Date("2022-01-31"))

  if (!identical(resultat_reel, resultat_attendu)) {
    cat("Les résultats ne sont pas identiques :\n")
    stop("Test échoué.")
  } else {
    cat("Test réussi.")
  }

  resultat_attendu <- data.frame(Mois = c("2022-01"), NombreTickets = c(1))
  resultat_reel <- calcule_nombre(tickets, "Problème logiciel", as.Date("2022-01-01"), as.Date("2022-01-31"))

  if (!identical(resultat_reel, resultat_attendu)) {
    cat("Les résultats ne sont pas identiques :\n")
    stop("Test échoué.")
  } else {
    cat("Test réussi.")
  }

  resultat_attendu <- data.frame(Mois = c("2022-01","2022-02"), NombreTickets = c(1,1))
  resultat_reel <- calcule_nombre(tickets, "Problème logiciel", as.Date("2022-01-01"), as.Date("2022-02-28"))

  if (!identical(resultat_reel, resultat_attendu)) {
    cat("Les résultats ne sont pas identiques :\n")
    stop("Test échoué.")
  } else {
    cat("Test réussi.")
  }

})

test_that("Test de la fonction calcule_loi_normale", {
  # Créer des tickets de test manuellement
  tickets <- list(
    Ticket("Problème logiciel", "Utilisateur1", 2, "Ouvert", as.POSIXct("2022-01-01")),
    Ticket("Problème logiciel", "Utilisateur1", 2, "Ouvert", as.POSIXct("2022-01-01")),
    Ticket("Problème matériel", "Utilisateur2", 3, "En cours de traitement", as.POSIXct("2022-01-31")),
    Ticket("Problème matériel", "Utilisateur2", 3, "En cours de traitement", as.POSIXct("2022-01-31")),
    Ticket("Problème matériel", "Utilisateur2", 3, "En cours de traitement", as.POSIXct("2022-01-31")),
    Ticket("Problème matériel", "Utilisateur3", 1, "Fermée", as.POSIXct("2022-02-01")),
    Ticket("Problème logiciel", "Utilisateur4", 4, "Ouvert", as.POSIXct("2022-02-28"))
  )

  resultat_attendu <- c(100,100)
  data = calcule_nombre(tickets, "Problème logiciel", as.Date("2022-01-01"), as.Date("2022-01-31"))
  moy = moy1(data$NombreTickets)
  sd = sd1(data$NombreTickets)
  resultat_reel <- calcule_loi_normale(moy,sd,data$NombreTickets)

  resultat_verification <- identical(resultat_reel, resultat_attendu)

  cat("Les vecteurs sont identiques et dans le même ordre :", resultat_verification, "\n")

  resultat_attendu <- c(33.33333,100)
  data = calcule_nombre(tickets, "all", as.Date("2022-01-01"), as.Date("2022-03-31"))
  moy = moy1(data$NombreTickets)
  sd = sd1(data$NombreTickets)
  resultat_reel <- calcule_loi_normale(moy,sd,data$NombreTickets)
  print(resultat_reel)

  resultat_verification <- all(sapply(seq_along(resultat_attendu), function(i) all.equal(resultat_reel[i],  resultat_attendu[i], tolerance = 1e-6)))

  cat("Les vecteurs sont identiques et dans le même ordre :", resultat_verification, "\n")
})
