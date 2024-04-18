-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 28, 2023 at 04:56 AM
-- Server version: 10.5.20-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id20954565_kbufc_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE `Admin` (
  `username` varchar(200) NOT NULL,
  `password` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Auction`
--

CREATE TABLE `Auction` (
  `auction_id` int(11) NOT NULL,
  `itemsName` varchar(200) NOT NULL,
  `image` text NOT NULL,
  `startPrice` double NOT NULL,
  `endPrice` double NOT NULL,
  `startDateTime` datetime NOT NULL,
  `endDateTime` datetime NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Bid`
--

CREATE TABLE `Bid` (
  `bid_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `bidDateTime` datetime NOT NULL,
  `winningBid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Cart`
--

CREATE TABLE `Cart` (
  `cart_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `totalPrice` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Cart_Items`
--

CREATE TABLE `Cart_Items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `LeagueTable`
--

CREATE TABLE `LeagueTable` (
  `table_id` int(11) NOT NULL,
  `title` varchar(40) NOT NULL,
  `image` text NOT NULL,
  `link` text NOT NULL,
  `season` varchar(9) NOT NULL,
  `week` int(3) NOT NULL,
  `update_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `LeagueTable`
--

INSERT INTO `LeagueTable` (`table_id`, `title`, `image`, `link`, `season`, `week`, `update_date`) VALUES
(1, 'Thai League 3 BKK มังกรฟ้าลีก 2021/22', 'img/Buriram_United_logo.svg.png', 'https://kbufc.000webhostapp.com/kbu-backoffice/leagueTable/img/Buriram_United_logo.svg.png', '2021/2022', 26, '2023-07-18'),
(2, 'Thai League 3 BKK กองสลากพลัสลีก 2022/23', 'img/table.jpg', 'https://kbufc.000webhostapp.com/kbu-backoffice/leagueTable/img/table.jpg', '2022/2023', 26, '2023-07-18'),
(3, 'Thaileague 3 BKK Tesla league 2023/2024', 'img/j8erjGzCJInBD1S02dZI.jpg', 'https://kbufc.000webhostapp.com/kbu-backoffice/leagueTable/img/j8erjGzCJInBD1S02dZI.jpg', '2023/2024', 1, '2023-07-20');

-- --------------------------------------------------------

--
-- Table structure for table `Match_Fixture`
--

CREATE TABLE `Match_Fixture` (
  `match_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `matchDate` date NOT NULL,
  `matchTime` time NOT NULL,
  `homeScore` int(11) NOT NULL,
  `awayScore` int(11) NOT NULL,
  `stadium` varchar(100) NOT NULL,
  `season` varchar(9) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Match_Fixture`
--

INSERT INTO `Match_Fixture` (`match_id`, `title`, `matchDate`, `matchTime`, `homeScore`, `awayScore`, `stadium`, `season`, `status`) VALUES
(2, 'Thaileague3 BKK', '2023-07-10', '15:30:00', 3, 2, 'Kasembundit University ', '2023/2024', 'live'),
(3, 'Thaileague3 BKK', '2023-07-20', '15:30:00', 0, 0, 'Anfield', '2023/2024', 'end');

-- --------------------------------------------------------

--
-- Table structure for table `Match_Team`
--

CREATE TABLE `Match_Team` (
  `match_team_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `match_team_status` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Match_Team`
--

INSERT INTO `Match_Team` (`match_team_id`, `match_id`, `team_id`, `match_team_status`) VALUES
(1, 2, 1, 'home'),
(2, 2, 2, 'away'),
(3, 3, 8, 'home'),
(4, 3, 1, 'away');

-- --------------------------------------------------------

--
-- Table structure for table `Member`
--

CREATE TABLE `Member` (
  `member_id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(60) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `address` varchar(300) NOT NULL,
  `points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Member`
--

INSERT INTO `Member` (`member_id`, `username`, `password`, `mail`, `firstname`, `lastname`, `phone`, `address`, `points`) VALUES
(1, 'supakritpetpon17', 'aa', 'supakritpetpon17@gmail.com', 'supakrit', 'petpon', '0954607529', 'home', 1),
(20, 'ddddddddd', 'Darkpiano9999@', 'darkpiano999@hotmail.com', 'daewd', 'wdqd', '2122222222', 'awd', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Merchandise`
--

CREATE TABLE `Merchandise` (
  `item_id` int(11) NOT NULL,
  `itemsName` varchar(200) NOT NULL,
  `image` text NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `News`
--

CREATE TABLE `News` (
  `news_id` int(11) NOT NULL,
  `image` text NOT NULL,
  `link` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `News`
--

INSERT INTO `News` (`news_id`, `image`, `link`, `title`, `description`, `date`) VALUES
(14, 'img/darkpiano.jpg', 'https://kbufc.000webhostapp.com/kbu-backoffice/news/img/darkpiano.jpg', 'Official Renew Contract : Dark chocolate is suck', ' THOMAS CHINOSO striker from nigeria\r\nhe still stay with lion rom-klao kasem bundit university and he should be a new dark chocolate\r\n2023-2024', '2023-07-18'),
(15, 'img/361086721_743263654466881_3610409451014605703_n.jpg', 'https://kbufc.000webhostapp.com/kbu-backoffice/news/img/361086721_743263654466881_3610409451014605703_n.jpg', 'Full Time : Woman result football in  Thai Womens League 2023', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2023-07-18'),
(16, 'img/Airforce_United.png', 'https://kbufc.000webhostapp.com/kbu-backoffice/news/img/Airforce_United.png', 'How TO - Custom Select Box like a boss', 'The border will remain red as long as the error is present, and the green checkmark will be removed. Once the user corrects the selection or chooses different teams, the border will turn green again to indicate that the selection is valid, and the green checkmark will not be shown.', '2023-07-20');

-- --------------------------------------------------------

--
-- Table structure for table `Order_items`
--

CREATE TABLE `Order_items` (
  `order_items_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `items_id` int(11) DEFAULT NULL,
  `auction_id` int(11) DEFAULT NULL,
  `quanity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Order_Member`
--

CREATE TABLE `Order_Member` (
  `order_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `orderDate` date NOT NULL,
  `orderStatus` varchar(50) NOT NULL,
  `shippingCost` double NOT NULL,
  `trackingNumber` varchar(30) NOT NULL,
  `shippingCompany` varchar(30) NOT NULL,
  `paymentTotalprice` double NOT NULL,
  `vat` double NOT NULL,
  `nonVatPrice` double NOT NULL,
  `paymentDateTime` datetime NOT NULL,
  `paymentStatus` varchar(50) NOT NULL,
  `orderType` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Order_Member`
--

INSERT INTO `Order_Member` (`order_id`, `member_id`, `orderDate`, `orderStatus`, `shippingCost`, `trackingNumber`, `shippingCompany`, `paymentTotalprice`, `vat`, `nonVatPrice`, `paymentDateTime`, `paymentStatus`, `orderType`) VALUES
(1, 1, '2023-07-10', 'OrderPlaced', 1, '111111', 'ket', 1111, 1, 11, '2023-07-10 00:00:00', 'Waiting', 'Merchandise');

-- --------------------------------------------------------

--
-- Table structure for table `Players`
--

CREATE TABLE `Players` (
  `players_id` int(11) NOT NULL,
  `firstName` varchar(45) NOT NULL,
  `lastName` varchar(45) NOT NULL,
  `players_number` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `image` text NOT NULL,
  `link` text NOT NULL,
  `age` varchar(4) NOT NULL,
  `height` varchar(10) NOT NULL,
  `weight` varchar(10) NOT NULL,
  `appearances` int(11) NOT NULL,
  `scored` int(11) NOT NULL,
  `position` varchar(50) NOT NULL,
  `country` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `joined` date NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Players`
--

INSERT INTO `Players` (`players_id`, `firstName`, `lastName`, `players_number`, `team_id`, `image`, `link`, `age`, `height`, `weight`, `appearances`, `scored`, `position`, `country`, `dob`, `joined`, `status`) VALUES
(1, 'chutidech', 'anugoolwattaka', 12, 1, 'img/316126949_3440887819480585_3516958542393722934_n.jpg', 'https://kbufc.000webhostapp.com/kbu-backoffice/players/img/316126949_3440887819480585_3516958542393722934_n.jpg', '20', '181', '78', 171, 20, 'Forward', 'Thailand', '2002-08-16', '2019-11-11', 'stay'),
(2, 'Supakrit', 'Petpon', 99, 1, 'img/pic1.jpg', 'https://kbufc.000webhostapp.com/kbu-backoffice/players/img/pic1.jpg', '22', '182', '74', 185, 71, 'Forward', 'Thailand', '2001-03-09', '2023-05-31', 'stay'),
(3, 'Attaphon', 'Kannoo', 34, 1, 'img/black.jpg', 'https://kbufc.000webhostapp.com/kbu-backoffice/players/img/black.jpg', '31', '175', '71', 185, 51, 'Midfielder', 'Thailand', '1991-08-25', '2020-09-07', 'stay'),
(4, 'Prasert', 'Pattawin', 22, 1, 'img/darkpiano.jpg', 'https://kbufc.000webhostapp.com/kbu-backoffice/players/img/darkpiano.jpg', '30', '190', '78', 108, 41, 'Forward', 'Thailand', '1992-10-20', '2021-07-12', 'stay'),
(5, 'Chaiyasan', 'Homboon', 8, 1, 'img/361086721_743263654466881_3610409451014605703_n.jpg', 'https://kbufc.000webhostapp.com/kbu-backoffice/players/img/361086721_743263654466881_3610409451014605703_n.jpg', '30', '183', '77', 50, 16, 'Midfielder', 'Thailand', '1992-11-10', '2020-05-14', 'stay');

-- --------------------------------------------------------

--
-- Table structure for table `Player_Score`
--

CREATE TABLE `Player_Score` (
  `playerScore_id` int(11) NOT NULL,
  `players_id` int(11) NOT NULL,
  `opponentPlayers` varchar(45) NOT NULL,
  `match_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Predict`
--

CREATE TABLE `Predict` (
  `predict_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL,
  `predict_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Predict`
--

INSERT INTO `Predict` (`predict_id`, `match_id`, `startTime`, `endTime`, `predict_status`) VALUES
(5, 2, '15:15:00', '15:30:00', 'soon'),
(7, 3, '15:15:00', '15:30:00', 'end');

-- --------------------------------------------------------

--
-- Table structure for table `Prediction`
--

CREATE TABLE `Prediction` (
  `prediction_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `predict_id` int(11) NOT NULL,
  `homeScore` int(11) NOT NULL,
  `awayScore` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Prediction`
--

INSERT INTO `Prediction` (`prediction_id`, `member_id`, `predict_id`, `homeScore`, `awayScore`) VALUES
(1, 1, 5, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `Record`
--

CREATE TABLE `Record` (
  `record_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `activityType` varchar(50) NOT NULL,
  `recordDateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `StaffCoach`
--

CREATE TABLE `StaffCoach` (
  `staff_id` int(11) NOT NULL,
  `firstName` varchar(45) NOT NULL,
  `lastName` varchar(45) NOT NULL,
  `team_id` int(11) NOT NULL,
  `image` text NOT NULL,
  `link` text NOT NULL,
  `age` varchar(4) NOT NULL,
  `position` varchar(50) NOT NULL,
  `country` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `joined` date NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `StartingXI`
--

CREATE TABLE `StartingXI` (
  `startXi_id` int(11) NOT NULL,
  `players_id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Team`
--

CREATE TABLE `Team` (
  `team_id` int(11) NOT NULL,
  `teamName` varchar(100) NOT NULL,
  `acronym` varchar(10) NOT NULL,
  `status` varchar(30) NOT NULL,
  `logo` text NOT NULL,
  `link` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Team`
--

INSERT INTO `Team` (`team_id`, `teamName`, `acronym`, `status`, `logo`, `link`) VALUES
(1, 'Kasem Bundit University FC', 'KBU FC', 'stay', 'img/kbu_logo.png', 'https://kbufc.000webhostapp.com/kbu-backoffice/team/img/kbu_logo.png'),
(2, 'North Bangkok University FC', 'NBU FC', 'stay', 'img/north-bkk-logo.png', 'https://kbufc.000webhostapp.com/kbu-backoffice/team/img/north-bkk-logo.png'),
(3, 'Prime Bangkok', 'LPC', 'stay', 'img/LPC_Bangkok_2020.png', 'https://kbufc.000webhostapp.com/kbu-backoffice/team/img/LPC_Bangkok_2020.png'),
(5, 'Samutsakhon City', 'SAM', 'stay', 'img/samutsakhon.jpg', 'https://kbufc.000webhostapp.com/kbu-backoffice/team/img/samutsakhon.jpg'),
(6, 'Chamchuri United', 'CHA', 'stay', 'img/Chamchuri_utd.png', 'https://kbufc.000webhostapp.com/kbu-backoffice/team/img/Chamchuri_utd.png'),
(7, 'ฺBolaven Samutprakan F.C.', 'BS FC', 'stay', 'img/samutprakan.png', 'https://kbufc.000webhostapp.com/kbu-backoffice/team/img/samutprakan.png'),
(8, 'Nonthaburi United', 'NON FC', 'stay', 'img/', 'https://kbufc.000webhostapp.com/kbu-backoffice/team/img/20210714-171939-c344e7ac-7e5f-41f6-bf15-f9b1ff9aefb5.png'),
(9, 'Royal Thai Army Football Club', 'RTA FC', 'stay', 'img/ffb8142775bd416b27aa294534df8756.png', 'https://kbufc.000webhostapp.com/kbu-backoffice/team/img/ffb8142775bd416b27aa294534df8756.png'),
(10, 'AUU Inter Bangkok Football Club', 'AUU', 'stay', 'img/Inter_Bangkok,_2020.png', 'https://kbufc.000webhostapp.com/kbu-backoffice/team/img/Inter_Bangkok,_2020.png'),
(11, 'VRN Muangnont F.C.', 'VRN FC', 'stay', 'img/Muangnont_Bankunmae_2020.png', 'https://kbufc.000webhostapp.com/kbu-backoffice/team/img/Muangnont_Bankunmae_2020.png'),
(12, 'Thonburi FC', 'THO', 'stay', 'img/2d655d86550a992b3631eb9b43696b38.png', 'https://kbufc.000webhostapp.com/kbu-backoffice/team/img/2d655d86550a992b3631eb9b43696b38.png'),
(13, 'Air Force United F.C.', 'AFU', 'stay', 'img/Airforce_United.png', 'https://kbufc.000webhostapp.com/kbu-backoffice/team/img/Airforce_United.png'),
(14, 'The iCON RSU Football Club', 'RSU', 'stay', 'img/The_Icon_RSU_logo.png', 'https://kbufc.000webhostapp.com/kbu-backoffice/team/img/The_Icon_RSU_logo.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Auction`
--
ALTER TABLE `Auction`
  ADD PRIMARY KEY (`auction_id`);

--
-- Indexes for table `Bid`
--
ALTER TABLE `Bid`
  ADD PRIMARY KEY (`bid_id`),
  ADD KEY `member_bid` (`member_id`),
  ADD KEY `auction_bid` (`auction_id`);

--
-- Indexes for table `Cart`
--
ALTER TABLE `Cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `cart_member` (`member_id`);

--
-- Indexes for table `Cart_Items`
--
ALTER TABLE `Cart_Items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_item` (`cart_id`),
  ADD KEY `item_cart` (`item_id`);

--
-- Indexes for table `LeagueTable`
--
ALTER TABLE `LeagueTable`
  ADD PRIMARY KEY (`table_id`);

--
-- Indexes for table `Match_Fixture`
--
ALTER TABLE `Match_Fixture`
  ADD PRIMARY KEY (`match_id`);

--
-- Indexes for table `Match_Team`
--
ALTER TABLE `Match_Team`
  ADD PRIMARY KEY (`match_team_id`),
  ADD KEY `match_team` (`match_id`),
  ADD KEY `team_match` (`team_id`);

--
-- Indexes for table `Member`
--
ALTER TABLE `Member`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `Merchandise`
--
ALTER TABLE `Merchandise`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `News`
--
ALTER TABLE `News`
  ADD PRIMARY KEY (`news_id`);

--
-- Indexes for table `Order_items`
--
ALTER TABLE `Order_items`
  ADD PRIMARY KEY (`order_items_id`),
  ADD KEY `order_item` (`order_id`),
  ADD KEY `items_order` (`items_id`),
  ADD KEY `auction_items` (`auction_id`);

--
-- Indexes for table `Order_Member`
--
ALTER TABLE `Order_Member`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `order_member` (`member_id`);

--
-- Indexes for table `Players`
--
ALTER TABLE `Players`
  ADD PRIMARY KEY (`players_id`),
  ADD KEY `players_team` (`team_id`);

--
-- Indexes for table `Player_Score`
--
ALTER TABLE `Player_Score`
  ADD PRIMARY KEY (`playerScore_id`),
  ADD KEY `players_score` (`players_id`),
  ADD KEY `players_score_match` (`match_id`);

--
-- Indexes for table `Predict`
--
ALTER TABLE `Predict`
  ADD PRIMARY KEY (`predict_id`),
  ADD KEY `predict_match` (`match_id`);

--
-- Indexes for table `Prediction`
--
ALTER TABLE `Prediction`
  ADD PRIMARY KEY (`prediction_id`),
  ADD KEY `prediction_member` (`member_id`),
  ADD KEY `predict_id` (`predict_id`);

--
-- Indexes for table `Record`
--
ALTER TABLE `Record`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `record_member` (`member_id`);

--
-- Indexes for table `StaffCoach`
--
ALTER TABLE `StaffCoach`
  ADD PRIMARY KEY (`staff_id`),
  ADD KEY `team_staff` (`team_id`);

--
-- Indexes for table `StartingXI`
--
ALTER TABLE `StartingXI`
  ADD PRIMARY KEY (`startXi_id`),
  ADD KEY `startXi_players` (`players_id`),
  ADD KEY `startXi_match` (`match_id`);

--
-- Indexes for table `Team`
--
ALTER TABLE `Team`
  ADD PRIMARY KEY (`team_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Auction`
--
ALTER TABLE `Auction`
  MODIFY `auction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Bid`
--
ALTER TABLE `Bid`
  MODIFY `bid_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Cart`
--
ALTER TABLE `Cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Cart_Items`
--
ALTER TABLE `Cart_Items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `LeagueTable`
--
ALTER TABLE `LeagueTable`
  MODIFY `table_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Match_Fixture`
--
ALTER TABLE `Match_Fixture`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Match_Team`
--
ALTER TABLE `Match_Team`
  MODIFY `match_team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Member`
--
ALTER TABLE `Member`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `Merchandise`
--
ALTER TABLE `Merchandise`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `News`
--
ALTER TABLE `News`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `Order_items`
--
ALTER TABLE `Order_items`
  MODIFY `order_items_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Order_Member`
--
ALTER TABLE `Order_Member`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Players`
--
ALTER TABLE `Players`
  MODIFY `players_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Player_Score`
--
ALTER TABLE `Player_Score`
  MODIFY `playerScore_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Predict`
--
ALTER TABLE `Predict`
  MODIFY `predict_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Prediction`
--
ALTER TABLE `Prediction`
  MODIFY `prediction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Record`
--
ALTER TABLE `Record`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `StaffCoach`
--
ALTER TABLE `StaffCoach`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `StartingXI`
--
ALTER TABLE `StartingXI`
  MODIFY `startXi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Team`
--
ALTER TABLE `Team`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Bid`
--
ALTER TABLE `Bid`
  ADD CONSTRAINT `auction_bid` FOREIGN KEY (`auction_id`) REFERENCES `Auction` (`auction_id`),
  ADD CONSTRAINT `member_bid` FOREIGN KEY (`member_id`) REFERENCES `Member` (`member_id`);

--
-- Constraints for table `Cart`
--
ALTER TABLE `Cart`
  ADD CONSTRAINT `cart_member` FOREIGN KEY (`member_id`) REFERENCES `Member` (`member_id`);

--
-- Constraints for table `Cart_Items`
--
ALTER TABLE `Cart_Items`
  ADD CONSTRAINT `cart_item` FOREIGN KEY (`cart_id`) REFERENCES `Cart` (`cart_id`),
  ADD CONSTRAINT `item_cart` FOREIGN KEY (`item_id`) REFERENCES `Merchandise` (`item_id`);

--
-- Constraints for table `Match_Team`
--
ALTER TABLE `Match_Team`
  ADD CONSTRAINT `match_team` FOREIGN KEY (`match_id`) REFERENCES `Match_Fixture` (`match_id`),
  ADD CONSTRAINT `team_match` FOREIGN KEY (`team_id`) REFERENCES `Team` (`team_id`);

--
-- Constraints for table `Order_items`
--
ALTER TABLE `Order_items`
  ADD CONSTRAINT `auction_items` FOREIGN KEY (`auction_id`) REFERENCES `Auction` (`auction_id`),
  ADD CONSTRAINT `items_order` FOREIGN KEY (`items_id`) REFERENCES `Merchandise` (`item_id`),
  ADD CONSTRAINT `order_item` FOREIGN KEY (`order_id`) REFERENCES `Order_Member` (`order_id`);

--
-- Constraints for table `Order_Member`
--
ALTER TABLE `Order_Member`
  ADD CONSTRAINT `order_member` FOREIGN KEY (`member_id`) REFERENCES `Member` (`member_id`);

--
-- Constraints for table `Players`
--
ALTER TABLE `Players`
  ADD CONSTRAINT `players_team` FOREIGN KEY (`team_id`) REFERENCES `Team` (`team_id`);

--
-- Constraints for table `Player_Score`
--
ALTER TABLE `Player_Score`
  ADD CONSTRAINT `players_score` FOREIGN KEY (`players_id`) REFERENCES `Players` (`players_id`),
  ADD CONSTRAINT `players_score_match` FOREIGN KEY (`match_id`) REFERENCES `Match_Fixture` (`match_id`);

--
-- Constraints for table `Predict`
--
ALTER TABLE `Predict`
  ADD CONSTRAINT `predict_match` FOREIGN KEY (`match_id`) REFERENCES `Match_Fixture` (`match_id`);

--
-- Constraints for table `Prediction`
--
ALTER TABLE `Prediction`
  ADD CONSTRAINT `predict_id` FOREIGN KEY (`predict_id`) REFERENCES `Predict` (`predict_id`),
  ADD CONSTRAINT `prediction_member` FOREIGN KEY (`member_id`) REFERENCES `Member` (`member_id`);

--
-- Constraints for table `Record`
--
ALTER TABLE `Record`
  ADD CONSTRAINT `record_member` FOREIGN KEY (`member_id`) REFERENCES `Member` (`member_id`);

--
-- Constraints for table `StaffCoach`
--
ALTER TABLE `StaffCoach`
  ADD CONSTRAINT `team_staff` FOREIGN KEY (`team_id`) REFERENCES `Team` (`team_id`);

--
-- Constraints for table `StartingXI`
--
ALTER TABLE `StartingXI`
  ADD CONSTRAINT `startXi_match` FOREIGN KEY (`match_id`) REFERENCES `Match_Fixture` (`match_id`),
  ADD CONSTRAINT `startXi_players` FOREIGN KEY (`players_id`) REFERENCES `Players` (`players_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
