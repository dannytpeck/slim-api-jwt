CREATE TABLE IF NOT EXISTS `employee` (
  `employee_id` int(11) NOT NULL,
  `emp_name` varchar(100) NOT NULL,
  `emp_contact` varchar(100) NOT NULL,
  `emp_role` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
 
 
INSERT INTO `employee` (`employee_id`, `emp_name`, `emp_contact`, `emp_role`) VALUES
(1, 'Danny Peck', '6025551234', 'Scrumlord'),
(2, 'Dan Bailey', '7771230981', 'Sys Admin'),
(3, 'Josh Braun', '5555533333', 'UI/UX'),
(4, 'Taylor Hoss', '1230991342', 'DBA');