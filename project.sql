-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2025 at 12:49 PM
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
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrators`
--

CREATE TABLE `administrators` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `id_num` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administrators`
--

INSERT INTO `administrators` (`admin_id`, `name`, `email`, `password`, `dob`, `id_num`) VALUES
(5, 'Emmanuel Ayisi', 'emm4090@gmail.com', '$2y$10$iFI2DnR4xDp6uA8buVwn1uFUraXu1zYEIh27Fpev3NB17Y6qv.6cy', '2025-05-16', 'AC5gt0'),
(6, 'Agyei Sofaraa', 'jsofaraaagyei@gmail.com', '$2y$10$N04qo3GpDjdWOldWFpOx4.inVPHPN2XPqSr0EkGY.tXcgAjchWZca', '2025-05-06', 'ADM1100343');

--
-- Triggers `administrators`
--
DELIMITER $$
CREATE TRIGGER `before_insert_administrators` BEFORE INSERT ON `administrators` FOR EACH ROW BEGIN
    IF NEW.admin_id IS NULL THEN
        SET NEW.admin_id = IFNULL((SELECT MAX(admin_id) + 1 FROM administrators), 1);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `sender_type` enum('admin','student','lecturer') DEFAULT NULL,
  `sender_id` varchar(50) DEFAULT NULL,
  `receiver_type` enum('admin','student','lecturer') DEFAULT NULL,
  `receiver_id` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lecturers`
--

CREATE TABLE `lecturers` (
  `lecturer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `school` varchar(255) NOT NULL,
  `id_num` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lecturers`
--

INSERT INTO `lecturers` (`lecturer_id`, `name`, `email`, `password`, `dob`, `school`, `id_num`) VALUES
(8, 'Mr. Asantee', 'asante@gmail.com', '$2y$10$YX0hVwKBk7G1V.a4MZajQeeIslcEjVXvciz0lg0cVAwaXshenmZCC', '1996-02-07', 'GEOSCIENCES', 'LECF4YP0UHM'),
(9, 'Karim Banda', 'karim@gmail.com', '$2y$10$aN45BVG.TbzgyFuXOSXS2.JBA1wkrCftTo1lSsEtiUTL3Ff3pyjya', '2001-08-15', 'ENGINEERING', 'LEC1108022');

--
-- Triggers `lecturers`
--
DELIMITER $$
CREATE TRIGGER `before_insert_lecturers` BEFORE INSERT ON `lecturers` FOR EACH ROW BEGIN
    IF NEW.lecturer_id IS NULL THEN
        SET NEW.lecturer_id = IFNULL((SELECT MAX(lecturer_id) + 1 FROM lecturers), 1);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `lecturer_id` varchar(255) NOT NULL,
  `exam_id` varchar(255) NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_answer` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `lecturer_id`, `exam_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`) VALUES
(1, '9', 'CENG102', 'Which of the following is a correct identifier in C++?', '1variable', '_myVar', 'int', 'float', 'B'),
(2, '9', 'CENG102', 'Which of the following is a valid C++ keyword?', 'function', 'var', 'class', 'define', 'C'),
(3, '9', 'CENG102', 'What will cout << 5 / 2; output?', '2.5', '2', '2.0', '3', 'B'),
(4, '9', 'CENG102', 'Which of the following is used to include libraries in C++?', '#import', 'include()', '#include', 'using', 'C'),
(5, '9', 'CENG102', 'What is the default return type of a function if not specified in C++?', 'void', 'int', 'float', 'None', 'B'),
(6, '9', 'CENG102', 'What does the using namespace std; statement do?', 'Declares a new namespace', 'Imports all standard C++ library symbols', 'Links the std library', 'Declares standard variables', 'B'),
(7, '9', 'CENG102', 'What is the output of cout << true + false?', '0', '1', 'true', 'false', 'B'),
(8, '9', 'CENG102', 'Which of these is NOT a loop structure in C++?', 'for', 'do...while', 'until', 'while', 'C'),
(9, '9', 'CENG102', 'What operator is used for assignment?', '==', ':=', '=', '=>', 'C'),
(10, '9', 'CENG102', 'What is the size of int typically in C++?', '2 bytes', '4 bytes', '8 bytes', 'Depends on system', 'D'),
(11, '9', 'CENG102', 'What will int x = 5.7; cout << x; print?', '5.7', '6', '5', 'Error', 'C'),
(12, '9', 'CENG102', 'Which of these is a correct way to define a function?', 'function void display()', 'display() void', 'void display()', 'function display(): void', 'C'),
(13, '9', 'CENG102', 'Which keyword is used to return a value from a function?', 'break', 'end', 'return', 'exit', 'C'),
(14, '9', 'CENG102', 'The modulus operator (%) is used to:', 'Divide numbers', 'Get decimal part', 'Get remainder', 'Multiply', 'C'),
(15, '9', 'CENG102', 'Which header file is required for cout and cin?', 'stdlib.h', 'cstdio', 'iostream', 'input.h', 'C'),
(16, '9', 'CENG102', 'How do you take input for a variable x?', 'cin >> x;', 'input(x);', 'read(x);', 'scan >> x;', 'A'),
(17, '9', 'CENG102', 'What is a correct syntax to declare an array?', 'int a[5];', 'array int a[5];', 'int a = [5];', 'a[5] int;', 'A'),
(18, '9', 'CENG102', 'Which of these is a relational operator?', '&&', '||', '==', '+=', 'C'),
(19, '9', 'CENG102', 'What is the output of cout << (4 < 5)?', 'true', '1', 'false', '0', 'B'),
(20, '9', 'CENG102', 'How do you define a constant in C++?', '#define PI 3.14', 'const float PI = 3.14;', 'constant PI = 3.14;', 'static const PI = 3.14;', 'B'),
(21, '9', 'CENG102', 'What data type is used to store true/false?', 'bool', 'boolean', 'bit', 'truth', 'A'),
(22, '9', 'CENG102', 'Which of the following opens a block of code?', '(', '[', '{', '<', 'C'),
(23, '9', 'CENG102', 'Which of these is not a valid loop?', 'repeat...until', 'for', 'while', 'do...while', 'A'),
(24, '9', 'CENG102', 'Which keyword ends a switch-case block?', 'exit', 'break', 'end', 'stop', 'B'),
(25, '9', 'CENG102', 'What is the output of cout << \"Hello\" + 1?', 'Hello1', 'ello', 'Hello', 'Error', 'B'),
(26, '9', 'CENG102', 'What does ++x mean?', 'Increase after use', 'Increase before use', 'Decrease', 'Divide', 'B'),
(27, '9', 'CENG102', 'Which header file is used for mathematical operations?', 'math.h', 'iostream', 'cmath', 'conio.h', 'C'),
(28, '9', 'CENG102', 'Which of these functions is used to find square root?', 'sqrt()', 'sq()', 'power()', 'squareroot()', 'A'),
(29, '9', 'CENG102', 'What is a correct way to define a character variable?', 'char ch = \"A\";', 'char ch = \'A\';', 'char ch = A;', 'char ch = \"a\";', 'B'),
(30, '9', 'CENG102', 'A function that does not return any value is declared as:', 'returnless', 'int', 'void', 'none', 'C'),
(31, '9', 'CENG102', 'Which of the following is a preprocessor directive?', '#define', 'main()', 'namespace', 'stdio', 'A'),
(32, '9', 'CENG102', 'What is the result of 5 == 5 && 3 < 4?', 'true', '1', 'false', 'Error', 'B'),
(33, '9', 'CENG102', 'Which is the correct way to write if condition?', 'if x > y then', 'if x > y:', 'if (x > y)', 'if x > y;', 'C'),
(34, '9', 'CENG102', 'Which function starts program execution?', 'start()', 'main()', 'init()', 'program()', 'B'),
(35, '9', 'CENG102', 'A switch statement works with:', 'bool', 'float', 'string', 'int', 'D'),
(36, '9', 'CENG102', 'Which operator is used to access members of a structure?', '::', '->', '.', '#', 'C'),
(37, '9', 'CENG102', 'What is the result of 10 % 3?', '3', '1', '0', '2', 'B'),
(38, '9', 'CENG102', 'What will int a = 5, b = 3; cout << a++ - --b; print?', '2', '1', '3', 'Error', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `randadmin`
--

CREATE TABLE `randadmin` (
  `admin_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `randadmin`
--

INSERT INTO `randadmin` (`admin_id`) VALUES
('AC5gt0'),
('ADM1100343'),
('ADMLXIE7Y3K'),
('ADMM7XE3RI1'),
('C');

-- --------------------------------------------------------

--
-- Table structure for table `randlec`
--

CREATE TABLE `randlec` (
  `lecturer_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `randlec`
--

INSERT INTO `randlec` (`lecturer_id`) VALUES
('A'),
('LEC1108022'),
('LECF3PQVZT2'),
('LECF4YP0UHM'),
('LECUI76UBX0');

-- --------------------------------------------------------

--
-- Table structure for table `randstu`
--

CREATE TABLE `randstu` (
  `student_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `randstu`
--

INSERT INTO `randstu` (`student_id`) VALUES
('B'),
('OOOO'),
('STUBEHHBKF8'),
('STUJUNPVA9Q'),
('UEB110111'),
('UEB111111');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `result_id` int(10) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `student_idNum` varchar(255) NOT NULL,
  `exam_id` varchar(255) NOT NULL,
  `score` varchar(255) NOT NULL,
  `attempts` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`result_id`, `student_id`, `student_name`, `student_idNum`, `exam_id`, `score`, `attempts`) VALUES
(1, '8', 'Naheema', 'STUBEHHBKF8', 'CENG102', '6/38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `level` varchar(50) NOT NULL,
  `school` varchar(255) NOT NULL,
  `program` varchar(255) NOT NULL,
  `id_num` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `name`, `email`, `password`, `dob`, `level`, `school`, `program`, `id_num`) VALUES
(8, 'Naheema', 'naheem@gmail.com', '$2y$10$hiP7NQmn/pMq0d4OxVXlbOL2eP53SJ4LAPhDLDIHHtVir7PE3qj7W', '2025-05-08', '100', 'ENGINEERING', 'BSc. Computer Engineering', 'STUBEHHBKF8');

--
-- Triggers `students`
--
DELIMITER $$
CREATE TRIGGER `before_insert_students` BEFORE INSERT ON `students` FOR EACH ROW BEGIN
    IF NEW.student_id IS NULL THEN
        SET NEW.student_id = IFNULL((SELECT MAX(student_id) + 1 FROM students), 1);
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrators`
--
ALTER TABLE `administrators`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lecturers`
--
ALTER TABLE `lecturers`
  ADD PRIMARY KEY (`lecturer_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `randadmin`
--
ALTER TABLE `randadmin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `randlec`
--
ALTER TABLE `randlec`
  ADD PRIMARY KEY (`lecturer_id`);

--
-- Indexes for table `randstu`
--
ALTER TABLE `randstu`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`result_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrators`
--
ALTER TABLE `administrators`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lecturers`
--
ALTER TABLE `lecturers`
  MODIFY `lecturer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `result_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
