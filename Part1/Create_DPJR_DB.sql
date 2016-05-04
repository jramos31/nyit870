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
('Dave', 'Atherton', 'daather1031@gmail.com', SHA1('pass_16'), 1, NOW()),
('Paul', 'Stanley', 'starchild@kiss.com', SHA1('pass_16'), 1, NOW()),
('Gene', 'Simmons', 'demon@kiss.com', SHA1('pass_16'), 1, NOW());

# ******************************************************************************************************************************************



# *******************************************************************************************
# Create and populate the courses table
# ** NOTE ON 'prof_id' Foreign Key:
#    - prof_id references the user_id property in the users table 
#    - it will only reference those users are professors
#      prof_id identifies who the instructor of the course is
# *******************************************************************************************
CREATE TABLE courses (
course_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
course_num VARCHAR(10) NOT NULL,
course_title VARCHAR(60) NOT NULL,
section_num VARCHAR(3) NOT NULL,
semester VARCHAR(12) NOT NULL,
prof_id INT UNSIGNED NOT NULL,
PRIMARY KEY (course_id),
INDEX (prof_id),
FOREIGN KEY(prof_id) REFERENCES users(user_id) ON DELETE NO ACTION ON UPDATE NO ACTION
);

INSERT INTO courses (course_num, course_title, section_num, semester, prof_id) VALUES 
('CSCI 641', 'Computer Architecture', 'M01', 'Fall 2015', 4),
('CSCI 641', 'Computer Architecture', 'M02', 'Fall 2015', 5),
('CSCI 690', 'Computer Networks', 'M01', 'Fall 2015', 5),
('CSCI 755', 'Artificial Intelligence I', 'M01', 'Fall 2015', 4),
('CSCI 760', 'Database Systems', 'M01', 'Fall 2015', 6);

# ******************************************************************************************************************************************


# *******************************************************************************************
# Create and populate the assignments table
# ** NOTE ON 'course_id' Foreign Key:
#    - course_id references the course_id property in the courses table
#    - it represents the course the assignment is for 
#    - there is no need to directly identify the instructor who posted the assignment
#      since the courses table already does that with its prof_id property 
#      which also happens to be a foreign key that references the users table
# *******************************************************************************************
CREATE TABLE assignments (
asmnt_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
asmnt_title VARCHAR(60) NOT NULL,
content VARCHAR(100) NOT NULL,
file_path VARCHAR(100) NULL,
date_posted DATETIME NOT NULL,
date_due DATETIME NOT NULL,
course_id INT UNSIGNED NOT NULL,
PRIMARY KEY (asmnt_id),
INDEX (course_id),
FOREIGN KEY(course_id) REFERENCES courses(course_id) ON DELETE NO ACTION ON UPDATE NO ACTION
);

INSERT INTO assignments (asmnt_title, content, date_posted, course_id) VALUES
('HW 1 - Chapter 1', 'Do questions # 1 - 5', NOW(), 1),
('HW 1 - Lecture 1', 'Write a 2-page paper on Watson', NOW(), 4);

# ******************************************************************************************************************************************

# *******************************************************************************************
# Create and populate the announcements table
# ** NOTE:
#    - nearly identical to the assignments table
#    - Purpose: for posting general info (non-homework related)
#      such as absences or test dates (ex: Quizzes, mid-terms, finals)
# *******************************************************************************************
CREATE TABLE announcements (
ann_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
subject VARCHAR(60) NOT NULL,
content VARCHAR(150) NOT NULL,
file_path VARCHAR(100) NULL,
date_posted DATETIME NOT NULL,
course_id INT UNSIGNED NOT NULL,
PRIMARY KEY (ann_id),
INDEX (course_id),
FOREIGN KEY(course_id) REFERENCES courses(course_id) ON DELETE NO ACTION ON UPDATE NO ACTION
);

INSERT INTO announcements (subject, content, date_posted, course_id) VALUES
('No class on Monday', 'I will be out on Monday because I need to have surgery. Class resumes following monday', NOW(), 1),
('Quiz # 3', 'The third quiz will be postponed til after mid-terms.', NOW(), 4),
('Quiz # 3 - Update', 'Sorry Kids!!! Quiz 3 is Back On! NO EXTRA CREDIT!!!.', NOW(), 4);


# ******************************************************************************************************************************************

# *******************************************************************************************
# Create and populate the students table
# ** NOTE: 
#    - Since students are also registered users, this table will have foreign key referencing the users table 
#    - Another foreign key will reference the courses table, since a student may be a student in more than one class
# *******************************************************************************************
CREATE TABLE students (
stud_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
user_id INT UNSIGNED NOT NULL,
course_id INT UNSIGNED NOT NULL,
PRIMARY KEY(stud_id),
INDEX(user_id),
FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY(course_id) REFERENCES courses(course_id) ON DELETE NO ACTION ON UPDATE NO ACTION
);

INSERT INTO students (user_id, course_id) VALUES
(2, 1),
(2, 3),
(2, 4),
(3, 2),
(3, 4),
(3, 5);


# ******************************************************************************************************************************************

# *******************************************************************************************
# Create and populate the homeworks table
# ** NOTE: 
#    - This table contain the file path to the homework documents submitted by Student-users. 
#    - This table will also hold the numerical grade awarded for the submitted assignment .
#    - It contains Foreign Keys the students and assignments. 
#    - There is no need to directly reference the courses table since the students and assignments
#      tables both have the Foreign Key 'course_id' referencing that table.
# *******************************************************************************************
CREATE TABLE homeworks (
hw_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
comments VARCHAR(120) NULL,
file_path VARCHAR(100) NOT NULL,
grade TINYINT UNSIGNED NULL,
date_posted DATETIME NOT NULL,
asmnt_id INT UNSIGNED NOT NULL,
s_id INT UNSIGNED NOT NULL,
PRIMARY KEY(hw_id),
INDEX(asmnt_id),
FOREIGN KEY(asmnt_id) REFERENCES assignments(asmnt_id) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY(s_id) REFERENCES users(user_id) ON DELETE NO ACTION ON UPDATE NO ACTION
);

INSERT INTO homeworks (comments, file_path, asmnt_id, s_id) VALUES 
('First Homework - Watson, COMPLETED!', 'uploads_homeworks/hw1.docx', 2, 2);

# ******************************************************************************************************************************************

# *******************************************************************************************
# Create and the two database tables for the blog
# ** NOTE: 
#    - Both tables contain a foreign key 'user_id' that references the users table. 
#    - The posts table also has a foreign key referencing the threads table to aid in
#       keeping track of message threads.
# *******************************************************************************************
CREATE TABLE threads (
thread_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
user_id INT UNSIGNED NOT NULL,
subject VARCHAR(150) NOT NULL,
PRIMARY KEY  (thread_id),
INDEX (user_id),
FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE NO ACTION ON UPDATE NO ACTION
);

CREATE TABLE posts (
post_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
thread_id INT UNSIGNED NOT NULL,
user_id INT UNSIGNED NOT NULL,
message TEXT NOT NULL,
posted_on DATETIME NOT NULL,
PRIMARY KEY (post_id),
INDEX (thread_id),
INDEX (user_id),
FOREIGN KEY(thread_id) REFERENCES threads(thread_id) ON DELETE NO ACTION ON UPDATE NO ACTION,
FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE NO ACTION ON UPDATE NO ACTION
);