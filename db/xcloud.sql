-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2014 at 08:50 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `xcloud`
--

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `memo` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`, `memo`) VALUES
(1, '人力资源部', '阿是的萨尔是的噶'),
(2, '维修部', '观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏鸽撒个观赏'),
(3, '售后服务部', '嘎嘎阿和神的'),
(13, '销售部', '好哈干是个'),
(14, '生产部', '少个奇偶'),
(15, '管理部', '件发生日');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_img` varchar(50) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `virtual_machine` varchar(30) NOT NULL,
  `department` varchar(40) NOT NULL,
  `machine_id` varchar(50) NOT NULL,
  `appoint_machine` varchar(40) NOT NULL,
  `instant_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `user_img`, `full_name`, `virtual_machine`, `department`, `machine_id`, `appoint_machine`, `instant_id`) VALUES
(2, 'zhangsan', '0ae23ff09b252cb2b3c847d5a3069deb', '', '张三', 'Window 7 - Clean', '维修部', 'bef51907-2f51-400f-b802-ad08e2e5a1c2', '', ''),
(3, 'lisi', 'd41d8cd98f00b204e9800998ecf8427e', '', '李四', 'Window 7 - Clean', '人力资源部', 'bef51907-2f51-400f-b802-ad08e2e5a1c2', 'Window 7 - Clean-lisi', '65c39aac-8b71-4b14-b42a-8373a44e0e59'),
(11, 'admin', '21232f297a57a5a743894a0e4a801fc3', '', 'admin', 'Fedora20', '管理部', '8d3db62e-b13a-49a4-8c67-ec9719715e19', '', ''),
(12, 'liuliu', '332a0eb2bf3c6ce2c13b81384888faf8', '', '刘六', 'Window 7 - Clean', '维修部', 'bef51907-2f51-400f-b802-ad08e2e5a1c2', '', ''),
(22, 'wangwu', '9f001e4166cf26bfbdd3b4f67d9ef617', '', '王五', 'Fedora20', '销售部', '8d3db62e-b13a-49a4-8c67-ec9719715e19', 'Fedora20-wangwu', '3d628461-8316-4415-b7ad-e190f8fcffcd'),
(23, 'xiaoqiang', 'a2ffa5c9be07488bbb04a3a47d3c5f6a', '', '小强', 'cirros', '生产部', '5d8105b2-39a8-4825-ba2e-a8fc5a357b97', 'cirros-xiaoqiang', '362dadd9-6536-4ce3-95eb-bca2d7f60809'),
(24, 'liuxing', '5ba3d8f04ee981992f75862d79a28019', '', '刘星', 'Window 7 - Clean', '人力资源部', 'bef51907-2f51-400f-b802-ad08e2e5a1c2', 'Window 7 - Clean-liuxing', 'e75f8631-84ce-43db-b652-4ef39f13c381');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
