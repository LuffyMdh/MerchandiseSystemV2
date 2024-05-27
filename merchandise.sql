-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2024 at 03:51 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `merchandise`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` varchar(8) NOT NULL,
  `user_id` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`) VALUES
('CT368FE9', 'E269'),
('CT9DBECB', 'M026');

-- --------------------------------------------------------

--
-- Table structure for table `cartitem`
--

CREATE TABLE `cartitem` (
  `cart_id` varchar(8) NOT NULL,
  `quantity` int(11) NOT NULL,
  `product_id` varchar(8) NOT NULL,
  `mer_loc_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `finishedrequest`
--

CREATE TABLE `finishedrequest` (
  `finished_request_id` varchar(8) NOT NULL,
  `finished_date` datetime NOT NULL,
  `request_id` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `merchandiselocation`
--

CREATE TABLE `merchandiselocation` (
  `mer_loc_id` int(11) NOT NULL,
  `mer_loc_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `merchandiselocation`
--

INSERT INTO `merchandiselocation` (`mer_loc_id`, `mer_loc_name`) VALUES
(1, 'Kuching'),
(2, 'Kuala Lumpur'),
(10, 'Sibu'),
(11, 'Miri'),
(12, 'Bintulu'),
(13, 'Sri Aman');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `noti_id` varchar(8) NOT NULL,
  `noti_msg` text NOT NULL,
  `noti_isRead` tinyint(1) NOT NULL,
  `noti_date` datetime NOT NULL,
  `user_id` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`noti_id`, `noti_msg`, `noti_isRead`, `noti_date`, `user_id`) VALUES
('NT188337', 'Request ID #RQ18806E has been sent for approval.', 1, '2024-05-08 16:37:05', 'M026'),
('NT2D54CC', 'Your request #RQ18806E has been approved!', 1, '2024-05-09 09:27:30', 'M026'),
('NT319DB3', 'Request ID #RQ31994F has been sent for approval.', 1, '2024-05-08 14:36:19', 'M026'),
('NT3E0E3C', 'Your request #RQ31994F has been approved!', 1, '2024-05-08 14:42:27', 'M026'),
('NT407049', 'Request ID #RQ406E85 has been sent for approval.', 1, '2024-05-08 11:49:24', 'E269'),
('NT4EAF4D', 'Request ID #RQ4EACC7 has been sent for approval.', 1, '2024-05-08 16:36:36', 'M026'),
('NT6881F2', 'Request ID #RQ687FCE has been sent for approval.', 1, '2024-05-07 17:51:18', 'M026'),
('NT7BDC2A', 'Request ID #RQ7BDA44 has been sent for approval.', 1, '2024-05-08 16:06:31', 'M026'),
('NT817F15', 'Request ID #RQ817C99 has been sent for approval.', 1, '2024-05-08 16:08:08', 'M026'),
('NT81DA84', 'Your request ID #RQ406E85 has been rejected!', 0, '2024-05-08 14:55:52', 'E269'),
('NT81DA99', 'Your request ID #RQ31994F has been rejected!', 1, '2024-05-08 14:55:52', 'M026'),
('NTBD6E00', 'Request ID #RQBD6B46 has been sent for approval.', 0, '2024-05-08 13:36:11', 'E269'),
('NTC3409E', 'Request ID #RQC33ED1 has been sent for approval.', 0, '2024-05-08 16:10:20', 'E269');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` varchar(8) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_desc` text NOT NULL,
  `date` datetime DEFAULT NULL,
  `product_img` tinytext NOT NULL,
  `product_status` tinyint(1) NOT NULL,
  `product_cate_id` varchar(8) NOT NULL,
  `p_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_desc`, `date`, `product_img`, `product_status`, `product_cate_id`, `p_group_id`) VALUES
('PR34903F', 'Foldable Duffle Bag', '85L', '2024-05-08 14:58:11', 'assets/img/merchandise/PR663c435061730.png', 1, 'PC000001', 3),
('PR4968ED', 'Test', 'Test', '2024-05-08 14:58:12', 'assets/img/merchandise/PR663b2292c76ee.png', 1, 'PC000001', 2),
('PR4B283A', 'Polo T-Shirt', 'DESIGN : SHORT & LONG SLEEVE                                      MATERIAL : PREMIUM COTTON \r\nCOLOUR : WHITE                       \r\nSAMPLE STATUS : WITH CLIENT', '2024-05-07 15:24:36', 'assets/img/merchandise/PR6639d734b288d.png', 0, 'PC83BED7', 1),
('PRAEE0FB', 'Test', 'Test', '2024-05-08 14:43:54', 'assets/img/merchandise/PR663b1f2aee183.png', 1, 'PC000003', 2),
('PRBF1878', 'Reversible Umbrella', 'NEED TO PREPARE MOCK UP SAMPLE FOR CLIENT', '2024-05-08 13:47:55', 'assets/img/merchandise/PR663b120bf18f9.png', 1, 'PC000004', 1);

-- --------------------------------------------------------

--
-- Table structure for table `productcategory`
--

CREATE TABLE `productcategory` (
  `product_cate_id` varchar(8) NOT NULL,
  `cate_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productcategory`
--

INSERT INTO `productcategory` (`product_cate_id`, `cate_name`) VALUES
('PC000001', 'Bag'),
('PC000002', 'Fan'),
('PC000003', 'Bottle'),
('PC000004', 'Stationery'),
('PC418C0F', 'Accessories'),
('PC83BED7', 'Apparel');

-- --------------------------------------------------------

--
-- Table structure for table `productgroupcategory`
--

CREATE TABLE `productgroupcategory` (
  `p_group_id` int(11) NOT NULL,
  `p_group_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productgroupcategory`
--

INSERT INTO `productgroupcategory` (`p_group_id`, `p_group_name`) VALUES
(1, 'VIP'),
(2, 'Public'),
(3, 'General'),
(4, 'Corporate Box'),
(5, 'Test gain'),
(6, 'School'),
(7, 'School');

-- --------------------------------------------------------

--
-- Table structure for table `productquantity`
--

CREATE TABLE `productquantity` (
  `product_id` varchar(8) NOT NULL,
  `mer_loc_id` int(11) NOT NULL,
  `product_quan` int(255) NOT NULL,
  `product_location_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productquantity`
--

INSERT INTO `productquantity` (`product_id`, `mer_loc_id`, `product_quan`, `product_location_status`) VALUES
('PR34903F', 1, 250, 1),
('PR34903F', 2, 50, 1),
('PR34903F', 10, 0, 0),
('PR34903F', 11, 0, 0),
('PR34903F', 12, 0, 0),
('PR34903F', 13, 0, 0),
('PR4968ED', 1, 123, 1),
('PR4968ED', 2, 0, 0),
('PR4968ED', 10, 0, 0),
('PR4968ED', 11, 0, 0),
('PR4968ED', 12, 0, 0),
('PR4968ED', 13, 0, 0),
('PR4B283A', 1, 900, 1),
('PR4B283A', 2, 0, 0),
('PR4B283A', 10, 100, 0),
('PR4B283A', 11, 0, 0),
('PR4B283A', 12, 0, 0),
('PR4B283A', 13, 0, 0),
('PRAEE0FB', 1, 1400, 1),
('PRAEE0FB', 2, 100, 0),
('PRAEE0FB', 10, 0, 0),
('PRAEE0FB', 11, 0, 0),
('PRAEE0FB', 12, 0, 0),
('PRAEE0FB', 13, 0, 1),
('PRBF1878', 1, 300, 1),
('PRBF1878', 2, 100, 1),
('PRBF1878', 10, 0, 0),
('PRBF1878', 11, 0, 0),
('PRBF1878', 12, 0, 0),
('PRBF1878', 13, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `reqdoc`
--

CREATE TABLE `reqdoc` (
  `req_doc_id` varchar(8) NOT NULL,
  `request_id` varchar(8) NOT NULL,
  `req_doc_location` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `request_id` varchar(8) NOT NULL,
  `request_date` datetime NOT NULL,
  `request_status` tinyint(4) NOT NULL,
  `request_purpose` longtext NOT NULL,
  `modify_date` datetime DEFAULT NULL,
  `mer_loc_id` int(11) NOT NULL,
  `user_id` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`request_id`, `request_date`, `request_status`, `request_purpose`, `modify_date`, `mer_loc_id`, `user_id`) VALUES
('RQ18806E', '2024-05-08 16:37:05', 1, 'Test', '2024-05-09 09:27:30', 2, 'M026'),
('RQ31994F', '2024-05-08 14:36:19', 1, 'Test', '2024-05-08 14:42:27', 1, 'M026'),
('RQ406E85', '2024-05-08 11:49:24', -1, 'Test', '2024-05-08 13:18:37', 1, 'E269'),
('RQ4EACC7', '2024-05-08 16:36:36', 0, 'Test', '2024-05-08 16:36:36', 1, 'M026'),
('RQ687FCE', '2024-05-07 17:51:18', -1, 'Test', '2024-05-08 11:11:56', 1, 'M026'),
('RQ7BDA44', '2024-05-08 16:06:31', 0, 'Test', '2024-05-08 16:06:31', 1, 'M026'),
('RQ817C99', '2024-05-08 16:08:08', -1, 'Test', '2024-05-08 16:08:08', 2, 'M026'),
('RQBD6B46', '2024-05-08 13:36:11', -1, 'Test', '2024-05-08 13:36:11', 10, 'E269'),
('RQC33ED1', '2024-05-08 16:10:20', -1, 'Test', '2024-05-08 16:10:20', 2, 'E269');

-- --------------------------------------------------------

--
-- Table structure for table `requestassignment`
--

CREATE TABLE `requestassignment` (
  `request_id` varchar(8) NOT NULL,
  `admin_in_charge` varchar(8) NOT NULL,
  `comment` varchar(150) NOT NULL,
  `date` datetime NOT NULL,
  `pick_up_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requestassignment`
--

INSERT INTO `requestassignment` (`request_id`, `admin_in_charge`, `comment`, `date`, `pick_up_date`) VALUES
('RQ18806E', 'E246', 'NA', '2024-05-09 09:27:30', '2024-05-11 09:27:00'),
('RQ817C99', 'E246', 'Request is rejected due to one of the merchandise is not available/inactive', '2024-05-08 18:21:03', NULL),
('RQC33ED1', 'E246', 'Request is rejected due to one of the merchandise is not available/inactive', '2024-05-08 18:16:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `requestdetail`
--

CREATE TABLE `requestdetail` (
  `request_id` varchar(8) NOT NULL,
  `product_id` varchar(8) NOT NULL,
  `request_quan` int(11) NOT NULL,
  `request_product_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requestdetail`
--

INSERT INTO `requestdetail` (`request_id`, `product_id`, `request_quan`, `request_product_status`) VALUES
('RQ18806E', 'PRBF1878', 50, 1),
('RQ31994F', 'PR4B283A', 40, -1),
('RQ31994F', 'PRBF1878', 50, 1),
('RQ406E85', 'PR4B283A', 40, 0),
('RQ4EACC7', 'PRBF1878', 100, 0),
('RQ687FCE', 'PR4B283A', 30, 0),
('RQ7BDA44', 'PRAEE0FB', 50, 0),
('RQ7BDA44', 'PRBF1878', 50, 0),
('RQ817C99', 'PRAEE0FB', 50, -1),
('RQ817C99', 'PRBF1878', 50, -1),
('RQBD6B46', 'PR4B283A', 50, 0),
('RQC33ED1', 'PRAEE0FB', 50, -1),
('RQC33ED1', 'PRBF1878', 50, -1);

-- --------------------------------------------------------

--
-- Table structure for table `requestdetailcomment`
--

CREATE TABLE `requestdetailcomment` (
  `rd_comment_id` int(11) NOT NULL,
  `rd_comment` varchar(150) NOT NULL,
  `rd_comment_date` datetime DEFAULT NULL,
  `product_id` varchar(8) NOT NULL,
  `request_id` varchar(8) NOT NULL,
  `admin` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requestdetailcomment`
--

INSERT INTO `requestdetailcomment` (`rd_comment_id`, `rd_comment`, `rd_comment_date`, `product_id`, `request_id`, `admin`) VALUES
(78, 'Rejected amount: 5. Reason: Test', '2024-05-08 11:11:56', 'PR4B283A', 'RQ687FCE', 'E246'),
(79, 'Rejected amount: 50. Reason: Test', '2024-05-08 11:50:15', 'PR4B283A', 'RQ406E85', 'E246'),
(80, 'Rejected amount: 10. Reason: Test', '2024-05-08 13:18:37', 'PR4B283A', 'RQ406E85', 'E246'),
(81, 'Rejected amount: 10. Reason: Test', '2024-05-08 14:40:49', 'PR4B283A', 'RQ31994F', 'E246'),
(82, 'Merchandise rejected. Reason: Test', '2024-05-08 14:41:17', 'PR4B283A', 'RQ31994F', 'E246');

-- --------------------------------------------------------

--
-- Table structure for table `returnitem`
--

CREATE TABLE `returnitem` (
  `finished_request_id` varchar(8) NOT NULL,
  `return_detail_id` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `returnitemdetail`
--

CREATE TABLE `returnitemdetail` (
  `return_detail_id` varchar(8) NOT NULL,
  `product_id` varchar(8) NOT NULL,
  `return_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` varchar(8) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `role_desc` varchar(100) NOT NULL,
  `role_level` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role_name`, `role_desc`, `role_level`) VALUES
('RL000001', 'Normal', 'Normal User', 0),
('RL000002', 'Admin', 'Super user', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` varchar(8) NOT NULL,
  `user_fname` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_phone` varchar(15) NOT NULL,
  `user_pass` varchar(5) NOT NULL,
  `department` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL,
  `user_img` longblob NOT NULL,
  `role_id` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_fname`, `user_email`, `user_phone`, `user_pass`, `department`, `date_created`, `user_img`, `role_id`) VALUES
('US000001', 'Tun Ahamad Zaidi', 'khai@gmail.com', '016-301-9163', '123', 'IT & Broadcast', '0000-00-00 00:00:00', '', 'RL000001'),
('US000002', 'Lut', 'lut@gmail.com', '016-286-4005', '123', '', '0000-00-00 00:00:00', '', 'RL000002'),
('US000003', 'Zaidi', 'zaidi@gmail.com', '016-301-9163', '123', '', '0000-00-00 00:00:00', '', 'RL000001');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cartitem`
--
ALTER TABLE `cartitem`
  ADD PRIMARY KEY (`cart_id`,`product_id`,`mer_loc_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `req_loc_id` (`mer_loc_id`);

--
-- Indexes for table `finishedrequest`
--
ALTER TABLE `finishedrequest`
  ADD PRIMARY KEY (`finished_request_id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `merchandiselocation`
--
ALTER TABLE `merchandiselocation`
  ADD PRIMARY KEY (`mer_loc_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`noti_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `product_cate_id` (`product_cate_id`),
  ADD KEY `p_group_id` (`p_group_id`);

--
-- Indexes for table `productcategory`
--
ALTER TABLE `productcategory`
  ADD PRIMARY KEY (`product_cate_id`);

--
-- Indexes for table `productgroupcategory`
--
ALTER TABLE `productgroupcategory`
  ADD PRIMARY KEY (`p_group_id`);

--
-- Indexes for table `productquantity`
--
ALTER TABLE `productquantity`
  ADD PRIMARY KEY (`product_id`,`mer_loc_id`);

--
-- Indexes for table `reqdoc`
--
ALTER TABLE `reqdoc`
  ADD PRIMARY KEY (`req_doc_id`,`request_id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `mer_loc_id` (`mer_loc_id`);

--
-- Indexes for table `requestassignment`
--
ALTER TABLE `requestassignment`
  ADD PRIMARY KEY (`request_id`,`admin_in_charge`),
  ADD KEY `admin_in_charge` (`admin_in_charge`);

--
-- Indexes for table `requestdetail`
--
ALTER TABLE `requestdetail`
  ADD PRIMARY KEY (`request_id`,`product_id`) USING BTREE,
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `requestdetailcomment`
--
ALTER TABLE `requestdetailcomment`
  ADD PRIMARY KEY (`rd_comment_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `returnitem`
--
ALTER TABLE `returnitem`
  ADD PRIMARY KEY (`finished_request_id`,`return_detail_id`),
  ADD KEY `return_detail_id` (`return_detail_id`);

--
-- Indexes for table `returnitemdetail`
--
ALTER TABLE `returnitemdetail`
  ADD PRIMARY KEY (`return_detail_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `merchandiselocation`
--
ALTER TABLE `merchandiselocation`
  MODIFY `mer_loc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `productgroupcategory`
--
ALTER TABLE `productgroupcategory`
  MODIFY `p_group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `requestdetailcomment`
--
ALTER TABLE `requestdetailcomment`
  MODIFY `rd_comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cartitem`
--
ALTER TABLE `cartitem`
  ADD CONSTRAINT `cartitem_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`),
  ADD CONSTRAINT `cartitem_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `cartitem_ibfk_3` FOREIGN KEY (`mer_loc_id`) REFERENCES `merchandiselocation` (`mer_loc_id`);

--
-- Constraints for table `finishedrequest`
--
ALTER TABLE `finishedrequest`
  ADD CONSTRAINT `finishedrequest_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `request` (`request_id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`product_cate_id`) REFERENCES `productcategory` (`product_cate_id`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`p_group_id`) REFERENCES `productgroupcategory` (`p_group_id`);

--
-- Constraints for table `reqdoc`
--
ALTER TABLE `reqdoc`
  ADD CONSTRAINT `reqdoc_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `request` (`request_id`),
  ADD CONSTRAINT `reqdoc_ibfk_2` FOREIGN KEY (`req_doc_id`) REFERENCES `requsetdocument` (`req_doc_id`);

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`mer_loc_id`) REFERENCES `merchandiselocation` (`mer_loc_id`);

--
-- Constraints for table `requestassignment`
--
ALTER TABLE `requestassignment`
  ADD CONSTRAINT `requestassignment_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `request` (`request_id`);

--
-- Constraints for table `requestdetail`
--
ALTER TABLE `requestdetail`
  ADD CONSTRAINT `requestdetail_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  ADD CONSTRAINT `requestdetail_ibfk_2` FOREIGN KEY (`request_id`) REFERENCES `request` (`request_id`);

--
-- Constraints for table `requestdetailcomment`
--
ALTER TABLE `requestdetailcomment`
  ADD CONSTRAINT `requestdetailcomment_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `requestdetail` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `requestdetailcomment_ibfk_2` FOREIGN KEY (`request_id`) REFERENCES `requestdetail` (`request_id`) ON DELETE CASCADE;

--
-- Constraints for table `returnitem`
--
ALTER TABLE `returnitem`
  ADD CONSTRAINT `returnitem_ibfk_1` FOREIGN KEY (`finished_request_id`) REFERENCES `finishedrequest` (`finished_request_id`),
  ADD CONSTRAINT `returnitem_ibfk_2` FOREIGN KEY (`return_detail_id`) REFERENCES `returnitemdetail` (`return_detail_id`);

--
-- Constraints for table `returnitemdetail`
--
ALTER TABLE `returnitemdetail`
  ADD CONSTRAINT `returnitemdetail_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
