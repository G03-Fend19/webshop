-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Värd: localhost:3306
-- Tid vid skapande: 04 maj 2020 kl 16:06
-- Serverversion: 5.7.26
-- PHP-version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `ws_db`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `ws_active_orders`
--

CREATE TABLE `ws_active_orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `delivery_address_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_cost` float NOT NULL,
  `shipping_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `ws_active_orders`
--

INSERT INTO `ws_active_orders` (`id`, `customer_id`, `delivery_address_id`, `order_date`, `total_cost`, `shipping_id`, `status_id`) VALUES
(4, 3, 2, '2020-04-23 11:15:57', 35000, 2, 2),
(5, 2, 4, '2020-04-23 11:16:16', 6798, 1, 1),
(6, 1, 1, '2020-04-28 13:49:28', 1452.99, 2, 1),
(7, 11, 7, '2020-04-28 13:55:36', 1452.99, 2, 1),
(8, 13, 8, '2020-04-28 14:03:40', 1452.99, 2, 1),
(9, 13, 8, '2020-04-28 14:05:08', 1452.99, 2, 1),
(10, 13, 8, '2020-04-28 14:08:04', 1452.99, 2, 2),
(11, 14, 8, '2020-04-28 14:11:04', 1452.99, 2, 1),
(12, 15, 9, '2020-04-29 10:05:17', 2450.98, 2, 1),
(13, 15, 10, '2020-04-29 10:24:28', 2450.98, 2, 1),
(14, 15, 9, '2020-04-29 10:29:38', 2450.98, 2, 1),
(15, 15, 9, '2020-04-30 15:08:36', 899, 2, 1),
(16, 16, 11, '2020-04-30 15:12:54', 699, 2, 1),
(17, 17, 12, '2020-04-30 15:27:22', 399, 1, 1),
(18, 18, 13, '2020-05-01 13:14:56', 4197.99, 2, 1),
(19, 19, 14, '2020-05-01 14:00:50', 3596, 2, 1),
(20, 19, 14, '2020-05-01 14:07:20', 3596, 2, 1),
(21, 19, 14, '2020-05-01 14:07:32', 3596, 2, 1),
(22, 20, 15, '2020-05-01 14:34:55', 13, 1, 1),
(23, 20, 15, '2020-05-01 15:43:39', 3127, 2, 1),
(24, 21, 16, '2020-05-01 15:44:23', 3017, 2, 1),
(25, 1, 1, '2020-05-01 15:59:30', 2318, 2, 1),
(26, 1, 1, '2020-05-01 17:03:43', 2318, 2, 1),
(27, 1, 1, '2020-05-01 17:05:53', 1558, 2, 1),
(28, 1, 1, '2020-05-04 09:00:14', 4814, 2, 1),
(29, 1, 1, '2020-05-04 09:01:33', 4814, 2, 1),
(30, 1, 1, '2020-05-04 11:07:06', 711, 2, 1),
(31, 1, 1, '2020-05-04 11:08:08', 12, 1, 1),
(32, 1, 1, '2020-05-04 11:13:47', 12, 1, 1),
(33, 1, 1, '2020-05-04 11:16:27', 3011, 2, 1),
(34, 1, 1, '2020-05-04 12:31:09', 3046, 2, 1);

-- --------------------------------------------------------

--
-- Tabellstruktur `ws_categories`
--

CREATE TABLE `ws_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `ws_categories`
--

INSERT INTO `ws_categories` (`id`, `name`) VALUES
(7, 'Blazer'),
(3, 'Dresses'),
(4, 'Jeans'),
(1, 'Shoes'),
(6, 'Tops'),
(5, 'Watches');

-- --------------------------------------------------------

--
-- Tabellstruktur `ws_completed_orders`
--

CREATE TABLE `ws_completed_orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `delivery_address_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `total_cost` float NOT NULL,
  `shipping_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `ws_completed_orders`
--

INSERT INTO `ws_completed_orders` (`id`, `customer_id`, `delivery_address_id`, `order_date`, `total_cost`, `shipping_id`, `status_id`) VALUES
(1, 1, 1, '2020-04-23 11:14:57', 200, 1, 3),
(2, 5, 3, '2020-04-23 11:15:19', 3500, 1, 3),
(3, 4, 5, '2020-04-23 11:15:40', 4578, 2, 3),
(6, 1, 1, '2020-04-23 11:23:19', 2000, 1, 3),
(7, 2, 2, '2020-04-23 11:24:08', 789, 2, 3),
(8, 4, 3, '2020-04-23 11:25:06', 200, 1, 3);

-- --------------------------------------------------------

--
-- Tabellstruktur `ws_completed_orders_products`
--

CREATE TABLE `ws_completed_orders_products` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellstruktur `ws_customers`
--

CREATE TABLE `ws_customers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `last_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 NOT NULL,
  `tel_nr` varchar(30) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `ws_customers`
--

INSERT INTO `ws_customers` (`id`, `first_name`, `last_name`, `email`, `tel_nr`) VALUES
(1, 'Andreas', 'Henningson', 'henningsonandreas@gmail.com', '0737075986'),
(2, 'Svante', 'Gustavsson', 'svantepante@hotmail.com', '70708780708'),
(3, 'Mahmud', 'Al Hakim', 'mahmud@yh.nackademin.se', '172787303'),
(4, 'Kristina', 'Bengtsson', 'lillafina@edu.amal.se', '80177889'),
(5, 'Hans', 'Olsson', 'olle@hotmail.com', '783708127'),
(6, 'Olof', 'Gustavsson', 'nöken@gmail.com', '11111'),
(7, 'Frank', 'Björn', 'bjorne@gmail.com', '5680708'),
(8, 'Farid', 'Fakhouri', 'bossman@gmail.com', '5687898'),
(9, 'Farid', 'Fakhouri', 'bosskid@gmail.com', '5687898'),
(10, 'Farid', 'Fakhouri', 'bossdog@gmail.com', '5687898'),
(11, 'Sven', 'Tumba', 'svempa@hotmail.com', '789647948'),
(12, 'Bengt', 'Karlsson', 'benke@hotmail.com', '7389648964'),
(13, 'Bengte', 'Karlberg', 'benkes@hotmail.com', '7389648989'),
(14, 'Greger', 'Karlberg', 'benkt@hotmail.com', '7389648989'),
(15, 'Sven', 'Bös', 'bossen@gmail.com', '70787876962'),
(16, 'Gurkan', 'Jansson', 'gurkan@yahoo.com', '72807120874'),
(17, 'Gurkan', 'Olssom', 'hdohasc@gmail.com', '78027480174'),
(18, 'Sven', 'Karlsson', 'hoho@gmail.com', '78087875865'),
(19, 'Sven', 'Karlsson', 'hyhohdoj@hotmail.com', '788640864'),
(20, 'dascljab', 'ascbajlsblc', 'baljscba@bascbaljc.com', '78462379462'),
(21, 'lnfljas', 'asbljabs', 'hbclab@ansljcb.com', '7898748074');

-- --------------------------------------------------------

--
-- Tabellstruktur `ws_delivery_address`
--

CREATE TABLE `ws_delivery_address` (
  `id` int(11) NOT NULL,
  `street` varchar(50) NOT NULL,
  `postal_code` varchar(30) NOT NULL,
  `city` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `ws_delivery_address`
--

INSERT INTO `ws_delivery_address` (`id`, `street`, `postal_code`, `city`) VALUES
(1, 'Vänershöjd 8', '66236', 'Åmål'),
(2, 'Oppundavägen 31', '10372', 'Stockholm'),
(3, 'Sörens stig 19', '78686', 'Örebro'),
(4, 'Kungsbrovägen 18', '7927020', 'Ljungskile'),
(5, 'Skärholmsvägen 2', '27899', 'Stockholm'),
(6, 'Bullgatan 5', '78968', 'Åkerstyckebruk'),
(7, 'Vänskapsstigen 7', '67973', 'Arvidsjaur'),
(8, 'Vänskapssgatan 8', '67988', 'Örebro'),
(9, 'Störgatan 45', '79875', 'Umeå'),
(10, 'stör', '', ''),
(11, 'Hundvägen 91', '68736', 'Töreboda'),
(12, 'Vänskapssgatan 8', '66236', 'Åmål'),
(13, 'Gundes sväng 3', '68987', 'Årjäng'),
(14, 'Vänershöjd 8', '73863', 'Åmål'),
(15, 'absljcba 7', '78797', 'Åmål'),
(16, 'vsckhva 7', '7373878', 'Stockholm');

-- --------------------------------------------------------

--
-- Tabellstruktur `ws_images`
--

CREATE TABLE `ws_images` (
  `id` int(11) NOT NULL,
  `img` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `ws_images`
--

INSERT INTO `ws_images` (`id`, `img`) VALUES
(3, 'blouse.jpeg'),
(4, 'dress.jpeg'),
(5, 'jeans.jpeg'),
(6, 'shirt.jpeg'),
(7, 'watch.jpeg'),
(8, 'iwatch.jpeg'),
(9, 'marsone.jpg'),
(10, 'travelson.jpg'),
(11, 'placeholder.jpg');

-- --------------------------------------------------------

--
-- Tabellstruktur `ws_orders_products`
--

CREATE TABLE `ws_orders_products` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `ws_orders_products`
--

INSERT INTO `ws_orders_products` (`order_id`, `product_id`, `product_qty`) VALUES
(1, 2, 2),
(2, 3, 2),
(3, 8, 1),
(4, 5, 22),
(5, 1, 1),
(12, 2, 1),
(12, 5, 2),
(12, 6, 3),
(12, 10, 1),
(13, 2, 1),
(13, 5, 2),
(13, 6, 3),
(13, 10, 1),
(14, 2, 1),
(14, 5, 2),
(14, 6, 3),
(14, 10, 1),
(15, 1, 1),
(16, 2, 1),
(17, 3, 1),
(18, 2, 1),
(18, 5, 1),
(18, 7, 1),
(19, 1, 4),
(20, 1, 4),
(21, 1, 4),
(22, 11, 1),
(23, 1, 3),
(23, 2, 1),
(24, 1, 2),
(24, 2, 2),
(25, 1, 2),
(25, 2, 1),
(26, 1, 2),
(26, 2, 1),
(27, 1, 1),
(27, 3, 1),
(27, 4, 1),
(28, 1, 2),
(28, 2, 4),
(28, 3, 1),
(29, 1, 2),
(29, 2, 4),
(29, 3, 1),
(30, 2, 1),
(30, 11, 1),
(31, 11, 1),
(32, 11, 1),
(33, 7, 1),
(33, 11, 1),
(34, 7, 1),
(34, 11, 4);

-- --------------------------------------------------------

--
-- Tabellstruktur `ws_order_status`
--

CREATE TABLE `ws_order_status` (
  `id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `ws_order_status`
--

INSERT INTO `ws_order_status` (`id`, `status`) VALUES
(3, 'completed'),
(2, 'in progress'),
(1, 'pending');

-- --------------------------------------------------------

--
-- Tabellstruktur `ws_products`
--

CREATE TABLE `ws_products` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(800) NOT NULL,
  `stock_qty` int(5) NOT NULL,
  `price` float NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `added_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `ws_products`
--

INSERT INTO `ws_products` (`id`, `name`, `description`, `stock_qty`, `price`, `active`, `added_date`) VALUES
(1, 'Red running shoes', 'Red shoes from Nike. Great for running.', 4, 899, 0, '2018-04-08 15:23:02'),
(2, 'Blazer', 'This is a nice product that will suit you well.', 1, 699, 1, '2020-04-29 14:48:33'),
(3, 'Fluffy blouse', 'This is a nice product that will suit you well.', 241, 399, 1, '2020-04-08 14:48:33'),
(4, 'Cute dress', 'This is a nice product that will suit you well.', 234, 349, 1, '2020-04-29 14:48:33'),
(5, 'Ruff jeans', 'This is a nice product that will suit you well.', 531, 499.99, 1, '2020-04-29 14:48:33'),
(6, 'White shirt', 'This is a nice product that will suit you well.', 214, 249, 1, '2020-04-29 14:48:33'),
(7, 'Nice watch', 'This is a nice product that will suit you well.', 31, 2999, 1, '2020-04-29 14:48:33'),
(8, 'iWatch', 'This is a nice product that will suit you well.', 10, 3499, 1, '2020-04-29 14:48:33'),
(10, 'thjgfmhkpjgk hokgh pof gkhpofghkpofkghpofkghpofg', 'rfghd gfödfkgöldfgöldf glfdg dölfgkdlöfgk döfgködf göldf g', 9, 5, 1, '2020-04-08 15:05:00'),
(11, 'Nice Shirt', 'This is a really nice shirt', 0, 13, 1, '2018-03-29 17:26:08');

-- --------------------------------------------------------

--
-- Tabellstruktur `ws_products_categories`
--

CREATE TABLE `ws_products_categories` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `ws_products_categories`
--

INSERT INTO `ws_products_categories` (`product_id`, `category_id`) VALUES
(10, 1),
(4, 3),
(5, 4),
(7, 5),
(8, 5),
(3, 6),
(6, 6),
(11, 6),
(2, 7);

-- --------------------------------------------------------

--
-- Tabellstruktur `ws_products_images`
--

CREATE TABLE `ws_products_images` (
  `product_id` int(11) NOT NULL,
  `img_id` int(11) NOT NULL,
  `feature` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `ws_products_images`
--

INSERT INTO `ws_products_images` (`product_id`, `img_id`, `feature`) VALUES
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 6, 1),
(7, 7, 1),
(8, 8, 1),
(10, 11, 1);

-- --------------------------------------------------------

--
-- Tabellstruktur `ws_shipping_fee`
--

CREATE TABLE `ws_shipping_fee` (
  `id` int(11) NOT NULL,
  `fee` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumpning av Data i tabell `ws_shipping_fee`
--

INSERT INTO `ws_shipping_fee` (`id`, `fee`) VALUES
(2, 0),
(1, 50);

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `ws_active_orders`
--
ALTER TABLE `ws_active_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `delivery_address_id` (`delivery_address_id`),
  ADD KEY `shipping_id` (`shipping_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Index för tabell `ws_categories`
--
ALTER TABLE `ws_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index för tabell `ws_completed_orders`
--
ALTER TABLE `ws_completed_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `shipping_id` (`shipping_id`),
  ADD KEY `delivery_address_id` (`delivery_address_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Index för tabell `ws_completed_orders_products`
--
ALTER TABLE `ws_completed_orders_products`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index för tabell `ws_customers`
--
ALTER TABLE `ws_customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index för tabell `ws_delivery_address`
--
ALTER TABLE `ws_delivery_address`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `ws_images`
--
ALTER TABLE `ws_images`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `ws_orders_products`
--
ALTER TABLE `ws_orders_products`
  ADD PRIMARY KEY (`order_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index för tabell `ws_order_status`
--
ALTER TABLE `ws_order_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `status` (`status`);

--
-- Index för tabell `ws_products`
--
ALTER TABLE `ws_products`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `ws_products_categories`
--
ALTER TABLE `ws_products_categories`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Index för tabell `ws_products_images`
--
ALTER TABLE `ws_products_images`
  ADD PRIMARY KEY (`product_id`,`img_id`),
  ADD KEY `ws_products_images_ibfk_2` (`img_id`);

--
-- Index för tabell `ws_shipping_fee`
--
ALTER TABLE `ws_shipping_fee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fee` (`fee`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `ws_active_orders`
--
ALTER TABLE `ws_active_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT för tabell `ws_categories`
--
ALTER TABLE `ws_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT för tabell `ws_customers`
--
ALTER TABLE `ws_customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT för tabell `ws_delivery_address`
--
ALTER TABLE `ws_delivery_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT för tabell `ws_images`
--
ALTER TABLE `ws_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT för tabell `ws_order_status`
--
ALTER TABLE `ws_order_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT för tabell `ws_products`
--
ALTER TABLE `ws_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT för tabell `ws_shipping_fee`
--
ALTER TABLE `ws_shipping_fee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restriktioner för dumpade tabeller
--

--
-- Restriktioner för tabell `ws_active_orders`
--
ALTER TABLE `ws_active_orders`
  ADD CONSTRAINT `ws_active_orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `ws_customers` (`id`),
  ADD CONSTRAINT `ws_active_orders_ibfk_2` FOREIGN KEY (`delivery_address_id`) REFERENCES `ws_delivery_address` (`id`),
  ADD CONSTRAINT `ws_active_orders_ibfk_3` FOREIGN KEY (`shipping_id`) REFERENCES `ws_shipping_fee` (`id`),
  ADD CONSTRAINT `ws_active_orders_ibfk_4` FOREIGN KEY (`status_id`) REFERENCES `ws_order_status` (`id`);

--
-- Restriktioner för tabell `ws_completed_orders`
--
ALTER TABLE `ws_completed_orders`
  ADD CONSTRAINT `ws_completed_orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `ws_customers` (`id`),
  ADD CONSTRAINT `ws_completed_orders_ibfk_2` FOREIGN KEY (`shipping_id`) REFERENCES `ws_shipping_fee` (`id`),
  ADD CONSTRAINT `ws_completed_orders_ibfk_3` FOREIGN KEY (`delivery_address_id`) REFERENCES `ws_delivery_address` (`id`),
  ADD CONSTRAINT `ws_completed_orders_ibfk_4` FOREIGN KEY (`status_id`) REFERENCES `ws_order_status` (`id`);

--
-- Restriktioner för tabell `ws_orders_products`
--
ALTER TABLE `ws_orders_products`
  ADD CONSTRAINT `ws_orders_products_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `ws_products` (`id`);

--
-- Restriktioner för tabell `ws_products_categories`
--
ALTER TABLE `ws_products_categories`
  ADD CONSTRAINT `ws_products_categories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `ws_categories` (`id`),
  ADD CONSTRAINT `ws_products_categories_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `ws_products` (`id`) ON DELETE CASCADE;

--
-- Restriktioner för tabell `ws_products_images`
--
ALTER TABLE `ws_products_images`
  ADD CONSTRAINT `ws_products_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `ws_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ws_products_images_ibfk_2` FOREIGN KEY (`img_id`) REFERENCES `ws_images` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
