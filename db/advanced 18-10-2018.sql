-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2018 at 11:15 AM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `advanced`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `id` int(11) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`id`, `branch_name`, `description`, `created_at`, `updated_at`) VALUES
(2, 'werw', 'rwer', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m130524_201442_init', 1539597015),
('m140506_102106_rbac_init', 1539240186),
('m170907_052038_rbac_add_index_on_auth_assignment_user_id', 1539240186),
('m180913_044939_user_profile', 1539597016);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_by` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES
(1, 'mohammad', 'iaXaJzVMFZknDi9uEbd6YiHV0Zjt2yGh', '$2y$13$HpSR183dKfxVKyDEWFR7peTfj9dfbBZF6ee/mPym1t4Dvzib8rlPm', 'lQOV9_LK52aFTORc-ra9hTWoYcop6KHV_1539597014', 'mohmaz1985@yahoo.com', 10, 1, 1539597014, 5, 1539853709),
(2, 'demo', 'CxVvPHkTSXnlgWie4MvPPnoRmFkPK-kS', '$2y$13$oQMl5JMCkVi0tPOtwScCgugvLT8I1v86dd/7/IL1277XX5kPOUltG', 'EMzuEPncu_hqhEGkOqS-0-Ag2tyFtHm0_1539597015', 'demo@yahoo.com', 10, 1, 1539597015, 1, 1539849596),
(3, 'test', 'AFvo1yJRYkBxzZhiI3GymXqhR_x3MMkj', '$2y$13$4E5gakx7HqjTA9G.ESeF7OMYC.a786BYuYKqS70pz9fpkl0ML9h8K', 'ZZQHqZk2GMXjIHpJHUM2YsLm_V2kynWL_1539597103', 'test@yahoo.com', 0, 1, 1539597103, 0, 0),
(4, 'ddd', 'RZGstJTd2tRW2vuMC9ZUmMgLoVA4VZeF', '$2y$13$9DiKLCdfsKsN.dws8uu8cO3yuWrbAMHzWmFBkTRsT6izAgCG8P4nq', 'K-iMwAoFv36sw-k7IA5daNRUwjWhNAAV_1539770546', 'd@q.c', 10, 1, 1539770546, 1, 1539772175),
(5, 'NewName', 'iBDXVXFWpn6yBD1XaQ9TufHnFUlWWnU5', '$2y$13$jOry5VNSae/3vzpvIqIwtO6fNg92orfUopck/lBqWsbpyzg6zL5ri', 'dpOEsHpIvCTHMs5GqqLtwXtfZXwEMJ8i_1539770703', 'NewName@yahoo.com', 10, 1, 1539770703, 1, 1539853687),
(6, 'sss', 'bsLXyju6fiQyEYuzDKpRSxzpKE0T_feo', '$2y$13$nkAkynJsuLGoWF4o2/NCAOt5wU3ag4cvCLNwpqH9HbQYSh3dXm276', 'INRQ_jKnULsAd257mh_NThoAIs9l_U51_1539772303', 'a@q.com', 10, 1, 1539772303, 1, 1539772595);

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name_ar` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `full_name_en` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `user_image` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `full_address` varchar(400) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`id`, `user_id`, `full_name_ar`, `full_name_en`, `user_image`, `country`, `city`, `zip`, `full_address`) VALUES
(1, 1, 'محمد السيد', 'Mohammad Alsayyed', 'uploads/users/mohammad/mohammad_1539777529_8060.jpg', 'JO', 'JO-AM', '+962', 'Amman Jordan - Tariq2'),
(2, 2, 'تجربة', 'Demo Demo', 'uploads/users/demo/demo_1539849596_8975.jpg', 'JO', 'JO-SA', '+20066', 'Amman Jordan - Demo Location'),
(3, 3, 'تجربة', 'test', '', 'JO', 'JO-IR', '+962', 'Amman Jordan'),
(4, 4, 'ss', 'ss', 'uploads/users/ddd/ddd_1539772175_9563.jpg', 'AF', 'AF-KA', '+93', 'ssss'),
(5, 5, 'New Name Ar', 'New Name', 'uploads/users/NewName/NewName_1539772960_9319.jpg', 'PS', 'PS-NA', '+970', 'AAAA'),
(6, 6, 'dd', 'ddd', 'uploads/users/sss/sss_1539772587_3808.jpg', 'AF', 'AF-KA', '+93', 'sss');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `auth_assignment_user_id_idx` (`user_id`);

--
-- Indexes for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);

--
-- Indexes for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Indexes for table `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
