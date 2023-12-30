libelles_valides = c(
  "all",
  "Problème matériel", "Problème logiciel", "Problème Réseau",
  "Problème de sécurité", "Problème d'accès et d'authentification",
  "Problème de Performances", "Problème de serveur", "Problème de sauvegarde"
)

Ticket = function(libelle, utilisateur, niveau_urgence, etat, date_creation) {


  if (!(libelle %in% libelles_valides)) {
    stop("Libellé invalide. Les libellés valides sont : ", paste(libelles_valides, collapse = ", "))
  }

  # Vérification de la date
  annee_limite_inf = as.POSIXct("2021-12-31", format="%Y-%m-%d")
  annee_limite_sup = as.POSIXct("2023-01-01", format="%Y-%m-%d")
  if (date_creation < annee_limite_inf | date_creation > annee_limite_sup) {
    stop("Date de création invalide. La date doit être comprise entre le 1er janvier et le 31 décembre 2022.")
  }

  # Création de l'objet Ticket
  return(list(
    date_creation = date_creation,
    libelle = libelle,
    utilisateur = utilisateur,
    niveau_urgence = niveau_urgence,
    etat = etat
  ))
}

# Générer des tickets aléatoires pour toute l'année 2022

nb_tickets = 1000

tickets_2022 = lapply(1:nb_tickets, function(i)
{
  libelle = sample(libelles_valides, 1)
  utilisateur = paste("Utilisateur", i)
  niveau_urgence = sample(1:4, 1)
  etat = sample(c("En cours de traitement", "Fermée", "Ouvert"), 1)
  date_creation = as.POSIXct(sample(seq(as.Date("2022-01-01"), as.Date("2022-12-01"), by="month"), 1))


  Ticket(libelle, utilisateur, niveau_urgence, etat, date_creation)
})
