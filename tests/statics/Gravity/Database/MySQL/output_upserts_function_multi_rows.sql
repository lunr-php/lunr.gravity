INSERT INTO `database`.`table` (`identifier`, `language`, `content`) VALUES (COALESCE(`param1`,`param2`,?),?,?) ON DUPLICATE KEY UPDATE `content`=?;
