# 19/10/2020 | beta 1.0.4 (Par Piug)
- Ajout de la traduction en Anglais
- Ajout d'une courte description des commandes dans la configuration

# 29/08/2020 | Stable 1.0.3
- Passage en stable

# 27/08/2020 | Beta 1.0.3

- Suppression à la règle qui annonce la prédiction de l'heure d'après à 50.
- Texte prédictif : Correction si précipitation min et max sont identiques.
- Texte prédictif : Ajustement des espaces en fin de phrase.
- Affichage la version dans la page de configuration.
- Ajout de logs

# 25/08/2020 | Beta 1.0.2

- BUG : Correction sur les alertes et plus particulièrement sur le cumul avec le vent.
- BUG : Correction d'une faute de frappe dans le message
- Les alertes météos sont prioritaires à celles du vent
- Modification du widget quand il n'y a pas de précipitation
- Amélioration des logs en mode debug 

# 03/07/2020 | Stable 1.0.1

- BUG : Dans le message prédictif, l'heure annoncée était l'heure de fin de l'alerte

# 01/07/2020 | Stable 1

- Passage en stable
- BUG : Fix PHP Warning - number_format() expects parameter 1 to be float
- BUG : Fix PHP Notice - Undefined property: stdClass::$errors

# 30/06/2020 | Beta 1.2

- BUG : Priorisation des alertes par rapport aux alertes vent
- BUG : Correction du décompte du temps d'alerte
- Possibilité d'installer sur une V3 pour test
- Fix décalage config en V3

# 05/06/2020 | Beta 1.1

- BUG : Récupération du type d'alerte trop tard entrainant une icone vent au lieu de la pluie
- Enregistrement des configurations à defauts si pas enregitrées lors de l'installation
- Ajout de la commande Type_degre

# 01/06/2020 | Beta 1

- Première version du plugin previsy 
- Documentation Fr