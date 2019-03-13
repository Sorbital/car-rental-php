-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2019 at 06:07 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `car-rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `cr_admin`
--

CREATE TABLE `cr_admin` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_role` varchar(20) NOT NULL,
  `permissions` text NOT NULL,
  `main_permission` text NOT NULL,
  `active` varchar(10) NOT NULL,
  `last_modified` datetime DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

--
-- Dumping data for table `cr_admin`
--

INSERT INTO `cr_admin` (`id`, `fname`, `lname`, `username`, `email`, `password`, `user_role`, `permissions`, `main_permission`, `active`, `last_modified`, `created`) VALUES
(1, 'Saurabh', 'B', 'admin', 'sorbital11@gmail.com', '$2y$10$i2wEVPdzjVe/gSx7vM3CKugSxvWlMQWuIiMTHvvPgAIf1lz2g2qP2', 'super', '', '', 'Yes', '2018-12-13 10:59:18', '2019-01-26 19:44:21');

-- --------------------------------------------------------

--
-- Table structure for table `cr_car_master`
--

CREATE TABLE `cr_car_master` (
  `id` int(11) NOT NULL,
  `model_id` int(11) DEFAULT NULL,
  `car_registration_no` varchar(20) DEFAULT NULL,
  `car_manufacturing_year` varchar(5) DEFAULT NULL,
  `car_color` varchar(20) DEFAULT NULL,
  `note` text,
  `image_name1` varchar(100) DEFAULT NULL,
  `image_name2` varchar(100) DEFAULT NULL,
  `is_sold` tinyint(4) DEFAULT '0',
  `sold_datetime` datetime DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `is_deleted` tinyint(4) DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cr_car_master`
--

INSERT INTO `cr_car_master` (`id`, `model_id`, `car_registration_no`, `car_manufacturing_year`, `car_color`, `note`, `image_name1`, `image_name2`, `is_sold`, `sold_datetime`, `active`, `is_deleted`, `created`) VALUES
(1, 1, 'MH 02 AB 1111', '2019', 'Red', 'Experience the 2019 Corvette Stingray sports car delivering 0-60 in 3.7 seconds & a driver-focused cockpit.', '1548582538.jpg', '1548582632.jpeg', 1, '2019-01-27 18:35:07', 1, 0, '2019-01-27 09:47:02'),
(2, 1, 'MH 02 AB 2323', '2018', 'Yellow', 'Explore the 2018 Corvette Z06 racing machine, this supercar offers 650 HP & 0-60 in 2.95 seconds.', '1548582953.jpg', '1548582954.jpg', 0, NULL, 1, 0, '2019-01-27 09:55:54'),
(3, 4, 'MH 02 ER 4545', '2013', 'Silver White', 'Maruti 800 is a small city car that was manufactured by Maruti Suzuki in India from 1983 to 18 January 2013. The first generation (SS80) was based on the 1979 Suzuki Fronte and had an 800 cc F8B engine, hence the moniker.', '1552136934.jpg', '1552136935.jpg', 0, NULL, 1, 0, '2019-03-09 11:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `cr_car_photos`
--

CREATE TABLE `cr_car_photos` (
  `id` int(11) NOT NULL,
  `car_id` int(11) DEFAULT NULL,
  `image_name` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cr_manufacturer_master`
--

CREATE TABLE `cr_manufacturer_master` (
  `id` int(11) NOT NULL,
  `manufacturer_name` varchar(255) DEFAULT NULL,
  `active` tinyint(4) DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cr_manufacturer_master`
--

INSERT INTO `cr_manufacturer_master` (`id`, `manufacturer_name`, `active`, `is_deleted`, `created`) VALUES
(1, 'Chevrolet', 1, 0, '2019-01-26 20:14:38'),
(2, 'Tata', 1, 0, '2019-01-26 22:04:12'),
(3, 'Maruti', 1, 0, '2019-01-26 22:04:19'),
(4, 'Honda', 1, 0, '2019-01-26 22:04:27'),
(5, 'Mercedes', 1, 0, '2019-01-26 22:04:36'),
(6, 'BMW', 1, 0, '2019-01-26 22:05:02');

-- --------------------------------------------------------

--
-- Table structure for table `cr_model_master`
--

CREATE TABLE `cr_model_master` (
  `id` int(11) NOT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  `model_name` varchar(255) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `is_deleted` tinyint(4) DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cr_model_master`
--

INSERT INTO `cr_model_master` (`id`, `manufacturer_id`, `model_name`, `active`, `is_deleted`, `created`) VALUES
(1, 1, 'Corvette', 1, 0, '2019-01-26 22:10:13'),
(2, 1, 'Colorado', 1, 0, '2019-01-26 22:10:30'),
(3, 2, 'Nano', 1, 0, '2019-01-26 22:10:56'),
(4, 3, 'Maruti 800', 1, 0, '2019-01-26 22:11:16'),
(5, 1, 'Bolt EV', 1, 0, '2019-01-26 22:11:35'),
(6, 3, 'Ertiga', 1, 0, '2019-01-26 22:11:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cr_admin`
--
ALTER TABLE `cr_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cr_car_master`
--
ALTER TABLE `cr_car_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cr_car_photos`
--
ALTER TABLE `cr_car_photos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cr_manufacturer_master`
--
ALTER TABLE `cr_manufacturer_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cr_model_master`
--
ALTER TABLE `cr_model_master`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cr_admin`
--
ALTER TABLE `cr_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cr_car_master`
--
ALTER TABLE `cr_car_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cr_car_photos`
--
ALTER TABLE `cr_car_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cr_manufacturer_master`
--
ALTER TABLE `cr_manufacturer_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cr_model_master`
--
ALTER TABLE `cr_model_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
