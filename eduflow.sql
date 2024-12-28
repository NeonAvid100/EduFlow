-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2024 at 10:28 AM
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
-- Database: `eduflow`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

CREATE TABLE `assignment` (
  `id` int(11) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `question` varchar(1000) DEFAULT NULL,
  `deadline` char(15) DEFAULT NULL,
  `assignment_id` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignment`
--

INSERT INTO `assignment` (`id`, `title`, `question`, `deadline`, `assignment_id`, `status`) VALUES
(1, 'Math Assignment', 'Solve 10 calculus problems.', '2025-01-10', 1, 'Pending'),
(1, 'Physics Lab Report', 'Write a report on the pendulum motion.', '2025-01-15', 2, 'Pending'),
(1, 'History Essay', 'Discuss the impact of WWII.', '2025-01-20', 3, 'Pending'),
(1, 'Programming Task', 'Create a login system using PHP.', '2025-01-25', 4, 'Pending'),
(1, 'Chemistry Quiz', 'Prepare for the organic chemistry quiz.', '2025-01-30', 5, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL,
  `c_name` varchar(30) DEFAULT NULL,
  `c_description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `c_id`, `c_name`, `c_description`) VALUES
(1, 1, 'Introduction to Programming', 'This course covers the basics of programming in Python, including data types, control structures, an'),
(1, 2, 'Data Structures and Algorithms', 'This course introduces various data structures like arrays, linked lists, trees, and algorithms for '),
(1, 3, 'Database Management Systems', 'Learn about relational databases, SQL, normalization, and database design in this course.'),
(1, 4, 'Web Development Fundamentals', 'This course provides an introduction to web development using HTML, CSS, and JavaScript, focusing on'),
(1, 5, 'Software Engineering Principle', 'This course explores software development methodologies, project management, and design principles f');

-- --------------------------------------------------------

--
-- Table structure for table `co_curriculars`
--

CREATE TABLE `co_curriculars` (
  `id` int(11) DEFAULT NULL,
  `t_id` int(11) NOT NULL,
  `title` varchar(70) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `t_link` varchar(200) DEFAULT NULL,
  `t_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `co_curriculars`
--

INSERT INTO `co_curriculars` (`id`, `t_id`, `title`, `description`, `t_link`, `t_date`) VALUES
(1, 1, 'Co-Curricular Activity Title', 'Description of the activity', 'https://bracu.com', '2024-12-31'),
(1, 2, 'Basketball Match Fixtures', 'Details about upcoming basketball matches', 'https://match1.example.com, https://match2.example.com', '2024-12-25'),
(1, 3, 'Football Match Fixtures', 'Upcoming football matches and events', 'https://footballmatch1.example.com, https://footballmatch2.example.com', '2024-12-27');

-- --------------------------------------------------------

--
-- Table structure for table `file_storage`
--

CREATE TABLE `file_storage` (
  `upload_id` int(11) NOT NULL,
  `id` int(11) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `file_size` bigint(20) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `file_storage`
--

INSERT INTO `file_storage` (`upload_id`, `id`, `file_name`, `file_type`, `file_size`, `file_path`, `upload_date`, `title`) VALUES
(5, 1, 'hichori.jpg', 'image/jpeg', 144483, 'uploads/676fbc3c321ac2.37375702.jpg', '2024-12-28 08:52:12', ''),
(6, 1, 'Shab E Barat_EDF.png', 'image/png', 1112490, 'uploads/676fbc61255376.92674126.png', '2024-12-28 08:52:49', ''),
(7, 1, 'IMG20220701135159.jpg', 'image/jpeg', 14777356, 'uploads/676fbc81ae7500.94723877.jpg', '2024-12-28 08:53:21', '');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `email`, `name`, `password`) VALUES
(1, 'patkheterbatija@gmail.com', 'Habib', '$2y$10$ydZpFi/2E8ZE/Z1OLRa8Cu6mHyhlDJfxUysqAUyyKXgZhC2rgA2mG');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `user_id` int(11) DEFAULT NULL,
  `q_title` varchar(50) DEFAULT NULL,
  `q_description` varchar(70) DEFAULT NULL,
  `q_date` char(15) DEFAULT NULL,
  `quiz_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`user_id`, `q_title`, `q_description`, `q_date`, `quiz_id`) VALUES
(1, 'Physics Quiz', 'Test on Newtonian Mechanics', '2025-02-10', 1),
(1, 'Chemistry Quiz', 'Quiz on Organic Chemistry', '2025-02-20', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignment`
--
ALTER TABLE `assignment`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `fk_assignment_user` (`id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `co_curriculars`
--
ALTER TABLE `co_curriculars`
  ADD PRIMARY KEY (`t_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `file_storage`
--
ALTER TABLE `file_storage`
  ADD PRIMARY KEY (`upload_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`quiz_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignment`
--
ALTER TABLE `assignment`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `co_curriculars`
--
ALTER TABLE `co_curriculars`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `file_storage`
--
ALTER TABLE `file_storage`
  MODIFY `upload_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignment`
--
ALTER TABLE `assignment`
  ADD CONSTRAINT `fk_assignment_user` FOREIGN KEY (`id`) REFERENCES `login` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`id`) REFERENCES `login` (`id`);

--
-- Constraints for table `co_curriculars`
--
ALTER TABLE `co_curriculars`
  ADD CONSTRAINT `co_curriculars_ibfk_1` FOREIGN KEY (`id`) REFERENCES `login` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `file_storage`
--
ALTER TABLE `file_storage`
  ADD CONSTRAINT `file_storage_ibfk_1` FOREIGN KEY (`id`) REFERENCES `login` (`id`);

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `login` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
