-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2024 at 12:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `homework_u`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `del_department` (IN `in_id_department` INT)   BEGIN
                    UPDATE
                        `departments`
                    SET
                        `features`=(`features` | 2) & ~1
                    WHERE
                        ID=in_id_department;
                END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `departmentsGetById` (IN `in_id_departament` INT)   BEGIN
    SELECT
        d.*,
        p.name AS parent_name
    FROM
        departments d
    LEFT JOIN departments p ON
        p.ID = d.id_parent
    WHERE
        d.ID = in_id_departament ;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_department_parent_by_parent_id` (IN `in_old_id_parent` INT, IN `in_new_id_parent` INT)   BEGIN
                    UPDATE
                        `departments`
                    SET
                        `id_parent`=in_new_id_parent 
                    WHERE
                        `id_parent`=in_old_id_parent;
                END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_department_prv` (IN `in_id_department` INT, IN `in_name` VARCHAR(100), IN `in_id_parent` INT)   BEGIN
                    UPDATE
                        `departments`
                    SET
                        `name`=in_name,
                        `id_parent`=in_id_parent,
                        `features`=`features` & ~1
                    WHERE
                        ID=in_id_department;
                END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_department_pub` (IN `in_id_department` INT, IN `in_name` VARCHAR(100), IN `in_id_parent` INT)   BEGIN
                    UPDATE
                        `departments`
                    SET
                        `name`=in_name,
                        `id_parent`=in_id_parent,
                        `features`=`features` | 1
                    WHERE
                        ID=in_id_department;
                END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_departments_all` ()   BEGIN
                    SELECT d.*, p.name AS parent_name FROM departments d LEFT JOIN departments p ON p.ID=d.id_parent;
                END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_departments_byparent` (IN `in_id_parent` INT)   BEGIN
                    SELECT 
                        `name`, 
                        `ID`, 
                        `id_parent` AS `id_parent`, 
                        'dept' AS `type`
                    FROM `departments`  
                    WHERE 
                        `id_parent`=in_id_parent
                        AND (`features` & 1) > 0 AND (`features` & 2) = 0 
                    UNION 
                    SELECT 
                        `name`, 
                        `ID`, 
                        `id_department` AS `id_parent`, 
                        'emp' AS `type`
                    FROM `employees`  
                    WHERE 
                        `id_department`=in_id_parent
                        AND (`features` & 1) > 0 AND (`features` & 2) = 0 
                    ORDER BY 
                        `type` ASC,
                        `name` ASC;
                END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_departments_public` ()   BEGIN
     SELECT d.*, p.name AS parent_name FROM departments d LEFT JOIN departments p ON p.ID=d.id_parent WHERE (d.features & 1) > 0 AND (d.features & 2) = 0;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_departments_root` ()   BEGIN
                    SELECT 
                        d.`name`, 
                        d.`ID`, 
                        d.`id_parent`, 
                        'dept' AS `type`
                    FROM `departments` d 
                    WHERE 
                        d.`id_parent` IS NULL 
                        AND (d.`features` & 1) > 0 AND (d.`features` & 2) = 0 
                    ORDER BY 
                        d.`name` ASC;
                END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_by_email` (IN `in_email` VARCHAR(256), IN `in_hash` VARCHAR(16))   BEGIN
    SELECT *, AES_DECRYPT(email, in_hash) AS email_user 
    FROM users 
    WHERE 
        AES_DECRYPT(email, in_hash)=in_email;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_by_id` (IN `in_id_user` INT, IN `in_hash` VARCHAR(16))   BEGIN
    SELECT *, AES_DECRYPT(email, in_hash) AS email_user 
    FROM users 
    WHERE 
        ID=in_id_user;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_by_trm` (IN `in_id_user` INT, IN `in_trm` VARCHAR(64), IN `in_hash` VARCHAR(16))   BEGIN
                    SELECT *, AES_DECRYPT(email, in_hash) AS email_user 
                FROM users 
                WHERE 
                    token_rememberme=in_trm
                    AND ID=in_id_user;
                END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_by_trp` (IN `in_trp` VARCHAR(64), IN `in_hash` VARCHAR(16))   BEGIN
                    SELECT *, AES_DECRYPT(email, in_hash) AS email_user 
                FROM users 
                WHERE 
                    token_resetare_parola=in_trp;
                END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_by_tve` (IN `in_tve` VARCHAR(64), IN `in_hash` VARCHAR(16))   BEGIN
    SELECT *, AES_DECRYPT(email, in_hash) AS email_user 
    FROM users 
    WHERE 
        token_validare_email=in_tve;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_department` (IN `in_name` VARCHAR(100), IN `in_id_parent` INT, OUT `out_id_department` INT)   BEGIN
                    INSERT INTO `departments`(name, id_parent, features) VALUES (in_name, in_id_parent, 1 | 8);
                    SELECT last_insert_id() into out_id_department;
                END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_user` (IN `in_password` VARCHAR(64), IN `in_email` VARCHAR(256), IN `in_hash` VARCHAR(16), IN `in_token_validare_email` VARCHAR(64))   BEGIN
    INSERT INTO users(password,status,token_validare_email,email,created_time) VALUES(in_password,1,in_token_validare_email,AES_ENCRYPT(in_email,in_hash),NOW());
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_user_status` (IN `in_id_user` INT, IN `in_status` INT)   BEGIN
                    UPDATE
                        `users`
                    SET
                        `status`=in_status
                    WHERE
                        ID=in_id_user;
                END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `ID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `id_parent` int(11) DEFAULT NULL,
  `features` int(11) NOT NULL DEFAULT 0 COMMENT 'published/draft deleted',
  `created_time` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_time` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`ID`, `name`, `id_parent`, `features`, `created_time`, `modified_time`) VALUES
(4, 'Management', NULL, 9, '2024-04-21 11:34:03', '2024-04-25 13:15:07'),
(5, 'Marketing', NULL, 9, '2024-04-21 11:36:04', '2024-04-25 13:15:07'),
(6, 'HR', NULL, 9, '2024-04-21 11:36:51', '2024-04-25 13:15:07'),
(7, 'HR minic', 6, 9, '2024-04-21 11:38:20', '2024-04-25 13:15:07'),
(8, 'Sales', NULL, 9, '2024-04-21 11:41:42', '2024-04-25 13:15:07'),
(9, 'Prod', 5, 9, '2024-04-21 11:41:58', '2024-04-25 13:15:07'),
(10, 'People', 9, 9, '2024-04-21 11:42:08', '2024-04-25 13:15:07'),
(11, 'Coffee', 10, 9, '2024-04-21 11:42:44', '2024-04-25 13:15:07'),
(12, 'Acquisition', NULL, 9, '2024-04-21 11:43:13', '2024-04-25 13:15:07'),
(13, 'Production', NULL, 9, '2024-04-21 18:47:46', '2024-04-25 13:15:07'),
(17, 'test', 11, 8, '2024-04-25 12:37:06', '2024-04-25 13:37:19'),
(18, 'Sorin Dan', NULL, 10, '2024-04-25 13:05:52', '2024-04-25 13:18:08'),
(19, 'Sorin Dan 2', 13, 10, '2024-04-25 13:12:06', '2024-04-25 13:37:07');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `ID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `id_department` int(11) NOT NULL,
  `features` int(11) NOT NULL,
  `created_time` datetime NOT NULL DEFAULT current_timestamp(),
  `modified_time` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`ID`, `name`, `id_department`, `features`, `created_time`, `modified_time`) VALUES
(1, 'Alex', 4, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(2, 'Andreea', 4, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(3, 'Bogdan', 5, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(4, 'Bella', 5, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(5, 'Cornel', 6, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(6, 'Corina', 6, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(7, 'Dan', 7, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(8, 'Dorina', 7, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(9, 'Emanuel', 8, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(10, 'Eliza', 8, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(11, 'Florin', 9, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(12, 'Geta', 9, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(13, 'Horia', 10, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(14, 'Ionela', 10, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(15, 'Jean', 11, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(16, 'Karen', 11, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(17, 'Laurentiu', 12, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(18, 'Mihaela', 12, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(19, 'Nicu', 13, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21'),
(20, 'Ophelia', 13, 1, '2024-04-23 11:16:05', '2024-04-23 11:21:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `password` varchar(64) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '-1-deleted, 0-blocked, 1-pending email confirmation, 2-pending admin confirmation, 3-active',
  `token_validare_email` varchar(64) DEFAULT NULL,
  `token_rememberme` varchar(64) DEFAULT NULL,
  `token_resetare_parola` varchar(64) DEFAULT NULL,
  `trp_time` datetime DEFAULT NULL,
  `email` varbinary(256) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `modified_time` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `delete_time` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `password`, `status`, `token_validare_email`, `token_rememberme`, `token_resetare_parola`, `trp_time`, `email`, `created_time`, `modified_time`, `delete_time`) VALUES
(12, '$2y$10$jI8ROCHEzLfY6dFa6b8E3Oo1X3UH31ycjfSkHJuaOpTlQYWViHwvW', 3, '8f8f49b3dc886f5c64ffd647037220a98338d683', NULL, NULL, NULL, 0xd4c7b61dc3979b5ce66b9d81da282c5569652b8c4cba9d0973280129e58c66a7, '2024-04-20 20:04:32', '2024-04-25 13:39:47', NULL),
(13, '$2y$10$IOxNlPAVCVfBfjyUFjPww.tir1GgmQhqAt8vly2.EKUrK2SrCN3tO', 3, '906717f91f2616750b992a5ce167fb9d2e3e70ed', NULL, NULL, NULL, 0x1c12fbf19de3564fc46095590a983c5c803f222cb9e46acfeee74d2aa2f3c448, '2024-04-25 13:40:02', '2024-04-25 13:42:49', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `name` (`name`),
  ADD KEY `features` (`features`),
  ADD KEY `id_parent` (`id_parent`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `name` (`name`),
  ADD KEY `name_2` (`name`),
  ADD KEY `id_department` (`id_department`),
  ADD KEY `features` (`features`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `token_rememberme` (`token_rememberme`),
  ADD KEY `token_resetare_parola` (`token_resetare_parola`),
  ADD KEY `token_validare_email` (`token_validare_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
