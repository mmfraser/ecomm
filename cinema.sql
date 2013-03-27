-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2013 at 01:00 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cinema`
--

-- --------------------------------------------------------

--
-- Table structure for table `directors`
--

CREATE TABLE IF NOT EXISTS `directors` (
  `DirectorID` int(11) NOT NULL AUTO_INCREMENT,
  `Forename` varchar(255) NOT NULL,
  `Surname` varchar(255) NOT NULL,
  PRIMARY KEY (`DirectorID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `directors`
--

INSERT INTO `directors` (`DirectorID`, `Forename`, `Surname`) VALUES
(3, 'Tom', 'Hanks'),
(6, 'Steven', 'Spielberg'),
(7, 'Alfred', 'Hitchcock'),
(8, 'Woody', 'Allen'),
(9, 'Martin', 'Scorsese'),
(10, 'Quentin', 'Tarantino'),
(11, 'Clint', 'Eastwood');

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE IF NOT EXISTS `genre` (
  `GenreID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  PRIMARY KEY (`GenreID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`GenreID`, `Name`, `Description`) VALUES
(1, 'Action', 'Thrilling films, with lots of guns and fighting'),
(5, 'Adventure', 'Exciting stories, located in usually exotic locales.  Can usually feature a treasure hunt, or such plots.'),
(6, 'Thriller', 'Thriller');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `LanguageID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`LanguageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`LanguageID`, `Name`) VALUES
(1, 'English'),
(4, 'Spanish'),
(5, 'German'),
(6, 'Chinese (Simplified)'),
(7, 'Thriller');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `MemberID` int(11) NOT NULL AUTO_INCREMENT,
  `Forename` varchar(255) NOT NULL,
  `Surname` varchar(255) NOT NULL,
  `EmailAddress` varchar(255) NOT NULL,
  `Password` text NOT NULL,
  `AddressLine1` varchar(255) NOT NULL,
  `AddressLine2` varchar(255) NOT NULL,
  `Town` varchar(255) NOT NULL,
  `Postcode` varchar(10) NOT NULL,
  `IsAdmin` tinyint(1) NOT NULL,
  `IsActive` tinyint(1) NOT NULL,
  PRIMARY KEY (`MemberID`),
  KEY `Surname` (`Surname`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`MemberID`, `Forename`, `Surname`, `EmailAddress`, `Password`, `AddressLine1`, `AddressLine2`, `Town`, `Postcode`, `IsAdmin`, `IsActive`) VALUES
(1, 'Marc', '', 'mf111@hw.ac.uk', '098f6bcd4621d373cade4e832627b4f6', '11 East Bay', 'The Shores', 'North Queensferry', 'KY11 1JX', 1, 1),
(9, 'test', '', 'test@test1245.com', 'c4ca4238a0b923820dcc509a6f75849b', 'daf', 'adf', 'test', 'test', 0, 1),
(10, 'Forename', '', 'Marc@coopersoftware.co.uk', 'a69288770d988db5bf173940809b9b81', 'Cooper Software Ltd.', '4 Pitreavie Court', 'Dunfermline', 'KY11 8UU', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE IF NOT EXISTS `movies` (
  `MovieID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Cast` text NOT NULL,
  `ReleaseDate` date NOT NULL,
  `duration` int(11) NOT NULL,
  `GenreID` int(11) NOT NULL,
  `DirectorID` int(11) NOT NULL,
  `LanguageID` int(11) NOT NULL,
  PRIMARY KEY (`MovieID`),
  KEY `GenreID` (`GenreID`,`DirectorID`,`LanguageID`),
  KEY `DirectorID` (`DirectorID`),
  KEY `LanguageID` (`LanguageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`MovieID`, `Name`, `Description`, `Cast`, `ReleaseDate`, `duration`, `GenreID`, `DirectorID`, `LanguageID`) VALUES
(3, 'Jaws', 'Based on Peter Benchley''s novel of the same name\r\n\r\nGenerally well received by critics, Jaws became the highest-grossing film in history at the time. It won several awards for its soundtrack and editing, and it is often cited as one of the greatest films of all time.', 'Roy Scheider, Robert Shaw, Richard Dreyfuss, Lorraine Gary, Murray Hamilton', '1975-06-20', 0, 6, 6, 1),
(4, 'test', 'test', 'test', '2013-03-04', 245, 1, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `movie_reservation`
--

CREATE TABLE IF NOT EXISTS `movie_reservation` (
  `ReservationID` int(11) NOT NULL AUTO_INCREMENT,
  `MemberID` int(11) NOT NULL,
  `MovieScreeningID` int(11) NOT NULL,
  `PurchaseDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ReservationID`),
  KEY `MovieScreeningID` (`MovieScreeningID`),
  KEY `MemberID` (`MemberID`),
  KEY `MemberID_2` (`MemberID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `movie_screening`
--

CREATE TABLE IF NOT EXISTS `movie_screening` (
  `ScreeningID` int(11) NOT NULL AUTO_INCREMENT,
  `MovieID` int(11) NOT NULL,
  `ScreenID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Time` int(11) NOT NULL,
  PRIMARY KEY (`ScreeningID`),
  KEY `MovieID` (`MovieID`,`ScreenID`),
  KEY `MovieID_2` (`MovieID`,`ScreenID`),
  KEY `ScreenID` (`ScreenID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `movie_screening`
--

INSERT INTO `movie_screening` (`ScreeningID`, `MovieID`, `ScreenID`, `Date`, `Time`) VALUES
(1, 4, 3, '2013-05-03', 2200),
(2, 3, 2, '2013-07-03', 900);

-- --------------------------------------------------------

--
-- Table structure for table `movie_ticket_reservation`
--

CREATE TABLE IF NOT EXISTS `movie_ticket_reservation` (
  `MovieTicketReservationID` int(11) NOT NULL AUTO_INCREMENT,
  `ReservationID` int(11) NOT NULL,
  `TicketCategoryID` int(11) NOT NULL,
  `NoSeats` int(11) NOT NULL,
  PRIMARY KEY (`MovieTicketReservationID`),
  KEY `ReservationID` (`ReservationID`,`TicketCategoryID`),
  KEY `MovieTicketCostID` (`TicketCategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `screens`
--

CREATE TABLE IF NOT EXISTS `screens` (
  `ScreenID` int(11) NOT NULL AUTO_INCREMENT,
  `NoRows` int(11) NOT NULL,
  `NoColumns` int(11) NOT NULL,
  PRIMARY KEY (`ScreenID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `screens`
--

INSERT INTO `screens` (`ScreenID`, `NoRows`, `NoColumns`) VALUES
(1, 10, 10),
(2, 10, 10),
(3, 23, 26);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_category`
--

CREATE TABLE IF NOT EXISTS `ticket_category` (
  `TicketCategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(255) NOT NULL,
  `DefaultPrice` decimal(11,0) NOT NULL,
  PRIMARY KEY (`TicketCategoryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ticket_category`
--

INSERT INTO `ticket_category` (`TicketCategoryID`, `CategoryName`, `DefaultPrice`) VALUES
(1, 'Adult', 9),
(2, 'Child', 5),
(3, 'Student', 7),
(4, 'OAP', 5);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_cost`
--

CREATE TABLE IF NOT EXISTS `ticket_cost` (
  `MovieTicketCostID` int(11) NOT NULL AUTO_INCREMENT,
  `TicketCategoryID` int(11) NOT NULL,
  `MovieID` int(11) NOT NULL,
  `TicketCost` int(11) NOT NULL,
  PRIMARY KEY (`MovieTicketCostID`),
  KEY `MovieID` (`MovieID`),
  KEY `TicketCategoryID` (`TicketCategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_ibfk_2` FOREIGN KEY (`GenreID`) REFERENCES `genre` (`GenreID`),
  ADD CONSTRAINT `movies_ibfk_3` FOREIGN KEY (`DirectorID`) REFERENCES `directors` (`DirectorID`),
  ADD CONSTRAINT `movies_ibfk_4` FOREIGN KEY (`LanguageID`) REFERENCES `languages` (`LanguageID`);

--
-- Constraints for table `movie_reservation`
--
ALTER TABLE `movie_reservation`
  ADD CONSTRAINT `movie_reservation_ibfk_2` FOREIGN KEY (`MovieScreeningID`) REFERENCES `movie_screening` (`ScreeningID`),
  ADD CONSTRAINT `movie_reservation_ibfk_1` FOREIGN KEY (`MemberID`) REFERENCES `members` (`MemberID`);

--
-- Constraints for table `movie_screening`
--
ALTER TABLE `movie_screening`
  ADD CONSTRAINT `movie_screening_ibfk_1` FOREIGN KEY (`MovieID`) REFERENCES `movies` (`MovieID`),
  ADD CONSTRAINT `movie_screening_ibfk_2` FOREIGN KEY (`ScreenID`) REFERENCES `screens` (`ScreenID`);

--
-- Constraints for table `movie_ticket_reservation`
--
ALTER TABLE `movie_ticket_reservation`
  ADD CONSTRAINT `movie_ticket_reservation_ibfk_2` FOREIGN KEY (`TicketCategoryID`) REFERENCES `ticket_category` (`TicketCategoryID`),
  ADD CONSTRAINT `movie_ticket_reservation_ibfk_1` FOREIGN KEY (`ReservationID`) REFERENCES `movie_reservation` (`ReservationID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
