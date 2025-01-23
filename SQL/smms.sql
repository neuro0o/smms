-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 23, 2025 at 07:36 AM
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
  `accommodationImg` varchar(255) NOT NULL,
  `accommodationDesc` varchar(1000) NOT NULL,
  `accommodationPrice` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accommodation`
--

INSERT INTO `accommodation` (`accommodationID`, `accommodationName`, `accommodationImg`, `accommodationDesc`, `accommodationPrice`) VALUES
(1, 'Oceanview Suite', 'IMAGES/ACCOMMODATION/OceanviewRoom.jpg', 'A spacious room offering stunning views of the ocean with modern amenities. Perfect for a relaxing getaway.', 1250.00),
(2, 'Mountain Retreat Room', 'IMAGES/ACCOMMODATION/MountainRetreatRoom.jpg', 'A cozy room nestled at the foot of the mountains, designed for a peaceful and scenic stay.', 350.00),
(3, 'Garden Paradise Room', 'IMAGES/ACCOMMODATION/GardenParadiseRoom.jpg', 'A serene room overlooking beautifully landscaped gardens. Enjoy tranquility and nature from your private balcony.', 400.00),
(4, 'Cityscape Deluxe', 'IMAGES/ACCOMMODATION/CityscapeDeluxe.jpeg', 'A chic and modern room with panoramic views of the city skyline, perfect for business and leisure stays.', 500.00),
(5, 'Skyline Penthouse', 'IMAGES/ACCOMMODATION/SkylinePenthouse.jpg', 'A luxurious penthouse suite with a private terrace, offering unmatched views of the city’s skyline and ultimate comfort.', 1000.00);

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `activityID` int(11) NOT NULL,
  `activityName` varchar(255) NOT NULL,
  `activityImg` varchar(255) NOT NULL,
  `activityCategory` int(11) NOT NULL,
  `activityDesc` varchar(1000) NOT NULL,
  `activityPrice` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`activityID`, `activityName`, `activityImg`, `activityCategory`, `activityDesc`, `activityPrice`) VALUES
(1, 'Scuba Diving', 'IMAGES/ACTIVITY/ScubaDiving.jpg', 1, 'Explore the underwater world with a guided scuba diving session. Suitable for beginners and advanced divers.', 250.00),
(2, 'Mountain Trekking', 'IMAGES/ACTIVITY/MountainTrekking.jpg', 2, 'A thrilling hike through the lush mountains, guided by an experienced trek leader. Experience nature at its finest.', 150.00),
(3, 'Cooking Class', 'IMAGES/ACTIVITY/CookingClass.jpg', 3, 'Learn to prepare local dishes with expert chefs. A fun and interactive activity for food enthusiasts.', 120.00),
(4, 'Sunset Cruise', 'IMAGES/ACTIVITY/SunsetCruise.jpg', 1, 'Enjoy a relaxing evening on the water with a sunset cruise, complete with drinks and light snacks.', 180.00),
(5, 'Zipline Adventure', 'IMAGES/ACTIVITY/ZiplineAdventure.jpg', 2, 'Experience an adrenaline rush while soaring across valleys and forests with a thrilling zipline adventure.', 60.00),
(6, 'Art Workshop', 'IMAGES/ACTIVITY/ArtWorkshop.jpeg', 3, 'Unleash your creativity in an art workshop, where you can create your own masterpiece using various mediums.', 80.00);

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
  `foodImg` varchar(255) NOT NULL,
  `foodCategory` int(11) NOT NULL,
  `foodDesc` varchar(1000) NOT NULL,
  `foodPrice` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`foodID`, `foodName`, `foodImg`, `foodCategory`, `foodDesc`, `foodPrice`) VALUES
(1, 'Savory Party Bread', '../../IMAGES/FOOD/food1.jpg', 1, 'Warm, cheesy, onion bread perfect for snacking or parties.', 10.90),
(2, 'Teriyaki Pineapple Meatballs', '../../IMAGES/FOOD/food2.jpg', 1, 'Meatballs with teriyaki glaze and pineapple, a family-favorite dish.', 12.90),
(3, 'Fruit Charcuterie Board', '../../IMAGES/FOOD/food3.jpg', 1, 'Seasonal fruits arranged for a visually stunning and tasty treat.', 8.90),
(4, 'Hot Spinach Artichoke Dip', '../../IMAGES/FOOD/food4.jpg', 1, 'Savory artichoke, spinach, and Parmesan dip, irresistibly addictive.', 14.90),
(5, 'Orange-Glazed Meatballs', '../../IMAGES/FOOD/food5.jpg', 1, 'Meatballs in a sweet and tangy orange jalapeño glaze.', 11.90),
(6, 'Chicken Parm Sliders', '../../IMAGES/FOOD/food6.jpg', 1, 'Mini chicken Parmesan sliders, perfect for parties or casual dinners.', 8.90),
(7, 'Potsticker Soup', '../../IMAGES/FOOD/food7.jpg', 2, 'Light soup with dumplings, savory vegetables, and mushrooms in flavorful broth.', 9.90),
(8, 'Chicken Soup', '../../IMAGES/FOOD/food8.jpg', 2, 'Classic chicken soup, hearty and comforting for every occasion.', 11.50),
(9, 'Beef Soup', '../../IMAGES/FOOD/food9.jpg', 2, 'Rich and savory soup made with tender beef and spices.', 13.00),
(10, 'French Onion Soup', '../../IMAGES/FOOD/food10.jpg', 2, 'Caramelized onions in broth topped with melted cheese.', 15.90),
(11, 'Creamy Chicken and Rice Soup', '../../IMAGES/FOOD/food11.jpg', 2, 'Comforting soup with chicken, rice, and a creamy base.', 8.50),
(12, 'English Pub Split Pea Soup', '../../IMAGES/FOOD/food12.jpg', 2, 'Hearty pea soup inspired by classic English pub recipes.', 7.90),
(13, 'Mee bandung', '../../IMAGES/FOOD/food13.jpg', 3, 'Traditional noodle dish in rich, savory broth with local spices.', 14.90),
(14, 'Khao soi', '../../IMAGES/FOOD/food14.jpg', 3, 'Thai-inspired curry noodle soup with creamy coconut base.', 10.50),
(15, 'Pho', '../../IMAGES/FOOD/food15.jpg', 3, 'Vietnamese noodle soup with aromatic broth and fresh herbs.', 12.50),
(16, 'Quang-Style Noodles', '../../IMAGES/FOOD/food16.jpg', 3, 'Vietnamese turmeric noodles with rich flavors and fresh herbs.', 6.90),
(17, 'Southern Vietnamese Beef Noodle Salad', '../../IMAGES/FOOD/food17.jpg', 3, 'Refreshing noodle salad with beef, herbs, and savory dressing.', 5.50),
(18, 'Ohn no khao swè', '../../IMAGES/FOOD/food18.jpg', 3, 'Burmese coconut chicken noodles with savory and spicy flavors.', 9.00),
(19, 'Kimchi Fried Rice', '../../IMAGES/FOOD/food19.jpg', 4, 'Spicy fried rice with kimchi and bold Korean flavors.', 13.90),
(20, 'Shrimp Fried Rice', '../../IMAGES/FOOD/food20.jpg', 4, 'Classic fried rice with succulent shrimp and savory seasoning.', 7.90),
(21, 'Nasi Goreng', '../../IMAGES/FOOD/food21.jpg', 4, 'Indonesian-style fried rice with sweet soy sauce and spices.', 14.00),
(22, 'Garlic Fried Rice', '../../IMAGES/FOOD/food22.jpg', 4, 'Fragrant fried rice infused with garlic and umami flavors.', 12.90),
(23, 'Egg Fried Rice', '../../IMAGES/FOOD/food23.jpg', 4, 'Simple yet flavorful fried rice with egg and seasonings.', 16.50),
(24, 'Chicken Fried Rice', '../../IMAGES/FOOD/food24.jpg', 4, 'Delicious fried rice with tender chicken pieces and vegetables.', 6.50),
(25, 'Pasta Salad', '../../IMAGES/FOOD/food25.jpg', 5, 'Chilled pasta salad with fresh vegetables and tangy dressing.', 18.00),
(26, 'Cheesecake Factory Pasta da Vinci', '../../IMAGES/FOOD/food26.jpg', 5, 'Rich, creamy pasta with mushrooms and chicken, inspired by a classic.', 8.90),
(27, 'Pasta Bake with Feta', '../../IMAGES/FOOD/food27.jpg', 5, 'Baked pasta topped with creamy feta cheese and tomatoes.', 9.90),
(28, 'Pasta Napolitana', '../../IMAGES/FOOD/food28.jpg', 5, 'Traditional Italian pasta with rich tomato sauce and herbs.', 7.00),
(29, 'Pasta with Prosciutto and Peas', '../../IMAGES/FOOD/food29.jpg', 5, 'Classic pasta with prosciutto, peas, and light creamy sauce.', 5.90),
(30, 'Pasta with Creamy Fennel Sauce', '../../IMAGES/FOOD/food30.jpg', 5, 'Pasta served with a smooth fennel cream sauce.', 6.50),
(31, 'Beef Burger', '../../IMAGES/FOOD/food31.jpg', 6, 'Juicy beef burger with classic toppings on a toasted bun.', 12.50),
(32, 'Cajun Fries with Malt Vinegar Sauce', '../../IMAGES/FOOD/food32.jpg', 6, 'Crispy fries seasoned with Cajun spices, served with tangy dip.', 9.90),
(33, 'Apple Pie Egg Rolls', '../../IMAGES/FOOD/food33.jpg', 6, 'Crispy rolls filled with spiced apple pie filling.', 6.90),
(34, 'Creole Shrimp Pizza', '../../IMAGES/FOOD/food34.jpg', 6, 'Thin crust pizza topped with Creole-spiced shrimp.', 11.00),
(35, 'Macaroni Chicken Casserole', '../../IMAGES/FOOD/food35.jpg', 6, 'Creamy macaroni casserole with tender chicken and cheese.', 14.90),
(36, 'Mashed Potatoes and Gravy', '../../IMAGES/FOOD/food36.jpg', 6, 'Creamy mashed potatoes topped with savory gravy.', 8.90),
(37, 'Banana Cakes', '../../IMAGES/FOOD/food37.jpg', 7, 'Moist banana cake with a hint of cinnamon and sweetness.', 13.90),
(38, 'Chocolate Mousse Cakes', '../../IMAGES/FOOD/food38.jpg', 7, 'Rich chocolate mousse layered into decadent cakes.', 9.00),
(39, 'Carrot Cakes', '../../IMAGES/FOOD/food39.jpg', 7, 'Classic carrot cake with cream cheese frosting.', 12.90),
(40, 'Tiramisu', '../../IMAGES/FOOD/food40.jpg', 7, 'Classic Italian dessert with espresso and mascarpone layers.', 10.00),
(41, 'New York Baked Cheesecake', '../../IMAGES/FOOD/food41.jpg', 7, 'Creamy baked cheesecake with a graham cracker crust.', 8.50),
(42, 'Vanilla Ice Cream with Chocolate', '../../IMAGES/FOOD/food42.jpg', 7, 'Vanilla ice cream drizzled with rich chocolate syrup.', 15.90),
(43, 'Salad Sandwiches with Chicken', '../../IMAGES/FOOD/food43.jpg', 8, 'Fresh salad sandwiches with chicken, lettuce, and light dressing.', 11.50),
(44, 'Mega Salad Sandwiches', '../../IMAGES/FOOD/food44.jpg', 8, 'Hearty sandwiches with layers of fresh vegetables and spreads.', 10.90),
(45, 'French Toast Sandwiches with Cheese', '../../IMAGES/FOOD/food45.jpg', 8, 'Sweet French toast sandwiches filled with melted cheese.', 9.90),
(46, 'Orange Mint Ice Tea', '../../IMAGES/FOOD/food46.jpg', 9, 'Refreshing iced tea with orange and mint flavors.', 14.50),
(47, 'Cha Bang Ang', '../../IMAGES/FOOD/food47.jpg', 9, 'Popular Malaysian dessert with ice, syrup, and sweet toppings.', 8.90),
(48, 'Longan Limau Gula Melaka', '../../IMAGES/FOOD/food48.jpg', 9, 'Sweet dessert with longan, lime, and palm sugar syrup.', 13.90),
(49, 'Sirap Bandung Float', '../../IMAGES/FOOD/food49.jpg', 9, 'Classic rose-flavored drink topped with vanilla ice cream.', 10.50),
(50, 'Mangga Susu', '../../IMAGES/FOOD/food50.jpg', 9, 'Creamy mango drink with milk, perfect for a tropical treat.', 6.90),
(51, 'Tembikai Susu', '../../IMAGES/FOOD/food51.jpg', 9, 'Watermelon drink mixed with sweetened milk for a refreshing taste.', 7.90);

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
  MODIFY `accommodationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `activityID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `foodID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

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
