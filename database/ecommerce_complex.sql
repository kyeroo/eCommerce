CREATE DATABASE IF NOT EXISTS ecommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecommerce;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS shipments;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS product_reviews;
DROP TABLE IF EXISTS wishlists;
DROP TABLE IF EXISTS customer_addresses;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS coupons;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(30),
  address TEXT,
  role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL UNIQUE,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  name VARCHAR(120) NOT NULL,
  slug VARCHAR(140) NOT NULL UNIQUE,
  description TEXT,
  price DECIMAL(12,2) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  image VARCHAR(255),
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE coupons (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(40) NOT NULL UNIQUE,
  description VARCHAR(180),
  discount_type ENUM('percent','fixed') NOT NULL DEFAULT 'percent',
  discount_value DECIMAL(12,2) NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  coupon_id INT NULL,
  invoice_code VARCHAR(40) NOT NULL UNIQUE,
  customer_name VARCHAR(100) NOT NULL,
  customer_email VARCHAR(120) NOT NULL,
  customer_phone VARCHAR(30) NOT NULL,
  shipping_address TEXT NOT NULL,
  payment_method ENUM('transfer','cod','ewallet') NOT NULL DEFAULT 'transfer',
  status ENUM('pending','paid','packed','shipped','completed','cancelled') NOT NULL DEFAULT 'pending',
  subtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
  discount_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
  shipping_cost DECIMAL(12,2) NOT NULL DEFAULT 0,
  total_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_orders_coupon FOREIGN KEY (coupon_id) REFERENCES coupons(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(12,2) NOT NULL,
  subtotal DECIMAL(12,2) NOT NULL,
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE customer_addresses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  label VARCHAR(60) NOT NULL,
  recipient_name VARCHAR(100) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  address TEXT NOT NULL,
  is_default TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_addresses_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE wishlists (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_wishlist (user_id, product_id),
  CONSTRAINT fk_wishlist_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_wishlist_product FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE product_reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  user_id INT NOT NULL,
  rating TINYINT NOT NULL,
  comment TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reviews_product FOREIGN KEY (product_id) REFERENCES products(id) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_reviews_user FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL UNIQUE,
  payment_method ENUM('transfer','cod','ewallet') NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  status ENUM('waiting','paid','failed','unpaid') NOT NULL DEFAULT 'waiting',
  paid_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_payments_order FOREIGN KEY (order_id) REFERENCES orders(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE shipments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL UNIQUE,
  courier VARCHAR(60) NOT NULL DEFAULT 'JNE',
  service VARCHAR(60) NOT NULL DEFAULT 'REG',
  tracking_number VARCHAR(80),
  shipping_cost DECIMAL(12,2) NOT NULL DEFAULT 0,
  status ENUM('waiting','processing','shipped','delivered') NOT NULL DEFAULT 'waiting',
  shipped_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_shipments_order FOREIGN KEY (order_id) REFERENCES orders(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO users (name,email,password,phone,address,role) VALUES
('Administrator','admin@famms.test', '$2y$12$9eUc7UC4SKrZ7alyVHNJuOUBF750Lvsw1O9h8bKEcQZqcfqXqMuDq', '081234567890', 'Kantor Famms', 'admin'),
('Customer Demo','customer@famms.test', '$2y$12$9eUc7UC4SKrZ7alyVHNJuOUBF750Lvsw1O9h8bKEcQZqcfqXqMuDq', '081298765432', 'Jakarta', 'customer');

INSERT INTO categories (name, description) VALUES
('Men Fashion','Koleksi pakaian pria modern.'),('Women Fashion','Koleksi pakaian wanita.'),('Accessories','Pelengkap fashion harian.'),('Shoes','Sepatu kasual dan formal.'),('Outerwear','Jaket, blazer, dan hoodie.');

INSERT INTO coupons (code, description, discount_type, discount_value, is_active) VALUES
('DISC10','Diskon 10 persen untuk pelanggan baru','percent',10,1),('HEMAT25K','Potongan langsung Rp25.000','fixed',25000,1);

INSERT INTO products (category_id,name,slug,description,price,stock,image,is_featured) VALUES
(1,'Kemeja Oxford Pria','kemeja-oxford-pria','Kemeja oxford lengan panjang dengan bahan adem, cocok untuk kerja, kuliah, dan acara semi-formal.',125000,30,'images/p1.png',1),
(5,'Jaket Coach Pria','jaket-coach-pria','Jaket coach ringan dengan desain clean untuk gaya kasual dan aktivitas harian.',195000,24,'images/p2.png',1),
(2,'Dress Satin Ungu','dress-satin-ungu','Dress satin warna ungu dengan potongan elegan untuk acara spesial.',210000,20,'images/p3.png',1),
(2,'Mini Dress Merah','mini-dress-merah','Mini dress merah dengan detail rapi untuk tampilan feminin dan percaya diri.',185000,15,'images/p4.png',0),
(2,'Dress Navy Ikat Pinggang','dress-navy-ikat-pinggang','Dress navy dengan aksen ikat pinggang, mudah dipadukan untuk acara santai maupun formal.',175000,28,'images/p5.png',0),
(2,'Floral Summer Dress','floral-summer-dress','Dress bermotif floral dengan bahan ringan untuk tampilan segar dan nyaman.',160000,16,'images/p6.png',1),
(2,'Gamis Brokat Hitam','gamis-brokat-hitam','Gamis brokat hitam dengan siluet anggun untuk acara resmi dan keluarga.',240000,18,'images/p7.png',0),
(1,'Denim Shirt Pria','denim-shirt-pria','Kemeja denim pria dengan potongan modern dan bahan kuat untuk gaya casual urban.',150000,12,'images/p8.png',1),
(1,'Kemeja Navy Pria','kemeja-navy-pria','Kemeja navy lengan panjang yang cocok untuk tampilan rapi sehari-hari.',135000,35,'images/p9.png',0),
(1,'Kemeja Hijau Tosca','kemeja-hijau-tosca','Kemeja pria warna hijau tosca dengan bahan nyaman dan tampilan segar.',135000,25,'images/p10.png',0),
(1,'Kemeja Kotak Casual','kemeja-kotak-casual','Kemeja motif kotak dengan warna netral untuk gaya santai dan rapi.',115000,42,'images/p11.png',0),
(2,'Gaun Pesta Lavender','gaun-pesta-lavender','Gaun pesta lavender dengan desain mewah untuk momen formal dan perayaan.',260000,21,'images/p12.png',0),
(3,'Tas Selempang Compact','tas-selempang-compact','Tas selempang compact dengan kompartemen praktis untuk aktivitas harian.',120000,18,'images/p13.png',1),
(4,'Sneakers Minimalis','sneakers-minimalis','Sneakers ringan dengan desain minimalis untuk jalan santai, kerja, dan kampus.',185000,22,'images/p14.png',1),
(3,'Jam Tangan Steel','jam-tangan-steel','Jam tangan analog dengan strap steel dan tampilan elegan.',150000,14,'images/p15.png',1),
(3,'Tote Bag Kanvas','tote-bag-kanvas','Tote bag kanvas tebal untuk membawa barang kuliah, kerja, dan perjalanan singkat.',95000,30,'images/p16.png',1),
(3,'Dompet Slim Compact','dompet-slim-compact','Dompet tipis dengan kompartemen kartu yang rapi, ringan, dan aman.',85000,26,'images/p17.png',0),
(3,'Topi Casual Daily','topi-casual-daily','Topi casual dengan bentuk clean untuk melengkapi gaya harian.',70000,34,'images/p18.png',0),
(3,'Kacamata Urban Modern','kacamata-urban-modern','Kacamata hitam bergaya urban dengan frame ringan dan nyaman dipakai.',110000,16,'images/p19.png',1);

INSERT INTO customer_addresses(user_id,label,recipient_name,phone,address,is_default) VALUES
(2,'Rumah','Customer Demo','081298765432','Jakarta',1);

INSERT INTO product_reviews(product_id,user_id,rating,comment) VALUES
(1,2,5,'Bahan nyaman dan ukuran sesuai.'),(2,2,4,'Model rapi untuk kerja.'),(3,2,5,'Warna dan bahan bagus.');
