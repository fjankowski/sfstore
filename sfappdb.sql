-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2023 at 02:06 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sfappdb`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Brak Kategorii'),
(2, 'Testowa'),
(3, 'Klocki'),
(4, 'Karta Graficzna'),
(5, 'Dysk'),
(6, 'Zasilacz');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `shipping_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `order_product_entry`
--

CREATE TABLE `order_product_entry` (
  `id` int(11) NOT NULL,
  `order_ref_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `method_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `paid_amount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `payment_method`
--

CREATE TABLE `payment_method` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`id`, `name`) VALUES
(1, 'BRAK'),
(2, 'Opłacona przez Admina'),
(3, 'Darmowe Zamówienie'),
(4, 'BLIK'),
(5, 'Pobraniowo'),
(6, 'Przelew');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `payment_status`
--

CREATE TABLE `payment_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_status`
--

INSERT INTO `payment_status` (`id`, `name`) VALUES
(1, 'Processing'),
(2, 'Oczekiwanie na Zapłatę'),
(3, 'Opłacono Częściowo'),
(4, 'Opłacono w Całości'),
(5, 'Potwierdzono Ręcznie');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `shipping`
--

CREATE TABLE `shipping` (
  `id` int(11) NOT NULL,
  `method_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `shipped_date` datetime DEFAULT NULL,
  `tracking` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `shipping_address`
--

CREATE TABLE `shipping_address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `building_nr` int(11) NOT NULL,
  `locale_nr` int(11) DEFAULT NULL,
  `postcode` int(11) NOT NULL,
  `city` varchar(255) NOT NULL,
  `phone_nr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `shipping_method`
--

CREATE TABLE `shipping_method` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_method`
--

INSERT INTO `shipping_method` (`id`, `name`) VALUES
(1, 'Odbiór Osobisty'),
(2, 'Poczta Polska'),
(3, 'Teleportacja'),
(4, 'Jakiś inny kurier');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `shipping_status`
--

CREATE TABLE `shipping_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_status`
--

INSERT INTO `shipping_status` (`id`, `name`) VALUES
(1, 'Przetwarzanie'),
(2, 'Pakowanie'),
(3, 'Przekazano kurierowi'),
(4, 'Dostarczono'),
(5, 'Zakończono'),
(6, 'Zgubiono w Trasie');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `shop_item`
--

CREATE TABLE `shop_item` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_hidden` tinyint(1) NOT NULL,
  `require_login` tinyint(1) NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shop_item`
--

INSERT INTO `shop_item` (`id`, `category_id`, `title`, `description`, `image`, `is_hidden`, `require_login`, `price`) VALUES
(1, 2, 'Sekretny Obiekt', 'Widziany tylko przez tych godnych', '4234654a20db0a398036b80505caa9df.png', 1, 1, 999.99),
(2, 3, 'Zestaw Lego Saszetka', 'A taka tyci saszetka', 'becb7f6017459c0440093911757d886d.jpg', 0, 0, 11.99),
(3, 3, 'Zestaw Lego Mały', 'Mały ale fajny zestaw lego', 'e5687b68be157b52f8edecc44054f1ad.jpg', 0, 0, 39.99),
(4, 3, 'Zestaw Lego Większy', 'Większy od Mniejszego', '9e2519235c16b7cf1986521736f0b4e9.jpg', 0, 0, 99.99),
(5, 3, 'Zestaw Lego Spory', '2000 klocków to sporo', '26395c5567457a37ea708ebadf76abb6.jpg', 0, 0, 740.01),
(6, 3, 'Zestaw Lego z Pod Lady', 'Na taki to trzeba wydać. Bydlak jeden', '05c0bb6b4f45da061932380dfbc4fe90.jpg', 0, 1, 2000),
(7, 6, 'Bandit Power Zasilacz 3000W 99Plus Podwójny Diament', 'Najlepszy zasilacz na rynku. Niezawodny. Istna bomba!', 'b86c4b5301d9c52bb11bb8d7c746669c.jpg', 0, 0, 75.99),
(8, 6, 'Thermaltake Zasilacz 1200W', 'Taki fajny kolorowy. Cuda nie widy', '7df96738563849d4eea79eb11dfb9175.jpg', 0, 0, 401.77),
(9, 4, 'NVIDIA RTX 5099tiXL 128GB 470°C 4520W', 'Grzanie domu nie było nigdy prostrze', 'e1b60f1d2e2b704cdc974386eca75abe.jpg', 0, 0, 0.71);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `roles`, `password`) VALUES
(1, 'admin', '[]', '$2y$13$O4o.4oLghX4FR9aJW6lDiuCfOZZ1Llevg.bwlfNIi4pbRsMKIrLga'),
(2, 'magazyn', '[]', '$2y$13$ADddr5MMlnEqliQJL9TI/ese.0i040hljQGVE.YEOAhyAg0/txes6'),
(3, 'user', '[]', '$2y$13$bO3B5dnoRRd0xmRDxCg1TejhJLvb301TQZYfJCjZwvy03b14DOSsC');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indeksy dla tabeli `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_F52993984C3A3BB` (`payment_id`),
  ADD KEY `IDX_F5299398A76ED395` (`user_id`),
  ADD KEY `IDX_F52993984887F3F8` (`shipping_id`);

--
-- Indeksy dla tabeli `order_product_entry`
--
ALTER TABLE `order_product_entry`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_271EAEFEE238517C` (`order_ref_id`),
  ADD KEY `IDX_271EAEFE126F525E` (`item_id`);

--
-- Indeksy dla tabeli `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6D28840D19883967` (`method_id`),
  ADD KEY `IDX_6D28840D6BF700BD` (`status_id`);

--
-- Indeksy dla tabeli `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `payment_status`
--
ALTER TABLE `payment_status`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `shipping`
--
ALTER TABLE `shipping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2D1C172419883967` (`method_id`),
  ADD KEY `IDX_2D1C17246BF700BD` (`status_id`),
  ADD KEY `IDX_2D1C1724F5B7AF75` (`address_id`);

--
-- Indeksy dla tabeli `shipping_address`
--
ALTER TABLE `shipping_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_EB066945A76ED395` (`user_id`);

--
-- Indeksy dla tabeli `shipping_method`
--
ALTER TABLE `shipping_method`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `shipping_status`
--
ALTER TABLE `shipping_status`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `shop_item`
--
ALTER TABLE `shop_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DEE9C36512469DE2` (`category_id`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_product_entry`
--
ALTER TABLE `order_product_entry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment_status`
--
ALTER TABLE `payment_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `shipping`
--
ALTER TABLE `shipping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_address`
--
ALTER TABLE `shipping_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_method`
--
ALTER TABLE `shipping_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shipping_status`
--
ALTER TABLE `shipping_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `shop_item`
--
ALTER TABLE `shop_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `FK_F52993984887F3F8` FOREIGN KEY (`shipping_id`) REFERENCES `shipping` (`id`),
  ADD CONSTRAINT `FK_F52993984C3A3BB` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`id`),
  ADD CONSTRAINT `FK_F5299398A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `order_product_entry`
--
ALTER TABLE `order_product_entry`
  ADD CONSTRAINT `FK_271EAEFE126F525E` FOREIGN KEY (`item_id`) REFERENCES `shop_item` (`id`),
  ADD CONSTRAINT `FK_271EAEFEE238517C` FOREIGN KEY (`order_ref_id`) REFERENCES `order` (`id`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `FK_6D28840D19883967` FOREIGN KEY (`method_id`) REFERENCES `payment_method` (`id`),
  ADD CONSTRAINT `FK_6D28840D6BF700BD` FOREIGN KEY (`status_id`) REFERENCES `payment_status` (`id`);

--
-- Constraints for table `shipping`
--
ALTER TABLE `shipping`
  ADD CONSTRAINT `FK_2D1C172419883967` FOREIGN KEY (`method_id`) REFERENCES `shipping_method` (`id`),
  ADD CONSTRAINT `FK_2D1C17246BF700BD` FOREIGN KEY (`status_id`) REFERENCES `shipping_status` (`id`),
  ADD CONSTRAINT `FK_2D1C1724F5B7AF75` FOREIGN KEY (`address_id`) REFERENCES `shipping_address` (`id`);

--
-- Constraints for table `shipping_address`
--
ALTER TABLE `shipping_address`
  ADD CONSTRAINT `FK_EB066945A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `shop_item`
--
ALTER TABLE `shop_item`
  ADD CONSTRAINT `FK_DEE9C36512469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
