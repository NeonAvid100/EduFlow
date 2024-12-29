-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2024 at 05:53 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

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
  `status` varchar(20) DEFAULT 'Pending',
  `c_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `assignment`
--

INSERT INTO `assignment` (`id`, `title`, `question`, `deadline`, `assignment_id`, `status`, `c_name`) VALUES
(1, 'Assignment 1', 'Write a Python program for basic arithmetic operations.', '2024-12-30', 6, 'Pending', 'Introduction to Programming'),
(1, 'Assignment 2', 'Implement a simple calculator in Python.', '2024-12-30', 7, 'Pending', 'Data Structures and Algorithms'),
(1, 'Assignment 3', 'Design a basic web page using HTML and CSS.', '2024-12-30', 8, 'Pending', 'Web Development Fundamentals'),
(1, 'Assignment 4', 'Design a small web application using JavaScript.', '2024-12-30', 9, 'Pending', 'Web Development Fundamentals'),
(1, 'Assignment 5', 'Implement a simple database using SQL.', '2024-12-30', 10, 'Pending', 'Database Management Systems'),
(1, 'Assignment 6', 'Create a simple game using Python.', '2024-12-30', 11, 'Pending', 'Software Engineering'),
(1, 'Assignment 7', 'Write an algorithm to solve the Travelling Salesman Problem.', '2024-12-30', 12, 'Pending', 'Data Structures and Algorithms'),
(1, 'Assignment 8', 'Create a report on data structures and their applications.', '2024-12-30', 13, 'Pending', 'Data Structures and Algorithms'),
(2, 'Assignment 1', 'Create a flowchart for a simple OS scheduling algorithm.', '2024-12-31', 14, 'Pending', 'Operating Systems'),
(2, 'Assignment 2', 'Explain and implement the producer-consumer problem in C.', '2024-12-31', 15, 'Pending', 'Operating Systems'),
(2, 'Assignment 3', 'Write an algorithm to solve the Travelling Salesman Problem.', '2024-12-31', 16, 'Pending', 'Machine Learning Basics'),
(2, 'Assignment 4', 'Build a decision tree algorithm in Python.', '2024-12-31', 17, 'Pending', 'Machine Learning Basics'),
(2, 'Assignment 5', 'Implement a neural network in Python using TensorFlow.', '2024-12-31', 18, 'Pending', 'Machine Learning Basics'),
(2, 'Assignment 6', 'Write a program for basic networking protocols in C.', '2024-12-31', 19, 'Pending', 'Computer Networks'),
(3, 'Assignment 1', 'Create an AI chatbot using Python.', '2024-12-30', 20, 'Pending', 'Artificial Intelligence'),
(3, 'Assignment 2', 'Design and implement a recommendation system in Python.', '2024-12-30', 21, 'Pending', 'Mobile App Development'),
(3, 'Assignment 3', 'Implement a data structure for a linked list in C.', '2024-12-30', 22, 'Pending', 'Cloud Computing'),
(3, 'Assignment 4', 'Implement a simple game using Python and Pygame.', '2024-12-30', 23, 'Pending', 'Data Visualization'),
(3, 'Assignment 5', 'Write a report on machine learning algorithms and their applications.', '2024-12-30', 24, 'Pending', 'Artificial Intelligence'),
(3, 'Assignment 6', 'Design a mobile app using Android Studio.', '2024-12-30', 25, 'Pending', 'Mobile App Development'),
(3, 'Assignment 7', 'Create a game using JavaScript and HTML5.', '2024-12-30', 26, 'Pending', 'Web Development Fundamentals'),
(4, 'Assignment 1', 'Create a 3D model using basic computer graphics techniques.', '2024-12-30', 27, 'Pending', 'Computer Graphics'),
(4, 'Assignment 2', 'Build a simple game using Unity or Unreal Engine.', '2024-12-30', 28, 'Pending', 'Game Development'),
(4, 'Assignment 3', 'Design a website and implement a feedback system using PHP and MySQL.', '2024-12-30', 29, 'Pending', 'Blockchain Technology'),
(4, 'Assignment 4', 'Implement a version control system using Git for a team project.', '2024-12-30', 30, 'Pending', 'DevOps Practices'),
(4, 'Assignment 5', 'Create a portfolio website with advanced CSS animations.', '2024-12-30', 31, 'Pending', 'Web Development Fundamentals'),
(5, 'Assignment 1', 'Write a program to simulate a robotic arm using Python and Arduino.', '2024-12-31', 32, 'Pending', 'Robotics'),
(5, 'Assignment 2', 'Design a basic embedded system using Raspberry Pi.', '2024-12-31', 33, 'Pending', 'Parallel Computing'),
(5, 'Assignment 3', 'Build a basic neural network from scratch in Python.', '2024-12-31', 34, 'Pending', 'Digital Signal Processing'),
(5, 'Assignment 4', 'Explore and implement quantum algorithms in Qiskit.', '2024-12-31', 35, 'Pending', 'Quantum Computing Basics'),
(5, 'Assignment 5', 'Design an autonomous vehicle using AI-based techniques.', '2024-12-31', 36, 'Pending', 'Robotics'),
(5, 'Assignment 6', 'Design a deep learning model for image classification.', '2024-12-31', 37, 'Pending', 'Artificial Intelligence'),
(5, 'Assignment 7', 'Write a report on signal processing techniques in real-time applications.', '2024-12-31', 38, 'Pending', 'Digital Signal Processing'),
(5, 'Assignment 8', 'Create a quantum algorithm and run it on a quantum computer.', '2024-12-31', 39, 'Pending', 'Quantum Computing Basics'),
(6, 'Assignment 1', 'Create a basic ethical hacking toolkit using Python.', '2024-12-31', 40, 'Pending', 'Ethical Hacking'),
(6, 'Assignment 2', 'Develop an IoT-based temperature monitoring system.', '2024-12-31', 41, 'Pending', 'Big Data Analytics'),
(6, 'Assignment 3', 'Write a report on the applications of AR in healthcare.', '2024-12-31', 42, 'Pending', 'Augmented Reality'),
(6, 'Assignment 4', 'Design a basic VR game using Unity.', '2024-12-31', 43, 'Pending', 'Virtual Reality Development'),
(6, 'Assignment 5', 'Build a prototype for a smart home system using IoT.', '2024-12-31', 44, 'Pending', 'IoT Applications'),
(6, 'Assignment 6', 'Create a report on big data analytics in healthcare systems.', '2024-12-31', 45, 'Pending', 'Big Data Analytics'),
(6, 'Assignment 7', 'Develop an AR-based application for education.', '2024-12-31', 46, 'Pending', 'Augmented Reality');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL,
  `c_name` varchar(30) DEFAULT NULL,
  `c_description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `c_id`, `c_name`, `c_description`) VALUES
(1, 6, 'Introduction to Programming', 'Learn the basics of programming with Python.'),
(1, 7, 'Data Structures and Algorithms', 'Advanced problem-solving with data structures and algorithms.'),
(1, 8, 'Database Management Systems', 'Fundamentals of database design and SQL.'),
(1, 9, 'Web Development Fundamentals', 'Build modern web applications with HTML, CSS, and JavaScript.'),
(1, 10, 'Software Engineering', 'Principles and practices of software development.'),
(1, 11, 'Object-Oriented Programming', 'Advanced concepts in object-oriented design and programming.'),
(1, 12, 'Game Development Fundamentals', 'Learn the principles and tools of game development.'),
(1, 13, 'Cloud Storage Solutions', 'Study cloud storage systems and their implementation.'),
(2, 14, 'Discrete Mathematics', 'Mathematics for computer science.'),
(2, 15, 'Operating Systems', 'Design and implementation of operating systems.'),
(2, 16, 'Computer Networks', 'Introduction to networking concepts.'),
(2, 17, 'Machine Learning Basics', 'Introduction to machine learning algorithms.'),
(2, 18, 'Cybersecurity Fundamentals', 'Learn the basics of securing digital systems.'),
(2, 19, 'Internet of Things (IoT)', 'Building and integrating IoT systems for real-world applications.'),
(2, 20, 'Blockchain Technology', 'Study blockchain architecture and its applications.'),
(2, 21, 'Augmented Reality Applications', 'Develop and deploy augmented reality systems.'),
(2, 22, 'Cloud Computing', 'Overview of cloud services and architectures.'),
(3, 23, 'Artificial Intelligence', 'Basics of AI and its applications.'),
(3, 24, 'Human-Computer Interaction', 'Design and evaluation of user interfaces.'),
(3, 25, 'Mobile App Development', 'Building apps for Android and iOS.'),
(3, 26, 'Cloud Computing', 'Overview of cloud services and architectures.'),
(3, 27, 'Data Visualization', 'Techniques for presenting data effectively.'),
(3, 28, 'Big Data Management', 'Working with large datasets and distributed computing.'),
(3, 29, 'Game Programming', 'Development techniques for interactive games.'),
(3, 30, 'Advanced Machine Learning', 'Deep dive into machine learning algorithms and models.'),
(3, 31, 'Deep Learning Foundations', 'Understanding neural networks and deep learning concepts.'),
(3, 32, 'Data Science and Analytics', 'Exploring the process of data mining and data analysis.'),
(4, 33, 'Computer Graphics', 'Rendering and visualization techniques.'),
(4, 34, 'Game Development', 'Principles of game design and development.'),
(4, 35, 'Natural Language Processing', 'Processing and analyzing human language data.'),
(4, 36, 'Blockchain Technology', 'Introduction to distributed ledger systems.'),
(4, 37, 'DevOps Practices', 'Collaboration between development and IT operations.'),
(4, 38, 'Introduction to Data Engineeri', 'Learn the basics of data architecture and engineering.'),
(4, 39, 'Cyber Forensics', 'Understanding digital forensics and investigation techniques.'),
(4, 40, 'Digital Media Production', 'Learn techniques for producing media content.'),
(4, 41, 'Speech Recognition Systems', 'Building and understanding speech recognition technologies.'),
(5, 42, 'Parallel Computing', 'Programming for multi-core processors.'),
(5, 43, 'Robotics', 'Introduction to robotic systems and programming.'),
(5, 44, 'Digital Signal Processing', 'Processing signals using digital techniques.'),
(5, 45, 'Embedded Systems', 'Design of hardware-software integrated systems.'),
(5, 46, 'Quantum Computing Basics', 'Explore the fundamentals of quantum computing.'),
(5, 47, 'Advanced Robotics', 'In-depth study of autonomous and AI-driven robotic systems.'),
(5, 48, 'Neural Networks and Deep Learn', 'Study neural networks and their applications in AI.'),
(5, 49, 'Autonomous Systems', 'Designing self-driving systems and their components.'),
(5, 50, 'Smart City Technologies', 'Exploring smart city concepts and technologies.'),
(5, 51, 'AI for Healthcare', 'Exploring the applications of AI in the healthcare sector.'),
(6, 52, 'Ethical Hacking', 'Learn techniques for identifying system vulnerabilities.'),
(6, 53, 'Big Data Analytics', 'Working with large-scale data processing.'),
(6, 54, 'IoT Applications', 'Building and programming IoT devices.'),
(6, 55, 'Augmented Reality', 'Introduction to AR concepts and development.'),
(6, 56, 'Virtual Reality Development', 'Learn the principles of VR system design.'),
(6, 57, 'Smart Devices Programming', 'Programming for IoT-enabled smart devices.'),
(6, 58, 'Data Privacy and Security', 'Understanding privacy concerns and security measures.'),
(6, 59, 'Advanced Virtual Reality', 'Building complex VR systems and simulations.'),
(6, 60, 'Enterprise Architecture', 'Learn how to design and manage complex IT infrastructures.');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `title` varchar(255) NOT NULL,
  `c_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `email`, `name`, `password`) VALUES
(1, 'alice@example.com', 'Alice Johnson', 'password123'),
(2, 'bob@example.com', 'Bob Smith', 'password456'),
(3, 'carol@example.com', 'Carol Davis', 'password789'),
(4, 'david@example.com', 'David Brown', 'password321'),
(5, 'eve@example.com', 'Eve Wilson', 'password654'),
(6, 'frank@example.com', 'Frank White', 'password987');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `user_id` int(11) DEFAULT NULL,
  `q_title` varchar(50) DEFAULT NULL,
  `q_description` varchar(70) DEFAULT NULL,
  `q_date` char(15) DEFAULT NULL,
  `quiz_id` int(11) NOT NULL,
  `c_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`user_id`, `q_title`, `q_description`, `q_date`, `quiz_id`, `c_name`) VALUES
(1, 'Quiz 1', 'Basic programming concepts with Python.', '2024-12-30', 11, 'Introduction to Programming'),
(1, 'Quiz 2', 'Solve simple Python problems to test your skills.', '2024-12-30', 12, 'Introduction to Programming'),
(1, 'Quiz 1', 'Fundamental concepts of web development using HTML, CSS, and JavaScrip', '2024-12-30', 13, 'Web Development Fundamentals'),
(2, 'Quiz 1', 'Operating system concepts and memory management.', '2024-12-31', 14, 'Operating Systems'),
(2, 'Quiz 2', 'Understand file systems and process scheduling.', '2024-12-31', 15, 'Operating Systems'),
(2, 'Quiz 1', 'Learn about machine learning algorithms and their applications.', '2024-12-31', 16, 'Machine Learning Basics'),
(2, 'Quiz 2', 'Test your knowledge of supervised and unsupervised learning techniques', '2024-12-31', 17, 'Machine Learning Basics'),
(3, 'Quiz 1', 'Introduction to AI concepts and basic algorithms.', '2024-12-30', 18, 'Artificial Intelligence'),
(3, 'Quiz 2', 'Evaluate the performance of AI models and their applications.', '2024-12-30', 19, 'Artificial Intelligence'),
(3, 'Quiz 1', 'Mobile app development basics using Android and iOS platforms.', '2024-12-30', 20, 'Mobile App Development'),
(3, 'Quiz 1', 'Understand cloud computing principles and cloud services.', '2024-12-30', 21, 'Cloud Computing'),
(3, 'Quiz 1', 'Data visualization techniques using Python libraries.', '2024-12-30', 22, 'Data Visualization'),
(4, 'Quiz 1', 'Computer graphics fundamentals and rendering techniques.', '2024-12-30', 23, 'Computer Graphics'),
(4, 'Quiz 1', 'Learn about game development principles and design patterns.', '2024-12-30', 24, 'Game Development'),
(4, 'Quiz 2', 'Blockchain fundamentals and their applications in digital systems.', '2024-12-30', 25, 'Blockchain Technology'),
(4, 'Quiz 1', 'Principles of DevOps practices and collaboration.', '2024-12-30', 26, 'DevOps Practices'),
(5, 'Quiz 1', 'Robotics basics and control systems for robots.', '2024-12-31', 27, 'Robotics'),
(5, 'Quiz 1', 'Introduction to quantum computing and its practical applications.', '2024-12-31', 28, 'Quantum Computing Basics'),
(5, 'Quiz 2', 'Digital signal processing techniques and their applications.', '2024-12-31', 29, 'Digital Signal Processing'),
(6, 'Quiz 1', 'Introduction to ethical hacking and common penetration testing techniq', '2024-12-31', 30, 'Ethical Hacking'),
(6, 'Quiz 2', 'Big data analytics concepts and tools for processing large datasets.', '2024-12-31', 31, 'Big Data Analytics'),
(6, 'Quiz 1', 'IoT concepts and how to build IoT devices for various applications.', '2024-12-31', 32, 'IoT Applications'),
(6, 'Quiz 1', 'Basic augmented reality concepts and development tools.', '2024-12-31', 33, 'Augmented Reality'),
(6, 'Quiz 1', 'Learn the principles of virtual reality system design.', '2024-12-31', 34, 'Virtual Reality Development'),
(1, 'Quiz 1', 'Implement basic algorithms using Python for problem-solving.', '2024-12-30', 35, 'Introduction to Programming'),
(1, 'Quiz 1', 'Develop a simple calculator program using Python.', '2024-12-30', 36, 'Introduction to Programming'),
(2, 'Quiz 1', 'Memory management techniques and paging concepts.', '2024-12-31', 37, 'Operating Systems'),
(3, 'Quiz 1', 'Create and analyze machine learning algorithms using Python.', '2024-12-30', 38, 'Machine Learning Basics'),
(3, 'Quiz 1', 'Build and test a simple mobile app for event management.', '2024-12-30', 39, 'Mobile App Development'),
(4, 'Quiz 2', 'Game development concepts and interactive gameplay mechanics.', '2024-12-30', 40, 'Game Development'),
(5, 'Quiz 1', 'Build a neural network from scratch using Python.', '2024-12-31', 41, 'Robotics'),
(6, 'Quiz 1', 'Explore augmented reality in mobile app development.', '2024-12-31', 42, 'Augmented Reality'),
(1, 'Quiz 1', 'Learn the basics of data structures and algorithms in Python.', '2024-12-30', 43, 'Data Structures and Algorithms'),
(2, 'Quiz 1', 'Understand the operating system kernel and its functions.', '2024-12-31', 44, 'Operating Systems'),
(2, 'Quiz 1', 'Solve optimization problems using machine learning models.', '2024-12-31', 45, 'Machine Learning Basics'),
(3, 'Quiz 1', 'Apply cloud computing services to build scalable systems.', '2024-12-30', 46, 'Cloud Computing'),
(3, 'Quiz 1', 'Create a web app with cloud storage features.', '2024-12-30', 47, 'Cloud Computing'),
(4, 'Quiz 1', 'Create a game using Unity or other game engines.', '2024-12-30', 48, 'Game Development'),
(4, 'Quiz 2', 'Learn about cloud services and their impact on game development.', '2024-12-30', 49, 'Game Development'),
(5, 'Quiz 1', 'Design an embedded system for home automation.', '2024-12-31', 50, 'Embedded Systems'),
(5, 'Quiz 2', 'Understand the implementation of robotics in healthcare applications.', '2024-12-31', 51, 'Robotics'),
(6, 'Quiz 1', 'Build a facial recognition system using Python and OpenCV.', '2024-12-31', 52, 'Ethical Hacking'),
(6, 'Quiz 2', 'Create and manage a simple IoT device for home monitoring.', '2024-12-31', 53, 'IoT Applications'),
(1, 'Quiz 1', 'Build a Python program that simulates basic operations on a database.', '2024-12-30', 54, 'Database Management Systems'),
(2, 'Quiz 1', 'Implement a data structure for a graph in Python.', '2024-12-31', 55, 'Data Structures and Algorithms'),
(3, 'Quiz 2', 'Design and implement a mobile app that integrates with cloud services.', '2024-12-30', 56, 'Mobile App Development'),
(4, 'Quiz 1', 'Implement basic principles of blockchain and digital ledger.', '2024-12-30', 57, 'Blockchain Technology'),
(5, 'Quiz 1', 'Create a quantum computing simulation using Qiskit.', '2024-12-31', 58, 'Quantum Computing Basics'),
(6, 'Quiz 1', 'Learn how to build and deploy an IoT device with real-time data monito', '2024-12-31', 59, 'IoT Applications'),
(1, 'Quiz 1', 'Understand database normalization and implement SQL queries.', '2024-12-30', 60, 'Database Management Systems'),
(2, 'Quiz 1', 'Apply sorting and searching algorithms on large datasets.', '2024-12-31', 61, 'Data Structures and Algorithms'),
(3, 'Quiz 1', 'Create a mobile app that collects and displays real-time data from IoT', '2024-12-30', 62, 'Mobile App Development'),
(4, 'Quiz 1', 'Learn the principles of game mechanics and their applications in Unity', '2024-12-30', 63, 'Game Development'),
(5, 'Quiz 1', 'Build a digital signal processing model for audio filtering.', '2024-12-31', 64, 'Digital Signal Processing'),
(6, 'Quiz 1', 'Learn about data privacy and ethical hacking techniques.', '2024-12-31', 65, 'Ethical Hacking'),
(1, 'Quiz 1', 'Build a basic website layout using HTML, CSS, and JavaScript.', '2024-12-30', 66, 'Web Development Fundamentals'),
(2, 'Quiz 1', 'Implement basic file operations in a virtual file system.', '2024-12-31', 67, 'Operating Systems'),
(3, 'Quiz 1', 'Build a recommendation system based on collaborative filtering techniq', '2024-12-30', 68, 'Artificial Intelligence'),
(4, 'Quiz 1', 'Create a report on the future of blockchain technology in financial se', '2024-12-30', 69, 'Blockchain Technology'),
(5, 'Quiz 1', 'Develop a system to control robots using AI and sensors.', '2024-12-31', 70, 'Robotics'),
(6, 'Quiz 1', 'Create a basic IoT-based health monitoring system using sensors and Ra', '2024-12-31', 71, 'IoT Applications');

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
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `co_curriculars`
--
ALTER TABLE `co_curriculars`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `file_storage`
--
ALTER TABLE `file_storage`
  MODIFY `upload_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

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
