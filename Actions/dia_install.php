<?php

  global $db, $con, $dia;

  // Vytvara tabulku admin
  $query = "CREATE TABLE IF NOT EXISTS admin (
    id int AUTO_INCREMENT PRIMARY KEY,
    name varchar(55) NOT NULL,
    password varchar(55) NOT NULL
  )";

  if (!$con->query($query)) echo "Chyba";

  $query = "CREATE TABLE dia_tables(
    id int AUTO_INCREMENT PRIMARY KEY,
    table_name VARCHAR(55) NOT NULL,
    structure VARCHAR(255) NOT NULL
  )";

  if (!$con->query($query)) echo "Chyba";

// TO DO
/*
  // Vytvara tabulku alerts
  $query = "CREATE TABLE IF NOT EXISTS admin (
    id int AUTO_INCREMENT PRIMARY KEY,
    name varchar(55) NOT NULL,
    password varchar(55) NOT NULL
  )";

  // Vytvara tabulku navbar
  $query = "CREATE TABLE IF NOT EXISTS admin (
    id int AUTO_INCREMENT PRIMARY KEY,
    name varchar(55) NOT NULL,
    password varchar(55) NOT NULL
  )";

  CREATE TABLE dia_alerts_users(
    id int AUTO_INCREMENT PRIMARY KEY,
    id_user int,
    id_alert int,
    INDEX `id_user` (`id_user`),
    INDEX `id_alert` (`id_alert`)
)
*/ 

/*
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    serial_number INT(8) NULL,
    count INT(1) NOT NULL,
    id_type INT NOT NULL,
    id_customer_uid INT NOT NULL,
    id_product INT NOT NULL,
    id_invoice INT NULL
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT NULL,
    price DOUBLE(14,2) NULL,
    id_product_info INT NULL,
    id_product_accessories INT NULL
);

CREATE table order_type (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

*/

  $db->insert([
    'table' => 'admin',
  ]);

  $dia->redirect('home', 
    [
      'type' => 'success',
      'text' => "Úspešne sa ti podarilo ma nainštalovať ✔️",
    ]
  );

?>