-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2026 at 05:54 PM
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
-- Database: `library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL COMMENT 'name of book\r\n',
  `author` varchar(100) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`book_id`, `title`, `author`, `publisher`, `year`, `price`) VALUES
(1, 'Data Base System ', 'Abraham Silberschatz', 'McGraw Hill', 2019, 1500),
(2, 'Introduction to Algorithms', 'Thomas H. Cormen', 'MIT Press', 2022, 12000),
(3, 'Clean Code', 'Robert C. Martin', 'Prentice Hall', 2008, 6500),
(4, 'Computer Network', 'Andrew S. Tanenbaum', 'Pearson', 2021, 9000),
(5, 'Operating System Concepts', 'Abraham Silberschatz', 'Wiley', 2018, 8800),
(6, 'Artificial Intelligence: A Modern Approach', 'Stuart Russell', 'Pearson', 2022, 11000),
(7, 'The Pragmatic Programmer', 'Andrew Hunt', 'Addison-Wesley', 2019, 7000),
(8, 'Software Engineering', 'Ian Sommerville', 'Pearson', 2016, 75000),
(9, 'Computer Organization and Design', 'David A. Patterson', 'Morgan Kaufmann', 2020, 22000),
(10, 'Design Patterns: Elements of Reusable Object-Oriented', 'Erich Gamma', 'Addison-Wesley', 1994, 9000);

-- --------------------------------------------------------

--
-- Table structure for table `book_issue`
--

CREATE TABLE `book_issue` (
  `Issue_ID` int(11) NOT NULL,
  `Member_ID` int(11) DEFAULT NULL,
  `Book_ID` int(11) DEFAULT NULL,
  `Issue_Date` date DEFAULT NULL,
  `Return_Date` date DEFAULT NULL,
  `Librarian_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_issue`
--

INSERT INTO `book_issue` (`Issue_ID`, `Member_ID`, `Book_ID`, `Issue_Date`, `Return_Date`, `Librarian_ID`) VALUES
(1, 1, 1, '2026-05-01', '2026-05-15', 1),
(2, 2, 2, '2026-05-03', '2026-05-17', 2),
(3, 3, 3, '2026-05-05', '2026-05-19', 1);

-- --------------------------------------------------------

--
-- Table structure for table `book_return`
--

CREATE TABLE `book_return` (
  `Return_ID` int(11) NOT NULL,
  `Issue_ID` int(11) DEFAULT NULL,
  `Return_Date` date DEFAULT NULL,
  `Fine_Amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_return`
--

INSERT INTO `book_return` (`Return_ID`, `Issue_ID`, `Return_Date`, `Fine_Amount`) VALUES
(1, 1, '2026-05-15', 200.00),
(2, 2, '2026-05-17', 800.00),
(3, 3, '2026-05-19', 600.00);

-- --------------------------------------------------------

--
-- Table structure for table `librarian`
--

CREATE TABLE `librarian` (
  `Librarian_ID` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Phone_No` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `librarian`
--

INSERT INTO `librarian` (`Librarian_ID`, `Name`, `Phone_No`, `Email`, `Salary`) VALUES
(1, 'Ali Khan', '03001234567', 'ali@gmail.com', 50000.00),
(2, 'Sara Ahmed', '03111234567', 'sara@gmail.com', 55000.00),
(3, 'Usman Tariq', '03221234567', 'usman@gmail.com', 48000.00);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `Member_id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `phone No.` int(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `Address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`Member_id`, `Name`, `phone No.`, `email`, `Address`) VALUES
(1, 'Ali khan', 301235467, 'ali@gmail.com', 'Lahore'),
(2, 'Mariyam Naeem', 301235467, 'mariyam@gmail.com', 'Sahiwal'),
(3, 'Usman Malik', 324169743, 'usman@gmail.com', 'Islamabad'),
(4, 'Ayesha Malik', 3765418, 'ayesha@gmail.com', 'Karachi'),
(5, 'Abdullah khan', 365819422, 'abdullah@gmail.com', 'Faisalbad'),
(6, 'Rabia Ali', 343589176, 'rabia@gmail.comn', 'Multan'),
(7, 'Umer Saleem ', 365782144, 'umer@gmail.com', 'Sahiwal'),
(8, 'Fatima Rafique', 355441122, 'fatima@gmail.com', 'Sahiwal'),
(9, 'Hassan Raza', 27654137, 'hsn@gmail.com', 'Lahore');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `book_issue`
--
ALTER TABLE `book_issue`
  ADD PRIMARY KEY (`Issue_ID`),
  ADD KEY `fk_issue_member` (`Member_ID`),
  ADD KEY `Book_ID` (`Book_ID`),
  ADD KEY `Librarian_ID` (`Librarian_ID`);

--
-- Indexes for table `book_return`
--
ALTER TABLE `book_return`
  ADD PRIMARY KEY (`Return_ID`),
  ADD KEY `Issue_ID` (`Issue_ID`);

--
-- Indexes for table `librarian`
--
ALTER TABLE `librarian`
  ADD PRIMARY KEY (`Librarian_ID`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`Member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `book_issue`
--
ALTER TABLE `book_issue`
  MODIFY `Issue_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `book_return`
--
ALTER TABLE `book_return`
  MODIFY `Return_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `librarian`
--
ALTER TABLE `librarian`
  MODIFY `Librarian_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `Member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book_issue`
--
ALTER TABLE `book_issue`
  ADD CONSTRAINT `book_issue_ibfk_1` FOREIGN KEY (`Book_ID`) REFERENCES `book` (`book_id`),
  ADD CONSTRAINT `book_issue_ibfk_2` FOREIGN KEY (`Librarian_ID`) REFERENCES `librarian` (`Librarian_ID`),
  ADD CONSTRAINT `fk_issue_member` FOREIGN KEY (`Member_ID`) REFERENCES `member` (`Member_id`);

--
-- Constraints for table `book_return`
--
ALTER TABLE `book_return`
  ADD CONSTRAINT `book_return_ibfk_1` FOREIGN KEY (`Issue_ID`) REFERENCES `book_issue` (`Issue_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
