#' Calcule la probabilité d'occurrence d'un libellé spécifique parmi les tickets dans une plage de dates donnée.
#'
#' @param tickets Une liste de tickets à analyser.
#' @param libelle Le libellé spécifique pour lequel la probabilité doit être calculée. Utilisez "all" pour obtenir la probabilité totale.
#' @param dateDebut La date de début de la plage de dates à considérer.
#' @param dateFin La date de fin de la plage de dates à considérer.
#'
#' @return La probabilité d'occurrence du libellé spécifié dans la plage de dates donnée.
#'
calcule_proba = function(tickets, libelle, dateDebut, dateFin) {
  # Convertir les dates de début et de fin en format POSIXct
  dateDebut = as.POSIXct(dateDebut)
  dateFin = as.POSIXct(dateFin)

  # Initialiser le compteur des tickets adéquats et le compteur total des tickets dans la plage de dates
  ticket_apte = 0
  ticket_date = 0

  # Parcourir tous les tickets
  for (ticket in tickets) {
    # Vérifier si le ticket est dans la plage de dates spécifiée
    if ((ticket$date_creation >= dateDebut) && (ticket$date_creation <= dateFin)) {
      ticket_date = ticket_date + 1

      # Vérifier si le libellé du ticket correspond au libellé spécifié
      if (ticket$libelle == libelle) {
        ticket_apte = ticket_apte + 1
      }
    }
  }

  # Calculer la probabilité d'occurrence du libellé spécifié
  return(ticket_apte / ticket_date)
}

#' Calcule le nombre de tickets par mois pour un libellé spécifique dans une plage de dates donnée.
#'
#' @param tickets Une liste de tickets à analyser.
#' @param libelle Le libellé spécifique pour lequel le nombre de tickets doit être calculé. Utilisez "all" pour obtenir le nombre total.
#' @param dateDebut La date de début de la plage de dates à considérer.
#' @param dateFin La date de fin de la plage de dates à considérer.
#'
#' @return Un dataframe contenant le nombre de tickets par mois pour le libellé spécifié dans la plage de dates donnée.
#'
calcule_nombre = function(tickets, libelle, dateDebut, dateFin)
{
  dateDebut = as.POSIXct(dateDebut)
  dateFin = as.POSIXct(dateFin)

  # Générer tous les mois entre dateDebut et dateFin
  mois_entre_dates = seq(from = as.Date(cut(dateDebut, "month")), to = as.Date(cut(dateFin, "month")), by = "month")
  mois_entre_dates = unique(format(mois_entre_dates, "%Y-%m"))

  # Créer un dataframe résultat avec les mois et le nombre de tickets
  resultat = data.frame(Mois = mois_entre_dates, NombreTickets = 0)
  if (libelle == "all")
  {
    for (ticket in tickets)
    {
      if (ticket$date_creation >= dateDebut && ticket$date_creation <= dateFin)
      {
        mois_ticket = format(ticket$date_creation, "%Y-%m")
        resultat[resultat$Mois == mois_ticket, "NombreTickets"] = resultat[resultat$Mois == mois_ticket, "NombreTickets"] + 1
      }
    }
  }
  else
  {
    for (ticket in tickets)
    {
      if (ticket$date_creation >= dateDebut && ticket$date_creation <= dateFin && ticket$libelle == libelle)
      {
        mois_ticket = format(ticket$date_creation, "%Y-%m")
        resultat[resultat$Mois == mois_ticket, "NombreTickets"] = resultat[resultat$Mois == mois_ticket, "NombreTickets"] + 1
      }
    }

  }

  # Parcourir les tickets et remplir le dataframe résultat

  return(resultat)
}

#' Calcule la moyenne d'un vecteur.
#'
#' @param a Le vecteur pour lequel la moyenne doit être calculée.
#'
#' @return La moyenne du vecteur.
#'
moy1 = function(a) sum(a) / length(a)

#' Calcule la variance d'un vecteur.
#'
#' @param a Le vecteur pour lequel la variance doit être calculée.
#'
#' @return La variance du vecteur.
#'
var1 = function(a) moy1(a**2) - (moy1(a)**2)

#' Calcule l'écart-type d'un vecteur.
#'
#' @param a Le vecteur pour lequel l'écart-type doit être calculé.
#'
#' @return L'écart-type du vecteur.
#'
sd1 = function(a) sqrt(var1(a))


#' Calcule les pourcentages d'occurrence des valeurs dans l'intervalle [moyenne - sd1, moyenne + sd1]
#' et [moyenne - 2 * sd1, moyenne + 2 * sd1] pour un ensemble de données.
#'
#' @param moyenne La moyenne de l'ensemble de données.
#' @param sd1 L'écart-type de l'ensemble de données.
#' @param ticketsMois Le vecteur des valeurs pour lesquelles la loi normale doit être calculée.
#'
#' @return Un vecteur contenant les pourcentages d'occurrence dans les deux intervalles spécifiés.
#'
calcule_loi_normale = function(moyenne, sd1, ticketsMois) {
  loiN1 = 0
  loiN2 = 0
  for (mois in ticketsMois) {
    if ((mois > moyenne - sd1) && (mois < moyenne + sd1)) {
      loiN1 = loiN1 + 1
    }
    if ((mois > moyenne - 2 * sd1) && (mois < moyenne + 2 * sd1)) {
      loiN2 = loiN2 + 1
    }
  }
  loi = c((loiN1 / length(ticketsMois) * 100), (loiN2 / length(ticketsMois) * 100))
  return(loi)
}
