-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th4 04, 2025 lúc 04:11 AM
-- Phiên bản máy phục vụ: 8.2.0
-- Phiên bản PHP: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `thuc_tap`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `answer`
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

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `assignment`
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
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`assignment_id`),
  KEY `sub_list_id` (`sub_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `classroom`
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

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `course`
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
-- Đang đổ dữ liệu cho bảng `course`
--

INSERT INTO `course` (`course_id`, `course_name`, `process_ratio`, `midterm_ratio`, `final_ratio`, `created_at`, `updated_at`) VALUES
('bb18b2e3-b400-44f9-ae2a-d72853575eb3', 'Phân tích hệ thống thông tin', 20, 20, 60, '2025-04-02 08:25:57', '2025-04-02 08:25:57'),
('d45b556e-dddb-4c5f-a463-09db859f0aad', 'Cấu trúc giải thuật', 10, 30, 60, '2025-04-02 07:42:06', '2025-04-02 08:35:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `exam`
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
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`exam_id`),
  KEY `exam_ibfk_1` (`sub_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lecturer`
--

DROP TABLE IF EXISTS `lecturer`;
CREATE TABLE IF NOT EXISTS `lecturer` (
  `lecturer_id` char(50) NOT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `school_email` varchar(100) DEFAULT NULL,
  `personal_email` char(100) DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`lecturer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `list_question`
--

DROP TABLE IF EXISTS `list_question`;
CREATE TABLE IF NOT EXISTS `list_question` (
  `list_question_id` char(50) NOT NULL,
  `course_id` char(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`list_question_id`),
  KEY `list_question_ibfk_1` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `list_question`
--

INSERT INTO `list_question` (`list_question_id`, `course_id`, `created_at`, `updated_at`) VALUES
('03a61252-d21c-4cab-b813-9906b36ffa0f', 'bb18b2e3-b400-44f9-ae2a-d72853575eb3', '2025-04-03 12:54:20', '2025-04-03 12:54:20'),
('4d1ffa88-88e3-40e7-ad2c-503b2c2fde00', 'bb18b2e3-b400-44f9-ae2a-d72853575eb3', '2025-04-03 13:33:04', '2025-04-03 13:33:04');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `options`
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
-- Đang đổ dữ liệu cho bảng `options`
--

INSERT INTO `options` (`option_id`, `question_id`, `option_text`, `is_correct`, `option_order`) VALUES
('0ce540fa-e146-4de7-bed6-b7b6386d40ed', '49e57159-1318-4d77-981c-e7f01645cb4b', 'C++', 0, 1),
('1d9d1101-77e3-49f9-8b6d-a2d5ccb17512', '49e57159-1318-4d77-981c-e7f01645cb4b', 'JavaScript', 1, 2),
('4c1d7c7a-7b68-4d1a-8287-ca45a76a896a', '49e57159-1318-4d77-981c-e7f01645cb4b', 'Java', 0, 0),
('4faf9ebc-c487-4538-b623-5ba4f8bb0cff', '6b293128-e126-4a89-bb5c-6edafd7613bd', 'b', 0, 2),
('962c831c-aa0a-40a9-80a0-8f6bc7908b16', '49e57159-1318-4d77-981c-e7f01645cb4b', 'Python', 0, 3),
('9d975b5e-71cb-4589-b208-6fde623f2de4', '6b293128-e126-4a89-bb5c-6edafd7613bd', 'b', 0, 0),
('e575f3b1-df3a-4252-8317-20f9a2a9df42', '6b293128-e126-4a89-bb5c-6edafd7613bd', 'b', 1, 3),
('f267ceef-cec6-436a-af95-370940466a6c', '6b293128-e126-4a89-bb5c-6edafd7613bd', 'b', 0, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `question`
--

DROP TABLE IF EXISTS `question`;
CREATE TABLE IF NOT EXISTS `question` (
  `question_id` char(50) NOT NULL,
  `list_question_id` char(50) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `type` enum('Trắc nghiệm','Tự luận') DEFAULT NULL,
  `correct_answer` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`question_id`),
  KEY `list_question_id` (`list_question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `question`
--

INSERT INTO `question` (`question_id`, `list_question_id`, `title`, `content`, `type`, `correct_answer`, `created_at`, `updated_at`) VALUES
('39d8b431-0a37-4dee-b2cb-75e410a1786a', '4d1ffa88-88e3-40e7-ad2c-503b2c2fde00', 'acdasd', 'acdasd', 'Tự luận', NULL, '2025-04-03 13:39:58', '2025-04-03 13:39:58'),
('49e57159-1318-4d77-981c-e7f01645cb4b', '03a61252-d21c-4cab-b813-9906b36ffa0f', 'Ngôn ngữ nào chủ yếu được dùng để phát triển web?', 'Chọn ngôn ngữ lập trình được sử dụng phổ biến nhất trong phát triển web.', 'Trắc nghiệm', 'JavaScript', '2025-04-03 13:39:02', '2025-04-03 13:39:02'),
('6b293128-e126-4a89-bb5c-6edafd7613bd', '4d1ffa88-88e3-40e7-ad2c-503b2c2fde00', 'b', 'b', 'Trắc nghiệm', 'b', '2025-04-03 13:39:58', '2025-04-03 13:39:58'),
('7742b23f-7203-49aa-a802-c8a98d531b55', '03a61252-d21c-4cab-b813-9906b36ffa0f', 'Đâu là ngôn ngữ lập trình phổ biến?', 'Chọn ngôn ngữ lập trình được sử dụng nhiều nhất hiện nay.', 'Tự luận', NULL, '2025-04-03 13:39:02', '2025-04-03 13:39:02'),
('9e721de2-6a96-4a2e-8697-9cb7e2b2eaaf', '4d1ffa88-88e3-40e7-ad2c-503b2c2fde00', 'a', 'a', 'Tự luận', NULL, '2025-04-03 13:39:58', '2025-04-03 13:39:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `score`
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

--
-- Đang đổ dữ liệu cho bảng `score`
--

INSERT INTO `score` (`score_id`, `student_id`, `course_id`, `process_score`, `midterm_score`, `final_score`, `average_score`, `created_at`, `updated_at`) VALUES
('3ef23887-95b1-45e0-a329-d994978d054f', '516abf07-8586-4a42-8dd2-cd375f29f016', 'd45b556e-dddb-4c5f-a463-09db859f0aad', 4, 5, 7, 6.1, '2025-04-02 15:15:19', '2025-04-02 08:15:19'),
('61ee6dac-ff89-4695-8c4c-22d3d2bbec37', '516abf07-8586-4a42-8dd2-cd375f29f016', 'bb18b2e3-b400-44f9-ae2a-d72853575eb3', 7, 8, 9, 8.4, '2025-04-02 15:28:43', '2025-04-02 08:28:43'),
('6f0ffe0b-3d7b-4299-ba75-efffce0c07a7', '516abf07-8586-4a42-8dd2-cd375f29f016', 'bb18b2e3-b400-44f9-ae2a-d72853575eb3', 7, 8, 9, 8.4, '2025-04-02 15:30:21', '2025-04-02 08:30:21'),
('f30e3314-ee65-4bda-a01e-5162b9577980', '9a52f705-88dd-4b5d-9dbc-bdc302e1c0ca', 'bb18b2e3-b400-44f9-ae2a-d72853575eb3', 9.25, 7, 8, 8.05, '2025-04-02 17:39:50', '2025-04-02 10:46:32'),
('f962cb90-fd58-433d-b8f7-de3faeda4d7b', '9a52f705-88dd-4b5d-9dbc-bdc302e1c0ca', 'bb18b2e3-b400-44f9-ae2a-d72853575eb3', 7, 9, 9, 8.6, '2025-04-02 15:31:39', '2025-04-02 08:31:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `student`
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
-- Đang đổ dữ liệu cho bảng `student`
--

INSERT INTO `student` (`student_id`, `student_code`, `full_name`, `school_email`, `password`, `phone`, `created_at`, `updated_at`) VALUES
('516abf07-8586-4a42-8dd2-cd375f29f016', 'DH52111509', 'Nguyễn Thành Tỷ Phú', 'dh52111923@student.stu.edu.vn', '$2y$12$tTHk3G4/fftui7WpUJJxreLIcyW2F/c3pf/QKY0rNqTOmmbUaB1Cy', '07673920391', '2025-04-02 07:36:37', '2025-04-02 07:37:56'),
('9a52f705-88dd-4b5d-9dbc-bdc302e1c0ca', 'DH52111612', 'Trần Nguyễn Hoàng Quân', 'dh52111612@student.stu.edu.vn', '$2y$12$AeetGbYOxu7oiRxD.RBwvOyM8UFHnHr3bfWwLA6p7idltL.qv4RCm', '0767392038', '2025-04-02 07:35:58', '2025-04-02 07:35:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `student_class`
--

DROP TABLE IF EXISTS `student_class`;
CREATE TABLE IF NOT EXISTS `student_class` (
  `student_class_id` char(50) NOT NULL,
  `student_id` char(50) DEFAULT NULL,
  `class_id` char(50) DEFAULT NULL,
  `enrolled_at` datetime DEFAULT NULL,
  `status` enum('Active','Drop','Pending') DEFAULT NULL,
  `final_score` float DEFAULT NULL,
  PRIMARY KEY (`student_class_id`),
  KEY `student_id` (`student_id`),
  KEY `class_id` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `submission`
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

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sub_list`
--

DROP TABLE IF EXISTS `sub_list`;
CREATE TABLE IF NOT EXISTS `sub_list` (
  `sub_list_id` char(50) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `isShuffle` tinyint(1) DEFAULT NULL,
  `create_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sub_list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sub_list_question`
--

DROP TABLE IF EXISTS `sub_list_question`;
CREATE TABLE IF NOT EXISTS `sub_list_question` (
  `sub_list_id` char(50) NOT NULL,
  `question_id` char(50) NOT NULL,
  PRIMARY KEY (`sub_list_id`,`question_id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`submission_id`) REFERENCES `submission` (`submission_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `assignment`
--
ALTER TABLE `assignment`
  ADD CONSTRAINT `assignment_ibfk_1` FOREIGN KEY (`sub_list_id`) REFERENCES `sub_list` (`sub_list_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `classroom`
--
ALTER TABLE `classroom`
  ADD CONSTRAINT `classroom_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturer` (`lecturer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `classroom_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `exam`
--
ALTER TABLE `exam`
  ADD CONSTRAINT `exam_ibfk_1` FOREIGN KEY (`sub_list_id`) REFERENCES `sub_list` (`sub_list_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `list_question`
--
ALTER TABLE `list_question`
  ADD CONSTRAINT `list_question_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`list_question_id`) REFERENCES `list_question` (`list_question_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `score`
--
ALTER TABLE `score`
  ADD CONSTRAINT `score_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `score_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `student_class`
--
ALTER TABLE `student_class`
  ADD CONSTRAINT `student_class_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_class_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classroom` (`class_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `submission`
--
ALTER TABLE `submission`
  ADD CONSTRAINT `submission_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `submission_ibfk_2` FOREIGN KEY (`exam_id`) REFERENCES `exam` (`exam_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `submission_ibfk_3` FOREIGN KEY (`assignment_id`) REFERENCES `assignment` (`assignment_id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `sub_list_question`
--
ALTER TABLE `sub_list_question`
  ADD CONSTRAINT `sub_list_question_ibfk_1` FOREIGN KEY (`sub_list_id`) REFERENCES `sub_list` (`sub_list_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sub_list_question_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
