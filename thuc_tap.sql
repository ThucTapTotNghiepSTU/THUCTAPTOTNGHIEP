-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 24, 2025 at 06:33 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thuc_tap`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

DROP TABLE IF EXISTS `answer`;
CREATE TABLE IF NOT EXISTS `answer` (
  `answer_id` char(50) NOT NULL,
  `submission_id` char(50) DEFAULT NULL,
  `question_title` varchar(100) DEFAULT NULL,
  `question_content` varchar(255) DEFAULT NULL,
  `question_answer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`answer_id`),
  KEY `submission_id` (`submission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`answer_id`, `submission_id`, `question_title`, `question_content`, `question_answer`) VALUES
('b1739347-120c-4b07-bf00-cb26107a442c', '285d2a43-d06c-44fc-9d09-7175fbd60d56', 'Ngôn ngữ nào chủ yếu được dùng để phát triển web?', 'Chọn ngôn ngữ lập trình được sử dụng phổ biến nhất trong phát triển web.', 'JavaScript'),
('e0c79dff-4b47-4728-a987-68713fc9cf82', '285d2a43-d06c-44fc-9d09-7175fbd60d56', 'Đâu là ứng dụng AI', 'Chọn đáp án đúng', 'Chưa trả lời');

-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

DROP TABLE IF EXISTS `assignment`;
CREATE TABLE IF NOT EXISTS `assignment` (
  `assignment_id` char(50) NOT NULL,
  `sub_list_id` char(50) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `type` enum('Trắc nghiệm','Tự luận') DEFAULT NULL,
  `isSimultaneous` tinyint(1) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `show_result` tinyint(1) DEFAULT NULL,
  `status` enum('Pending','Processing','Completed') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`assignment_id`),
  KEY `sub_list_id` (`sub_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `assignment`
--

INSERT INTO `assignment` (`assignment_id`, `sub_list_id`, `title`, `content`, `type`, `isSimultaneous`, `start_time`, `end_time`, `show_result`, `status`, `created_at`, `updated_at`) VALUES
('3d246303-4195-44e3-baab-442293b2adda', '9505949f-d038-41db-88d6-2d52f21b9cce', 'Bài kiểm tra số 1', 'Điểm quá trình', 'Tự luận', 0, '2025-03-26 10:00:00', '2025-04-23 12:00:00', 0, 'Pending', '2025-04-17 08:01:01', '2025-04-20 11:35:27'),
('d10f353c-f986-4ced-9a66-b94faa974e70', '9505949f-d038-41db-88d6-2d52f21b9cce', 'Bài kiểm tra số 2', 'Điểm quá trình', 'Tự luận', 1, '2025-03-26 10:00:00', '2025-04-25 04:45:00', 0, 'Pending', '2025-04-17 11:06:21', '2025-04-20 21:44:59');

-- --------------------------------------------------------

--
-- Table structure for table `classroom`
--

DROP TABLE IF EXISTS `classroom`;
CREATE TABLE IF NOT EXISTS `classroom` (
  `class_id` char(50) NOT NULL,
  `lecturer_id` char(50) DEFAULT NULL,
  `course_id` char(50) DEFAULT NULL,
  `class_code` varchar(20) DEFAULT NULL,
  `class_description` varchar(100) DEFAULT NULL,
  `class_duration` int DEFAULT NULL,
  PRIMARY KEY (`class_id`),
  KEY `lecturer_id` (`lecturer_id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `classroom`
--

INSERT INTO `classroom` (`class_id`, `lecturer_id`, `course_id`, `class_code`, `class_description`, `class_duration`) VALUES
('3a7619a4-4a1f-475d-953e-0ae260ac6cbe', 'c767a243-2515-4f38-8cf2-9accd9ea7eb3', 'bb18b2e3-b400-44f9-ae2a-d72853575eb3', 'abcxyz', 'Lớp giỏi', 3),
('8d26b25e-e432-41a0-a60f-085df531d51b', '4291fd67-863c-4873-9c4f-8c32dd924af9', 'd45b556e-dddb-4c5f-a463-09db859f0aad', 'abcx2z', 'Lớp giỏi', 3),
('c4892109-7e51-4526-afe8-663e749539bd', '4291fd67-863c-4873-9c4f-8c32dd924af9', '6f15d909-9938-4b16-9835-22338c4c1e30', 'abcxy2', 'Lớp giỏi', 3);

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

DROP TABLE IF EXISTS `course`;
CREATE TABLE IF NOT EXISTS `course` (
  `course_id` char(50) NOT NULL,
  `course_name` varchar(100) DEFAULT NULL,
  `process_ratio` float DEFAULT NULL,
  `midterm_ratio` float DEFAULT NULL,
  `final_ratio` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `course_name`, `process_ratio`, `midterm_ratio`, `final_ratio`, `created_at`, `updated_at`) VALUES
('6f15d909-9938-4b16-9835-22338c4c1e30', 'AI cơ bản', 20, 20, 60, '2025-04-10 09:07:42', '2025-04-10 09:07:42'),
('bb18b2e3-b400-44f9-ae2a-d72853575eb3', 'Phân tích hệ thống thông tin', 20, 20, 60, '2025-04-02 08:25:57', '2025-04-02 08:25:57'),
('cc47ad7e-c6b8-47c1-afe9-8b762a6c5377', 'Toán A3', 20, 20, 60, '2025-04-10 09:08:00', '2025-04-10 09:08:00'),
('d45b556e-dddb-4c5f-a463-09db859f0aad', 'Cấu trúc giải thuật', 10, 30, 60, '2025-04-02 07:42:06', '2025-04-02 08:35:33');

-- --------------------------------------------------------

--
-- Table structure for table `exam`
--

DROP TABLE IF EXISTS `exam`;
CREATE TABLE IF NOT EXISTS `exam` (
  `exam_id` char(50) NOT NULL,
  `sub_list_id` char(50) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `type` enum('Trắc nghiệm','Tự luận') DEFAULT NULL,
  `isSimultaneous` tinyint(1) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `status` enum('Pending','Processing','Completed') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`exam_id`),
  KEY `exam_ibfk_1` (`sub_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `exam`
--

INSERT INTO `exam` (`exam_id`, `sub_list_id`, `title`, `content`, `type`, `isSimultaneous`, `start_time`, `end_time`, `status`, `created_at`, `updated_at`) VALUES
('26119fcb-5f1c-4cee-bf44-25aeacc70ed0', '40fa411a-1720-4e0a-a732-d2b81ec68a91', 'Bài kiểm tra số 3', 'Nội dung kiểm tra', 'Trắc nghiệm', 1, '2025-04-20 23:40:00', '2025-04-21 04:43:00', 'Completed', '2025-04-20 16:40:00', '2025-04-20 21:42:59'),
('b935dfb3-5cb4-4aa7-9212-6f2c755cb99a', '04b6aca7-6786-496c-ab73-8e001f54add6', 'Bài kiểm Giữ kỳ Phân tích', 'Nội dung kiểm tra', 'Trắc nghiệm', 0, '2025-04-20 23:40:00', '2025-04-22 00:29:00', 'Pending', '2025-04-20 08:58:42', '2025-04-20 10:28:59');

-- --------------------------------------------------------

--
-- Table structure for table `lecturer`
--

DROP TABLE IF EXISTS `lecturer`;
CREATE TABLE IF NOT EXISTS `lecturer` (
  `lecturer_id` char(50) NOT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `school_email` varchar(100) DEFAULT NULL,
  `personal_email` char(100) DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`lecturer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lecturer`
--

INSERT INTO `lecturer` (`lecturer_id`, `fullname`, `school_email`, `personal_email`, `phone`, `password`, `created_at`, `updated_at`) VALUES
('4291fd67-863c-4873-9c4f-8c32dd924af9', 'Nguyễn Văn A', 'a.nguyenvan@gmail.com', 'a.nguyenvan@gmail.com', '0153648523', '$2y$12$CplOdb2mNnakywG0VHFZue3ZEyXZA1YbJlMrDXyBEAP7kDW/sbF3a', '2025-04-04 09:20:58', '2025-04-04 09:20:58'),
('c767a243-2515-4f38-8cf2-9accd9ea7eb3', 'Nguyễn Văn B', 'nguyenvanb@university.edu.vn', 'vanb@gmail.com', '0912345676', '$2y$12$OC6K/Y1Iz6q8w5Eke1aLNetdUJKY7fBOH8S.r/sQ6BSXX4DqukRJa', '2025-04-19 20:38:50', '2025-04-19 20:38:50'),
('f07d5414-3145-4b8f-b32d-8ad14c179a37', 'Nguyễn Văn B', 'b.nguyenvan@gmail.com', 'b.nguyenvan@gmail.com', '0153648524', '$2y$12$.daLLDIJIFHYDCce0jHPBONivTpVM4XIKXr67yATbNSO9JziVRDo6', '2025-04-04 09:21:14', '2025-04-04 09:21:14');

-- --------------------------------------------------------

--
-- Table structure for table `list_question`
--

DROP TABLE IF EXISTS `list_question`;
CREATE TABLE IF NOT EXISTS `list_question` (
  `list_question_id` char(50) NOT NULL,
  `course_id` char(50) DEFAULT NULL,
  `topic` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `lecturer_id` char(36) DEFAULT NULL,
  PRIMARY KEY (`list_question_id`),
  KEY `list_question_ibfk_1` (`course_id`),
  KEY `fk_list_questions_lecturer` (`lecturer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `list_question`
--

INSERT INTO `list_question` (`list_question_id`, `course_id`, `topic`, `created_at`, `updated_at`, `lecturer_id`) VALUES
('082c3675-1d40-44fe-96d7-1dce7435aea5', '6f15d909-9938-4b16-9835-22338c4c1e30', 'Chương 2', '2025-04-19 21:55:13', '2025-04-19 21:55:13', 'c767a243-2515-4f38-8cf2-9accd9ea7eb3'),
('278321fb-7d5f-4bc2-b6e1-9f5b7cb4f5b9', '6f15d909-9938-4b16-9835-22338c4c1e30', 'chủ đề rất mới của môn AI', '2025-04-19 22:49:01', '2025-04-19 22:49:01', 'c767a243-2515-4f38-8cf2-9accd9ea7eb3'),
('82dc9604-dc2c-445c-9d24-edde91d102af', 'bb18b2e3-b400-44f9-ae2a-d72853575eb3', 'Chương 2', '2025-04-19 22:32:42', '2025-04-19 22:32:42', 'c767a243-2515-4f38-8cf2-9accd9ea7eb3'),
('e3d7698e-b3cc-4f89-83f6-8b04f82f35c5', '6f15d909-9938-4b16-9835-22338c4c1e30', 'Chương I', '2025-04-17 14:25:04', '2025-04-17 14:25:04', '4291fd67-863c-4873-9c4f-8c32dd924af9'),
('ed74481d-4a76-42f5-b0c1-d02883e58cd7', '6f15d909-9938-4b16-9835-22338c4c1e30', 'Chương 3', '2025-04-19 22:32:05', '2025-04-19 22:32:05', 'c767a243-2515-4f38-8cf2-9accd9ea7eb3');

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `option_id` char(50) NOT NULL,
  `question_id` char(50) DEFAULT NULL,
  `option_text` varchar(255) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `option_order` tinyint DEFAULT NULL,
  PRIMARY KEY (`option_id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`option_id`, `question_id`, `option_text`, `is_correct`, `option_order`) VALUES
('113093a0-df08-4830-a13e-5e0965172987', 'c01e4fae-e1fd-4bc1-9882-0d2eab7e117b', 'SOS', 0, 1),
('16ffd6e1-e34f-47dd-9713-566ced9d6009', 'c01e4fae-e1fd-4bc1-9882-0d2eab7e117b', 'TopTop', 0, 3),
('1ad4e9eb-e6b1-41a7-b73a-bf7a1898d01d', '4d83362b-f952-471d-92b1-c170a33f78e8', 'Java', 0, 0),
('1e1f364d-f913-4d9d-af9f-c9d3b08930b1', '6daf5248-5259-44c4-9694-7bbc58149c41', 'Shoppee', 0, 1),
('34a23261-c939-435c-a806-87ef897eb98e', '14819abc-a45e-4edd-8c06-307190593709', '2', 0, 3),
('3c5d7369-02f8-4142-9cb6-7d61c2254b07', 'ff2416d6-c681-4de9-8aac-eb9244b9fd64', 'Excel', 1, 2),
('408420bd-eb0c-414b-9316-72edf1aa12eb', '6daf5248-5259-44c4-9694-7bbc58149c41', 'Tiki', 0, 3),
('4a8240fe-d65d-4c91-830e-fe1c57bbf29b', 'ff2416d6-c681-4de9-8aac-eb9244b9fd64', 'Amazon', 0, 0),
('4bc414dd-b969-4bf3-a9f4-6b0b4ad80c93', 'ff2416d6-c681-4de9-8aac-eb9244b9fd64', 'Shoppee', 0, 1),
('5adcf315-650e-4175-9397-83194d7c4245', 'adae23e6-5daa-4efd-bb25-df655bb42957', 'Amazon', 0, 0),
('6dc4b20a-bc59-44c2-bebd-bb841bf9f6cc', 'c01e4fae-e1fd-4bc1-9882-0d2eab7e117b', 'JavaScript', 1, 2),
('7208a448-35eb-46b4-b614-41564dbf12c1', 'ff2416d6-c681-4de9-8aac-eb9244b9fd64', 'Tiki', 0, 3),
('73090621-4fbe-41c3-a3c7-ec63fa417f9c', '14819abc-a45e-4edd-8c06-307190593709', '2', 0, 2),
('880b71a4-8b69-4ba1-aa0c-4dba75644b7e', 'adae23e6-5daa-4efd-bb25-df655bb42957', 'Shoppee', 0, 1),
('890f76f0-50f2-49fb-a551-054359ea7ca4', 'bd08ac2d-2093-4d41-b9ed-64c7e1acc5c0', '2', 0, 1),
('9473dc99-acaa-4c9e-82e6-d6e3464b7f8f', '6daf5248-5259-44c4-9694-7bbc58149c41', 'ChatGPT', 1, 2),
('953bb55f-85da-4ff9-b1f9-57ce71add0e0', 'bd08ac2d-2093-4d41-b9ed-64c7e1acc5c0', '3', 1, 2),
('98e4dae1-5fc3-4214-ac18-3afcd344c87a', '3e1f8adb-4a7b-4bb9-9689-f698e78c0c6d', 'Java', 0, 0),
('99b49110-6b3d-4a2b-9756-2a5f7eab4b10', 'bd08ac2d-2093-4d41-b9ed-64c7e1acc5c0', '1', 0, 0),
('a3f7797f-32ec-4bda-bfeb-9256d608a0ba', 'adae23e6-5daa-4efd-bb25-df655bb42957', 'ChatGPT', 1, 2),
('a8ff0ccc-f71d-415a-88e5-8e600f88e3a0', '3e1f8adb-4a7b-4bb9-9689-f698e78c0c6d', 'C++', 0, 1),
('b2a71258-3db3-48d8-ae16-737d3a692dca', '4d83362b-f952-471d-92b1-c170a33f78e8', 'C++', 0, 1),
('b45b3fdb-ac58-46d2-bc07-4d0a643d4216', '14819abc-a45e-4edd-8c06-307190593709', '2', 0, 1),
('ce2107cc-e61b-47ec-919a-39c29b544eb6', 'adae23e6-5daa-4efd-bb25-df655bb42957', 'Tiki', 0, 3),
('d5c156d9-a5f0-4e70-8c95-1c8abd54e2f4', 'c01e4fae-e1fd-4bc1-9882-0d2eab7e117b', 'TOTO', 0, 0),
('da4b480e-1da0-4ada-9aab-392cb131b3e5', '6daf5248-5259-44c4-9694-7bbc58149c41', 'Amazon', 0, 0),
('df96165e-49b8-4040-a8d2-8b58d53f03e9', '14819abc-a45e-4edd-8c06-307190593709', '2', 1, 0),
('ea6d6834-1d63-4b54-b38b-96f938a87405', '4d83362b-f952-471d-92b1-c170a33f78e8', 'JavaScript', 1, 2),
('eb4c2fcb-034d-4221-ba96-e33c27061884', '4d83362b-f952-471d-92b1-c170a33f78e8', 'Python', 0, 3),
('ebddbb3d-de78-4fd0-80a8-3f6452c9251c', 'bd08ac2d-2093-4d41-b9ed-64c7e1acc5c0', '4', 0, 3),
('ee43dd53-31b6-44a3-8fee-c762bcf1e4aa', '3e1f8adb-4a7b-4bb9-9689-f698e78c0c6d', 'Python', 0, 3),
('fc65357f-9478-49a1-9ed2-695568b24dea', '3e1f8adb-4a7b-4bb9-9689-f698e78c0c6d', 'JavaScript', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
CREATE TABLE IF NOT EXISTS `question` (
  `question_id` char(50) NOT NULL,
  `list_question_id` char(50) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `type` enum('Trắc nghiệm','Tự luận') DEFAULT NULL,
  `correct_answer` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`question_id`),
  KEY `list_question_id` (`list_question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`question_id`, `list_question_id`, `title`, `content`, `type`, `correct_answer`, `created_at`, `updated_at`) VALUES
('14819abc-a45e-4edd-8c06-307190593709', '82dc9604-dc2c-445c-9d24-edde91d102af', '2', '2', 'Trắc nghiệm', '2', '2025-04-19 22:33:15', '2025-04-19 22:33:15'),
('3e1f8adb-4a7b-4bb9-9689-f698e78c0c6d', 'e3d7698e-b3cc-4f89-83f6-8b04f82f35c5', 'Ngôn ngữ nào chủ yếu được dùng để phát triển web?', 'Chọn ngôn ngữ lập trình được sử dụng phổ biến nhất trong phát triển web.', 'Trắc nghiệm', 'JavaScript', '2025-04-18 06:47:08', '2025-04-18 06:47:08'),
('4d83362b-f952-471d-92b1-c170a33f78e8', 'e3d7698e-b3cc-4f89-83f6-8b04f82f35c5', 'Ngôn ngữ nào chủ yếu được dùng để phát triển web?', 'Chọn ngôn ngữ lập trình được sử dụng phổ biến nhất trong phát triển web.', 'Trắc nghiệm', 'JavaScript', '2025-04-17 14:29:52', '2025-04-17 14:29:52'),
('6a60c0fe-5a0e-45c5-b351-fba1abbb7e34', 'ed74481d-4a76-42f5-b0c1-d02883e58cd7', '1', '1', 'Tự luận', NULL, '2025-04-19 22:32:17', '2025-04-19 22:32:17'),
('6daf5248-5259-44c4-9694-7bbc58149c41', 'e3d7698e-b3cc-4f89-83f6-8b04f82f35c5', 'Đâu là ứng dụng AI', 'Chọn đáp án đúng', 'Trắc nghiệm', 'ChatGPT', '2025-04-17 14:29:52', '2025-04-17 14:29:52'),
('adae23e6-5daa-4efd-bb25-df655bb42957', 'e3d7698e-b3cc-4f89-83f6-8b04f82f35c5', 'Đâu là ứng dụng AI', 'Chọn đáp án đúng', 'Trắc nghiệm', 'ChatGPT', '2025-04-18 06:47:08', '2025-04-18 06:47:08'),
('b8895f04-a949-44f4-8d30-751d61ddae42', '278321fb-7d5f-4bc2-b6e1-9f5b7cb4f5b9', 'hello', 'hello', 'Tự luận', NULL, '2025-04-19 22:50:07', '2025-04-19 22:50:07'),
('bd08ac2d-2093-4d41-b9ed-64c7e1acc5c0', '82dc9604-dc2c-445c-9d24-edde91d102af', 'Câu hỏi 1', 'Câu hỏi 1', 'Trắc nghiệm', '3', '2025-04-19 22:33:15', '2025-04-19 22:33:15'),
('c01e4fae-e1fd-4bc1-9882-0d2eab7e117b', '82dc9604-dc2c-445c-9d24-edde91d102af', 'Đâu là ngôn ngữ lập trình', 'Chọn ngôn ngữ lập trình', 'Trắc nghiệm', 'JavaScript', '2025-04-20 15:44:26', '2025-04-20 15:44:26'),
('d4b28849-4efd-42e9-bc8e-46b21dcb7c70', 'e3d7698e-b3cc-4f89-83f6-8b04f82f35c5', 'Đâu là ngôn ngữ lập trình phổ biến?', 'Ai là con người dầu tiên.', 'Tự luận', NULL, '2025-04-18 06:47:08', '2025-04-18 06:47:08'),
('e4e1f81f-e453-4331-8257-eb3e840492c6', 'e3d7698e-b3cc-4f89-83f6-8b04f82f35c5', 'Đâu là ngôn ngữ lập trình phổ biến?', 'Chọn ngôn ngữ lập trình được sử dụng nhiều nhất hiện nay.', 'Tự luận', NULL, '2025-04-17 14:29:52', '2025-04-17 14:29:52'),
('e707365b-b1e3-4bb9-878c-7931dec98e03', '82dc9604-dc2c-445c-9d24-edde91d102af', 'Bạn là ai', 'Bạn là ai', 'Tự luận', NULL, '2025-04-20 15:44:26', '2025-04-20 15:44:26'),
('e8f2b29f-3526-41f9-8772-6eacae5bf3eb', '82dc9604-dc2c-445c-9d24-edde91d102af', 'Tôi là ai', 'Tôi là ai', 'Tự luận', NULL, '2025-04-20 15:44:26', '2025-04-20 15:44:26'),
('ff2416d6-c681-4de9-8aac-eb9244b9fd64', '82dc9604-dc2c-445c-9d24-edde91d102af', 'Cái nào thiết bị phần mền?', 'Cái nào thiết bị phần mền?', 'Trắc nghiệm', 'Excel', '2025-04-20 15:44:26', '2025-04-20 15:44:26');

-- --------------------------------------------------------

--
-- Table structure for table `score`
--

DROP TABLE IF EXISTS `score`;
CREATE TABLE IF NOT EXISTS `score` (
  `score_id` char(50) NOT NULL,
  `student_id` char(50) DEFAULT NULL,
  `course_id` char(50) DEFAULT NULL,
  `process_score` float DEFAULT NULL,
  `midterm_score` float DEFAULT NULL,
  `final_score` float DEFAULT NULL,
  `average_score` float DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`score_id`),
  KEY `student_id` (`student_id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `student_id` char(50) NOT NULL,
  `student_code` varchar(20) DEFAULT NULL,
  `full_name` varchar(50) DEFAULT NULL,
  `school_email` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `student_code`, `full_name`, `school_email`, `password`, `phone`, `created_at`, `updated_at`) VALUES
('516abf07-8586-4a42-8dd2-cd375f29f016', 'DH52111509', 'Nguyễn Thành Tỷ Phú', 'dh52111509@student.stu.edu.vn', '$2y$12$tTHk3G4/fftui7WpUJJxreLIcyW2F/c3pf/QKY0rNqTOmmbUaB1Cy', '07673920391', '2025-04-03 15:52:11', '2025-04-03 15:52:11'),
('53b388b4-1353-4f99-bc89-d60abb5396b2', 'Dh52111599', 'Trần Anh Tuấn', 'dh52111599@student.stu.edu.vn', '$2y$12$6cabfzBMkSYzuSJs/nTvC.3t/Vu1.ZZzbMu8VBfygoQCSDBfZTMZi', '767392036', '2025-04-03 10:47:01', '2025-04-03 10:47:01'),
('9a52f705-88dd-4b5d-9dbc-bdc302e1c0ca', 'DH52111612', 'Trần Nguyễn Hoàng Quân', 'dh52111612@student.stu.edu.vn', '$2y$12$AeetGbYOxu7oiRxD.RBwvOyM8UFHnHr3bfWwLA6p7idltL.qv4RCm', '0767392038', '2025-04-02 07:35:58', '2025-04-02 07:35:58'),
('f6f482b0-07f5-4835-ba9c-c7192b3ba8c7', 'DH52111923', 'Đỗ Minh Trí', 'dh52111923@student.stu.edu.vn', '$2y$12$xrRJt2rhUtkNohDpAMi1xufHinRV/UkzqPLbYJMQo759eBDvCsihq', 'DH52111923', '2025-04-03 10:41:08', '2025-04-03 10:41:08');

-- --------------------------------------------------------

--
-- Table structure for table `student_class`
--

DROP TABLE IF EXISTS `student_class`;
CREATE TABLE IF NOT EXISTS `student_class` (
  `student_class_id` char(50) NOT NULL,
  `student_id` char(50) DEFAULT NULL,
  `class_id` char(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `status` enum('Active','Drop','Pending') DEFAULT NULL,
  `final_score` float DEFAULT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`student_class_id`),
  KEY `student_id` (`student_id`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student_class`
--

INSERT INTO `student_class` (`student_class_id`, `student_id`, `class_id`, `created_at`, `status`, `final_score`, `updated_at`) VALUES
('1fde997f-adfa-4c2c-8e7a-67555f21d8d9', '53b388b4-1353-4f99-bc89-d60abb5396b2', '3a7619a4-4a1f-475d-953e-0ae260ac6cbe', '2025-04-10 09:11:03', 'Active', 10, '2025-04-10 09:11:03'),
('4e51c118-c505-49f2-bd4a-61a5934ef667', '516abf07-8586-4a42-8dd2-cd375f29f016', '3a7619a4-4a1f-475d-953e-0ae260ac6cbe', '2025-04-04 09:37:21', 'Active', 10, '2025-04-04 09:37:21'),
('8003eaec-80bb-4ab7-bef1-a87cc8976d61', 'f6f482b0-07f5-4835-ba9c-c7192b3ba8c7', 'c4892109-7e51-4526-afe8-663e749539bd', '2025-04-10 09:11:23', 'Active', 10, '2025-04-10 09:11:23'),
('b7fac88f-621c-4917-8b88-a900d66b8069', '516abf07-8586-4a42-8dd2-cd375f29f016', '8d26b25e-e432-41a0-a60f-085df531d51b', '2025-04-10 09:15:41', 'Active', 10, '2025-04-10 09:15:41'),
('cedee4ac-628a-4447-810c-e71e6806cbe8', '516abf07-8586-4a42-8dd2-cd375f29f016', 'c4892109-7e51-4526-afe8-663e749539bd', '2025-04-10 09:11:45', 'Active', 10, '2025-04-10 09:11:45');

-- --------------------------------------------------------

--
-- Table structure for table `submission`
--

DROP TABLE IF EXISTS `submission`;
CREATE TABLE IF NOT EXISTS `submission` (
  `submission_id` char(50) NOT NULL,
  `student_id` char(50) DEFAULT NULL,
  `exam_id` char(50) DEFAULT NULL,
  `assignment_id` char(50) DEFAULT NULL,
  `answer_file` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `is_late` tinyint(1) DEFAULT NULL,
  `temporary_score` float DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  PRIMARY KEY (`submission_id`),
  KEY `student_id` (`student_id`),
  KEY `exam_id` (`exam_id`),
  KEY `assignment_id` (`assignment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `submission`
--

INSERT INTO `submission` (`submission_id`, `student_id`, `exam_id`, `assignment_id`, `answer_file`, `created_at`, `is_late`, `temporary_score`, `update_at`) VALUES
('285d2a43-d06c-44fc-9d09-7175fbd60d56', '516abf07-8586-4a42-8dd2-cd375f29f016', '26119fcb-5f1c-4cee-bf44-25aeacc70ed0', NULL, NULL, '2025-04-21 04:42:59', 0, 5, '2025-04-21 04:42:59');

-- --------------------------------------------------------

--
-- Table structure for table `sub_list`
--

DROP TABLE IF EXISTS `sub_list`;
CREATE TABLE IF NOT EXISTS `sub_list` (
  `sub_list_id` char(50) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `isShuffle` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sub_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sub_list`
--

INSERT INTO `sub_list` (`sub_list_id`, `title`, `isShuffle`, `created_at`, `updated_at`) VALUES
('04b6aca7-6786-496c-ab73-8e001f54add6', 'Bài thi Giữ kỳ Phân tích', 1, '2025-04-20 15:49:37', '2025-04-20 15:49:37'),
('40fa411a-1720-4e0a-a732-d2b81ec68a91', 'Bài thi AI cuối kì', 0, '2025-04-17 14:35:32', '2025-04-17 14:35:32'),
('9505949f-d038-41db-88d6-2d52f21b9cce', 'Bài tập AI tuần 2', 0, '2025-04-17 14:35:23', '2025-04-17 14:35:23'),
('b040265f-b4a2-4d40-b96d-db40623a0701', 'Bài tập AI', 0, '2025-04-17 14:35:11', '2025-04-17 14:35:11'),
('f484a551-dd7c-4eca-89f1-86937200a566', 'Bài thi AI', 1, '2025-04-17 14:34:56', '2025-04-17 14:34:56');

-- --------------------------------------------------------

--
-- Table structure for table `sub_list_question`
--

DROP TABLE IF EXISTS `sub_list_question`;
CREATE TABLE IF NOT EXISTS `sub_list_question` (
  `sub_list_id` char(50) NOT NULL,
  `question_id` char(50) NOT NULL,
  PRIMARY KEY (`sub_list_id`,`question_id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sub_list_question`
--

INSERT INTO `sub_list_question` (`sub_list_id`, `question_id`) VALUES
('b040265f-b4a2-4d40-b96d-db40623a0701', '3e1f8adb-4a7b-4bb9-9689-f698e78c0c6d'),
('40fa411a-1720-4e0a-a732-d2b81ec68a91', '4d83362b-f952-471d-92b1-c170a33f78e8'),
('40fa411a-1720-4e0a-a732-d2b81ec68a91', '6daf5248-5259-44c4-9694-7bbc58149c41'),
('b040265f-b4a2-4d40-b96d-db40623a0701', '6daf5248-5259-44c4-9694-7bbc58149c41'),
('04b6aca7-6786-496c-ab73-8e001f54add6', 'c01e4fae-e1fd-4bc1-9882-0d2eab7e117b'),
('9505949f-d038-41db-88d6-2d52f21b9cce', 'd4b28849-4efd-42e9-bc8e-46b21dcb7c70'),
('9505949f-d038-41db-88d6-2d52f21b9cce', 'e4e1f81f-e453-4331-8257-eb3e840492c6'),
('04b6aca7-6786-496c-ab73-8e001f54add6', 'ff2416d6-c681-4de9-8aac-eb9244b9fd64');

-- --------------------------------------------------------

--
-- Table structure for table `temp_answers`
--

DROP TABLE IF EXISTS `temp_answers`;
CREATE TABLE IF NOT EXISTS `temp_answers` (
  `temp_answers_id` char(50) NOT NULL,
  `student_id` char(50) NOT NULL,
  `exam_id` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `assignment_id` char(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `question_id` char(50) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`temp_answers_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `temp_answers`
--

INSERT INTO `temp_answers` (`temp_answers_id`, `student_id`, `exam_id`, `assignment_id`, `question_id`, `answer`, `created_at`, `updated_at`) VALUES
('a907ef07-7cf0-45ce-807c-12b38b365b3d', '516abf07-8586-4a42-8dd2-cd375f29f016', NULL, 'd10f353c-f986-4ced-9a66-b94faa974e70', 'd4b28849-4efd-42e9-bc8e-46b21dcb7c70', 'ád', '2025-04-23 21:40:40', '2025-04-23 21:40:40'),
('9e232d68-d026-4ba8-b331-556b3941dbc7', '516abf07-8586-4a42-8dd2-cd375f29f016', NULL, 'd10f353c-f986-4ced-9a66-b94faa974e70', 'e4e1f81f-e453-4331-8257-eb3e840492c6', 'áđaxxx', '2025-04-23 21:40:57', '2025-04-23 21:41:05');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submission` (`submission_id`) ON DELETE CASCADE;

--
-- Constraints for table `assignment`
--
ALTER TABLE `assignment`
  ADD CONSTRAINT `assignment_ibfk_1` FOREIGN KEY (`sub_list_id`) REFERENCES `sub_list` (`sub_list_id`) ON DELETE CASCADE;

--
-- Constraints for table `classroom`
--
ALTER TABLE `classroom`
  ADD CONSTRAINT `classroom_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturer` (`lecturer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `classroom_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `exam`
--
ALTER TABLE `exam`
  ADD CONSTRAINT `exam_ibfk_1` FOREIGN KEY (`sub_list_id`) REFERENCES `sub_list` (`sub_list_id`) ON DELETE CASCADE;

--
-- Constraints for table `list_question`
--
ALTER TABLE `list_question`
  ADD CONSTRAINT `fk_list_questions_lecturer` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturer` (`lecturer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `list_question_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`list_question_id`) REFERENCES `list_question` (`list_question_id`) ON DELETE CASCADE;

--
-- Constraints for table `score`
--
ALTER TABLE `score`
  ADD CONSTRAINT `score_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `score_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_class`
--
ALTER TABLE `student_class`
  ADD CONSTRAINT `student_class_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_class_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classroom` (`class_id`) ON DELETE CASCADE;

--
-- Constraints for table `submission`
--
ALTER TABLE `submission`
  ADD CONSTRAINT `submission_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `submission_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `exam` (`exam_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `submission_ibfk_3` FOREIGN KEY (`assignment_id`) REFERENCES `assignment` (`assignment_id`) ON DELETE SET NULL;

--
-- Constraints for table `sub_list_question`
--
ALTER TABLE `sub_list_question`
  ADD CONSTRAINT `sub_list_question_ibfk_1` FOREIGN KEY (`sub_list_id`) REFERENCES `sub_list` (`sub_list_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sub_list_question_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
