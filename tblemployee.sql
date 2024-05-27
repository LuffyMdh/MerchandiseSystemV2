-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 21, 2024 at 02:08 AM
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
-- Database: `mainportal`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblemployee`
--

CREATE TABLE `tblemployee` (
  `id` int(11) NOT NULL,
  `name` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `leave_verifier` varchar(250) NOT NULL,
  `leave_approver` varchar(250) DEFAULT NULL,
  `supervisors` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `email` varchar(29) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `on_shift` varchar(120) NOT NULL DEFAULT 'No',
  `designation` varchar(70) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `division` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `unit` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `subunit` varchar(50) DEFAULT NULL,
  `password` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `icnum_passport` varchar(14) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `passport_num` varchar(150) DEFAULT NULL,
  `visafromdate` text DEFAULT NULL,
  `visaenddate` text DEFAULT NULL,
  `jobgrade` varchar(9) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `level` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `gender` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `race` varchar(14) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `dob` varchar(19) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `placeofbirth` text DEFAULT NULL,
  `file_name` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT 'avatar.png',
  `phonenumber` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `empid` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `home_address` varchar(113) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `contract_start` varchar(19) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `probation_end_date` varchar(19) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `contract_end` varchar(19) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `retirement_date` varchar(19) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `employment_status` varchar(14) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `nationality` text DEFAULT NULL,
  `religion` text DEFAULT NULL,
  `bloodtype` text DEFAULT NULL,
  `statusofvaccine` text DEFAULT NULL,
  `currentmedication` text DEFAULT NULL,
  `disabilities` text DEFAULT NULL,
  `marital_status` varchar(9) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `spouse_name` varchar(250) DEFAULT NULL,
  `spouse_prof` text DEFAULT NULL,
  `spouse_nric` text DEFAULT NULL,
  `spouse_address` text DEFAULT NULL,
  `spouse_upload` varchar(150) DEFAULT NULL,
  `kin_name` varchar(150) DEFAULT NULL,
  `kin_nric` text DEFAULT NULL,
  `kin_contactnumber` text DEFAULT NULL,
  `father_name` varchar(150) DEFAULT NULL,
  `father_nric` text DEFAULT NULL,
  `father_phonenumber` text DEFAULT NULL,
  `mother_name` text DEFAULT NULL,
  `mother_nric` text DEFAULT NULL,
  `mother_phonenumber` text DEFAULT NULL,
  `em_name` text DEFAULT NULL,
  `em_phonenumber` text DEFAULT NULL,
  `em_address` text DEFAULT NULL,
  `em_relationship` text DEFAULT NULL,
  `professional_membership` text DEFAULT NULL,
  `year_admission` text DEFAULT NULL,
  `membership_status` text DEFAULT NULL,
  `targethour` int(11) NOT NULL DEFAULT 40,
  `online_status` int(11) NOT NULL DEFAULT 0,
  `form_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblemployee`
--

INSERT INTO `tblemployee` (`id`, `name`, `leave_verifier`, `leave_approver`, `supervisors`, `status`, `email`, `on_shift`, `designation`, `division`, `unit`, `subunit`, `password`, `icnum_passport`, `passport_num`, `visafromdate`, `visaenddate`, `jobgrade`, `level`, `gender`, `race`, `dob`, `placeofbirth`, `file_name`, `phonenumber`, `empid`, `home_address`, `contract_start`, `probation_end_date`, `contract_end`, `retirement_date`, `employment_status`, `nationality`, `religion`, `bloodtype`, `statusofvaccine`, `currentmedication`, `disabilities`, `marital_status`, `spouse_name`, `spouse_prof`, `spouse_nric`, `spouse_address`, `spouse_upload`, `kin_name`, `kin_nric`, `kin_contactnumber`, `father_name`, `father_nric`, `father_phonenumber`, `mother_name`, `mother_nric`, `mother_phonenumber`, `em_name`, `em_phonenumber`, `em_address`, `em_relationship`, `professional_membership`, `year_admission`, `membership_status`, `targethour`, `online_status`, `form_id`) VALUES
(287, 'Rodney Chua Ben Seng', 'Ahmad Afizal Bin Zainoren', 'Kushairi Bin Abang', 'Ahmad Afizal Bin Zainoren', 1, 'rodney@smg.my', 'No', 'Head of Marketing and Communications ', 'Marketing Communications', '', '', 'c4ca4238a0b923820dcc509a6f75849b', '', '', '', '', '', 'Executive', 'Male', 'Chinese', '', '', '104.jpg', '', 'M026', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 27, 0, 2),
(302, 'Gabriel Libau - Menoa', 'Rodney Chua Ben Seng', 'Rodney Chua Ben Seng', 'Rodney Chua Ben Seng', 1, 'gabriellibau@smg.my', 'No', 'Account Manager', 'Marketing Communications', 'Sales and Marketing', '', 'c4ca4238a0b923820dcc509a6f75849b', '', '', '', '', '', 'Executive', 'Male', 'Iban', '', '', '172.jpg', '', 'E269', '', '', '', '', '', '', '', '', '', '', 'NA', 'NA', '', '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 10, 1, 8),
(81, 'Nurul Izzah binti Zainudin', 'Aidil Amsyar bin Mohd Azamee', 'Mohd Helmy bin Ibrahim', 'Aidil Amsyar bin Mohd Azamee', 1, 'nurulizzah@smg.my', 'No', 'Information Technology (IT) Executive', 'Broadcast Infra', 'IT and Digital Platform', 'Development', 'c4ca4238a0b923820dcc509a6f75849b', '', '', '', '', '', 'Executive', 'Female', 'Malay', '', '', '', '', 'E246', '', '', '', '', '', '', '', '', '', '', 'NA', 'NA', '', '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 10, 1, 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblemployee`
--
ALTER TABLE `tblemployee`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblemployee`
--
ALTER TABLE `tblemployee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=313;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
