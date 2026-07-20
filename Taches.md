# Examen Projet Final - S4 Info et Design Juillet 2026

# Version 1

## Etape 1 : Conception MCD du projet

- Identifier les entités principales du projet. (Tous)
- Définir les relations entre les entités. (Tous)
- Créer un diagramme MCD (Modèle Conceptuel de Données) pour représenter visuellement les entités et leurs relations. (
  Juan)
- Assurer que le MCD respecte les règles de normalisation pour éviter les redondances et les anomalies. (Tous)

## Etape 2 : Creer les entity dans le projet

- Créer les classes d'entités correspondant aux entités identifiées dans le MCD. (Harry)
- Définir les attributs et les méthodes pour chaque classe d'entité. (Harry)
- Implémenter les relations entre les entités en utilisant des associations, des agrégations ou des compositions selon
  les besoins du projet. (Harry)
- Ajouter des contraintes et des validations pour garantir l'intégrité des données. (prefix, etc) (Harry)

## Etape 3 : Definir les metiers et les services

- Login: automatique via un numero de telephone valide, pas d'inscription, pas de mot de passe, pas de mail, pas de
  pseudo. (Juan)
- Gestion des utilisateurs: création, modification, suppression des utilisateurs. (Harry)
- Configuration des prefix et des numeros de telephone valides pour l'authentification. (Harry)
- CRUD des frais et des transactions: création, lecture, mise à jour et suppression des frais et des transactions. (
  Harry)
- Situation des transactions: visualisation de l'historique des transactions et gain via les frais. (Harry)
- Situation des clients: soldes, historique des transactions, et autres informations pertinentes sur les clients. (
  Harry)
- Operations clients: (Juan)
    - Voir le solde (coté client) : calcul du solde en fonction des transactions et des frais appliqués.
    - Faire un depot: pas besoin de confirmation operateur, frais appliqué automatiquement.
    - Faire un retrait: pas besoin de confirmation operateur, frais appliqué automatiquement.
    - Faire un transfert: pas besoin de confirmation operateur, frais appliqué automatiquement.
    - Voir les historique des transactions: liste des transactions effectuées par le client avec les détails (date,
      montant, type de transaction, frais appliqués).

## Etape 4 : Creer les controllers et les routes

- Créer les controllers pour gérer les différentes fonctionnalités du projet. (Tous)
- Définir les routes pour chaque fonctionnalité (Tous)
- Assurer que les routes sont sécurisées et accessibles uniquement aux utilisateurs autorisés. (Juan)

## Etape 5 : Creer les vues et les interfaces utilisateur

- Concevoir les interfaces utilisateur pour chaque fonctionnalité du projet. (Tous)
- Assurer que les interfaces sont intuitives et faciles à utiliser. (Tous)
- Intégrer les vues avec les controllers pour permettre l'interaction entre l'utilisateur et le système. (Tous)

=====================================================================================================================

# Version 2

## Etape 1 : Conception MCD du projet

- Ajouter une table operateur
- Ajouter une colonne "operateur_id" dans la table configuration pour lier chaque prefix à un opérateur
  spécifique.
- Ajouter une colonne "operateur_id" dans la table transactions pour lier chaque transaction à un opérateur spécifique.
- Ajouter une colonne boolean "frais_retrait_inclus" dans la table transactions pour indiquer si les frais de retrait
  sont inclus dans le montant du retrait ou non.
- Au lieu de destinataire_id, ajouter une colonne "destinataire_numero" dans la table transactions pour stocker le
  numéro de téléphone du destinataire du transfert.

## Etape 2 : Creer les entity dans le projet

- Modifier la classe Configuration pour inclure l'attribut "operateur_id" et les méthodes associées pour gérer cette
  relation.
- Modifier la classe Transaction pour inclure l'attribut "operateur_id", "frais retrait_inclus" et "
  destinataire_numero", ainsi que les méthodes associées pour gérer ces relations et attributs.
- Créer la classe Operateur avec les attributs nécessaires (nom, code, etc.) et les méthodes associées pour gérer les
  opérateurs.

## Etape 3 : Definir les metiers et les services

- Gestion des opérateurs: création, modification, suppression des opérateurs. (Juan)
- Gestion des configurations: création, modification, suppression des configurations avec l'association à un opérateur
  spécifique. (Harry)
- Gestion des transactions: création, lecture, mise à jour et suppression des transactions avec l'association à un
  opérateur spécifique et la gestion des frais de retrait inclus ou non.