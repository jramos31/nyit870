# *****************************************************************
# Database for the DP/JR Blackboard Site for the CSCI 870 Project
# *****************************************************************

# Create the Database
# ****************************
CREATE DATABASE DPJR_DB;

USE DPJR_DB;

# **********************************************************
# Create and populate the users table
# ** NOTE ON 'user_level' property:
#    - Level 0: Students
#    - Level 1: Professors/Faculty
#    - Level 2: System Administrators
#
# ** NOTE ON 'active' property:
#    - this property will default to NULL if you do not insert any values for it 
#    - when this property is NULL the user account is considered activated 
#      and the user will able to log in and access the site 
# **********************************************************
CREATE TABLE users (
user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
first_name VARCHAR(20) NOT NULL,
last_name VARCHAR(40) NOT NULL,
email VARCHAR(80) NOT NULL,
pass CHAR(40) NOT NULL,
user_level TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
active CHAR(32) NULL,
registration_date DATETIME NOT NULL,
PRIMARY KEY (user_id),
UNIQUE KEY (email),
INDEX login (email, pass)
);

# With these INSERT statements, 'active' will default to NULL
# *********************************************************************
INSERT INTO users (first_name, last_name, email, pass, user_level, registration_date) VALUES
('Dix', 'Porras', 'dicksonporras@gmail.com', SHA1('pass_16'), 2, NOW()),
('Dicks', 'Porras', 'dicksonporras@yahoo.com', SHA1('pass_16'), 0, NOW()),
('Dickson', 'Porras', 'dporras@nyit.edu', SHA1('pass_16'), 0, NOW()),
('Dave', 'Atherton', 'daather1031@gmail.com', SHA1('pass_16'), 1, NOW());

# ******************************************************************************************************************************************



# **********************************************************
# Create and populate the courses table
# ** NOTE ON 'prof_id' Foreign Key:
#    - prof_id references the user_id property in the users table 
#    - it will only reference those users are professors
#      prof_id identifies who the instructor of the course is
# **********************************************************
CREATE TABLE courses (
course_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
course_num VARCHAR(10) NOT NULL,
course_title VARCHAR(60) NOT NULL,
section_num VARCHAR(3) NOT NULL,
prof_id INT UNSIGNED NOT NULL,
PRIMARY KEY (course_id),
INDEX (prof_id),
FOREIGN KEY(prof_id) REFERENCES users(user_id) ON DELETE NO ACTION ON UPDATE NO ACTION
);

INSERT INTO courses (course_num, course_title, section_num, prof_id) VALUES 
('CSCI 641', 'Computer Architecture', 'M01', 4),
('CSCI 755', 'Artificial Intelligence I', 'M01', 4);

# ******************************************************************************************************************************************


# **********************************************************
# Create and populate the assignments table
# ** NOTE ON 'course_id' Foreign Key:
#    - course_id references the course_id property in the courses table
#    - it represents the course the assignment is for 
#    - there is no need to directly identify the instructor who posted the assignment
#      since the courses table already does that with its prof_id property 
#      which also happens to be a foreign key that references the users table
# **********************************************************
CREATE TABLE assignments (
asmnt_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
asmnt_title VARCHAR(60) NOT NULL,
content VARCHAR(100) NOT NULL,
date_posted DATETIME NOT NULL,
course_id INT UNSIGNED NOT NULL,
PRIMARY KEY (asmnt_id),
INDEX (course_id),
FOREIGN KEY(course_id) REFERENCES courses(course_id) ON DELETE NO ACTION ON UPDATE NO ACTION
);

INSERT INTO assignments (asmnt_title, content, date_posted, course_id) VALUES
('HW 1 - Chapter 1', 'Do questions # 1 - 5', NOW(), 1),
('HW 1 - Lecture 1', 'Write a 2-page paper on Watson', NOW(), 2);

# ******************************************************************************************************************************************