
--
-- Database: `sms`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` varchar(20) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category`, `price`, `stock`, `created_at`) VALUES
('PID001', 'Notebook', 'book', 2.50, 2, '2026-03-01 17:39:28'),
('PID002', 'blue Pen', 'pen', 1.00, 195, '2026-03-01 17:39:28'),
('PID003', 'laravel', 'Programming', 5.00, 4, '2026-03-02 16:23:30'),
('PID004', 'oracle databse', 'Technology', 6.00, 40, '2026-03-02 16:25:14'),
('PID005', 'basic ai', 'Programming', 7.50, 48, '2026-03-02 16:25:42'),
('PID006', 'A4 paper', 'tools', 0.10, 145, '2026-03-02 16:29:55'),
('PID007', 'book', 'books', 2.00, 42, '2026-03-05 10:52:20'),
('PID008', 'python', 'Programming', 3.00, 80, '2026-03-08 14:35:40'),
('PRDLIB009', 'UX/UI design ', 'books', 2.00, 0, '2026-03-15 18:33:03'),
('PRDLIB010', 'UX/UI design ', 'books', 2.00, 0, '2026-03-15 18:33:28');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `sale_id` varchar(20) NOT NULL,
  `student_id` varchar(15) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `sale_date` datetime NOT NULL,
  `sold_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`sale_id`, `student_id`, `product_id`, `quantity`, `total_price`, `sale_date`, `sold_by`) VALUES
('SAL001', 'STDLIB001', 'PID001', 2, 5.00, '2026-03-02 19:06:44', 2),
('SAL002', 'STDLIB002', 'PID002', 5, 5.00, '2026-03-02 19:07:28', 2),
('SAL003', 'STDLIB002', 'PID001', 3, 7.50, '2026-03-02 19:11:10', 2),
('SAL004', 'STDLIB001', 'PID003', 2, 10.00, '2026-03-02 19:26:54', 2),
('SAL005', 'STDLIB002', 'PID001', 1, 2.50, '2026-03-02 19:28:19', 2),
('SAL006', 'STDLIB001', 'PID006', 5, 1.00, '2026-03-03 17:28:53', 2),
('SAL007', 'STDLIB003', 'PID003', 2, 10.00, '2026-03-05 08:06:19', 2),
('SAL008', 'STDLIB005', 'PID003', 2, 10.00, '2026-03-05 08:36:07', 2);

-- --------------------------------------------------------

--
-- Table structure for table `sales_items`
--

CREATE TABLE `sales_items` (
  `item_id` varchar(20) NOT NULL,
  `sale_id` varchar(20) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` varchar(15) NOT NULL,
  `student_name` varchar(150) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `shift` enum('Morning','afternoon','Evening') NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `student_name`, `phone`, `department`, `semester`, `shift`, `gender`, `created_at`) VALUES
('STDLIB001', 'kamaal warsame ', '0907808067', 'computer science', 'Semester 8', 'Morning', 'Male', '2026-03-01 18:58:39'),
('STDLIB002', 'abdiwahid ali', '0907500751', 'Civil Engineering', 'Semester 6', 'afternoon', 'Male', '2026-03-01 18:59:25'),
('STDLIB003', 'warfaa cilmi', '0907223344', 'Civil Engineering', 'Semester 4', 'afternoon', 'Male', '2026-03-03 16:55:22'),
('STDLIB004', 'abdi warfaa', '6994242', 'Computer Science', 'Semester 4', 'Morning', 'Male', '2026-03-03 18:30:45'),
('STDLIB005', 'xasan waaberi', '0907667777', 'Nursing', 'Semester 6', 'afternoon', 'Male', '2026-03-03 18:41:56'),
('STDLIB006', 'abdiwahid', '907808067', 'Bussiness Administrartion', 'Semester 3', 'Evening', 'Male', '2026-03-05 12:50:46'),
('STDLIB007', 'abdikani wiish', '0905112200', 'Medical Lab', 'Semester 3', 'Morning', 'Male', '2026-03-15 18:24:51');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL DEFAULT 1,
  `system_name` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `low_stock_limit` int(11) DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `system_name`, `email`, `phone`, `currency`, `low_stock_limit`) VALUES
(1, 'CityCot Library', 'citycot.edu.so@gmail.com', '0907000000', 'USD', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `role` enum('admin','staff','special member') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `username`, `email`, `password`, `photo`, `role`, `created_at`) VALUES
(1, 'admin', 'admin', 'admin@mail.com', '$2y$10$AH3D5mRAP6hed4ri0i7aAe8kSH7cMvhb34m7BGkRMrk4ER1ehbli2', '', 'admin', '2026-03-01 17:35:48'),
(2, 'Staff', 'staff', 'staff@mail.com', '$2y$10$Y1VeTBT58EKABQJi4aqDUuKWMOd4J0Zo6ZaNSBBJll0HsLmv4wdQ2', '', 'staff', '2026-03-01 17:35:48'),
(3, 'jama', 'jama', 'jama@mail.com', '$2y$10$.WnYuSblJGBOfmBGrkcUt.h/oIO34LgNVe3XetlqKnupgEhdJ/pNq', '', 'special member', '0000-00-00 00:00:00'),
(4, 'kamaal', 'kamaal', 'kamaalwarsame0@gmail.com', '$2y$10$yAaN1FKNrGvZMua2l/KvsOYqIBHk76uwiuG8cgMbXCCPuO93pCIlC', '', 'admin', '2026-03-05 17:51:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `fk_sales_student` (`student_id`),
  ADD KEY `fk_sales_product` (`product_id`),
  ADD KEY `fk_sales_user` (`sold_by`);

--
-- Indexes for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sales_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sales_user` FOREIGN KEY (`sold_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD CONSTRAINT `sales_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`sale_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
