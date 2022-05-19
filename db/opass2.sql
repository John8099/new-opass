-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2022 at 05:06 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `opass2`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `attorney_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `request` text NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `cancelation_reason` text DEFAULT NULL,
  `ended_remark` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_accepted` date DEFAULT NULL,
  `date_ended` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `attorney_id`, `user_id`, `request`, `appointment_date`, `appointment_time`, `cancelation_reason`, `ended_remark`, `status`, `date_created`, `date_accepted`, `date_ended`) VALUES
(2, 2, 1, 'Test', '2022-05-13', '00:00:00', NULL, NULL, 'pending', '2022-05-13 04:06:44', NULL, NULL),
(3, 2, 1, 'Test', '2022-05-13', '00:00:00', NULL, NULL, 'accepted', '2022-05-13 02:52:29', NULL, NULL),
(4, 2, 1, 'Test', '2022-05-13', '00:00:00', 'Sample canceled', NULL, 'canceled', '2022-05-13 02:13:37', NULL, NULL),
(6, 2, 1, 'Test', '2022-05-13', '00:00:00', NULL, NULL, 'done', '2022-05-13 03:33:17', '2022-05-13', '2022-05-13'),
(7, 2, 1, 'Test', '2022-05-13', '00:00:00', 'test cancel', NULL, 'canceled', '2022-05-13 07:18:18', '2022-05-13', '2022-05-13'),
(8, 2, 1, 'Test', '2022-05-13', '00:00:00', 'test', 'Robery', 'done', '2022-05-13 07:19:49', '2022-05-13', '2022-05-13'),
(9, 4, 1, 'Test', '2022-05-20', '10:23:00', NULL, NULL, 'pending', '2022-05-17 01:22:29', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `chat_id` int(11) NOT NULL,
  `incoming_id` int(11) NOT NULL,
  `outgoing_id` int(11) NOT NULL,
  `sender_type` enum('user','attorney') NOT NULL,
  `message` text DEFAULT NULL,
  `message_type` enum('text','file') NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`chat_id`, `incoming_id`, `outgoing_id`, `sender_type`, `message`, `message_type`, `date_created`) VALUES
(1, 2, 1, 'user', 'NlpuVmZCSjZ4b1JuQVpPakVGdzk4QT09', 'text', '2022-05-19 14:42:23'),
(2, 1, 2, 'attorney', 'MTdzUERlNkhiUXVsM2dBR05sa3Q2QVVQVUo1WE1aODM1L04rdndWUmxmZHpJakNIbDN2KzRIS250QXJtYjRjZ0U2Z282S2ZLWEFCbnBpRFZzQllzcnVCNjZ2aEpHRGFQQXY1bms3TjY2NTFDMGFZb3JkbGlhNThwOTVzYk5zdGpxRGU0a2ZFYmE3UllBUURES1BaVkFvdEFxcHN4NEhwRG02dTNHUVZ4VFM1SS9JN2pNcGhKVVRRNnhib1JraFkvdVlNZjBPODl2eTZXNTBiUnBmME5sWWdQMGtHWGRwZjVjQkpOY3J2eTNOam1TeTZ4UWJDWDdERU5JU0Fkam5rWXg0V2I4Rk5UdE5wS1JPVlIrK29BdWc9PQ==', 'text', '2022-05-19 14:42:23');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `notify_to` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_seen` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `notify_to`, `creator_id`, `text`, `created_at`, `is_seen`) VALUES
(2, 2, 1, 'Test1 T. Test book an Appointment', '2022-05-17 07:12:11', 1),
(7, 0, 2, 'Test_atty1 T. Test canceled the Appointment', '2022-05-13 04:17:58', 0),
(10, 0, 2, 'Test_atty1 T. Test canceled the Appointment', '2022-05-13 06:34:55', 0),
(11, 0, 2, 'Test_atty1 T. Test canceled the Appointment', '2022-05-13 06:35:54', 0),
(12, 0, 2, 'Test_atty1 T. Test canceled the Appointment', '2022-05-13 06:36:27', 0),
(13, 0, 2, 'Test_atty1 T. Test canceled the Appointment', '2022-05-13 06:38:02', 0),
(14, 0, 2, 'Test_atty1 T. Test canceled the Appointment', '2022-05-13 06:38:32', 0),
(15, 0, 2, 'Test_atty1 T. Test canceled the Appointment', '2022-05-13 06:42:05', 0),
(27, 4, 1, 'Test1 T. Test book an Appointment', '2022-05-17 02:09:14', 0);

-- --------------------------------------------------------

--
-- Table structure for table `otp`
--

CREATE TABLE `otp` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `is_email_sent` tinyint(1) DEFAULT NULL,
  `is_sms_sent` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `specialization`
--

CREATE TABLE `specialization` (
  `specialization_id` int(11) NOT NULL,
  `specialization_name` text NOT NULL,
  `spec_description` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `specialization`
--

INSERT INTO `specialization` (`specialization_id`, `specialization_name`, `spec_description`) VALUES
(1, 'Animal Law', 'The primary objective will be to stand for the rights of animals along with the organizations and allies that serve and represent them. '),
(2, 'Bankruptcy Law', 'A legal proceeding that involves a person or business that is unable to repay outstanding debts.'),
(3, 'Banking and Finance Law', 'A legal practice that oversees “the organization, ownership, and operation of banks and depository institutions, mortgage banks, other providers of financial services regulated or licensed by state or federal banking regulators and holding companies.'),
(4, 'Civil Rights Law', 'Civil rights law guarantees the right for individuals to receive equal treatment and prohibits discrimination.'),
(5, 'Corporate Law', 'Corporate law is the field of law that establishes the rules and regulations needed for corporations to form and function. Working in corporate law means your primary objective will be addressing all aspects of a corporation’s legal administration.'),
(6, 'Criminal Law', 'Criminal law, as distinguished from civil law, is a system of laws concerned with punishment of individuals who commit crimes. '),
(7, 'Education Law', 'Education law is the field of law that covers legal matters related to schools, their students, and their staff. Duties of an education attorney include advocating for students’ and teachers’ rights, exposing tuition fraud, and developing new education policies.'),
(8, 'Employment Law', 'Addresses the rights of workers and the relationships they share with their employers. Duties of a labor law attorney include representing clients within issues ranging from wages and compensation to harassment and discrimination.'),
(9, 'Environmental & Natural Resources Law', 'Examines the ways humans interact with and impact the environment. Duties of an environmental law attorney include defending clients in areas of practice such as air and water quality, mining, deforestation, pollution, and more.'),
(11, 'Family Law', 'Addresses relational problems that arise in a familial context. Duties of a family law lawyer include working on varied cases involving areas of practice like divorce. However, although people often think of family law in the context of divorce, it is not limited to when a marriage dissolves.'),
(12, 'Health Law', 'Concerns the health of individuals and concentrates on policies implemented in the healthcare industry. '),
(13, 'Immigration Law', 'The primary objective will be to serve immigrant clients at all points of their naturalization process, as well as refugee and asylum seekers and individuals who have entered the country without the proper documentation.'),
(14, 'Intellectual Property Law\r\n', 'Encompasses the protection of creative works and symbols uniquely developed by individual persons or groups of people. Working in intellectual property law means your primary objective will align within a particular domain of practice, such as patent law or copyright law,'),
(15, 'Personal Injury Law', 'Personal injury lawyers deliver legal aid and counsel to clients who have experienced injury (mental, emotional, physical) due to the negligence or malpractice of another party.'),
(16, 'Real Estate Law', 'Concerns land, homes, construction, your neighbor’s property, legal solutions for construction defects like poor infrastructure, mold, or improperly installed fixtures, and more.');

-- --------------------------------------------------------

--
-- Table structure for table `todo`
--

CREATE TABLE `todo` (
  `todo_id` int(11) NOT NULL,
  `attorney_id` int(11) NOT NULL,
  `todo_text` text NOT NULL,
  `is_checked` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `todo`
--

INSERT INTO `todo` (`todo_id`, `attorney_id`, `todo_text`, `is_checked`) VALUES
(2, 2, 'stand up', 1),
(3, 2, 'don\'t give up the fight', 1),
(4, 2, 'do something else', 1),
(5, 2, 'get up', 1),
(6, 2, 'stand up', 1),
(7, 2, 'don\'t give up the fight', 1),
(8, 2, 'do something else', 1),
(9, 2, 'test', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `profile` text DEFAULT NULL,
  `fname` text NOT NULL,
  `mname` text NOT NULL,
  `lname` text NOT NULL,
  `email` text NOT NULL,
  `contact` text NOT NULL,
  `address` text NOT NULL,
  `birthday` date DEFAULT NULL,
  `schedule` text DEFAULT NULL,
  `year_exp` int(11) DEFAULT NULL,
  `specialization_id` int(11) NOT NULL,
  `role` text NOT NULL,
  `uname` text NOT NULL,
  `password` text NOT NULL,
  `opened_appointment` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `profile`, `fname`, `mname`, `lname`, `email`, `contact`, `address`, `birthday`, `schedule`, `year_exp`, `specialization_id`, `role`, `uname`, `password`, `opened_appointment`) VALUES
(1, '05112022-104407_8.png', 'fname', 'test', 'test', 'montemarjohn66@gmail.com', '09279172745', 'test', '1997-07-03', NULL, NULL, 0, 'user', 'uname', '$argon2i$v=19$m=65536,t=4,p=1$Lk1CY2hQcTV0Q1FCOGN5cQ$sBb3f7Tchw7xi546JHVLmMVpM+sMHP6gxnpqosxTFK4', 0),
(2, '05172022-122605_3.png', 'test_atty1', 'test', 'test', 'instructor@gmail.com', '09279172745', 'test1', '1997-05-30', 'M W F', 10, 5, 'atty', 'atty_uname', '$argon2i$v=19$m=65536,t=4,p=1$b05FS0xPdjVRR2l5VVFvdQ$hIW7+QNbBibXGH3EpdMMx1F++luexEGqF404wiZpZT4', 1),
(3, '05172022-122547_4.png', 'test1', 'test1', 'test1', 'coordinator@gmail.com', '09279172744', 'test', '1997-07-03', NULL, NULL, 0, 'user', 'awd', '$argon2i$v=19$m=65536,t=4,p=1$QlpQajJjblpxRFlnRWpGYg$VIcc5sd6DKuOxDXVYgmPY+9fDGQ18FU4AFKJZH3ilr8', 0),
(4, NULL, 'test_atty_2', 'test', 'test', 'instructor@gmail.com', '09279172742', 'test', '1997-05-30', 'M W F', 3, 1, 'atty', 'atty_uname2', '$argon2i$v=19$m=65536,t=4,p=1$b05FS0xPdjVRR2l5VVFvdQ$hIW7+QNbBibXGH3EpdMMx1F++luexEGqF404wiZpZT4', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`chat_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `otp`
--
ALTER TABLE `otp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `specialization`
--
ALTER TABLE `specialization`
  ADD PRIMARY KEY (`specialization_id`);

--
-- Indexes for table `todo`
--
ALTER TABLE `todo`
  ADD PRIMARY KEY (`todo_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `otp`
--
ALTER TABLE `otp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `specialization`
--
ALTER TABLE `specialization`
  MODIFY `specialization_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `todo`
--
ALTER TABLE `todo`
  MODIFY `todo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
