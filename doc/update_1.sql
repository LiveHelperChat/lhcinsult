ALTER TABLE `lhc_insult`
ADD `terminated` tinyint(1) NOT NULL DEFAULT '0',
ADD `ctime` bigint(20) NOT NULL AFTER `terminated`,
COMMENT='';

ALTER TABLE `lhc_insult`
ADD INDEX `terminated` (`terminated`),
ADD INDEX `ctime` (`ctime`);

UPDATE `lhc_insult`
INNER JOIN `lh_msg` ON `lhc_insult`.`msg_id` = `lh_msg`.`id`
SET `lhc_insult`.`ctime` = `lh_msg`.`time`;

UPDATE `lhc_insult`
INNER JOIN `lh_msg` ON `lhc_insult`.`msg_id` = `lh_msg`.`id`
SET `lhc_insult`.`terminated` = 1
WHERE `lh_msg`.`meta_msg` LIKE ('%This chat has been terminated%');