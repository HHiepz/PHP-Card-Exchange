-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2024 at 03:18 PM
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
-- Database: `doithecao_demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank-user`
--

CREATE TABLE `bank-user` (
  `bank-user_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `bank-user_key` text NOT NULL,
  `bank-user_number_account` varchar(255) NOT NULL,
  `bank-user_owner` varchar(255) NOT NULL,
  `bank-user_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buy-card-data`
--

CREATE TABLE `buy-card-data` (
  `buy-card-data_id` bigint(20) NOT NULL,
  `buy-card-order_code` varchar(255) NOT NULL,
  `buy-card-data_price` bigint(20) NOT NULL,
  `buy-card-data_telco` varchar(255) NOT NULL,
  `buy-card-data_pin` varchar(255) NOT NULL,
  `buy-card-data_serial` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buy-card-order`
--

CREATE TABLE `buy-card-order` (
  `buy-card-order_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `buy-card-order_code` varchar(255) NOT NULL,
  `buy-card-order_telco` varchar(255) NOT NULL,
  `buy-card-order_price` bigint(20) NOT NULL,
  `buy-card-order_quantity` bigint(20) NOT NULL,
  `buy-card-order_status` varchar(255) NOT NULL,
  `buy-card-order_api_status` varchar(255) DEFAULT NULL,
  `buy-card-order_api_message` varchar(255) DEFAULT NULL,
  `buy-card-order_total_pay` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `buy-card-order_api_data` text DEFAULT NULL,
  `buy-card-order_order_code` varchar(255) DEFAULT NULL,
  `updated_api` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `card-data`
--

CREATE TABLE `card-data` (
  `card-data_id` bigint(20) NOT NULL,
  `card-data_telco` varchar(255) NOT NULL,
  `card-data_code` varchar(255) NOT NULL,
  `card-data_seri` varchar(255) NOT NULL,
  `card-data_amount` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `card-data_server` varchar(255) NOT NULL,
  `card-data_request_id` varchar(255) NOT NULL,
  `card-data_amount_real` bigint(20) NOT NULL DEFAULT 0,
  `card-data_punish` bigint(20) NOT NULL DEFAULT 0,
  `card-data_amount_recieve` bigint(20) NOT NULL DEFAULT 0,
  `card-data_profit` bigint(20) NOT NULL DEFAULT 0,
  `card-data_status` varchar(255) NOT NULL,
  `card-data_api_message` varchar(255) DEFAULT NULL,
  `card-data_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `card-data_updated_api` varchar(255) DEFAULT NULL,
  `card-data_partner_key` varchar(255) DEFAULT NULL,
  `card-data_callback` varchar(255) DEFAULT NULL,
  `card-data_fee` float NOT NULL,
  `card-data_partner_sign` varchar(255) DEFAULT NULL,
  `card-data_partner_request_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `card-rare`
--

CREATE TABLE `card-rare` (
  `card-rare_id` bigint(20) NOT NULL,
  `card-rare_code` varchar(255) NOT NULL,
  `card-rare_status` tinyint(4) NOT NULL DEFAULT 0,
  `10000` varchar(255) DEFAULT NULL,
  `20000` varchar(255) DEFAULT NULL,
  `30000` varchar(255) DEFAULT NULL,
  `50000` varchar(255) DEFAULT NULL,
  `100000` varchar(255) DEFAULT NULL,
  `200000` varchar(255) DEFAULT NULL,
  `300000` varchar(255) DEFAULT NULL,
  `500000` varchar(255) DEFAULT NULL,
  `1000000` varchar(255) DEFAULT NULL,
  `card-rare_name` varchar(255) DEFAULT NULL,
  `card-rare_type` varchar(255) DEFAULT NULL,
  `card-rare_img` varchar(255) DEFAULT NULL,
  `2000000` varchar(255) DEFAULT NULL,
  `3000000` varchar(255) DEFAULT NULL,
  `5000000` varchar(255) DEFAULT NULL,
  `650000` varchar(255) DEFAULT NULL,
  `405000` varchar(255) DEFAULT NULL,
  `810000` varchar(255) DEFAULT NULL,
  `1500000` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `card-rare`
--

INSERT INTO `card-rare` (`card-rare_id`, `card-rare_code`, `card-rare_status`, `10000`, `20000`, `30000`, `50000`, `100000`, `200000`, `300000`, `500000`, `1000000`, `card-rare_name`, `card-rare_type`, `card-rare_img`, `2000000`, `3000000`, `5000000`, `650000`, `405000`, `810000`, `1500000`) VALUES
(1, 'VIETTEL', 1, '02', '02', '02', '02', '02', '02', '02', '02', '02', 'Viettel', 'phone', 'viettel.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'VINA', 1, '4', '4', '4', '4', '4', '4', NULL, '4', NULL, 'Vinaphone', 'phone', 'VINAPHONE.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'MOBI', 1, '4', '4', NULL, '4', '4', '4', NULL, '4', NULL, 'mobifone', 'phone', 'MOBIFONE.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'VNMOBILE', 1, '5.5', '5.5', '5.5', '5.5', '5.5', '5.5', '5.5', '5.5', NULL, 'Vietnamobile', 'phone', 'VIETNAMOBILE.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'GARENA', 1, NULL, '5.5', NULL, '5.5', '5.5', '5.5', NULL, '5.5', NULL, 'Garena', 'game', 'garena.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'GATE', 1, '4.0', '4.0', '4.0', '4.0', '4.0', '4.0', '4.0', '4.0', '4.0', 'Gate', 'game', 'gate.png', '4.0', '4.0', '4.0', NULL, NULL, NULL, NULL),
(7, 'VCOIN', 1, NULL, '4.5', '4.5', '4.5', '4.5', '4.5', '4.5', '4.5', '4.5', 'Vcoin / VTC', 'game', 'vcoin.png', '4.5', '4.5', '4.5', NULL, NULL, NULL, NULL),
(8, 'ZING', 1, '3.5', '3.5', NULL, '3.5', '3.5', '3.5', NULL, '3.5', '3.5', 'Zing', 'game', 'zing.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'GMOBILE', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Gmobile', 'game', 'GMOBILE.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'APPOTA', 1, '4.0', '4.0', '4.0', '4.0', '4.0', '4.0', '4.0', '4.0', '4.0', 'Appota', 'game', 'APPOTA.png', '4.0', '4.0', '4.0', NULL, NULL, NULL, NULL),
(11, 'CAROT', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Carot', 'game', 'CAROT.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'FUNCARD', 1, '4.0', '4.0', '4.0', '4.0', '4.0', '4.0', NULL, '4.0', '4.0', 'Funcard', 'game', 'FUNCARD.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'SCOIN', 1, '4.0', '4.0', NULL, '4.0', '4.0', '4.0', NULL, '4.0', '4.0', 'Scoin', 'game', 'SCOIN.png', '4.0', NULL, '4.0', NULL, NULL, NULL, NULL),
(14, 'GOSU', 1, '4.0', '4.0', NULL, '4.0', '4.0', '4.0', NULL, '4.0', '4.0', 'Gosu', 'game', 'GOSU.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'SOHACOIN', 1, '4.0', '4.0', NULL, '4.0', '4.0', '4.0', NULL, '4.0', '4.0', 'Sohacoin', 'game', 'SOHACOIN.png', '4.0', NULL, '4.0', NULL, NULL, NULL, NULL),
(16, 'ONCASH', 1, NULL, '4.5', NULL, '4.5', '4.5', '4.5', NULL, '4.5', NULL, 'oncash', 'game', 'ONCASH.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 'BITVN', 1, NULL, NULL, NULL, '4.0', '4.0', '4.0', NULL, '4.0', '4.0', 'BITVN', 'game', 'BIT.png', '4.0', NULL, '4.0', NULL, NULL, NULL, NULL),
(18, 'ANPAY', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Anpay', 'game', 'ANPAY.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'KUL', 1, '5.0', '5.0', NULL, '5.0', '5.0', '5.0', NULL, '5.0', '5.0', 'KUL', 'game', 'KUL.png', '5.0', NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'VEGA', 1, '2.0', '2.0', NULL, '2.0', '2.0', '2.0', NULL, '2.0', '2.0', 'Vega', 'game', 'VEGAID.png', '2.0', NULL, NULL, NULL, NULL, NULL, NULL),
(21, 'KCONG', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'KCONG', 'game', 'KCONG.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'VGA', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'VGA', 'game', 'AVG.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'KIS', 1, NULL, NULL, NULL, NULL, NULL, NULL, '4.5', NULL, NULL, 'Kis', 'game', 'KIS.png', NULL, NULL, NULL, '4.5', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `email_queue`
--

CREATE TABLE `email_queue` (
  `id` bigint(20) NOT NULL,
  `recipient_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dataJson` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `money`
--

CREATE TABLE `money` (
  `money_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `money_before` bigint(20) NOT NULL,
  `money_change` bigint(20) NOT NULL,
  `money_after` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `money_note` text NOT NULL,
  `money_user_change` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partner`
--

CREATE TABLE `partner` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `partner_id` bigint(20) NOT NULL,
  `partner_key` text NOT NULL,
  `partner_type` varchar(10) NOT NULL,
  `partner_status` varchar(255) NOT NULL DEFAULT 'non-active',
  `partner_callback` varchar(255) DEFAULT NULL,
  `partner_action` varchar(4) NOT NULL,
  `partner_ip` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rank`
--

CREATE TABLE `rank` (
  `rank_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `rank_before` varchar(255) NOT NULL,
  `rank_change` varchar(255) NOT NULL,
  `rank_after` varchar(255) NOT NULL,
  `rank_note` text NOT NULL,
  `rank_user_change` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `value` text NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `name`, `value`, `note`, `created_at`) VALUES
(1, 'webhook_exchange_card', '', 'Nhập link thông báo người dùng đổi thẻ ', '2024-02-16 17:13:37'),
(2, 'webhook_money', '', 'Nhập link thông báo lịch sử dòng tiền', '2024-02-16 18:17:54'),
(3, 'webhook_login', '', 'Nhập link thông báo người dùng đăng nhập', '2024-02-16 18:31:09'),
(4, 'webhook_register', '', 'Nhập link thông báo người dùng đăng ký', '2024-02-16 18:31:09'),
(5, 'webhook_withdraw', '', 'Nhập link thông báo người dùng rút tiền', '2024-02-16 18:31:44'),
(6, 'partner_id', '', 'Partner_id - Server mẹ gửi thẻ', '2024-02-17 16:51:24'),
(7, 'partner_key', '', 'Partner_key - Server mẹ gửi thẻ', '2024-02-17 16:51:24'),
(8, 'partner_server_name', '', 'Tên miền partner - Server đổi thẻ', '2024-02-17 16:53:05'),
(9, 'email', '', 'Email quên mật khẩu, xác thực đăng ký.', '2024-02-18 06:10:18'),
(10, 'email_password', '', 'Mật khẩu ứng dụng email', '2024-02-18 06:10:18'),
(11, 'min_withdraw', '2000', 'Số tiền rút tối thiểu trong 1 lần', '2024-02-23 17:13:45'),
(12, 'max_withdraw', '5000000', 'Số tiền rút tối đa trong 1 lần', '2024-02-23 17:13:45'),
(13, 'fee_withdraw', '0.0', 'Phí rút tiền trong 1 lần', '2024-02-23 17:14:16'),
(14, 'partner_atm', '', 'Partner_atm - Server mẹ gửi lệnh rút tiền', '2024-02-23 23:57:44'),
(15, 'status_exchange_card', '1', 'Trạng thái đổi thẻ, 1 => kích hoạt, 0 => bảo trì', '2024-02-24 17:17:43'),
(16, 'status_withdraw', '1', 'Trạng thái rút tiền, 1 => kích hoạt, 0 => bảo trì', '2024-02-24 17:21:18'),
(17, 'status_transfer', '1', 'Trạng thái chuyển tiền, 1 => kích hoạt, 0 => bảo trì', '2024-02-24 18:59:35'),
(18, 'min_transfer', '10000', 'Chuyển tiền - Số tiền tối thiểu', '2024-02-24 19:00:48'),
(19, 'max_transfer', '10000000', 'Chuyển tiền - Số tiền tối đa ', '2024-02-24 19:00:48'),
(20, 'max_api_partner', '10', 'API Partner - Số lượng tạo API tối đa', '2024-02-25 13:31:10'),
(21, 'min_buyCard', '1', 'Mua thẻ cào - Số lượng tối thiểu', '2024-02-26 11:39:59'),
(22, 'max_buyCard', '10', 'Mua thẻ cào - Số lượng tối đa', '2024-02-26 11:39:59'),
(23, 'status_buyCard', '1', 'Trạng thái bán thẻ cào, 1 => kích hoạt, 0 => bảo trì', '2024-02-26 16:34:36'),
(24, 'noti_index', '<p>✨ Card2k chân thành cảm ơn người dùng đã và đang tin dùng dịch vụ của chúng tôi. Năm mới chúc tài lộc an khang.</p>\n<p class=\"text-danger\"> Cấm tích hợp game bài, cơ bạc, phi pháp luật,.. Nếu phát hiện chúng tôi sẽ giam số dư và không thanh toán ! </p>\n<p>\n    <span> Liên hệ trợ giúp : <a href=\"https://discord.gg/B6kYEuhwjv\">Giải đáp thắc mắc</a> </span> <br />\n    <span> Kênh thông báo : <a href=\"https://discord.gg/B6kYEuhwjv\">Discord</a> </span> <br />\n    <span> Top nạp : <a href=\"\">xem chi tiết</a> </span>\n</p>\n<p>✨ <span> <b class=\"text-danger\">Qua tang:</b> Nếu bạn là chủ SHOP (lớn/nhỏ), chỉ cần liên hệ hỗ trợ sẽ cấp rank cho bạn. </span> <br /> ✨ <span> <b class=\"text-danger\">Qua tang:</b> <span> Hoặc mời 5 người dùng.</span> </span></p>\n<p class=\"text-warning\"> // Chúng tôi cho phép bạn mỡ cổng tích hợp kiểm tra lịch sử chuyển tiền nội bộ nếu bạn là chủ SHOP (lớn/nhỏ) có nhu cầu. </p>\n<p> <b>API</b> : <span>Tài liệu <a href=\"https://documenter.getpostman.com/view/27137333/2sA2xnw9Wg#f1a4a027-013e-470c-aed7-a76e9f612e61\">nạp thẻ</a>, hỗ trợ setup cho SHOP miễn phí.</span></p>\n<p> <b>Min rút</b> : <span class=\"text-danger\">2.000đ</span></p>\n<h3>  <i> <img src=\"https://i.ibb.co/5GbyZPd/gift-box.gif\" height=\"50px\" alt=\"\"> NHẬN RANK ĐẠI LÝ - <a href=\"https://discord.gg/PEG2CgPsQP\">Bấm vào đây</a> </h3>', 'Hiển thị thông báo - INDEX', '2024-02-26 19:22:11'),
(25, 'noti_withdraw', '<div class=\"container mt-3\">\n  <div class=\"alert alert-warning\">\n    <h5 class=\"alert-heading\">Chú ý:</h5>\n    <ul class=\"mb-0\">\n      <li>Chúng tôi cam kết không giam giữ số dư của bạn.</li>\n      <li>Đơn hàng được xử lý tự động trong 5 - 15 phút.</li>\n      <li>Nếu bạn nhập sai thông tin, đơn hàng sẽ bị treo 48h và không được hoàn tiền.</li>\n      <li>Hãy kiểm tra kỹ thông tin trước khi tạo đơn.</li>\n      <li class=\"text-danger fw-bold\">VUI LÒNG KHÔNG LIÊN HỆ KHI ĐƠN RÚT CHƯA ĐỦ 24H</li>\n    </ul>\n  </div>\n\n  <div class=\"p-3 rounded mb-3\">\n    <p class=\"mb-1\"><span class=\"fw-bold\">Rút tối thiểu:</span> <span class=\"text-danger fw-bold\">2.000đ</span></p>\n    <p class=\"mb-1\">\n      <span class=\"fw-bold\">Giới hạn rút (ngày):</span> <br>\n      <span class=\"fw-bold\">Momo:</span> <span class=\"fst-italic text-danger fw-bold\">500.000đ</span> <br>\n      <span class=\"fw-bold\">Bank:</span> <span class=\"fst-italic text-danger fw-bold\">Không giới hạn</span> <br>\n    </p>\n  </div>\n\n  <div class=\"text-center\">\n    <p class=\"mb-1\"><span class=\"fw-bold\">Liên hệ trợ giúp:</span> <a href=\"https://discord.gg/B6kYEuhwjv\"\n        class=\"text-info\">Giải đáp thắc mắc</a></p>\n    <p class=\"mb-1\"><span class=\"fw-bold\">Kênh thông báo:</span> <a href=\"https://discord.gg/B6kYEuhwjv\"\n        class=\"text-info\">Discord</a></p>\n  </div>\n</div>', 'Hiển thị thông báo - WITHDRAW', '2024-02-26 19:37:11'),
(26, 'exchange_card_rare_member', '1.6', 'Phí cố định THÀNH VIÊN THƯỜNG', '2024-02-26 20:20:02'),
(27, 'exchange_card_rare_vip', '1', 'Phí cố định THÀNH VIÊN VIP', '2024-02-26 20:20:02'),
(28, 'exchange_card_rare_agency', '0.1', 'Phí cố định THÀNH VIÊN ĐẠI LÝ', '2024-02-26 20:20:34'),
(29, 'partner_id_buyCard', '', 'Partner_id - Server mẹ mua thẻ', '2024-02-29 12:43:40'),
(30, 'partner_key_buyCard', '', 'Partner_key - Server mẹ mua thẻ', '2024-02-29 12:43:40'),
(31, 'partner_server_name_buyCard', '', 'Tên miền partner - Server mẹ mua thẻ', '2024-02-29 12:44:05'),
(32, 'wallet_buyCard', '', NULL, '2024-03-01 18:33:22'),
(33, 'buyCard_rare_viettel', '02', 'Phí giảm giá mua thẻ cào', '2024-03-05 21:58:49'),
(34, 'buyCard_rare_vina', '4', 'Phí giảm giá mua thẻ cào', '2024-03-05 21:58:49'),
(35, 'buyCard_rare_mobi', '4', 'Phí giảm giá mua thẻ cào', '2024-03-05 21:59:12'),
(36, 'buyCard_rare_vnmobile', '5.5', 'Phí giảm giá mua thẻ cào', '2024-03-05 21:59:12'),
(37, 'buyCard_rare_garena', '5.5', 'Phí giảm giá mua thẻ cào', '2024-03-05 21:59:24'),
(38, 'buyCard_rare_gate', '4.0', 'Phí giảm giá mua thẻ cào', '2024-03-05 21:59:24'),
(39, 'buyCard_rare_vcoin', '4.5', 'Phí giảm giá mua thẻ cào', '2024-03-05 21:59:36'),
(40, 'buyCard_rare_zing', '3.5', 'Phí giảm giá mua thẻ cào', '2024-03-05 21:59:36'),
(41, 'buyCard_rare_gmobile', '0.0', 'Phí giảm giá mua thẻ cào', '2024-03-05 21:59:48'),
(42, 'buyCard_rare_appota', '4.0', 'Phí giảm giá mua thẻ cào', '2024-03-05 21:59:48'),
(43, 'buyCard_rare_carot', '0.0', 'Phí giảm giá mua thẻ cào', '2024-03-05 22:00:11'),
(44, 'buyCard_rare_funcard', '4.0', 'Phí giảm giá mua thẻ cào', '2024-03-05 22:00:11'),
(45, 'buyCard_rare_scoin', '4.0', 'Phí giảm giá mua thẻ cào', '2024-03-05 22:00:26'),
(46, 'buyCard_rare_gosu', '4.0', 'Phí giảm giá mua thẻ cào', '2024-03-05 22:00:26'),
(47, 'buyCard_rare_sohacoin', '4.0', 'Phí giảm giá mua thẻ cào', '2024-03-05 22:00:38'),
(48, 'buyCard_rare_oncash', '4.5', 'Phí giảm giá mua thẻ cào', '2024-03-05 22:00:38'),
(49, 'buyCard_rare_bitvn', '4.0', 'Phí giảm giá mua thẻ cào', '2024-03-05 22:00:53'),
(50, 'buyCard_rare_anpay', '0.0', 'Phí giảm giá mua thẻ cào', '2024-03-05 22:00:53'),
(51, 'buyCard_rare_kul', '5.0', 'Phí giảm giá mua thẻ cào', '2024-03-05 22:01:03'),
(52, 'buyCard_rare_vega', '2.0', 'Phí giảm giá mua thẻ cào', '2024-03-05 22:01:03'),
(53, 'buyCard_rare_kcong', '0.0', 'Phí giảm giá mua thẻ cào', '2024-03-05 22:01:15'),
(54, 'buyCard_rare_vga', '0.0', 'Phí giảm giá mua thẻ cào', '2024-03-05 22:01:15'),
(55, 'buyCard_rare_kis', '4.5', 'Phí giảm giá mua thẻ cào', '2024-03-05 22:01:22'),
(56, 'partner_key_withdraw', '', 'api-key-withdraw - Server mẹ rút tiền', '2024-03-06 00:55:28'),
(57, 'status_server', '1', 'Trạng thái toàn server, 1 => kích hoạt, 0 => bảo trì', '2024-03-06 01:31:30'),
(58, 'google_search', 'đổi thẻ cào thành tiền mặt, doi the cao thanh tiên mat, đổi thẻ cào sang tiền mặt, đổi thẻ cào, đổi thẻ game,doi the cao,đổi thẻ cào thành tiền mặt,đổi thẻ cào sang tiền mặt,đổi thẻ cào ra tiền mặt,đổi thẻ điện thoại sang tiền mặt,đổi thẻ điện thoại ra tiền mặt,đổi thẻ cào sang tiền mặt không mất phí,web đổi thẻ cào uy tín', NULL, '2024-03-08 20:51:39'),
(59, 'google_logo_light', 'https://i.ibb.co/xJ8jfN0/logo.png', NULL, '2024-03-08 20:51:39'),
(60, 'google_description', 'Đổi thẻ cào thành tiền mặt / momo / ATM chiết khấu siêu thấp. Uy tín - xử lý nhanh gọn là ưu tiên hàng đầu của chúng tôi.', NULL, '2024-03-08 20:52:07'),
(61, 'google_logo_dark', 'https://i.ibb.co/JpXSDjY/logo-dark.png', NULL, '2024-03-08 20:57:37'),
(62, 'header_script', '', 'Dùng để thêm google analytics,...', '2024-03-08 21:02:04'),
(63, 'google_analytic', '', NULL, '2024-03-08 21:04:33'),
(64, 'facebook_message', '', NULL, '2024-03-08 21:04:33'),
(65, 'dmca_link', '', NULL, '2024-03-08 21:30:49'),
(66, 'dmca_meta_verify', '', NULL, '2024-03-08 21:30:49'),
(67, 'footer_script', '', NULL, '2024-03-08 22:23:07'),
(68, 'footer_support', 'https://discord.gg/4q83x5ceg4', NULL, '2024-03-08 22:24:35'),
(69, 'footer_facebook', 'https://www.facebook.com/profile.php?id=100066408558292', NULL, '2024-03-08 22:24:35'),
(70, 'footer_discord', 'https://discord.gg/4q83x5ceg4', NULL, '2024-03-08 22:24:53'),
(71, 'footer_telegram', '', NULL, '2024-03-08 22:24:53'),
(72, 'footer_youtube', 'https://www.youtube.com/channel/UCvPhp1CKk3RWR1aaHID2gyg', NULL, '2024-03-08 22:25:00'),
(73, 'google_favicon', 'https://i.ibb.co/tH82Hqx/favicon.png', NULL, '2024-03-08 23:17:14'),
(74, 'last_min_telco_rare', '10.5', NULL, '2024-03-12 01:37:07'),
(75, 'webhook_min_telco_rare', 'https://discord.com/api/webhooks/1204822095926665296/aK55VaH3tjhUI0bvW3ZT65TPGAwA8ZXwy4UlJC5SuRE1Yc-y6ZB7EX7ODd14B1-0bp_B', NULL, '2024-03-12 01:39:11'),
(76, 'register_verify_email', '0', 'Xác minh email khi đăng ký, 1 => kích hoạt, 0 => tắt', '2024-03-13 00:08:00'),
(77, 'momo_number', '', NULL, '2024-03-13 01:20:00'),
(78, 'momo_owner', '', NULL, '2024-03-13 01:20:00'),
(79, 'momo_bank_code', '', NULL, '2024-03-13 01:20:08'),
(80, 'noti_index_title', '<div style=\"font-family: Roboto, Arial, Helvetica, sans-serif; font-size: 14px;\">\n    <p style=\"margin-bottom: 5px;  \"><i><span style=\"font-weight: bolder;\">#API CÓ SẢN LƯỢNG CAO LIÊN HỆ ĐỂ CÓ CHIẾT KHẤU TỐT , HỖ TRỢ ĐẤU NỐI MIỄN PHÍ !</span></i></p>\n    <p style=\"margin-bottom: 5px;  \"><span style=\"font-weight: bolder;\">► WEB MIN RÚT <i class=\"text-danger\">5.000VNĐ </i>TỰ ĐỘNG XỬ LÝ VÀ MIỄN PHÍ RÚT <img alt=\"\" src=\"https://i.ibb.co/KXk89fD/new-1.gif\"></span></p>\n    <p style=\"margin-bottom: 5px;  \"><span style=\"font-weight: bolder;\">► SAI MỆNH GIÁ - 50 % THỰC NHẬN MỆNH GIÁ NHỎ HƠN</span></p>\n    <p style=\"margin-bottom: 5px;  \"><span style=\"font-weight: bolder;\">► SAI NHÀ CUNG CẤP - MẤT THẺ</span></p>\n    <p style=\"margin-bottom: 5px;  \"><span style=\"font-weight: bolder;\">► </span><a href=\"https://discord.gg/bKH7BGAvw5\" target=\"_blank\"><b>NHÓM DISCORD HỖ TRỢ</b></a><span style=\"font-weight: bolder;\"> <img alt=\"\" src=\"https://i.ibb.co/ZTYC1Fr/new-1.gif\"><br></span></p>\n    <p style=\"margin-bottom: 5px;  \"><span style=\"font-weight: bolder;\">► </span><a href=\"https://discord.gg/bKH7BGAvw5\" target=\"_blank\"><b>NHẬN THÔNG BÁO KHI CÓ GIÁ CHIẾT KHẤU TỐT! </b></a><span style=\"font-weight: bolder;\"></span><img alt=\"\" src=\"https://i.ibb.co/ZTYC1Fr/new-1.gif\" style=\"font-weight: bolder;\"></p><span style=\"  font-weight: 700;\">► CHO PHÉP XÃ THẺ, CHỌN LỌC KHÔNG KHÓA TÀI KHOẢN.</span>\n    <p style=\"margin-bottom: 5px;  \"><span style=\"font-weight: 700;\">► ĐẤU API TỰ ĐỘNG NÂNG CẤP VIP. </span><img alt=\"\" src=\"https://i.ibb.co/ZTYC1Fr/new-1.gif\" style=\"font-weight: bolder;\"><span style=\"font-weight: 700;\"><br></span><span style=\"font-weight: bolder;\">► </span><i>Quý khách cần điền đúng <span style=\"font-weight: bolder;\">Seri & Nhà Mạng</span>, Cố tình điền sai khiếu nại web <span style=\"font-weight: bolder;\">KHÔNG </span>xử lý, (nhiều lần => <b>KHÓA TK</b>)</i></p>\n    <h3 style=\"margin-right: 0px; margin-bottom: 10px; margin-left: 0px; \"></h3>\n</div>\n\n', NULL, '2024-03-14 04:37:31'),
(81, 'google_logo_seo', 'https://i.ibb.co/tH82Hqx/favicon.png', NULL, '2024-03-16 03:21:09'),
(82, 'status_top', '0', 'TOP nạp thẻ, 1 => kích hoạt, 0 => bảo trì', '2024-03-16 12:03:04'),
(83, 'webhook_tongKet', 'https://discord.com/api/webhooks/1220332991272980531/Nhi4-QSnI3AxVm5JFd24czpy1MV4K1PuOkXZDmEPhPvLBd8zmwc_ywcMLdz16xiqveLQ', NULL, '2024-03-21 12:13:57'),
(84, 'register_verify_ip', '0', 'Xác minh IP khi đăng ký (Kiểm tra proxy, vpn, tor, cloudflare, bot)', '2024-03-21 19:20:23'),
(85, 'last_min_telco', 'VINAPHONE', NULL, '2024-04-01 05:07:37'),
(86, 'fee_noti_transfer', '1000', NULL, '2024-04-02 19:48:09'),
(87, 'status_ggReCaptcha', '0', '1: Bật / 0: Tắt - Xác thực google recapcha', '2024-04-11 19:49:38'),
(88, 'ggReCaptcha_site_key', '', NULL, '2024-04-11 19:49:38'),
(89, 'ggReCaptcha_secret_key', '', NULL, '2024-04-11 19:49:47'),
(90, 'webhook_backup', '', NULL, '2024-04-14 01:50:33'),
(91, 'momo_limit', '500000', 'Hạn mức rút momo / ngày', '2024-05-04 11:53:50'),
(92, 'last_time_min_telco_rare', '1724844007', 'Lần cuối gữi thông báo', '2024-05-27 13:34:08'),
(93, 'webhook_bug', '', NULL, '2024-06-06 05:03:13'),
(94, 'version', '1.6.2', 'Phiên bản hiện tại', '2024-08-24 06:38:57'),
(95, 'wallet_exCard', '', 'Ví đổi thẻ cào', '2024-08-31 04:56:43'),
(96, 'webhook_withdraw_profit', '', 'Nhập link thông báo khi có lệnh lãnh lương', '2024-08-31 04:57:52'),
(97, 'bank_code_withdraw_profit', 'MBBANK', 'Mã Bank - Rút tiền tự động khi đến đầu tháng (cấu hình CronJob)', '2024-08-31 08:10:19'),
(98, 'account_number_withdraw_profit', '', 'Số tài khoản - Rút tiền tự động khi đến đầu tháng ...', '2024-08-31 08:10:19'),
(99, 'account_owner_withdraw_profit', '', 'Tên tài khoản - Rút tiền tự động khi đến đầu tháng (cấu hình CronJob)', '2024-08-31 08:10:28'),
(100, 'role_withdraw_profit', '', 'ID Discord Role (Chỉ nhập số) - Rút tiền lời cuối tháng', '2024-08-31 08:23:51'),
(101, 'status_topup', '1', '0: tắt / 1: bật | Nạp tiền điện thoại, game', '2024-09-05 12:20:12'),
(102, 'partner_id_topup', '', 'Partner_id: Server topup', '2024-09-09 07:41:17'),
(103, 'partner_key_topup', '', 'Partner_key: Server topup', '2024-09-09 07:41:17'),
(104, 'partner_server_name_topup', '', 'Partner_server_name : Tên miền Server topup', '2024-09-09 07:42:20'),
(105, 'wallet_topup', '', 'Wallet_topup : Mã ví', '2024-09-09 07:42:20'),
(106, 'webhook_topup', '', 'Thông báo đến discord về nạp topup', '2024-09-09 07:53:31'),
(107, 'role_topup', '', 'Role dùng để ping pong về nạp điện thoại', '2024-09-09 07:53:31'),
(108, 'topup_rare_vietteltt', '1', 'Phí lãi nạp điện thoại', '2024-09-09 10:42:20'),
(109, 'topup_rare_vinatt', '1', 'Phí lãi nạp điện thoại', '2024-09-09 10:42:20'),
(110, 'topup_rare_mobitt', '1', 'Phí lãi nạp điện thoại', '2024-09-09 10:42:59'),
(111, 'topup_rare_so', '1', 'Phí lãi nạp game', '2024-09-09 10:42:59'),
(112, 'status_demo', '0', 'Trạng thái website demo', '2024-10-24 15:59:55');

-- --------------------------------------------------------

--
-- Table structure for table `telco-rare`
--

CREATE TABLE `telco-rare` (
  `telco-rare_id` bigint(20) NOT NULL,
  `telco-rare_code` varchar(255) NOT NULL,
  `telco-rare_name` varchar(255) NOT NULL,
  `member_10000` float NOT NULL,
  `member_20000` float NOT NULL,
  `member_30000` float NOT NULL,
  `member_50000` float NOT NULL,
  `member_100000` float NOT NULL,
  `member_200000` float NOT NULL,
  `member_300000` float NOT NULL,
  `member_500000` float NOT NULL,
  `member_1000000` float NOT NULL,
  `vip_10000` float NOT NULL,
  `vip_20000` float NOT NULL,
  `vip_30000` float NOT NULL,
  `vip_50000` float NOT NULL,
  `vip_100000` float NOT NULL,
  `vip_200000` float NOT NULL,
  `vip_300000` float NOT NULL,
  `vip_500000` float NOT NULL,
  `vip_1000000` float NOT NULL,
  `agency_10000` float NOT NULL,
  `agency_20000` float NOT NULL,
  `agency_30000` float NOT NULL,
  `agency_50000` float NOT NULL,
  `agency_100000` float NOT NULL,
  `agency_200000` float NOT NULL,
  `agency_300000` float NOT NULL,
  `agency_500000` float NOT NULL,
  `agency_1000000` float NOT NULL,
  `telco-rare_status` tinyint(4) NOT NULL DEFAULT 1,
  `member_2000000` float NOT NULL,
  `vip_2000000` float NOT NULL,
  `agency_2000000` float NOT NULL,
  `member_5000000` float NOT NULL,
  `vip_5000000` float NOT NULL,
  `agency_5000000` float NOT NULL,
  `member_10000000` float NOT NULL,
  `vip_10000000` float NOT NULL,
  `agency_10000000` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `telco-rare`
--

INSERT INTO `telco-rare` (`telco-rare_id`, `telco-rare_code`, `telco-rare_name`, `member_10000`, `member_20000`, `member_30000`, `member_50000`, `member_100000`, `member_200000`, `member_300000`, `member_500000`, `member_1000000`, `vip_10000`, `vip_20000`, `vip_30000`, `vip_50000`, `vip_100000`, `vip_200000`, `vip_300000`, `vip_500000`, `vip_1000000`, `agency_10000`, `agency_20000`, `agency_30000`, `agency_50000`, `agency_100000`, `agency_200000`, `agency_300000`, `agency_500000`, `agency_1000000`, `telco-rare_status`, `member_2000000`, `vip_2000000`, `agency_2000000`, `member_5000000`, `vip_5000000`, `agency_5000000`, `member_10000000`, `vip_10000000`, `agency_10000000`) VALUES
(1, 'VIETTEL', 'Viettel', 17.1, 20.6, 20.6, 19.1, 19.1, 19.9, 20.1, 18.8, 18.7, 16.5, 20, 20, 18.5, 18.5, 19.3, 19.5, 18.2, 18.1, 15.6, 19.1, 19.1, 17.6, 17.6, 18.4, 18.6, 17.3, 17.2, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 'MOBIFONE', 'Mobifone', 20.1, 20.1, 20.1, 19.6, 17.4, 17.4, 17.4, 17.4, 0, 19.5, 19.5, 19.5, 19, 16.8, 16.8, 16.8, 16.8, 0, 18.6, 18.6, 18.6, 18.1, 15.9, 15.9, 15.9, 15.9, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 'VINAPHONE', 'Vinaphone', 13.6, 13.6, 13.6, 12.6, 11.1, 11.1, 11.1, 11.1, 0, 13, 13, 13, 12, 10.5, 10.5, 10.5, 10.5, 0, 12.1, 12.1, 12.1, 11.1, 9.6, 9.6, 9.6, 9.6, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 'ZING', 'Zing', 11.6, 11.6, 0, 11.6, 11.6, 11.6, 0, 11.6, 11.6, 11, 11, 0, 11, 11, 11, 0, 11, 11, 10.1, 10.1, 0, 10.1, 10.1, 10.1, 0, 10.1, 10.1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 'GARENA', 'Garena', 0, 13.1, 0, 13.1, 13.1, 13.1, 0, 13.1, 0, 0, 12.5, 0, 12.5, 12.5, 12.5, 0, 12.5, 0, 0, 11.6, 0, 11.6, 11.6, 11.6, 0, 11.6, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 'GATE', 'Gate', 17.2, 17.2, 0, 16.2, 16.2, 16.2, 15.8, 16.2, 16.2, 16.6, 16.6, 0, 15.6, 15.6, 15.6, 15.2, 15.6, 15.6, 15.7, 15.7, 0, 14.7, 14.7, 14.7, 14.3, 14.7, 14.7, 1, 17.2, 16.6, 15.7, 17.2, 16.6, 15.7, 0, 0, 0),
(7, 'VNMOBI', 'Vietnamobile (chậm)', 27.6, 27.6, 27.6, 27.6, 27.6, 27.6, 27.6, 27.6, 0, 27, 27, 27, 27, 27, 27, 27, 27, 0, 26.1, 26.1, 26.1, 26.1, 26.1, 26.1, 26.1, 26.1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(8, 'VCOIN', 'Vcoin', 12.3, 12.3, 0, 12.3, 11.3, 11.3, 11.3, 11.3, 11.3, 11.7, 11.7, 0, 11.7, 10.7, 10.7, 10.7, 10.7, 10.7, 10.8, 10.8, 0, 10.8, 9.8, 9.8, 9.8, 9.8, 9.8, 1, 13.9, 13.3, 12.4, 13.9, 13.3, 12.4, 0, 0, 0),
(9, 'APPOTA', 'Appota', 0, 0, 0, 15.1, 15.1, 15.1, 0, 15.1, 0, 0, 0, 0, 14.5, 14.5, 14.5, 0, 14.5, 0, 0, 0, 0, 13.6, 13.6, 13.6, 0, 13.6, 0, 1, 15.1, 14.5, 13.6, 15.1, 14.5, 13.6, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `top`
--

CREATE TABLE `top` (
  `top_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `top_cash` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `topup`
--

CREATE TABLE `topup` (
  `topup_id` bigint(20) NOT NULL,
  `topup_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `topup_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `topup_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `topup_status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `topup`
--

INSERT INTO `topup` (`topup_id`, `topup_code`, `topup_name`, `topup_type`, `topup_status`, `created_at`, `updated_at`) VALUES
(1, 'vietteltt', 'Nạp Viettel trả trước, trả sau', 'phone', 1, '2024-09-05 11:38:44', '2024-09-05 11:38:44'),
(2, 'vinatt', 'Nạp Vinaphone trả trước, trả sau', 'phone', 1, '2024-09-05 11:38:44', '2024-09-05 11:38:44'),
(3, 'mobitt', 'Nạp Mobifone trả trước, trả sau', 'phone', 1, '2024-09-05 11:39:11', '2024-09-09 10:55:51'),
(4, 'so', 'NẠP SÒ GARENA', 'game', 0, '2024-09-05 11:39:11', '2024-09-05 11:39:11'),
(5, 'freefire', 'NẠP KIM CƯƠNG FREE FIRE', 'game', 0, '2024-09-05 11:39:21', '2024-09-09 07:09:03');

-- --------------------------------------------------------

--
-- Table structure for table `topup-order`
--

CREATE TABLE `topup-order` (
  `topup-order_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `topup_id` bigint(20) NOT NULL,
  `topup-order_order_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `topup-order_request_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `topup-order_pay_amount` int(11) NOT NULL,
  `topup-order_discount` double NOT NULL,
  `topup-order_amount` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `topup-order_status` varchar(255) NOT NULL,
  `topup-order_account` varchar(255) NOT NULL,
  `topup-order_cron` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `topup-rare`
--

CREATE TABLE `topup-rare` (
  `topup-rare_id` bigint(20) NOT NULL,
  `topup_id` bigint(20) NOT NULL,
  `topup-rare_status` tinyint(4) NOT NULL DEFAULT 0,
  `topup-rare_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `topup-rare_value` int(11) NOT NULL,
  `topup-rare_price` int(11) NOT NULL,
  `topup-rare_discount` double NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `topup-rare`
--

INSERT INTO `topup-rare` (`topup-rare_id`, `topup_id`, `topup-rare_status`, `topup-rare_name`, `topup-rare_value`, `topup-rare_price`, `topup-rare_discount`, `created_at`, `updated_at`) VALUES
(27, 1, 1, '20,000 đ', 20000, 20000, 6, '2024-09-05 11:50:26', '2024-09-09 10:52:06'),
(28, 1, 1, '30,000 đ', 30000, 30000, 6, '2024-09-05 11:50:26', '2024-09-09 10:52:06'),
(29, 1, 1, '50,000 đ', 50000, 50000, 6, '2024-09-05 11:50:26', '2024-09-09 10:52:07'),
(30, 1, 1, '100,000 đ', 100000, 100000, 6, '2024-09-05 11:50:26', '2024-09-09 10:52:07'),
(31, 1, 1, '200,000 đ', 200000, 200000, 6, '2024-09-05 11:50:26', '2024-09-09 10:52:08'),
(32, 1, 1, '300,000 đ', 300000, 300000, 6, '2024-09-05 11:50:26', '2024-09-09 10:52:08'),
(33, 1, 1, '500,000 đ', 500000, 500000, 6, '2024-09-05 11:50:26', '2024-09-09 10:52:09'),
(34, 1, 1, '1,000,000 đ', 1000000, 1000000, 6, '2024-09-05 11:50:26', '2024-09-09 10:52:09'),
(43, 2, 1, '20,000 đ', 20000, 20000, 5.5, '2024-09-05 11:52:04', '2024-09-09 10:52:10'),
(44, 2, 1, '30,000 đ', 30000, 30000, 5.5, '2024-09-05 11:52:04', '2024-09-09 10:52:10'),
(45, 2, 1, '50,000 đ', 50000, 50000, 5.5, '2024-09-05 11:52:04', '2024-09-09 10:52:11'),
(46, 2, 1, '100,000 đ', 100000, 100000, 5.5, '2024-09-05 11:52:04', '2024-09-09 10:52:11'),
(47, 2, 1, '200,000 đ', 200000, 200000, 5.5, '2024-09-05 11:52:04', '2024-09-09 10:52:12'),
(48, 2, 1, '300,000 đ', 300000, 300000, 5.5, '2024-09-05 11:52:04', '2024-09-09 10:52:12'),
(49, 2, 1, '500,000 đ', 500000, 500000, 5.5, '2024-09-05 11:52:04', '2024-09-09 10:52:13'),
(50, 2, 1, '1,000,000 đ', 1000000, 1000000, 5.5, '2024-09-05 11:52:04', '2024-09-09 10:52:14'),
(51, 3, 1, '20,000 đ', 20000, 20000, 9, '2024-09-05 11:52:26', '2024-09-09 10:52:18'),
(52, 3, 1, '30,000 đ', 30000, 30000, 9, '2024-09-05 11:52:26', '2024-09-09 10:52:18'),
(53, 3, 1, '50,000 đ', 50000, 50000, 9, '2024-09-05 11:52:26', '2024-09-09 10:52:15'),
(54, 3, 1, '100,000 đ', 100000, 100000, 9, '2024-09-05 11:52:26', '2024-09-09 10:52:15'),
(55, 3, 1, '200,000 đ', 200000, 200000, 9, '2024-09-05 11:52:26', '2024-09-09 10:52:16'),
(56, 3, 1, '300,000 đ', 300000, 300000, 9, '2024-09-05 11:52:26', '2024-09-09 10:52:16'),
(57, 3, 1, '500,000 đ', 500000, 500000, 9, '2024-09-05 11:52:26', '2024-09-09 10:52:17'),
(58, 3, 1, '1,000,000 đ', 1000000, 1000000, 9, '2024-09-05 11:52:26', '2024-09-09 10:52:17');

-- --------------------------------------------------------

--
-- Table structure for table `transfer`
--

CREATE TABLE `transfer` (
  `transfer_id` bigint(20) NOT NULL,
  `transfer_code` text NOT NULL,
  `transfer_user_from` bigint(20) NOT NULL,
  `transfer_cash` bigint(20) NOT NULL,
  `transfer_user_to` bigint(20) NOT NULL,
  `transfer_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` bigint(20) NOT NULL,
  `user_login` varchar(255) DEFAULT NULL,
  `user_fullname` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_cash` bigint(20) NOT NULL DEFAULT 0,
  `user_phone` varchar(255) DEFAULT NULL,
  `user_token` text NOT NULL,
  `user_rank` varchar(255) NOT NULL DEFAULT 'member',
  `user_warning` int(11) NOT NULL DEFAULT 0,
  `user_admin` int(11) NOT NULL DEFAULT 0,
  `user_ip` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_banned` int(11) NOT NULL DEFAULT 0,
  `user_banned_reason` varchar(255) DEFAULT NULL,
  `user_expire_time` varchar(255) NOT NULL DEFAULT '0',
  `user_rank_expire` varchar(255) NOT NULL DEFAULT '0',
  `user_invite_code` varchar(10) DEFAULT NULL,
  `user_invite_by` varchar(10) DEFAULT NULL,
  `expire_noti_transfer` bigint(20) NOT NULL DEFAULT 0,
  `webhook_transfer` varchar(255) DEFAULT NULL,
  `noti_email_login` bigint(20) NOT NULL DEFAULT 0,
  `user_last_buyCard` bigint(20) DEFAULT 0,
  `user_last_withdraw` bigint(20) NOT NULL DEFAULT 0,
  `user_last_transfer` bigint(20) NOT NULL DEFAULT 0,
  `user_last_email` bigint(20) NOT NULL DEFAULT 0,
  `user_verify_email` bigint(20) NOT NULL DEFAULT 0,
  `user_has_changePhone` bigint(20) NOT NULL DEFAULT 0,
  `user_has_changeFullname` bigint(20) NOT NULL DEFAULT 0,
  `user_2fa_code` varchar(255) DEFAULT NULL,
  `user_is_verify_email` bigint(20) NOT NULL DEFAULT 0,
  `user_is_verify_2fa` bigint(20) NOT NULL DEFAULT 0,
  `user_is_verify` bigint(20) NOT NULL DEFAULT 0,
  `user_last_topup` bigint(20) NOT NULL DEFAULT 0,
  `user_backup_rank_date` bigint(20) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user-log-ip`
--

CREATE TABLE `user-log-ip` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `user_ip` varchar(255) NOT NULL,
  `user_agent` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw`
--

CREATE TABLE `withdraw` (
  `wd_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `wd_code` varchar(255) NOT NULL,
  `wd_bank_name` varchar(255) NOT NULL,
  `wd_bank_owner` varchar(255) NOT NULL,
  `wd_number_account` varchar(255) NOT NULL,
  `wd_cash` bigint(20) NOT NULL,
  `wd_status` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `wd_api_order_code` varchar(255) DEFAULT NULL,
  `wd_api_status` varchar(255) DEFAULT NULL,
  `wd_api_message` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `wd_updated_api` timestamp NULL DEFAULT NULL,
  `wd_cron` tinyint(4) NOT NULL DEFAULT 0,
  `wd_description` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank-user`
--
ALTER TABLE `bank-user`
  ADD PRIMARY KEY (`bank-user_id`);

--
-- Indexes for table `buy-card-data`
--
ALTER TABLE `buy-card-data`
  ADD PRIMARY KEY (`buy-card-data_id`);

--
-- Indexes for table `buy-card-order`
--
ALTER TABLE `buy-card-order`
  ADD PRIMARY KEY (`buy-card-order_id`);

--
-- Indexes for table `card-data`
--
ALTER TABLE `card-data`
  ADD PRIMARY KEY (`card-data_id`);

--
-- Indexes for table `card-rare`
--
ALTER TABLE `card-rare`
  ADD PRIMARY KEY (`card-rare_id`);

--
-- Indexes for table `email_queue`
--
ALTER TABLE `email_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `money`
--
ALTER TABLE `money`
  ADD PRIMARY KEY (`money_id`);

--
-- Indexes for table `partner`
--
ALTER TABLE `partner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rank`
--
ALTER TABLE `rank`
  ADD PRIMARY KEY (`rank_id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `telco-rare`
--
ALTER TABLE `telco-rare`
  ADD PRIMARY KEY (`telco-rare_id`);

--
-- Indexes for table `top`
--
ALTER TABLE `top`
  ADD PRIMARY KEY (`top_id`);

--
-- Indexes for table `topup`
--
ALTER TABLE `topup`
  ADD PRIMARY KEY (`topup_id`);

--
-- Indexes for table `topup-order`
--
ALTER TABLE `topup-order`
  ADD PRIMARY KEY (`topup-order_id`),
  ADD KEY `topup_id` (`topup_id`);

--
-- Indexes for table `topup-rare`
--
ALTER TABLE `topup-rare`
  ADD PRIMARY KEY (`topup-rare_id`),
  ADD KEY `topup_id` (`topup_id`);

--
-- Indexes for table `transfer`
--
ALTER TABLE `transfer`
  ADD PRIMARY KEY (`transfer_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user-log-ip`
--
ALTER TABLE `user-log-ip`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw`
--
ALTER TABLE `withdraw`
  ADD PRIMARY KEY (`wd_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank-user`
--
ALTER TABLE `bank-user`
  MODIFY `bank-user_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `buy-card-data`
--
ALTER TABLE `buy-card-data`
  MODIFY `buy-card-data_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `buy-card-order`
--
ALTER TABLE `buy-card-order`
  MODIFY `buy-card-order_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `card-data`
--
ALTER TABLE `card-data`
  MODIFY `card-data_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `card-rare`
--
ALTER TABLE `card-rare`
  MODIFY `card-rare_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `email_queue`
--
ALTER TABLE `email_queue`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `money`
--
ALTER TABLE `money`
  MODIFY `money_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `partner`
--
ALTER TABLE `partner`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rank`
--
ALTER TABLE `rank`
  MODIFY `rank_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `telco-rare`
--
ALTER TABLE `telco-rare`
  MODIFY `telco-rare_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `top`
--
ALTER TABLE `top`
  MODIFY `top_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `topup`
--
ALTER TABLE `topup`
  MODIFY `topup_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `topup-order`
--
ALTER TABLE `topup-order`
  MODIFY `topup-order_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `topup-rare`
--
ALTER TABLE `topup-rare`
  MODIFY `topup-rare_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `transfer`
--
ALTER TABLE `transfer`
  MODIFY `transfer_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user-log-ip`
--
ALTER TABLE `user-log-ip`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw`
--
ALTER TABLE `withdraw`
  MODIFY `wd_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `topup-order`
--
ALTER TABLE `topup-order`
  ADD CONSTRAINT `topup-order_ibfk_1` FOREIGN KEY (`topup_id`) REFERENCES `topup` (`topup_id`);

--
-- Constraints for table `topup-rare`
--
ALTER TABLE `topup-rare`
  ADD CONSTRAINT `topup-rare_ibfk_1` FOREIGN KEY (`topup_id`) REFERENCES `topup` (`topup_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
