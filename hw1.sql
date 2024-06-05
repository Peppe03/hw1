CREATE DATABASE hw1;
USE hw1;

CREATE TABLE UTENTI(
	id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    name VARCHAR(255) NOT NULL,
    surname VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE CARRELLI(
	id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    ID_utente INTEGER NOT NULL,
    INDEX idx_utente(ID_utente),
    FOREIGN KEY(ID_utente) REFERENCES UTENTI(ID)
);

CREATE TABLE PRODOTTI(
	id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    img VARCHAR(255) NOT NULL,
    prezzo DECIMAL(10,2) NOT NULL
);

CREATE TABLE ARTICOLI_CARRELLO(
	id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    ID_carrello INTEGER NOT NULL,
    ID_prodotto INTEGER NOT NULL,
    quantita INTEGER NOT NULL,
    INDEX idx_carrello(ID_carrello),
    FOREIGN KEY(ID_carrello) REFERENCES CARRELLI(ID),
    INDEX idx_prodotto(ID_prodotto),
    FOREIGN KEY(ID_prodotto) REFERENCES PRODOTTI(ID)
);

INSERT INTO `UTENTI` VALUES (3, 'ciao1', 'ciao2', 'ciao@gmail.com', '$2y$10$Xs5hAuk3Na9emaclCoaOnuB1cgQfdYfMvuCxwlribHYzWE2eZa9.m');

INSERT INTO `prodotti` (`id`, `nome`, `img`, `prezzo`) VALUES
(1, 'Gran Crispy McBacon®', '/progetti/hw1/assets/prodotti/isolated--gran-crispymcbacon_1.png', 7.00),
(2, 'Crispy McBacon®', '/progetti/hw1/assets/prodotti/isolated--crispymcbacon_0.png', 5.90),
(3, 'Big Mac®', '/progetti/hw1/assets/prodotti/big-mac-isolated.png', 5.60),
(4, 'McChicken®', '/progetti/hw1/assets/prodotti/mcchicken--hero-isolated.png', 5.60),
(5, 'Double Cheeseburger', '/progetti/hw1/assets/prodotti/double-cheeseburger--isolated.png', 2.90),
(6, 'Double Chicken BBQ', '/progetti/hw1/assets/prodotti/double-chicken-bbq--hero-isolated.png', 2.90),
(7, 'Gran Crispy McBacon® Menu', '/progetti/hw1/assets/prodotti/isolated--menu-gran-crispymcbacon_0.png', 10.10),
(8, 'Crispy McBacon® Menu', '/progetti/hw1/assets/prodotti/isolated--menu-crispymcbacon.png', 9.00),
(9, 'Big Mac® Menu', '/progetti/hw1/assets/prodotti/isolated--menu-bigmac_0.png', 8.70),
(10, 'McChicken® Menu', '/progetti/hw1/assets/prodotti/isolated--menu-mcchicken-originale_3.png', 8.70),
(11, 'Double Cheeseburger Menu', '/progetti/hw1/assets/prodotti/isolated--menu-double-cheeseburger.png', 5.40),
(12, 'Double Chicken BBQ Menu', '/progetti/hw1/assets/prodotti/isolated--menu-double-chicken-bbq_2.png', 5.40);