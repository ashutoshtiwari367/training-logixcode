-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 02, 2026 at 11:09 AM
-- Server version: 11.8.6-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u447123054_institute_regi`
--

-- --------------------------------------------------------

--
-- Table structure for table `admission_table`
--

CREATE TABLE `admission_table` (
  `id` int(11) NOT NULL,
  `admission_id` varchar(30) DEFAULT NULL,
  `student_name` varchar(150) NOT NULL,
  `father_name` varchar(150) NOT NULL,
  `aadhar_number` varchar(20) DEFAULT NULL,
  `college_name` varchar(200) DEFAULT NULL,
  `branch` varchar(120) DEFAULT NULL,
  `year` varchar(20) DEFAULT NULL,
  `technology_interest` text DEFAULT NULL,
  `student_photo` varchar(255) DEFAULT NULL,
  `custom_fees` decimal(10,2) DEFAULT 0.00,
  `submitted_fee` decimal(10,2) DEFAULT 0.00,
  `fee_status` enum('PAID','PARTIAL','PENDING') DEFAULT 'PENDING',
  `student_email` varchar(150) DEFAULT NULL,
  `registration_confirmed` enum('YES','NO') DEFAULT 'NO',
  `hostel_required` enum('YES','NO') DEFAULT 'NO',
  `rent_laptop` enum('YES','NO') DEFAULT 'NO',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admission_table`
--

INSERT INTO `admission_table` (`id`, `admission_id`, `student_name`, `father_name`, `aadhar_number`, `college_name`, `branch`, `year`, `technology_interest`, `student_photo`, `custom_fees`, `submitted_fee`, `fee_status`, `student_email`, `registration_confirmed`, `hostel_required`, `rent_laptop`, `created_at`, `updated_at`) VALUES
(23, 'ADM-2026-6967', 'ashutosh', 'Dinesh Chandra Tiwari', '522937478939382', 'faFf', 'CSE', '1st', 'laravel', 'student_69b07334309f56.37105222.jpeg', 6890.00, 890.00, 'PARTIAL', 'ashutoshtiwari9453@gmail.com', 'YES', 'YES', 'NO', '2026-03-10 19:38:28', '2026-03-10 19:38:28'),
(24, 'ADM-2026-5909', 'ashuosh tiwari', 'dinesh chandra tiwari', '522937931235', 'defef', 'cs it q', '2nd', 'java', 'student_69b0ea61ce6041.88508993.png', 5000.00, 500.00, 'PARTIAL', 'logixcode6@gmail.com', 'YES', 'YES', 'NO', '2026-03-11 04:06:57', '2026-03-11 04:06:57'),
(25, 'ADM-2026-4201', 'ashuosh tiwari', 'dinesh chandra tiwari', '32874892748374834783', 'svm', 'cs it q', '1st', 'react', 'student_69b0eb2dc4d617.82347944.png', 4433.00, 400.00, 'PARTIAL', 'logixcode6@gmail.com', 'YES', 'YES', 'NO', '2026-03-11 04:10:21', '2026-03-11 04:10:21'),
(26, 'ADM-2026-6796', 'ashutosh', 'Dinesh Chandra Tiwari', '38209482984934892348', 'Axis Institute of Higher Education', 'CSE', '1st', 'php', 'student_69b10a5c808522.34264518.png', 3000.00, 500.00, 'PARTIAL', 'ashutoshtiwari9453@gmail.com', 'YES', 'YES', 'NO', '2026-03-11 06:23:24', '2026-03-11 06:23:24'),
(27, 'ADM-2026-4678', 'Piyush rastogi', 'NARESH RASTOGI', '1234567890', 'Government Polytechnic Mohammdi, Kheri', 'Computer Science', '1st', 'laravel,react', 'student_69b11caf668151.37463975.png', 8000.00, 2000.00, 'PARTIAL', 'piyushrastogi170@gmail.com', 'YES', 'YES', 'NO', '2026-03-11 07:41:35', '2026-03-11 07:41:35'),
(28, 'ADM-2026-7603', 'Piyush Rastogi', 'Naresh Rastogi', '123456789012', 'Government Polytechnic Mohammdi, Kheri', 'Computer Science & Engineering', '3rd', 'php', 'student_69b11f232f7e63.52158959.jpg', 6000.00, 6000.00, 'PAID', 'piyushrastogi170@gmail.com', 'YES', 'YES', 'YES', '2026-03-11 07:52:03', '2026-03-11 07:52:03');

-- --------------------------------------------------------

--
-- Table structure for table `id_card_logs`
--

CREATE TABLE `id_card_logs` (
  `log_id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `email_to` varchar(150) NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `status` enum('Sent','Failed') DEFAULT 'Sent',
  `pdf_path` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Logs every ID card email sent to students';

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `registration_id` varchar(50) NOT NULL,
  `payment_gateway_id` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'INR',
  `status` enum('SUCCESS','FAILED','PENDING','OFFLINE') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `registration_id`, `payment_gateway_id`, `amount`, `currency`, `status`, `created_at`) VALUES
(3, 'REG20260216DEC92C', 'pay_SGnBJDVfZrzUDk', 1000.00, 'INR', 'SUCCESS', '2026-02-16 10:58:05'),
(4, 'REG20260217122060', 'pay_SHFVUzvw8gG3jN', 1000.00, 'INR', 'SUCCESS', '2026-02-17 14:43:13'),
(5, 'REG20260217BA3A33', 'pay_SHG1spsC07AcT7', 1000.00, 'INR', 'SUCCESS', '2026-02-17 15:11:39'),
(6, 'REG20260218ECCC22', 'pay_SHiZhAXA0rKPkY', 1000.00, 'INR', 'SUCCESS', '2026-02-18 19:07:10'),
(7, 'REG202602223DE24C', 'pay_SJ8XyF6DIO5QJ4', 500.00, 'INR', 'SUCCESS', '2026-02-22 09:10:27'),
(8, 'REG20260222D3D614', 'pay_SJ8laVt5lCXPTG', 500.00, 'INR', 'SUCCESS', '2026-02-22 09:23:25'),
(9, 'REG20260222BEAA76', 'pay_SJGzw3XaTMfNBz', 500.00, 'INR', 'SUCCESS', '2026-02-22 17:26:19'),
(10, 'REG20260222F51C72', 'pay_SJH38Rk6uXf0qo', 500.00, 'INR', 'SUCCESS', '2026-02-22 17:29:51'),
(11, 'REG20260222814846', 'pay_SJH3EW3q9ca5Me', 500.00, 'INR', 'SUCCESS', '2026-02-22 17:30:16'),
(12, 'REG202602248DC359', 'pay_SK0roPK40nylfk', 500.00, 'INR', 'SUCCESS', '2026-02-24 14:22:16'),
(13, 'REG2026022674AA03', 'OFFLINE-1772089735', 500.00, 'INR', 'OFFLINE', '2026-02-26 07:08:55'),
(14, 'REG20260227BCAA6A', 'OFFLINE-1772203371', 500.00, 'INR', 'OFFLINE', '2026-02-27 14:42:51'),
(17, 'REG20260308163EB6', 'pay_SOfhOsKlD2LUKc', 500.00, 'INR', 'SUCCESS', '2026-03-08 08:51:13'),
(18, 'REG2026030865A0AD', 'pay_SOgXUA5VVGAln9', 500.00, 'INR', 'SUCCESS', '2026-03-08 09:40:38'),
(19, 'REG20260311EC5CA6', 'OFFLINE-1773207486', 500.00, 'INR', 'OFFLINE', '2026-03-11 05:38:06'),
(21, 'REG20260311D44D15', 'OFFLINE-1773227053', 500.00, 'INR', 'OFFLINE', '2026-03-11 11:04:13'),
(22, 'REG2026031125E1E9', 'OFFLINE-1773227138', 500.00, 'INR', 'OFFLINE', '2026-03-11 11:05:38'),
(23, 'REG20260313C85855', 'pay_SQky6vbNtY8tf5', 500.00, 'INR', 'SUCCESS', '2026-03-13 15:18:36'),
(24, 'REG202603195E5334', 'pay_ST9RZ4KQBeTe99', 500.00, 'INR', 'SUCCESS', '2026-03-19 16:34:29'),
(25, 'REG2026032572C5EE', 'pay_SVRBI7yNBqxrBm', 500.00, 'INR', 'SUCCESS', '2026-03-25 11:12:55'),
(26, 'REG20260403E0A65F', 'pay_SYxAKbwA1moX1R', 500.00, 'INR', 'SUCCESS', '2026-04-03 08:26:06'),
(27, 'REG20260403BDFD18', 'OFFLINE-1775209259', 500.00, 'INR', 'OFFLINE', '2026-04-03 09:40:59'),
(28, 'REG2026040319D84F', 'OFFLINE-1775217553', 500.00, 'INR', 'OFFLINE', '2026-04-03 11:59:13'),
(29, 'REG202604049502CB', 'OFFLINE-1775319289', 500.00, 'INR', 'OFFLINE', '2026-04-04 16:14:49'),
(30, 'REG202604042CD11A', 'OFFLINE-1775319458', 500.00, 'INR', 'OFFLINE', '2026-04-04 16:17:38'),
(31, 'REG202604066AD99A', 'pay_SaCgPQ6nqiUkDs', 500.00, 'INR', 'SUCCESS', '2026-04-06 12:15:50'),
(32, 'REG2026040766FDA2', 'pay_SaXFio9r7GzoXV', 500.00, 'INR', 'SUCCESS', '2026-04-07 08:23:50'),
(33, 'REG202604099D3600', 'OFFLINE-1775734009', 500.00, 'INR', 'OFFLINE', '2026-04-09 11:26:49'),
(34, 'REG202604100C8E23', 'pay_Sbnz7OS2UqYQZ5', 500.00, 'INR', 'SUCCESS', '2026-04-10 13:24:32'),
(35, 'REG2026041152D5CD', 'pay_Sc4RoasedYnYaK', 500.00, 'INR', 'SUCCESS', '2026-04-11 05:30:45'),
(36, 'REG202604113C3815', 'pay_Sc4boROk9E285v', 500.00, 'INR', 'SUCCESS', '2026-04-11 05:40:19'),
(41, 'REG20260416DBF9CE', 'pay_Se0Uw7BK3Ru8fW', 500.00, 'INR', 'SUCCESS', '2026-04-16 02:57:01'),
(42, 'REG20260416141A70', 'pay_Se3CxVR05ujP71', 500.00, 'INR', 'SUCCESS', '2026-04-16 05:35:45'),
(43, 'REG202604196EF4B7', 'pay_SfEfjoVxYtnp1x', 500.00, 'INR', 'SUCCESS', '2026-04-19 05:27:50'),
(44, 'REG20260419D7FC32', 'OFFLINE-1776601181', 500.00, 'INR', 'OFFLINE', '2026-04-19 12:19:41'),
(45, 'REG20260419AD7E5E', 'OFFLINE-1776601594', 500.00, 'INR', 'OFFLINE', '2026-04-19 12:26:34'),
(46, 'REG20260419364103', 'OFFLINE-1776601715', 500.00, 'INR', 'OFFLINE', '2026-04-19 12:28:35');

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) NOT NULL,
  `sr_no` varchar(20) DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `student_name` varchar(150) DEFAULT NULL,
  `college_name` varchar(200) DEFAULT NULL,
  `student_year` varchar(20) DEFAULT NULL,
  `course_name` varchar(150) DEFAULT NULL,
  `account_of` varchar(100) DEFAULT NULL,
  `payment_mode` enum('CASH','ONLINE','UPI','CHEQUE') DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `amount_words` varchar(255) DEFAULT NULL,
  `fee_type` enum('training','registration') DEFAULT NULL,
  `payment_received_by` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`id`, `sr_no`, `receipt_date`, `student_name`, `college_name`, `student_year`, `course_name`, `account_of`, `payment_mode`, `amount`, `discount`, `total_amount`, `amount_words`, `fee_type`, `payment_received_by`, `created_at`) VALUES
(1, 'TR-2026-0001', '2026-03-13', 'Piyush Rastogi', 'Government Polytechnic Mohammdi Kheri ', '3rd Year', 'XYZ', 'Summer Training', 'CASH', 8000.00, 3000.00, 5000.00, 'Eight Thousand Rupees Only', 'training', 'Piyush Rastogi', '2026-03-13 10:40:55'),
(2, 'TR-2026-0002', '2026-04-11', 'ashuosh tiwari', 'dwjdhwdhwi', '2nd Year', 'idwiod', 'Summer Training', 'ONLINE', 5000.00, 600.00, 4400.00, 'fffdsfsc', 'registration', 'ashutosh tiwari', '2026-04-11 06:12:54');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `registration_id` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `address` text NOT NULL,
  `qualification` varchar(100) NOT NULL,
  `percentage` varchar(20) NOT NULL,
  `college` varchar(255) DEFAULT NULL,
  `year_of_passing` varchar(4) DEFAULT NULL,
  `program` varchar(255) NOT NULL,
  `experience` text DEFAULT NULL,
  `motivation` text DEFAULT NULL,
  `updates_opt_in` tinyint(1) DEFAULT 0,
  `payment_mode` enum('ONLINE','OFFLINE') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `registration_id`, `first_name`, `last_name`, `email`, `phone`, `dob`, `gender`, `address`, `qualification`, `percentage`, `college`, `year_of_passing`, `program`, `experience`, `motivation`, `updates_opt_in`, `payment_mode`, `created_at`) VALUES
(3, 'REG20260216DEC92C', 'ABHISHEK', 'UPADHYAY', 'sk740709000@gmail.com', '+918009780555', '2004-09-13', 'male', 'MEERUT,42 HORAM NAGAR', 'Diploma', '83.67', 'MPIT', '2027', 'Summer Training (45-60 Days)', 'C++ AND DSA', 'TO LEARN SOMETHING..', 1, 'ONLINE', '2026-02-16 10:58:05'),
(4, 'REG20260217122060', 'Saloni', 'Singh', 'saloniii12112008@gmail.com', '+919084863687', '2008-11-12', 'female', 'Muzaffarnagar', '10th', '66.5', 'UP board', '2024', 'Summer Training (45-60 Days)', 'No experience', 'I want to join this program to improve my technical skills and gain practical knowledge in programming languages like C, Python, and Java. This training will help me build my career and increase my confidence.', 1, 'ONLINE', '2026-02-17 14:43:13'),
(5, 'REG20260217BA3A33', 'Khushbu', 'Khattkwal', 'khushbookumari682060@gmail.com', '+919675682060', '2006-07-04', 'female', 'Shamli', '12th', '62.5', 'UP board', '2025', 'Summer Training (45-60 Days)', 'No experience', 'I want to join this program to improve my technical skills and gain practical knowledge in programming languages like C, Python, and Java. This training will help me build my career and increase my confidence.', 1, 'ONLINE', '2026-02-17 15:11:39'),
(6, 'REG20260218ECCC22', 'Durgesh Pratap', 'Singh', 'durgeshgolu9293@gmail.com', '+919919292915', '2008-02-16', 'male', 'Goverment Polytechnic Mohamadi Kheri', '12th', '75', 'KB Inter collage Payagpur Bahraich', '2024', 'Summer Training (45-60 Days)', NULL, 'Hello Sir, I attended Ashutosh Sir’ Nitin sir, workshop in our college, and I was really impressed. That is why I am enrolling in this training program. I want to learn everything from the very basic level to the best/advanced level so that I can build strong skills and confidence. Please guide me further. Thank you.', 1, 'ONLINE', '2026-02-18 19:07:10'),
(7, 'REG202602223DE24C', 'Sanjana', 'Kushwaha', 'sanjanakushwaha7034@gmail.com', '+919839313569', '2007-02-07', 'female', 'Kushinagar Up', 'Diploma', '82', 'Government girls polytechnic shamli', NULL, 'Summer Training (45-60 Days)', NULL, 'Python PHP', 0, 'ONLINE', '2026-02-22 09:10:27'),
(8, 'REG20260222D3D614', 'Madhavi', 'Kumari', 'bkbadal1972@gmail.com', '+917672862336', '2009-07-10', 'female', 'Patna kumhrar Kali mandir road i h', '10th', '76', 'CBSE', '2024', 'Summer Training (45-60 Days)', 'I have no experience', 'Python and php', 0, 'ONLINE', '2026-02-22 09:23:25'),
(9, 'REG20260222BEAA76', 'Ayan', 'Hasan', 'ah6075908@gmail.com', '+919250011648', '2005-09-15', 'male', 'Village-Kamhariya, Post-Beili,\r\nBahadurpur, PO: Nagar Bazar, DIST: Basti, Uttar Pradesh - 272302', '10th', '80', 'Mahamaya Polytechnic Of Information Technology JP Nagar Amroha', '2022', 'Summer Training (45-60 Days)', 'C Programming Language', 'Your company organized seminar in my college and I found to be best.', 1, 'ONLINE', '2026-02-22 17:26:19'),
(10, 'REG20260222F51C72', 'Piyush', 'Piyush', 'shuklapiyush477@gmail.com', '+919044283274', '2006-06-19', 'male', 'Sanichara babu post Naguwa \r\nSant Kabir Nagar 272162', '12th', '60', 'Mahamaya Polytechnic of Information Technology Amroha', '2024', 'Summer Training (45-60 Days)', 'Technology- Python with Django', 'Ur company organised seminar in my college so I really liked it..', 1, 'ONLINE', '2026-02-22 17:29:51'),
(11, 'REG20260222814846', 'Vijaylal', 'Vijaylal', 'vijaylal0015@gmail.com', '+919076687896', '2008-01-01', 'male', 'Jangal Agahi Bharawal Pepeeganj Gorakhpur 273165', '10th', '59', 'Mahamaya politechnic of information technology Amroha', '2023', 'Summer Training (45-60 Days)', 'Artificial intelligence and Machine Learning', 'Ur company organised a seminar in my college. \r\nI really appreciate it. \r\nLecturers r also good..', 1, 'ONLINE', '2026-02-22 17:30:16'),
(12, 'REG202602248DC359', 'Riya', 'Mishra', 'bhaktivideos57@gmail.com', '+919369743672', '2009-06-15', 'female', 'Sultanpur up', '10th', '82', 'Government girls polytechnic shamli', NULL, 'Summer Training (45-60 Days)', NULL, NULL, 0, 'ONLINE', '2026-02-24 14:22:16'),
(13, 'REG2026022674AA03', 'Saurabh', 'Pandey', 'saurabhpandit2945@gmail.com', '+918808413427', '2006-07-03', 'male', 'bhohalpur post shankarpur district basti (51)', '12th', '8', 'Government polytechnic mohammadi kheri', '2025', 'Summer Training (45-60 Days)', 'na', 'na', 1, 'OFFLINE', '2026-02-26 07:08:55'),
(14, 'REG20260227BCAA6A', 'Anjali', 'Tripathi', 'tiwarianjali1221a@gmail.com', '+916389723364', '2008-08-23', 'female', '2/21 Dhakna Purwa T.P Nagar Kanpur', 'Diploma', '79%', 'Government Polytechnic Kanpur', '2025', 'Java Programming', 'I am Fresher Student.', 'My Motivation is to keep learning and growing every day to achieve my career goals.', 1, 'OFFLINE', '2026-02-27 14:42:51'),
(17, 'REG20260308163EB6', 'Anchal', 'Pandey', 'aachal25092008@gmail.com', '+919214803510', '2008-09-25', 'female', 'Shahpur,shiv temple \r\nPO: Handia\r\nDIST: Prayagraj \r\nUttar Pradesh -221503', '10th', '60', 'PT. Laxminarayan Mishra Inter college Prayagraj', '2025', 'Summer Training (45-60 Days)', 'No any Experience', 'Experience  knowledge', 1, 'ONLINE', '2026-03-08 08:51:13'),
(18, 'REG2026030865A0AD', 'Muskan', 'Yadav', 'muskanyadav2551@gmail.com', '+919517203251', '2006-02-12', 'female', 'Majhauva ,\r\n post Ataula,\r\n Dist Sultanpur ,UP-227814', '12th', '71.1', 'Dharma Devi badri Prasad smark inter college kurwar sultanpur', '2024', 'Summer Training (45-60 Days)', NULL, NULL, 1, 'ONLINE', '2026-03-08 09:40:38'),
(19, 'REG20260311EC5CA6', 'Prince Raj', 'Singh', 'singhprince07032@gmail.com', '+919236441582', '2009-03-07', 'male', 'G76 rajeev nqgar yashoda nagar kanpur', '12th', '73.2%', 'Yashoda Inter Collage', '2025', 'Summer Training (45-60 Days)', 'na', 'na', 1, 'OFFLINE', '2026-03-11 05:38:06'),
(21, 'REG20260311D44D15', 'Sneha', 'Pandey', 'pandeysneha9707@gmail.com', '+919214540522', '2002-01-11', 'female', 'na', '12th', '', NULL, NULL, 'Summer Training (45-60 Days)', NULL, NULL, 1, 'OFFLINE', '2026-03-11 11:04:13'),
(22, 'REG2026031125E1E9', 'Najma', 'Khatton', 'najmakhatoon9118@gmail.com', '+919118701268', '2026-03-19', 'female', 'na', '12th', '', NULL, NULL, 'Summer Training (45-60 Days)', NULL, NULL, 0, 'OFFLINE', '2026-03-11 11:05:38'),
(23, 'REG20260313C85855', 'Riti', 'Singh', 'reetisingh663@gmail.com', '+918882566607', '2007-10-12', 'female', 'Jagatpur Pratapgarh', '10th', '79', 'UP board', '2024', 'Summer Training (45-60 Days)', 'No experience', 'I want to join this program to improve my technical skills and gain practical knowledge in programming languages like C, Python, and Java. This training will help me build my career and increase my confidence.', 1, 'ONLINE', '2026-03-13 15:18:36'),
(24, 'REG202603195E5334', 'Ajay', 'Kumar', 'ajaykumarlpm1122@gmail.com', '+919554539042', '2007-03-08', 'male', 'Government polytechnic Mohamdi Kheri', '12th', '63.60', 'PK Inter College', '2025', 'Summer Training (45-60 Days)', NULL, NULL, 1, 'ONLINE', '2026-03-19 16:34:29'),
(25, 'REG2026032572C5EE', 'Himanshu', 'Yadav', 'yadavhimanshu7942@gmail.com', '+919161953307', '2007-02-05', 'male', 'GRAM-TITIRA(KUDIYARI) POST-UCHAHUWAN, BANSGAON, BANSGAON,AZAMGARH, UTTAR PRADESH, 276126', '10th', '67', NULL, '2025', 'Summer Training (45-60 Days)', NULL, NULL, 1, 'ONLINE', '2026-03-25 11:12:55'),
(26, 'REG20260403E0A65F', 'Nandanee', 'Chauhan', 'nandanichauhan8948@gmail.com', '+919972159661', '2006-02-16', 'female', 'Shamli', '10th', '74', 'UP', '2025', 'Summer Training (45-60 Days)', 'i have  done experinced  Emmerging Trends Technology WorkShop', 'i wan to learn technical Demanded Skill', 1, 'ONLINE', '2026-04-03 08:26:06'),
(27, 'REG20260403BDFD18', 'Divyani', 'Dubey', 'divyanidubey68@gmail.com', '+917783081913', '2004-06-21', 'female', 'Government Girls Polytechnic Shamli', 'Diploma', '8.4', 'Government Girls Polytechnic Shamli', '2028', 'Summer Training (45-60 Days)', NULL, NULL, 1, 'OFFLINE', '2026-04-03 09:40:59'),
(28, 'REG2026040319D84F', 'Saumya', 'soni', 'saumyasoni7808@gmail.com', '+919451938722', '2006-04-12', 'female', 'Prayagraj', '12th', '80', 'College: Government girls polytechnic shamli', '2027', 'Summer Training (45-60 Days)', 'N/A', 'N/A', 1, 'OFFLINE', '2026-04-03 11:59:13'),
(29, 'REG202604049502CB', 'nandani', 'yadav', 'nandaniyadav7860@gmail.com', '+919236894732', '2006-10-02', 'female', 'rasra Ballia', 'Diploma', '8', 'GGP Shamli', '2027', 'Summer Training (45-60 Days)', NULL, NULL, 1, 'OFFLINE', '2026-04-04 16:14:49'),
(30, 'REG202604042CD11A', 'Jagriti', 'Mall', 'jagritimall883@gmail.com', '+919793798274', '2007-08-28', 'female', 'Ram janki Nagar Basharatpur Gorakhpur', 'Diploma', '8', 'GGP Shamli', '2027', 'Summer Training (45-60 Days)', NULL, NULL, 1, 'OFFLINE', '2026-04-04 16:17:38'),
(31, 'REG202604066AD99A', 'Ayush', 'Rao', 'ayushrao674@gmail.com', '+918957940926', '2006-06-23', 'male', 'kanpur\r\nNabausta', '12th', '50', 'GOVT POLYTECHNIC UNNAO', '2025', 'Summer Training (45-60 Days)', 'nO', 'I WANT TO JOIN FOR UPGRADE SKILL', 1, 'ONLINE', '2026-04-06 12:15:50'),
(32, 'REG2026040766FDA2', 'Rishu', 'Singh', 'rishusingh961987@gmail.com', '+918423879619', '2007-01-01', 'female', 'paryagraj koraon ambedkar nagar', '10th', '69.333', 'GGP shamli', '2023', 'Summer Training (45-60 Days)', 'No experience', 'I&#039;m joining this program because I aspire to be great engineer one day', 1, 'ONLINE', '2026-04-07 08:23:50'),
(33, 'REG202604099D3600', 'khushi', 'yagik', 'yagikkhushi5@gmail.com', '+917800401678', '2008-08-19', 'female', 'Conch', '12th', '80', 'Madhoghar', '2028', 'Summer Training (45-60 Days)', NULL, NULL, 1, 'OFFLINE', '2026-04-09 11:26:49'),
(34, 'REG202604100C8E23', 'Sahitya', 'Kulshrestha', 'sahityakulshrestha15@gmail.com', '+919452124300', '2006-03-19', 'male', 'Indra nagar kalpi', '12th', '75', 'Government Polytechnic Madhogarh', '2024', 'Summer Training (45-60 Days)', 'I am have done previous summer training BTPS kanpur', 'I am want to learn skill', 1, 'ONLINE', '2026-04-10 13:24:32'),
(35, 'REG2026041152D5CD', 'Harshita', 'Tiwari', 'harshitatiwari9935@gmail.com', '+917985826379', '2006-01-16', 'female', 'Kalpi', '12th', '70', 'Government politechnic madhogarh', '2023', 'Summer Training (45-60 Days)', 'I am no experience', 'I want to learn and great skill', 1, 'ONLINE', '2026-04-11 05:30:45'),
(36, 'REG202604113C3815', 'Himanshu', 'yadav', 'himanshuyadav74613@gmail.com', '+917307466341', '2007-07-07', 'male', 'Village and post shekhpur ahir', '12th', '71', 'Government Polytechnic Madhogarh Jalaun', '2024', 'Summer Training (45-60 Days)', 'I have done summer internship at btps kanpur', 'I want to learn and upgrade skills', 1, 'ONLINE', '2026-04-11 05:40:19'),
(41, 'REG20260416DBF9CE', 'Kritika', 'Chauhan', 'erkritikachuhan@gmail.com', '+919520738025', '2009-04-05', 'female', 'GGP Shamli', '10th', '75', 'Government girls polytechnic shamli', '2024', 'Summer Training (45-60 Days)', NULL, 'Python', 1, 'ONLINE', '2026-04-16 02:57:01'),
(42, 'REG20260416141A70', 'Dikshika', 'Chahuhan', 'dikshikachauhan@gmail.com', '+919520738025', '2008-10-29', 'female', 'Bijnor', '10th', '65', 'GOVT POLYTECHNIC Shamli', '2024', 'Summer Training (45-60 Days)', 'i have done expercince summer internship at Digicoder', 'I want to learn from Logixcode', 1, 'ONLINE', '2026-04-16 05:35:45'),
(43, 'REG202604196EF4B7', 'Kalpana', 'Devi', 'ramsevakpal2450@gmail.com', '+918545983389', '2009-01-12', 'female', 'Lucknow', '10th', '65', 'Government girls polytechnic shamli', '2024', 'Summer Training (45-60 Days)', 'No', 'C programming', 1, 'ONLINE', '2026-04-19 05:27:50'),
(44, 'REG20260419D7FC32', 'SHIVGAURI', 'KUMARI', 'logixcode6@gmail.com', '+918470814815', '2002-05-19', 'female', 'e-2-77 KDA Colony Dheli Sujanpur', 'Diploma', '8', 'svm', '2027', 'Summer Training (45-60 Days)', 'i', 'j', 0, 'OFFLINE', '2026-04-19 12:19:41'),
(45, 'REG20260419AD7E5E', 'ANUBHAV', 'MISHRA', 'mishraanubhav92386@gmail.com', '+918467898854', '2008-07-03', 'male', 'SIMRAMAU BANIPARA KANPUR DEHAT', 'Diploma', '72', 'GOVERNMENT POLYTECHNIC MADHOGARH JALAUN', '2028', 'Summer Training (45-60 Days)', 'f', 'f', 0, 'OFFLINE', '2026-04-19 12:26:34'),
(46, 'REG20260419364103', 'kirti', 'Yadav', 'kirtiyadav975855@gmail.com', '+918470956292', '2012-07-19', 'female', 'czsd', 'Diploma', '76', 'GGP Shamli', '2024', 'Summer Training (45-60 Days)', 'd', 'ddd', 0, 'OFFLINE', '2026-04-19 12:28:35');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL COMMENT 'Auto-generated e.g. STU123456',
  `student_name` varchar(100) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `aadhar_card` varchar(14) NOT NULL COMMENT 'Format: XXXX XXXX XXXX',
  `email` varchar(150) DEFAULT NULL COMMENT 'Used to send ID card via email',
  `phone` varchar(15) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL COMMENT 'Path to uploaded photo e.g. uploads/photos/STU123.jpg',
  `college_name` varchar(200) NOT NULL,
  `branch` varchar(100) NOT NULL,
  `year` enum('1st Year','2nd Year','3rd Year','4th Year') NOT NULL,
  `technology` varchar(100) NOT NULL COMMENT 'Web Dev, Android, AI, etc.',
  `custom_fees` decimal(10,2) DEFAULT 0.00,
  `registration` enum('Yes','No') DEFAULT 'No',
  `hostel` enum('Yes','No') DEFAULT 'No',
  `rent_laptop` enum('Yes','No') DEFAULT 'No',
  `id_card_path` varchar(255) DEFAULT NULL COMMENT 'Path to generated ID card PDF',
  `id_card_sent` tinyint(1) DEFAULT 0 COMMENT '1 = email sent, 0 = not sent',
  `registered_on` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('Active','Inactive','Pending') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores all student registration data for admin panel';

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `student_name`, `father_name`, `aadhar_card`, `email`, `phone`, `photo`, `college_name`, `branch`, `year`, `technology`, `custom_fees`, `registration`, `hostel`, `rent_laptop`, `id_card_path`, `id_card_sent`, `registered_on`, `updated_at`, `status`) VALUES
(1, 'STU000001', 'Rahul Sharma', 'Rajesh Sharma', '1234 5678 9012', 'rahul@gmail.com', '9876543210', NULL, 'ABC College', 'Computer Science', '2nd Year', 'Web Development', 15000.00, 'Yes', 'Yes', 'No', NULL, 0, '2026-02-19 06:14:57', '2026-02-19 06:14:57', 'Active'),
(2, 'STU20260219881', 'ashuosh tiwari', 'dinesh chandra tiwari', '5229 3793 1235', 'ashutoshtiwari9453@gmail.com', '8188030468', 'uploads/photos/STU20260219881.jpg', 'sharanpur', 'cs it q', '2nd Year', 'UI/UX Design', 1000.00, 'Yes', 'Yes', 'No', NULL, 0, '2026-02-19 07:38:11', '2026-02-19 07:38:11', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `summer_training_students`
--

CREATE TABLE `summer_training_students` (
  `id` int(11) NOT NULL,
  `student_name` varchar(150) NOT NULL,
  `father_name` varchar(150) NOT NULL,
  `aadhar` varchar(20) DEFAULT NULL,
  `college_name` varchar(200) NOT NULL,
  `branch` varchar(100) NOT NULL,
  `year` enum('1st Year','2nd Year','3rd Year','4th Year') NOT NULL,
  `technology` text DEFAULT NULL,
  `student_image` varchar(255) DEFAULT NULL,
  `custom_fees` decimal(10,2) NOT NULL DEFAULT 0.00,
  `registration` enum('Yes','No') NOT NULL DEFAULT 'No',
  `hostel` enum('Yes','No') NOT NULL DEFAULT 'No',
  `rent_laptop` enum('Yes','No') NOT NULL DEFAULT 'No',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','office') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'System Administrator', 'info@training.logixcode.com', '$2a$12$2EBLpCT/bilXI2orsDVbU.Gfh3itHcuoevw1Q5oDhm.YQLwqAL9We', 'admin', '2026-02-05 12:51:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admission_table`
--
ALTER TABLE `admission_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admission_id` (`admission_id`);

--
-- Indexes for table `id_card_logs`
--
ALTER TABLE `id_card_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_student` (`student_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_registration_id` (`registration_id`),
  ADD KEY `idx_payment_gateway_id` (`payment_gateway_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sr_no` (`sr_no`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registration_id` (`registration_id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_registration_id` (`registration_id`),
  ADD KEY `idx_payment_mode` (`payment_mode`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD KEY `idx_student_id` (`student_id`),
  ADD KEY `idx_college` (`college_name`),
  ADD KEY `idx_year` (`year`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `summer_training_students`
--
ALTER TABLE `summer_training_students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admission_table`
--
ALTER TABLE `admission_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `id_card_logs`
--
ALTER TABLE `id_card_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `summer_training_students`
--
ALTER TABLE `summer_training_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `id_card_logs`
--
ALTER TABLE `id_card_logs`
  ADD CONSTRAINT `id_card_logs_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`registration_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
