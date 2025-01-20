-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2025 at 09:10 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smms`
--

-- --------------------------------------------------------

--
-- Table structure for table `accommodation`
--

CREATE TABLE `accommodation` (
  `accommodationID` int(11) NOT NULL,
  `accommodationName` varchar(255) NOT NULL,
  `accommodationDesc` varchar(1000) NOT NULL,
  `accommodationPrice` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `activityID` int(11) NOT NULL,
  `activityName` varchar(255) NOT NULL,
  `activityCategory` int(11) NOT NULL,
  `activityDesc` varchar(1000) NOT NULL,
  `activityPrice` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_category`
--

CREATE TABLE `activity_category` (
  `categoryID` int(11) NOT NULL,
  `categoryName` varchar(255) NOT NULL COMMENT '1:Water 2:Land 3:Creative 4:Special'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_category`
--

INSERT INTO `activity_category` (`categoryID`, `categoryName`) VALUES
(1, 'Water'),
(2, 'Land'),
(3, 'Creative'),
(4, 'Special');

-- --------------------------------------------------------

--
-- Table structure for table `activity_purchase`
--

CREATE TABLE `activity_purchase` (
  `apID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `purchaseDate` date NOT NULL,
  `purchaseAmt` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_purchase_detail`
--

CREATE TABLE `activity_purchase_detail` (
  `lineID` int(11) NOT NULL,
  `apID` int(11) NOT NULL,
  `activityID` int(11) NOT NULL,
  `purchaseQty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `foodID` int(11) NOT NULL,
  `foodName` varchar(255) NOT NULL,
  `foodCategory` int(11) NOT NULL,
  `foodDesc` varchar(1000) NOT NULL,
  `foodPrice` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_category`
--

CREATE TABLE `food_category` (
  `categoryID` int(11) NOT NULL,
  `categoryName` varchar(255) NOT NULL COMMENT '1:Appetizer 2:Soup 3:Asian Noodles 4:Asian Rice Dish 5:Pasta 6:Western Selection 7:Dessert 8:Snack and Sandwich 9:Beverage'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_category`
--

INSERT INTO `food_category` (`categoryID`, `categoryName`) VALUES
(1, 'Appetizer'),
(2, 'Soup'),
(3, 'Asian Noodles'),
(4, 'Asian Rice Dish'),
(5, 'pasta'),
(6, 'Western Selection'),
(7, 'Dessert'),
(8, 'Snack and Sandwich'),
(9, 'Beverage');

-- --------------------------------------------------------

--
-- Table structure for table `food_purchase`
--

CREATE TABLE `food_purchase` (
  `fpID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `purchaseDate` date NOT NULL,
  `purchaseAmt` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_purchase_detail`
--

CREATE TABLE `food_purchase_detail` (
  `lineID` int(11) NOT NULL,
  `fpID` int(11) NOT NULL,
  `foodID` int(11) NOT NULL,
  `purchaseQty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `reservationID` int(11) NOT NULL,
  `dateFrom` date NOT NULL,
  `dateUntil` date NOT NULL,
  `totalAmt` decimal(8,2) NOT NULL,
  `reservedBy` int(11) NOT NULL,
  `accommodationID` int(11) NOT NULL,
  `reservationStatus` int(11) NOT NULL COMMENT '1: Confirm 2:Cancel 3:Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review_activity`
--

CREATE TABLE `review_activity` (
  `reviewID` int(11) NOT NULL,
  `reviewText` varchar(1000) NOT NULL,
  `reviewRating` int(11) NOT NULL COMMENT 'Lowest(1) - Highest(5)',
  `reviewDate` date NOT NULL,
  `reviewedBy` int(11) NOT NULL,
  `activityID` int(11) NOT NULL,
  `apID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review_food`
--

CREATE TABLE `review_food` (
  `reviewID` int(11) NOT NULL,
  `reviewText` varchar(1000) NOT NULL,
  `reviewRating` int(11) NOT NULL COMMENT 'Lowest(1) - Highest(5)',
  `reviewDate` date NOT NULL,
  `reviewedBy` int(11) NOT NULL,
  `foodID` int(11) NOT NULL,
  `fpID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review_reservation`
--

CREATE TABLE `review_reservation` (
  `reviewID` int(11) NOT NULL,
  `reviewText` varchar(1000) NOT NULL,
  `reviewRating` int(11) NOT NULL COMMENT 'Lowest(1) - Highest(5)',
  `reviewDate` date NOT NULL,
  `reviewedBy` int(11) NOT NULL,
  `accommodationID` int(11) NOT NULL,
  `reservationID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `userName` varchar(255) NOT NULL,
  `userPwd` varchar(255) NOT NULL,
  `userImg` varchar(255) NOT NULL,
  `userRole` int(11) NOT NULL COMMENT '1:Admin 2:User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `userEmail`, `userName`, `userPwd`, `userImg`, `userRole`) VALUES
(1, 'admin@email.com', 'admin', '$2y$10$dCcKRaRIkMofMQFHJoJdhe.hVS1NuHCwpUKfCO7Qo1GM9D0TyMmBy', '/IMAGES/PROFILE/default.png', 1),
(2, 'user1@email.com', 'user1', '$2y$10$wfh1eN7mInFUa/2FWQYfMeSlsq2z4Ak8VCscxpdWna0LELNX9bvtS', '/IMAGES/PROFILE/default.png', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accommodation`
--
ALTER TABLE `accommodation`
  ADD PRIMARY KEY (`accommodationID`);

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`activityID`),
  ADD KEY `activityCategory` (`activityCategory`);

--
-- Indexes for table `activity_category`
--
ALTER TABLE `activity_category`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `activity_purchase`
--
ALTER TABLE `activity_purchase`
  ADD PRIMARY KEY (`apID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `activity_purchase_detail`
--
ALTER TABLE `activity_purchase_detail`
  ADD PRIMARY KEY (`lineID`),
  ADD KEY `apID` (`apID`),
  ADD KEY `activityID` (`activityID`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`foodID`),
  ADD KEY `foodCategory` (`foodCategory`);

--
-- Indexes for table `food_category`
--
ALTER TABLE `food_category`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `food_purchase`
--
ALTER TABLE `food_purchase`
  ADD PRIMARY KEY (`fpID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `food_purchase_detail`
--
ALTER TABLE `food_purchase_detail`
  ADD PRIMARY KEY (`lineID`),
  ADD KEY `fpID` (`fpID`),
  ADD KEY `foodID` (`foodID`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`reservationID`),
  ADD KEY `reservedBy` (`reservedBy`),
  ADD KEY `accommodationID` (`accommodationID`);

--
-- Indexes for table `review_activity`
--
ALTER TABLE `review_activity`
  ADD PRIMARY KEY (`reviewID`),
  ADD KEY `reviewedBy` (`reviewedBy`),
  ADD KEY `activityID` (`activityID`),
  ADD KEY `apID` (`apID`);

--
-- Indexes for table `review_food`
--
ALTER TABLE `review_food`
  ADD PRIMARY KEY (`reviewID`),
  ADD KEY `reviewedBy` (`reviewedBy`),
  ADD KEY `foodID` (`foodID`),
  ADD KEY `fpID` (`fpID`);

--
-- Indexes for table `review_reservation`
--
ALTER TABLE `review_reservation`
  ADD PRIMARY KEY (`reviewID`),
  ADD KEY `reviewedBy` (`reviewedBy`),
  ADD KEY `accommodationID` (`accommodationID`),
  ADD KEY `reservationID` (`reservationID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accommodation`
--
ALTER TABLE `accommodation`
  MODIFY `accommodationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `activityID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_category`
--
ALTER TABLE `activity_category`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `activity_purchase`
--
ALTER TABLE `activity_purchase`
  MODIFY `apID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_purchase_detail`
--
ALTER TABLE `activity_purchase_detail`
  MODIFY `lineID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `foodID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food_category`
--
ALTER TABLE `food_category`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `food_purchase`
--
ALTER TABLE `food_purchase`
  MODIFY `fpID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `food_purchase_detail`
--
ALTER TABLE `food_purchase_detail`
  MODIFY `lineID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `reservationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review_activity`
--
ALTER TABLE `review_activity`
  MODIFY `reviewID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review_food`
--
ALTER TABLE `review_food`
  MODIFY `reviewID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review_reservation`
--
ALTER TABLE `review_reservation`
  MODIFY `reviewID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `activity_ibfk_1` FOREIGN KEY (`activityCategory`) REFERENCES `activity_category` (`categoryID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `activity_purchase`
--
ALTER TABLE `activity_purchase`
  ADD CONSTRAINT `activity_purchase_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `activity_purchase_detail`
--
ALTER TABLE `activity_purchase_detail`
  ADD CONSTRAINT `activity_purchase_detail_ibfk_1` FOREIGN KEY (`apID`) REFERENCES `activity_purchase` (`apID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `activity_purchase_detail_ibfk_2` FOREIGN KEY (`activityID`) REFERENCES `activity` (`activityID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `food`
--
ALTER TABLE `food`
  ADD CONSTRAINT `food_ibfk_1` FOREIGN KEY (`foodCategory`) REFERENCES `food_category` (`categoryID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `food_purchase`
--
ALTER TABLE `food_purchase`
  ADD CONSTRAINT `food_purchase_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `food_purchase_detail`
--
ALTER TABLE `food_purchase_detail`
  ADD CONSTRAINT `food_purchase_detail_ibfk_1` FOREIGN KEY (`fpID`) REFERENCES `food_purchase` (`fpID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `food_purchase_detail_ibfk_2` FOREIGN KEY (`foodID`) REFERENCES `food` (`foodID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`reservedBy`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`accommodationID`) REFERENCES `accommodation` (`accommodationID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_activity`
--
ALTER TABLE `review_activity`
  ADD CONSTRAINT `review_activity_ibfk_1` FOREIGN KEY (`reviewedBy`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_activity_ibfk_2` FOREIGN KEY (`activityID`) REFERENCES `activity` (`activityID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_activity_ibfk_3` FOREIGN KEY (`apID`) REFERENCES `activity_purchase` (`apID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_food`
--
ALTER TABLE `review_food`
  ADD CONSTRAINT `review_food_ibfk_1` FOREIGN KEY (`reviewedBy`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_food_ibfk_2` FOREIGN KEY (`foodID`) REFERENCES `food` (`foodID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_food_ibfk_3` FOREIGN KEY (`fpID`) REFERENCES `food_purchase` (`fpID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_reservation`
--
ALTER TABLE `review_reservation`
  ADD CONSTRAINT `review_reservation_ibfk_1` FOREIGN KEY (`reviewedBy`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_reservation_ibfk_2` FOREIGN KEY (`accommodationID`) REFERENCES `accommodation` (`accommodationID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_reservation_ibfk_3` FOREIGN KEY (`reservationID`) REFERENCES `reservation` (`reservationID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
