-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 09, 2014 at 01:03 AM
-- Server version: 5.5.33
-- PHP Version: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sjames`
--

-- --------------------------------------------------------

--
-- Table structure for table `dormitory`
--

CREATE TABLE `dormitory` (
  `dorm_id` int(10) NOT NULL,
  `capacity_amount` int(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `dormitory`
--

INSERT INTO `dormitory` (`dorm_id`, `capacity_amount`) VALUES
(1, 14),
(3, 5),
(5, 5),
(2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `st_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` varchar(250) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `dob` varchar(20) NOT NULL,
  `phone_number` int(10) NOT NULL,
  `dorm_build_number` int(10) NOT NULL,
  `floor_number` int(1) NOT NULL,
  `unit_id` int(10) NOT NULL,
  `room_number` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=76 ;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `st_id`, `first_name`, `last_name`, `address`, `gender`, `dob`, `phone_number`, `dorm_build_number`, `floor_number`, `unit_id`, `room_number`) VALUES
(57, 59458465, 'Brian', 'Thompson', '6764 EW Timbeall', 'male', '1989-03-24', 3732463847, 2, 1, 2, 3),
(50, 59854938, 'Mark', 'Johnson', '555 W. Romont', 'male', '1989-01-03', 4979382938, 1, 1, 3, 2),
(49, 39485765, 'Jane', 'James', '342 S Lemon', 'female', '1984-05-15', 5456345323, 1, 1, 2, 2),
(39, 39485769, 'jen', 'chan', '444 chesapeake', 'female', '2014-02-02', 2364565434, 1, 1, 4, 1),
(40, 39333333, 'heather', 'wendy', '4444 Lemon', 'female', '1995-02-01', 4958475645, 1, 1, 4, 3),
(56, 88445376, 'Chanell', 'Moren', '3492 Komanel', 'female', '1985-02-04', 3434565756, 1, 2, 2, 4),
(51, 21324356, 'Jenny', 'Wilson', '6599 N Campbell St', 'female', '1986-08-04', 2147483647, 3, 2, 2, 1),
(52, 69506948, 'Samuel', 'Wardell', '6940 S. Kedzie', 'male', '1990-02-19', 2147483647, 3, 1, 1, 3),
(53, 23456795, 'Joanna', 'Watson', '4563 E. Kimbel Rd', 'female', '1988-09-20', 2147483647, 1, 1, 1, 1),
(54, 39394848, 'Julia', 'Stentonburg', '5865 Edison St', 'male', '1992-10-15', 2147483647, 1, 3, 4, 1),
(55, 58492832, 'Heather', 'Hunter', '5683 S. Franco Rd', 'female', '1986-08-14', 2147483647, 5, 1, 1, 4),
(58, 54565645, 'Kelia', 'Jones', '550 E Dawson Ave', 'female', '1987-07-06', 2147483647, 5, 1, 3, 1),
(59, 38343334, 'Adrianna', 'Henderson', '4435 Sanchesz Rd', 'female', '1992-04-04', 46756456, 3, 1, 2, 4),
(60, 67576567, 'Laura', 'Cramer', '5453 N Lemar', 'female', '1990-05-02', 2147483647, 1, 2, 2, 1),
(61, 75465847, 'Nicole', 'Shaffer', '5847 Newport Rd', 'female', '1992-06-11', 67867878, 3, 2, 1, 2),
(62, 18273624, 'Jennifer', 'Wilson', '575 E Causon Rd', 'female', '1988-04-07', 76789654, 3, 2, 1, 3),
(63, 68475934, 'Ryan', 'Lauer', '5844 W Cicero Ave', 'male', '1986-03-10', 2147483647, 1, 1, 3, 3),
(64, 37374634, 'Johnney', 'Franceo', '323 N Rolede', 'male', '1992-05-05', 2147483647, 5, 1, 2, 3),
(65, 83759574, 'Sarah', 'Styles', '4532 E Georlas', 'female', '1983-10-24', 2147483647, 1, 2, 2, 3),
(66, 68473958, 'Kia', 'Lowes', '4583 S. Leclaire', 'female', '1987-06-18', 76565434, 2, 1, 3, 1),
(67, 95485473, 'Valeta', 'Thompson', '6849 N Morgan St', 'female', '1988-02-15', 75837436, 1, 2, 2, 2),
(68, 97862387, 'Robert', 'Morrison', '339 S Levorn', 'male', '1989-09-20', 2147483647, 1, 2, 3, 1),
(69, 38483444, 'Lauren', 'Cauffer', '302 W Edine St', 'female', '1990-02-05', 2147483647, 2, 1, 3, 3),
(70, 58473847, 'Ryan', 'Matthers', '493 Hendman', 'male', '1986-11-27', 2147483647, 1, 3, 1, 3),
(71, 38493847, 'Henry', 'Hamilton', '434 S Kedzie Rd', 'male', '1989-09-13', 2147483647, 5, 1, 4, 1),
(72, 39482323, 'Ronnie', 'Ryan', '9483 N Robison Rd', 'male', '1988-05-17', 2147483647, 2, 1, 2, 2),
(73, 58574585, 'Jones', 'Williams', '989 E Juliio St', 'male', '1989-06-06', 2147483647, 1, 2, 1, 1),
(74, 58493285, 'Cameron', 'James', '4837 S Lemington St', 'male', '1985-11-05', 2147483647, 2, 1, 1, 3),
(75, 43543524, 'Johnathan', 'Joseph', '555 N Dearborn', 'male', '1988-05-18', 2147483647, 5, 1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `unit_id` int(1) NOT NULL,
  `room_amount` int(4) NOT NULL,
  `rooms_used` int(1) NOT NULL,
  `unit_gender` enum('male','female') DEFAULT NULL,
  `floor_number` int(3) NOT NULL,
  `dorm_id` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`unit_id`, `room_amount`, `rooms_used`, `unit_gender`, `floor_number`, `dorm_id`) VALUES
(2, 4, 1, 'female', 2, 3),
(4, 4, 2, 'female', 1, 1),
(2, 4, 1, 'female', 1, 1),
(3, 4, 2, 'male', 1, 1),
(1, 4, 1, 'male', 1, 3),
(1, 4, 1, 'female', 1, 1),
(4, 4, 1, 'male', 3, 1),
(1, 4, 1, 'female', 1, 5),
(2, 4, 4, 'female', 2, 1),
(2, 4, 2, 'male', 1, 2),
(3, 4, 1, 'female', 1, 5),
(2, 4, 1, 'female', 1, 3),
(1, 4, 2, 'female', 2, 3),
(2, 4, 2, 'male', 1, 5),
(3, 4, 2, 'female', 1, 2),
(3, 4, 1, 'male', 2, 1),
(1, 4, 1, 'male', 3, 1),
(4, 4, 1, 'male', 1, 5),
(1, 4, 1, 'male', 2, 1),
(1, 4, 1, 'male', 1, 2);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
