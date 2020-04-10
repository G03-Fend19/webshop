-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 10 apr 2020 kl 15:45
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
(1, 'category 1'),
(2, 'category 2'),
(3, 'category 3'),
(4, 'category 4'),
(5, 'category 5'),
(6, 'category 6');

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
(1, 'placeholder.jpg');

-- --------------------------------------------------------

--
-- Tabellstruktur `ws_products`
--

CREATE TABLE `ws_products` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(800) NOT NULL,
  `stock_qty` int(5) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `ws_products`
--

INSERT INTO `ws_products` (`id`, `name`, `description`, `stock_qty`, `price`) VALUES
(1, 'product 1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque aliquet eleifend pharetra. Aliquam erat volutpat. Interdum et malesuada fames ac ante ipsum primis in faucibus. Etiam ut ipsum metus. Aliquam elementum a eros ac ultricies. Proin vitae diam dignissim, elementum augue mattis, laoreet elit. Donec gravida magna ut nisl posuere, nec hendrerit libero maximus.', 15, 200),
(2, 'product 2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque aliquet eleifend pharetra. Aliquam erat volutpat. Interdum et malesuada fames ac ante ipsum primis in faucibus. Etiam ut ipsum metus. Aliquam elementum a eros ac ultricies. Proin vitae diam dignissim, elementum augue mattis, laoreet elit. Donec gravida magna ut nisl posuere, nec hendrerit libero maximus.', 2, 30),
(3, 'product 3', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque aliquet eleifend pharetra. Aliquam erat volutpat. Interdum et malesuada fames ac ante ipsum primis in faucibus. Etiam ut ipsum metus. Aliquam elementum a eros ac ultricies. Proin vitae diam dignissim, elementum augue mattis, laoreet elit. Donec gravida magna ut nisl posuere, nec hendrerit libero maximus.', 40, 900),
(4, 'product 4', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque aliquet eleifend pharetra. Aliquam erat volutpat. Interdum et malesuada fames ac ante ipsum primis in faucibus. Etiam ut ipsum metus. Aliquam elementum a eros ac ultricies. Proin vitae diam dignissim, elementum augue mattis, laoreet elit. Donec gravida magna ut nisl posuere, nec hendrerit libero maximus.', 12, 10),
(5, 'product 5', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque aliquet eleifend pharetra. Aliquam erat volutpat. Interdum et malesuada fames ac ante ipsum primis in faucibus. Etiam ut ipsum metus. Aliquam elementum a eros ac ultricies. Proin vitae diam dignissim, elementum augue mattis, laoreet elit. Donec gravida magna ut nisl posuere, nec hendrerit libero maximus.', 80, 100),
(6, 'product 6', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque aliquet eleifend pharetra. Aliquam erat volutpat. Interdum et malesuada fames ac ante ipsum primis in faucibus. Etiam ut ipsum metus. Aliquam elementum a eros ac ultricies. Proin vitae diam dignissim, elementum augue mattis, laoreet elit. Donec gravida magna ut nisl posuere, nec hendrerit libero maximus.', 35, 100),
(7, 'product 7', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque aliquet eleifend pharetra. Aliquam erat volutpat. Interdum et malesuada fames ac ante ipsum primis in faucibus. Etiam ut ipsum metus. Aliquam elementum a eros ac ultricies. Proin vitae diam dignissim, elementum augue mattis, laoreet elit. Donec gravida magna ut nisl posuere, nec hendrerit libero maximus.', 66, 199),
(8, 'product 8', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque aliquet eleifend pharetra. Aliquam erat volutpat. Interdum et malesuada fames ac ante ipsum primis in faucibus. Etiam ut ipsum metus. Aliquam elementum a eros ac ultricies. Proin vitae diam dignissim, elementum augue mattis, laoreet elit. Donec gravida magna ut nisl posuere, nec hendrerit libero maximus.', 300, 699),
(9, 'product 9', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque aliquet eleifend pharetra. Aliquam erat volutpat. Interdum et malesuada fames ac ante ipsum primis in faucibus. Etiam ut ipsum metus. Aliquam elementum a eros ac ultricies. Proin vitae diam dignissim, elementum augue mattis, laoreet elit. Donec gravida magna ut nisl posuere, nec hendrerit libero maximus.', 67, 450),
(10, 'product 10', 'Fusce tincidunt massa eget erat dictum vulputate. Vestibulum vel ligula sit amet justo tristique dignissim in sed ligula. Sed lacus enim, fermentum at tortor eget, dignissim volutpat odio.', 45, 300),
(11, 'product 11', 'Fusce tincidunt massa eget erat dictum vulputate. Vestibulum vel ligula sit amet justo tristique dignissim in sed ligula. Sed lacus enim, fermentum at tortor eget, dignissim volutpat odio.', 21, 1500),
(12, 'product 12', 'Fusce tincidunt massa eget erat dictum vulputate. Vestibulum vel ligula sit amet justo tristique dignissim in sed ligula. Sed lacus enim, fermentum at tortor eget, dignissim volutpat odio.', 500, 10),
(13, 'product 13', 'Fusce tincidunt massa eget erat dictum vulputate. Vestibulum vel ligula sit amet justo tristique dignissim in sed ligula. Sed lacus enim, fermentum at tortor eget, dignissim volutpat odio.', 50, 700),
(14, 'product 14', 'Fusce tincidunt massa eget erat dictum vulputate. Vestibulum vel ligula sit amet justo tristique dignissim in sed ligula. Sed lacus enim, fermentum at tortor eget, dignissim volutpat odio.', 90, 79),
(15, 'product 15', 'Fusce tincidunt massa eget erat dictum vulputate. Vestibulum vel ligula sit amet justo tristique dignissim in sed ligula. Sed lacus enim, fermentum at tortor eget, dignissim volutpat odio.', 135, 59),
(16, 'product 16', 'Etiam ut ipsum metus. Aliquam elementum a eros ac ultricies. Proin vitae diam dignissim, elementum augue mattis, laoreet elit. ', 687, 35),
(17, 'product 17', 'Etiam ut ipsum metus. Aliquam elementum a eros ac ultricies. Proin vitae diam dignissim, elementum augue mattis, laoreet elit. ', 2000, 5),
(18, 'product 18', 'Etiam ut ipsum metus. Aliquam elementum a eros ac ultricies. Proin vitae diam dignissim, elementum augue mattis, laoreet elit. ', 2, 400),
(19, 'product 19', 'Etiam ut ipsum metus. Aliquam elementum a eros ac ultricies. Proin vitae diam dignissim, elementum augue mattis, laoreet elit. ', 0, 799),
(20, 'product 20', 'Etiam ut ipsum metus. Aliquam elementum a eros ac ultricies. Proin vitae diam dignissim, elementum augue mattis, laoreet elit. ', 65, 349);

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
(1, 4),
(2, 2),
(3, 6),
(4, 5),
(5, 1),
(6, 3),
(7, 2),
(8, 5),
(9, 6),
(10, 4),
(11, 3),
(12, 1),
(13, 5),
(14, 4),
(15, 2),
(16, 1),
(17, 3),
(18, 6),
(19, 5),
(20, 1);

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
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1);

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `ws_categories`
--
ALTER TABLE `ws_categories`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `ws_images`
--
ALTER TABLE `ws_images`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `img_id` (`img_id`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `ws_categories`
--
ALTER TABLE `ws_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT för tabell `ws_images`
--
ALTER TABLE `ws_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT för tabell `ws_products`
--
ALTER TABLE `ws_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Restriktioner för dumpade tabeller
--

--
-- Restriktioner för tabell `ws_products_categories`
--
ALTER TABLE `ws_products_categories`
  ADD CONSTRAINT `ws_products_categories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `ws_categories` (`id`),
  ADD CONSTRAINT `ws_products_categories_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `ws_products` (`id`);

--
-- Restriktioner för tabell `ws_products_images`
--
ALTER TABLE `ws_products_images`
  ADD CONSTRAINT `ws_products_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `ws_products` (`id`),
  ADD CONSTRAINT `ws_products_images_ibfk_2` FOREIGN KEY (`img_id`) REFERENCES `ws_images` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
