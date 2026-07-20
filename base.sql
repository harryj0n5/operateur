
DROP TABLE IF EXISTS historique_transaction;
DROP TABLE IF EXISTS frais_operation;
DROP TABLE IF EXISTS configuration;
DROP TABLE IF EXISTS type_operation;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS type_user;




CREATE TABLE type_user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL
);


CREATE TABLE user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    telephone TEXT NOT NULL UNIQUE,
    type_user_id INTEGER NOT NULL,

    FOREIGN KEY (type_user_id)
        REFERENCES type_user(id)
);


CREATE TABLE configuration (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefix TEXT NOT NULL UNIQUE
);



CREATE TABLE type_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL
);



CREATE TABLE frais_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    montant_min REAL NOT NULL,
    montant_max REAL NOT NULL,
    frais REAL NOT NULL,
    type_operation_id INTEGER NOT NULL,

    FOREIGN KEY (type_operation_id)
        REFERENCES type_operation(id)
);



CREATE TABLE historique_transaction (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    frais REAL NOT NULL,
    date TEXT DEFAULT CURRENT_TIMESTAMP,
    user_id INTEGER NOT NULL,
    type_operation_id INTEGER NOT NULL,

    FOREIGN KEY (user_id)
        REFERENCES user(id),

    FOREIGN KEY (type_operation_id)
        REFERENCES type_operation(id)
);


INSERT INTO type_user(libelle) VALUES
('Operateur'),
('Client');


INSERT INTO user(telephone,type_user_id) VALUES
('0348632043',1),
('0331234567',2),
('0379876543',2);


INSERT INTO configuration(prefix) VALUES
('033'),
('037');


INSERT INTO type_operation(libelle) VALUES
('Depot'),
('Retrait'),
('Transfert');


INSERT INTO frais_operation
(montant_min, montant_max, frais, type_operation_id)
VALUES
(100, 1000, 50, 1),
(1001, 5000, 50, 1),
(5001, 10000, 100, 1),
(10001, 25000, 200, 1),
(25001, 50000, 400, 1),
(50001, 100000, 800, 1),
(100001, 250000, 1500, 1),
(250001, 500000, 1500, 1),
(500001, 1000000, 2500, 1),
(1000001, 2000000, 3000, 1);


