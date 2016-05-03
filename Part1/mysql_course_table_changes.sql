# Add the new columns
ALTER TABLE `courses` 
ADD `campus` VARCHAR(5) NOT NULL AFTER `section_num`, 
ADD `location` VARCHAR(30) NOT NULL AFTER `campus`, 
ADD `start_time` TIME NOT NULL AFTER `location`, 
ADD `end_time` TIME NOT NULL AFTER `start_time`, 
ADD `days` VARCHAR(30) NOT NULL AFTER `end_time`;

# Update new columns in courses table
UPDATE `courses` 
SET `campus` = 'M', 
    `location` = '16W61 - Rm 724', 
    `start_time` = '18:00:00', 
    `end_time` = '20:00:00', 
    `days` = 'M W' 
WHERE `courses`.`course_id` = 1;

UPDATE `courses` 
SET `campus` = 'M', 
    `location` = '16W61 - Rm 724', 
    `start_time` = '11:00:00', 
    `end_time` = '14:00:00', 
    `days` = 'SAT' 
WHERE `courses`.`course_id` = 2;

UPDATE `courses` 
SET `campus` = 'M', 
    `location` = '16W61 - Rm 724', 
    `start_time` = '18:00:00', 
    `end_time` = '20:00:00', 
    `days` = 'T TH' 
WHERE `courses`.`course_id` = 3;

UPDATE `courses` 
SET `campus` = 'M', 
    `location` = 'EGGC - Rm 701', 
    `start_time` = '20:00:00', 
    `end_time` = '22:00:00', 
    `days` = 'M W' 
WHERE `courses`.`course_id` = 4;

UPDATE `courses` 
SET `campus` = 'M', 
    `location` = '26W61 - Rm 314', 
    `start_time` = '16:00:00', 
    `end_time` = '18:00:00', 
    `days` = 'W F' 
WHERE `courses`.`course_id` = 5;

# Insert new row into courses table 
INSERT INTO `courses`(`course_num`, `course_title`, `section_num`, `campus`, `location`, `start_time`, `end_time`, `days`, `semester`, `prof_id`) 
VALUES ('CSCI 641', 'Computer Architecture', 'W02', 'OW', 'Theobald Hall - Rm 411', '11:00:00', '14:00:00', 'SAT', 'Fall 2015', '6');