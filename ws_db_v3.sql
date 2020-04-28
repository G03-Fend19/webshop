-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 28 apr 2020 kl 12:02
-- Serverversion: 10.1.38-MariaDB
-- PHP-version: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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
(2, 5, 3, '2020-04-23 11:15:19', 3500, 1, 2),
(3, 4, 5, '2020-04-23 11:15:40', 4578, 2, 2),
(4, 3, 2, '2020-04-23 11:15:57', 35000, 2, 1),
(5, 2, 4, '2020-04-23 11:16:16', 6798, 1, 1);

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
(1, 'Andreas', 'Henningson', 'henningsonandreas@gmail.com', '7008217323'),
(2, 'Svante', 'Gustavsson', 'svantepante@hotmail.com', '70708780708'),
(3, 'Mahmud', 'Al Hakim', 'mahmud@yh.nackademin.se', '172787303'),
(4, 'Kristina', 'Bengtsson', 'lillafina@edu.amal.se', '80177889'),
(5, 'Hans', 'Olsson', 'olle@hotmail.com', '783708127');

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
(5, 'Skärholmsvägen 2', '27899', 'Stockholm');

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
(1, 'redshoe.jpeg'),
(2, 'blazer.jpeg'),
(4, 'dress.jpeg'),
(5, 'jeans.jpeg'),
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
(5, 1, 1);

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
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `ws_products`
--

INSERT INTO `ws_products` (`id`, `name`, `description`, `stock_qty`, `price`, `active`) VALUES
(1, 'Red running shoes', 'Red shoes from Nike. Great for running.', 50, 899, 0),
(2, 'Blazer', 'This is a nice product that will suit you well.', 1, 699, 0),
(3, 'Fluffy blouse', 'This is a nice product that will suit you well.', 245, 399, 1),
(4, 'Cute dress', 'This is a nice product that will suit you well.', 235, 349, 0),
(5, 'Ruff jeans', 'This is a nice product that will suit you well.', 532, 499.99, 1),
(6, 'White shirt', 'This is a nice product that will suit you well.', 214, 249, 0),
(7, 'Nice watch', 'This is a nice product that will suit you well.', 34, 2999, 0),
(8, 'iWatch', 'This is a nice product that will suit you well.', 10, 3499, 0),
(10, 'thjgfmhkpjgk hokgh pof gkhpofghkpofkghpofkghpofg', 'rfghd gfödfkgöldfgöldf glfdg dölfgkdlöfgk döfgködf göldf g', 9, 5, 0),
(12, 'Blue watch', 'Nice blue watch', 45, 3800, 1),
(13, 'sdijbfsdfnsd sdo', 'dfg erg erf aerfaerf aerf aerfaerfearferferfaerfer  erf', 19, 6, 0),
(14, 'Ny product', 'En fin ny product som alla kommer älska.', 8, 5, 1);

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
(3, 6),
(5, 4),
(7, 5),
(8, 5),
(12, 5),
(14, 6);

-- --------------------------------------------------------

--
-- Tabellstruktur `ws_products_images`
--

CREATE TABLE `ws_products_images` (
  `product_id` int(11) NOT NULL,
  `img_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `ws_products_images`
--

INSERT INTO `ws_products_images` (`product_id`, `img_id`) VALUES
(1, 1),
(2, 2),
(4, 4),
(5, 5),
(7, 7),
(8, 8),
(10, 11);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT för tabell `ws_categories`
--
ALTER TABLE `ws_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT för tabell `ws_customers`
--
ALTER TABLE `ws_customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT för tabell `ws_delivery_address`
--
ALTER TABLE `ws_delivery_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
