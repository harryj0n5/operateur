DROP TABLE IF EXISTS historique_transaction;
DROP TABLE IF EXISTS frais_operation;
DROP TABLE IF EXISTS configuration;
DROP TABLE IF EXISTS type_operation;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS type_user;

CREATE TABLE type_user
(
    id      INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL
);

CREATE TABLE user
(
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    telephone    TEXT    NOT NULL UNIQUE,
    type_user_id INTEGER NOT NULL,

    FOREIGN KEY (type_user_id)
        REFERENCES type_user (id)
);

CREATE TABLE operateur
(
    id                INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle           TEXT    NOT NULL,
    principale        BOOLEAN NOT NULL DEFAULT 0,
    pourcentage_frais REAL    NOT NULL DEFAULT 0
);

CREATE TABLE configuration
(
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    prefix       TEXT    NOT NULL UNIQUE,
    operateur_id INTEGER NOT NULL,
    FOREIGN KEY (operateur_id)
        REFERENCES operateur (id)
);

CREATE TABLE type_operation
(
    id      INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL
);

CREATE TABLE frais_operation
(
    id                INTEGER PRIMARY KEY AUTOINCREMENT,
    montant_min       REAL    NOT NULL,
    montant_max       REAL    NOT NULL,
    frais             REAL    NOT NULL,
    type_operation_id INTEGER NOT NULL,

    FOREIGN KEY (type_operation_id)
        REFERENCES type_operation (id)
);

CREATE TABLE historique_transaction
(
    id                   INTEGER PRIMARY KEY AUTOINCREMENT,
    montant              REAL    NOT NULL,
    frais                REAL    NOT NULL DEFAULT 0,
    type_mouvement       TEXT    NOT NULL CHECK (type_mouvement IN ('credit', 'debit')),
    date                 TEXT             DEFAULT CURRENT_TIMESTAMP,
    user_id              INTEGER NOT NULL,
    destinataire_numero  TEXT,
    type_operation_id    INTEGER NOT NULL,
    frais_retrait_inclus BOOLEAN NOT NULL DEFAULT 0,

    FOREIGN KEY (user_id)
        REFERENCES user (id),
    FOREIGN KEY (type_operation_id)
        REFERENCES type_operation (id)
);


INSERT INTO type_user(libelle)
VALUES ('Operateur'),
       ('Client');

INSERT INTO user(telephone, type_user_id)
VALUES ('0338632043', 1), -- operateur
       ('0331234567', 2), -- client de test avec solde initial
       ('0379876543', 2); -- client de test avec solde initial

INSERT INTO operateur(libelle, principale, pourcentage_frais)
VALUES ('Orange Money', 1, 0),
       ('Yas', 0, 0.03),
       ('Airtel', 0, 0.04);

INSERT INTO configuration(prefix, operateur_id)
VALUES ('033', 1),
       ('037', 1);

INSERT INTO type_operation(libelle)
VALUES ('Depot'),
       ('Retrait'),
       ('Transfert');

INSERT INTO frais_operation (montant_min, montant_max, frais, type_operation_id)
VALUES (100, 1000, 50, 2),
       (1001, 5000, 50, 2),
       (5001, 10000, 100, 2),
       (10001, 25000, 200, 2),
       (25001, 50000, 400, 2),
       (50001, 100000, 800, 2),
       (100001, 250000, 1500, 2),
       (250001, 500000, 1500, 2),
       (500001, 1000000, 2500, 2),
       (1000001, 2000000, 3000, 2),

       (100, 1000, 50, 3),
       (1001, 5000, 50, 3),
       (5001, 10000, 100, 3),
       (10001, 25000, 200, 3),
       (25001, 50000, 400, 3),
       (50001, 100000, 800, 3),
       (100001, 250000, 1500, 3),
       (250001, 500000, 1500, 3),
       (500001, 1000000, 2500, 3),
       (1000001, 2000000, 3000, 3);