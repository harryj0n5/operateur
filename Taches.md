# Examen Projet Final - S4 Info et Design Juillet 2026

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
  pseudo.
- Gestion des utilisateurs: création, modification, suppression des utilisateurs.
- Configuration des prefix et des numeros de telephone valides pour l'authentification.
- CRUD des frais et des transactions: création, lecture, mise à jour et suppression des frais et des transactions.
- Situation des transactions: visualisation de l'historique des transactions et gain via les frais.
- Situation des clients: soldes, historique des transactions, et autres informations pertinentes sur les clients.
- Operations clients:
    - Voir le solde (coté client) : calcul du solde en fonction des transactions et des frais appliqués.
    - Faire un depot: pas besoin de confirmation operateur, frais appliqué automatiquement.
    - Faire un retrait: pas besoin de confirmation operateur, frais appliqué automatiquement.
    - Faire un transfert: pas besoin de confirmation operateur, frais appliqué automatiquement.
    - Voir les historique des transactions: liste des transactions effectuées par le client avec les détails (date,
      montant, type de transaction, frais appliqués).

## Etape 4 : Creer les controllers et les routes

- Créer les controllers pour gérer les différentes fonctionnalités du projet.
- Définir les routes pour chaque fonctionnalité
- Assurer que les routes sont sécurisées et accessibles uniquement aux utilisateurs autorisés.

## Etape 5 : Creer les vues et les interfaces utilisateur

- Concevoir les interfaces utilisateur pour chaque fonctionnalité du projet.
- Assurer que les interfaces sont intuitives et faciles à utiliser.
- Intégrer les vues avec les controllers pour permettre l'interaction entre l'utilisateur et le système.