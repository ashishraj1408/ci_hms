-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2022 at 05:42 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `hms_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_available_rooms` (IN `o_room_type` VARCHAR(50), IN `o_checkin_date` VARCHAR(50), IN `o_checkout_date` VARCHAR(50))   BEGIN
SELECT * FROM `room` WHERE room_type=o_room_type AND NOT EXISTS (
SELECT room_id FROM reservation WHERE reservation.room_id=room.room_id AND checkout_date >= o_checkin_date AND checkin_date <= o_checkout_date
UNION ALL
SELECT room_id FROM room_sales WHERE room_sales.room_id=room.room_id AND checkout_date >= o_checkin_date AND checkin_date <= o_checkout_date
);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_customers` (IN `today_date` VARCHAR(50))   BEGIN
SELECT * FROM `room_sales` NATURAL JOIN `customer` WHERE checkout_date >= today_date AND checkin_date <= today_date;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `todays_service_count` (IN `today_date` VARCHAR(50))   BEGIN
SELECT count(*) as amount, "laundry" as type FROM laundry_service WHERE laundry_date=today_date UNION ALL SELECT count(*) as amount, "massage" as type FROM massage_service WHERE massage_date=today_date UNION ALL SELECT count(*) as amount, "roomservice" as type FROM get_roomservice WHERE roomservice_date=today_date UNION ALL SELECT count(*) as amount, "medicalservice" as type FROM get_medicalservice WHERE medicalservice_date=today_date UNION ALL SELECT count(*) as amount, "sport" as type FROM do_sport WHERE dosport_date=today_date
UNION ALL SELECT count(*) as amount, "restaurant" as type FROM restaurant_booking WHERE book_date=today_date;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `customer_firstname` varchar(50) NOT NULL,
  `customer_lastname` varchar(50) NOT NULL,
  `customer_TCno` varchar(11) NOT NULL,
  `customer_city` varchar(50) DEFAULT NULL,
  `customer_country` varchar(50) DEFAULT NULL,
  `customer_telephone` varchar(50) NOT NULL,
  `customer_email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_firstname`, `customer_lastname`, `customer_TCno`, `customer_city`, `customer_country`, `customer_telephone`, `customer_email`) VALUES
(1, 'John', 'Cena', '12345', 'Easy', 'UK', '0123456789', 'john@mail.com'),
(2, 'Under ', 'Taker', '1234', 'Sample', 'Philippines', '09654789123', 'gwilson@mail.com'),
(3, 'Test', 'Test', '4321', 'Test', 'Test', '09123564789', 'test@mail.com');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(50) NOT NULL,
  `department_budget` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_name`, `department_budget`) VALUES
(1, 'Management', 10000000),
(2, 'Maintenance', 20000),
(3, 'Security', 25000),
(4, 'Medical', 40000);

-- --------------------------------------------------------

--
-- Table structure for table `do_sport`
--

CREATE TABLE `do_sport` (
  `customer_id` int(11) NOT NULL,
  `sportfacility_id` int(11) NOT NULL,
  `dosport_date` varchar(50) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `dosport_details` text DEFAULT NULL,
  `dosport_price` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `do_sport`
--

INSERT INTO `do_sport` (`customer_id`, `sportfacility_id`, `dosport_date`, `employee_id`, `dosport_details`, `dosport_price`) VALUES
(3, 2, '2022-06-29', 1, 'Sample Service', 500);

--
-- Triggers `do_sport`
--
DELIMITER $$
CREATE TRIGGER `after_insert_sport_service` AFTER INSERT ON `do_sport` FOR EACH ROW BEGIN
    UPDATE room_sales SET room_sales.total_service_price = room_sales.total_service_price + NEW.dosport_price WHERE room_sales.customer_id = NEW.customer_id AND room_sales.checkin_date <= NEW.dosport_date AND room_sales.checkout_date >= NEW.dosport_date;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_delete_sport_service` BEFORE DELETE ON `do_sport` FOR EACH ROW BEGIN
    UPDATE room_sales SET room_sales.total_service_price = room_sales.total_service_price - OLD.dosport_price WHERE room_sales.customer_id = OLD.customer_id AND room_sales.checkin_date <= OLD.dosport_date AND room_sales.checkout_date >= OLD.dosport_date;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `employee_username` varchar(50) NOT NULL,
  `employee_password` varchar(50) CHARACTER SET utf32 NOT NULL,
  `employee_firstname` varchar(50) NOT NULL,
  `employee_lastname` varchar(50) NOT NULL,
  `employee_telephone` varchar(50) DEFAULT NULL,
  `employee_email` varchar(50) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `employee_type` varchar(50) NOT NULL,
  `employee_salary` float DEFAULT NULL,
  `employee_hiring_date` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `employee_username`, `employee_password`, `employee_firstname`, `employee_lastname`, `employee_telephone`, `employee_email`, `department_id`, `employee_type`, `employee_salary`, `employee_hiring_date`) VALUES
(1, 'admin', 'admin123', 'admin', '', '0123456789', 'admin@gmail.com', 1, 'Admin', 10000000, '2024-06-23'),
(2, 'demo', 'demo123', 'Demo', '', '09321485987', 'demo@gmail.com', 4, 'Staff', 20000, '2022-05-31');

-- --------------------------------------------------------

--
-- Table structure for table `get_medicalservice`
--

CREATE TABLE `get_medicalservice` (
  `customer_id` int(11) NOT NULL,
  `medicalservice_id` int(11) NOT NULL,
  `medicalservice_date` varchar(50) CHARACTER SET utf8 NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `getmedicalservice_details` text CHARACTER SET utf8 DEFAULT NULL,
  `medicalservice_price` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Triggers `get_medicalservice`
--
DELIMITER $$
CREATE TRIGGER `after_delete_medical_service` BEFORE DELETE ON `get_medicalservice` FOR EACH ROW BEGIN
    UPDATE room_sales SET room_sales.total_service_price = room_sales.total_service_price - OLD.medicalservice_price WHERE room_sales.customer_id = OLD.customer_id AND room_sales.checkin_date <= OLD.medicalservice_date AND room_sales.checkout_date >= OLD.medicalservice_date;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_insert_medical_service` AFTER INSERT ON `get_medicalservice` FOR EACH ROW BEGIN
    UPDATE room_sales SET room_sales.total_service_price = room_sales.total_service_price + NEW.medicalservice_price WHERE room_sales.customer_id = NEW.customer_id AND room_sales.checkin_date <= NEW.medicalservice_date AND room_sales.checkout_date >= NEW.medicalservice_date;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `get_roomservice`
--

CREATE TABLE `get_roomservice` (
  `customer_id` int(11) NOT NULL,
  `roomservice_id` int(11) NOT NULL,
  `roomservice_date` varchar(50) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `getroomservice_details` text DEFAULT NULL,
  `roomservice_price` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `get_roomservice`
--
DELIMITER $$
CREATE TRIGGER `after_insert_room_service` AFTER INSERT ON `get_roomservice` FOR EACH ROW BEGIN
    UPDATE room_sales SET room_sales.total_service_price = room_sales.total_service_price + NEW.roomservice_price WHERE room_sales.customer_id = NEW.customer_id AND room_sales.checkin_date <= NEW.roomservice_date AND room_sales.checkout_date >= NEW.roomservice_date;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_delete_room_service` BEFORE DELETE ON `get_roomservice` FOR EACH ROW BEGIN
    UPDATE room_sales SET room_sales.total_service_price = room_sales.total_service_price - OLD.roomservice_price WHERE room_sales.customer_id = OLD.customer_id AND room_sales.checkin_date <= OLD.roomservice_date AND room_sales.checkout_date >= OLD.roomservice_date;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `laundry`
--

CREATE TABLE `laundry` (
  `laundry_id` int(11) NOT NULL,
  `laundry_open_time` varchar(50) DEFAULT NULL,
  `laundry_close_time` varchar(50) DEFAULT NULL,
  `laundry_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `laundry_service`
--

CREATE TABLE `laundry_service` (
  `customer_id` int(11) NOT NULL,
  `laundry_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `laundry_date` varchar(50) DEFAULT NULL,
  `laundry_amount` int(11) DEFAULT NULL,
  `laundry_price` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Triggers `laundry_service`
--
DELIMITER $$
CREATE TRIGGER `after_insert_laundry_service` AFTER INSERT ON `laundry_service` FOR EACH ROW BEGIN
    UPDATE room_sales SET room_sales.total_service_price = room_sales.total_service_price + NEW.laundry_price WHERE room_sales.customer_id = NEW.customer_id AND room_sales.checkin_date <= NEW.laundry_date AND room_sales.checkout_date >= NEW.laundry_date;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_delete_laundry_service` BEFORE DELETE ON `laundry_service` FOR EACH ROW BEGIN
    UPDATE room_sales SET room_sales.total_service_price = room_sales.total_service_price - OLD.laundry_price WHERE room_sales.customer_id = OLD.customer_id AND room_sales.checkin_date <= OLD.laundry_date AND room_sales.checkout_date >= OLD.laundry_date;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `massage_room`
--

CREATE TABLE `massage_room` (
  `massageroom_id` int(11) NOT NULL,
  `massageroom_open_time` varchar(10) DEFAULT NULL,
  `massageroom_close_time` varchar(10) DEFAULT NULL,
  `massageroom_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `massage_room`
--

INSERT INTO `massage_room` (`massageroom_id`, `massageroom_open_time`, `massageroom_close_time`, `massageroom_details`) VALUES
(1, '11:00', '18:00', 'Sample Spa');

-- --------------------------------------------------------

--
-- Table structure for table `massage_service`
--

CREATE TABLE `massage_service` (
  `customer_id` int(11) NOT NULL,
  `massageroom_id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `massage_date` varchar(50) DEFAULT NULL,
  `massage_details` text DEFAULT NULL,
  `massage_price` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `massage_service`
--

INSERT INTO `massage_service` (`customer_id`, `massageroom_id`, `employee_id`, `massage_date`, `massage_details`, `massage_price`) VALUES
(3, 1, 1, '2022-06-27', 'Sample Service', 1200);

--
-- Triggers `massage_service`
--
DELIMITER $$
CREATE TRIGGER `after_insert_massage_service` AFTER INSERT ON `massage_service` FOR EACH ROW BEGIN
    UPDATE room_sales SET room_sales.total_service_price = room_sales.total_service_price + NEW.massage_price WHERE room_sales.customer_id = NEW.customer_id AND room_sales.checkin_date <= NEW.massage_date AND room_sales.checkout_date >= NEW.massage_date;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_delete_massage_service` BEFORE DELETE ON `massage_service` FOR EACH ROW BEGIN
    UPDATE room_sales SET room_sales.total_service_price = room_sales.total_service_price - OLD.massage_price WHERE room_sales.customer_id = OLD.customer_id AND room_sales.checkin_date <= OLD.massage_date AND room_sales.checkout_date >= OLD.massage_date;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `medical_service`
--

CREATE TABLE `medical_service` (
  `medicalservice_id` int(11) NOT NULL,
  `medicalservice_open_time` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `medicalservice_close_time` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `medicalservice_details` text CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `medical_service`
--

INSERT INTO `medical_service` (`medicalservice_id`, `medicalservice_open_time`, `medicalservice_close_time`, `medicalservice_details`) VALUES
(1, '06:00', '03:00', 'Sample Medical Service'),
(2, '02:00', '12:00', 'Sample Medical Service 2');

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `customer_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `checkin_date` varchar(50) NOT NULL,
  `checkout_date` varchar(50) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `reservation_date` varchar(50) DEFAULT NULL,
  `reservation_price` float DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`customer_id`, `room_id`, `checkin_date`, `checkout_date`, `employee_id`, `reservation_date`, `reservation_price`, `status`) VALUES
(1, 5001, '2022-06-29', '2022-07-04', 1, NULL, NULL, NULL),
(2, 5003, '2022-07-04', '2022-07-08', 1, NULL, NULL, NULL),
(3, 5002, '2022-06-26', '2022-07-09', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant`
--

CREATE TABLE `restaurant` (
  `restaurant_name` varchar(50) NOT NULL,
  `restaurant_open_time` varchar(10) DEFAULT NULL,
  `restaurant_close_time` varchar(10) DEFAULT NULL,
  `restaurant_details` text DEFAULT NULL,
  `table_count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `restaurant`
--

INSERT INTO `restaurant` (`restaurant_name`, `restaurant_open_time`, `restaurant_close_time`, `restaurant_details`, `table_count`) VALUES
('Restaurant 101', '06:00', '00:00', 'Sample Restaurant Only', 15),
('Restaurant 102', '06:00', '22:00', 'Sample Restaurant 2', 25);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_booking`
--

CREATE TABLE `restaurant_booking` (
  `restaurant_name` varchar(50) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `book_date` varchar(50) NOT NULL,
  `table_number` int(11) DEFAULT NULL,
  `book_price` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `restaurant_booking`
--

INSERT INTO `restaurant_booking` (`restaurant_name`, `customer_id`, `book_date`, `table_number`, `book_price`) VALUES
('Restaurant 101', 1, '2022-07-01', 1, 3500);

--
-- Triggers `restaurant_booking`
--
DELIMITER $$
CREATE TRIGGER `after_insert_restaurant_service` AFTER INSERT ON `restaurant_booking` FOR EACH ROW BEGIN
    UPDATE room_sales SET room_sales.total_service_price = room_sales.total_service_price + NEW.book_price WHERE room_sales.customer_id = NEW.customer_id AND room_sales.checkin_date <= NEW.book_date AND room_sales.checkout_date >= NEW.book_date;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_delete_restaurant_service` BEFORE DELETE ON `restaurant_booking` FOR EACH ROW BEGIN
    UPDATE room_sales SET room_sales.total_service_price = room_sales.total_service_price - OLD.book_price WHERE room_sales.customer_id = OLD.customer_id AND room_sales.checkin_date <= OLD.book_date AND room_sales.checkout_date >= OLD.book_date;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_id` int(11) NOT NULL,
  `room_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `room_type`) VALUES
(5001, 'Deluxe'),
(5002, 'Deluxe'),
(5003, 'Deluxe'),
(5004, 'Deluxe'),
(5005, 'Deluxe'),
(5006, 'Deluxe'),
(5007, 'Deluxe'),
(5008, 'Deluxe'),
(5009, 'Deluxe'),
(5010, 'Deluxe'),
(5011, 'Deluxe'),
(5012, 'Deluxe'),
(5013, 'Deluxe'),
(5014, 'Deluxe'),
(5015, 'Deluxe'),
(5016, 'Deluxe'),
(5017, 'Deluxe'),
(5018, 'Deluxe'),
(5019, 'Deluxe'),
(5020, 'Deluxe');

--
-- Triggers `room`
--
DELIMITER $$
CREATE TRIGGER `after_insert_room` AFTER INSERT ON `room` FOR EACH ROW BEGIN
    UPDATE room_type SET room_type.room_quantity =room_type.room_quantity + 1 WHERE room_type.room_type = NEW.room_type;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_delete_room` BEFORE DELETE ON `room` FOR EACH ROW BEGIN
    UPDATE room_type SET room_type.room_quantity =room_type.room_quantity - 1 WHERE room_type.room_type = OLD.room_type;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `room_sales`
--

CREATE TABLE `room_sales` (
  `customer_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `checkin_date` varchar(50) NOT NULL,
  `checkout_date` varchar(50) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `room_sales_price` float DEFAULT NULL,
  `total_service_price` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `room_sales`
--

INSERT INTO `room_sales` (`customer_id`, `room_id`, `checkin_date`, `checkout_date`, `employee_id`, `room_sales_price`, `total_service_price`) VALUES
(1, 5001, '2022-06-29', '2022-07-04', 1, 10000, 3500),
(2, 5003, '2022-07-04', '2022-07-08', 1, 10000, 0),
(3, 5002, '2022-06-29', '2022-07-09', 1, 10000, 500);

-- --------------------------------------------------------

--
-- Table structure for table `room_service`
--

CREATE TABLE `room_service` (
  `roomservice_id` int(11) NOT NULL,
  `roomservice_open_time` varchar(50) DEFAULT NULL,
  `roomservice_close_time` varchar(50) DEFAULT NULL,
  `roomservice_floor` varchar(50) DEFAULT NULL,
  `roomservice_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `room_type`
--

CREATE TABLE `room_type` (
  `room_type` varchar(50) NOT NULL,
  `room_price` int(11) DEFAULT NULL,
  `room_details` text DEFAULT NULL,
  `room_quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `room_type`
--

INSERT INTO `room_type` (`room_type`, `room_price`, `room_details`, `room_quantity`) VALUES
('Deluxe', 10000, 'Deluxe Room', NULL),
('Double', 5000, 'Double Bed Room', NULL),
('Single', 3500, 'Single Bed Room', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sport_facilities`
--

CREATE TABLE `sport_facilities` (
  `sportfacility_id` int(11) NOT NULL,
  `sportfacility_open_time` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `sportfacility_close_time` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `sportfacility_details` text CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sport_facilities`
--

INSERT INTO `sport_facilities` (`sportfacility_id`, `sportfacility_open_time`, `sportfacility_close_time`, `sportfacility_details`) VALUES
(1, '10:00', '18:00', 'Basketball Court'),
(2, '05:00', '23:00', 'Swimming Pool'),
(3, '05:00', '22:00', 'Badminton Court');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `do_sport`
--
ALTER TABLE `do_sport`
  ADD PRIMARY KEY (`customer_id`,`sportfacility_id`,`dosport_date`),
  ADD KEY `customer` (`customer_id`),
  ADD KEY `sport_facility` (`sportfacility_id`),
  ADD KEY `employee` (`employee_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `username` (`employee_username`),
  ADD UNIQUE KEY `email` (`employee_email`),
  ADD KEY `department` (`department_id`),
  ADD KEY `login` (`employee_username`,`employee_password`);

--
-- Indexes for table `get_medicalservice`
--
ALTER TABLE `get_medicalservice`
  ADD PRIMARY KEY (`customer_id`,`medicalservice_id`,`medicalservice_date`),
  ADD KEY `customer` (`customer_id`),
  ADD KEY `medical_service` (`medicalservice_id`),
  ADD KEY `employee` (`employee_id`);

--
-- Indexes for table `get_roomservice`
--
ALTER TABLE `get_roomservice`
  ADD PRIMARY KEY (`customer_id`,`roomservice_id`,`roomservice_date`),
  ADD KEY `customer` (`customer_id`),
  ADD KEY `room_service` (`roomservice_id`),
  ADD KEY `employee` (`employee_id`);

--
-- Indexes for table `laundry`
--
ALTER TABLE `laundry`
  ADD PRIMARY KEY (`laundry_id`);

--
-- Indexes for table `laundry_service`
--
ALTER TABLE `laundry_service`
  ADD PRIMARY KEY (`customer_id`,`laundry_id`),
  ADD KEY `customer` (`customer_id`),
  ADD KEY `laundry` (`laundry_id`),
  ADD KEY `employee` (`employee_id`);

--
-- Indexes for table `massage_room`
--
ALTER TABLE `massage_room`
  ADD PRIMARY KEY (`massageroom_id`);

--
-- Indexes for table `massage_service`
--
ALTER TABLE `massage_service`
  ADD PRIMARY KEY (`customer_id`,`massageroom_id`),
  ADD KEY `customer` (`customer_id`),
  ADD KEY `massage` (`massageroom_id`),
  ADD KEY `employee` (`employee_id`);

--
-- Indexes for table `medical_service`
--
ALTER TABLE `medical_service`
  ADD PRIMARY KEY (`medicalservice_id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`customer_id`,`room_id`,`checkin_date`),
  ADD KEY `customer` (`customer_id`),
  ADD KEY `employee` (`employee_id`),
  ADD KEY `room` (`room_id`),
  ADD KEY `availability` (`room_id`,`checkin_date`,`checkout_date`);

--
-- Indexes for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`restaurant_name`);

--
-- Indexes for table `restaurant_booking`
--
ALTER TABLE `restaurant_booking`
  ADD PRIMARY KEY (`restaurant_name`,`customer_id`,`book_date`),
  ADD KEY `restaurant` (`restaurant_name`),
  ADD KEY `customer` (`customer_id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `room_type` (`room_type`);

--
-- Indexes for table `room_sales`
--
ALTER TABLE `room_sales`
  ADD PRIMARY KEY (`customer_id`,`room_id`,`checkin_date`),
  ADD KEY `customer` (`customer_id`),
  ADD KEY `employee` (`employee_id`),
  ADD KEY `room` (`room_id`),
  ADD KEY `availability` (`room_id`,`checkin_date`,`checkout_date`);

--
-- Indexes for table `room_service`
--
ALTER TABLE `room_service`
  ADD PRIMARY KEY (`roomservice_id`);

--
-- Indexes for table `room_type`
--
ALTER TABLE `room_type`
  ADD PRIMARY KEY (`room_type`);

--
-- Indexes for table `sport_facilities`
--
ALTER TABLE `sport_facilities`
  ADD PRIMARY KEY (`sportfacility_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `laundry`
--
ALTER TABLE `laundry`
  MODIFY `laundry_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `massage_room`
--
ALTER TABLE `massage_room`
  MODIFY `massageroom_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `medical_service`
--
ALTER TABLE `medical_service`
  MODIFY `medicalservice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5051;

--
-- AUTO_INCREMENT for table `room_service`
--
ALTER TABLE `room_service`
  MODIFY `roomservice_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sport_facilities`
--
ALTER TABLE `sport_facilities`
  MODIFY `sportfacility_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `do_sport`
--
ALTER TABLE `do_sport`
  ADD CONSTRAINT `do_sport_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `do_sport_ibfk_2` FOREIGN KEY (`sportfacility_id`) REFERENCES `sport_facilities` (`sportfacility_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `do_sport_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `get_medicalservice`
--
ALTER TABLE `get_medicalservice`
  ADD CONSTRAINT `get_medicalservice_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `get_medicalservice_ibfk_2` FOREIGN KEY (`medicalservice_id`) REFERENCES `medical_service` (`medicalservice_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `get_medicalservice_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `get_roomservice`
--
ALTER TABLE `get_roomservice`
  ADD CONSTRAINT `get_roomservice_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `get_roomservice_ibfk_2` FOREIGN KEY (`roomservice_id`) REFERENCES `room_service` (`roomservice_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `get_roomservice_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `laundry_service`
--
ALTER TABLE `laundry_service`
  ADD CONSTRAINT `laundry_service_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `laundry_service_ibfk_2` FOREIGN KEY (`laundry_id`) REFERENCES `laundry` (`laundry_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `laundry_service_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `massage_service`
--
ALTER TABLE `massage_service`
  ADD CONSTRAINT `massage_service_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `massage_service_ibfk_2` FOREIGN KEY (`massageroom_id`) REFERENCES `massage_room` (`massageroom_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `massage_service_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `restaurant_booking`
--
ALTER TABLE `restaurant_booking`
  ADD CONSTRAINT `restaurant_booking_ibfk_1` FOREIGN KEY (`restaurant_name`) REFERENCES `restaurant` (`restaurant_name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `restaurant_booking_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`room_type`) REFERENCES `room_type` (`room_type`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room_sales`
--
ALTER TABLE `room_sales`
  ADD CONSTRAINT `room_sales_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `room_sales_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `room_sales_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
