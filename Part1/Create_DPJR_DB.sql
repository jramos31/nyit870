# *****************************************************************
# Database for the DP/JR Blackboard Site for the CSCI 870 Project
# *****************************************************************

# Create the Database
# ****************************
CREATE DATABASE DPJR_DB;

USE DPJR_DB;

# **********************************************************
# Create and fill the users Table
# ** NOTE ON 'user_level' property:
#    - Level 0: Students
#    - Level 1: Professors/Faculty
#    - Level 2: System Administrators
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

INSERT INTO users (first_name, last_name, email, pass, user_level, registration_date) VALUES
('Dix', 'Porras', 'dicksonporras@gmail.com', SHA1('pass_16'), 2, NOW()),
('Dicks', 'Porras', 'dicksonporras@yahoo.com', SHA1('pass_16'), 0, NOW()),
('Dickson', 'Porras', 'dporras@nyit.edu', SHA1('pass_16'), 0, NOW()),
('Dave', 'Atherton', 'daather1031@gmail.com', SHA1('pass_16'), 1, NOW());

INSERT INTO users (first_name, last_name, email, pass, user_level, active, registration_date) VALUES
('Guy', 'Foxx', 'gfoh@gmail.com', SHA1('pass_16'), 0, , 'Bahhh', NOW());