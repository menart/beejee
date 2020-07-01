CREATE DATABASE `db_task`
/*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */
/*!80016 DEFAULT ENCRYPTION='N' */;

DELIMITER $$
USE `db_task`$$
CREATE TABLE `tTask` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `context` varchar(1000) DEFAULT NULL,
  `status` int DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tTaskId_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE DEFINER=`root`@`localhost` TRIGGER `tTask_before_UPDATE` BEFORE UPDATE ON `tTask` FOR EACH ROW BEGIN
    if old.context != new.context then set new.status = new.status | 2;
    end if;
END$$
DELIMITER ;

INSERT INTO `db_task`.`tTask` (`user`, `email`, `context`) VALUES ('user1', 'user1@test.com', 'context task #1');
INSERT INTO `db_task`.`tTask` (`user`, `email`, `context`) VALUES ('user2', 'user2@test.com', 'context task #2');
INSERT INTO `db_task`.`tTask` (`user`, `email`, `context`) VALUES ('user3', 'user3@test.com', 'context task #3');
