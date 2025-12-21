CREATE DATABASE IF NOT EXISTS bootcamp6;
USE bootcamp6;

DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(100) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE products (
  id INT(11) NOT NULL AUTO_INCREMENT,
  namaproduk VARCHAR(100) NOT NULL,
  harga INT(11) NOT NULL,
  deskripsi VARCHAR(255) NOT NULL,
  stok INT(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE orders (
  order_id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  product_id INT(11) NOT NULL,
  quantity INT(11) NOT NULL,
  total INT(11) NOT NULL,
  PRIMARY KEY (order_id),
  KEY idx_orders_user (user_id),
  KEY idx_orders_product (product_id),
  CONSTRAINT fk_orders_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT fk_orders_product
    FOREIGN KEY (product_id) REFERENCES products(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- CREATE (INSERT)
INSERT INTO `products` (`namaproduk`, `harga`, `deskripsi`, `stok`)
VALUES ('Sneaker', 350000, 'Sneaker putih size 42', 15);

INSERT INTO `users` (`nama`, `email`, `password`)
VALUES ('Hafiz', 'havis.id013@gmail.com', 'password123');

-- READ (SELECT ALL)
SELECT `id`, `namaproduk`, `harga`, `deskripsi`, `stok`
FROM `products`;

-- READ (SELECT BY ID)
SELECT `id`, `namaproduk`, `harga`, `deskripsi`, `stok`
FROM `products`
WHERE `id` = 1;

-- READ (SEARCH BY NAME)
SELECT `id`, `namaproduk`, `harga`, `deskripsi`, `stok`
FROM `products`
WHERE `namaproduk` LIKE '%hoodie%';

-- UPDATE
UPDATE `products`
SET
  `namaproduk` = 'Hoodie Premium',
  `harga` = 250000,
  `deskripsi` = 'Hoodie premium bahan tebal',
  `stok` = 8
WHERE `id` = 1;

-- DELETE
DELETE FROM `products`
WHERE `id` = 1;

