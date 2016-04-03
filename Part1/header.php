<?php

// Begin output buffering and initialize a session
ob_start();
session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="../../favicon.ico">
        <title>DP-JR Blackboard</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <!-- Custom styles for this template -->
        <link href="style.css" rel="stylesheet">

    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <aside class="sidebar-left">
                    <a class="company-logo" href="#">D-J</a>
                    <div class="sidebar-links">
                        <?php
                        if (isset($_SESSION['user_id'])) {  // User is logged in
                        echo '
                            <a href="hboard.php">Home</a>';

                        if ($_SESSION['user_level'] == '0') { // User is Student
						$id = $_SESSION['user_id'];
						echo '
							<a href="announcement_list_all.php?id=' . $id . '">Announcements</a>
                            <a href="view_grades.php">My Grades</a>
                            <a href="view_courses.php">My Courses</a>
                            <a href="#">Calendar</a>
                            <a href="#">Student Options <span class="sr-only">(current)</span></a>
                            <a href="change_password.php">Change Password</a>
                            <a href="logout.php">Logout</a>';
                        }

                        if ($_SESSION['user_level'] == '1') { // User is Professor/Faculty
                        echo '
                            <a href="announcement_list_all.php?id=' . $id . '">Announcements</a>
                            <a href="view_grades.php">Student Grades</a>
							<a href="view_courses.php">My Courses</a>
                            <a href="">Faculty Link 2</a>
                            <a href="#">Professor/Faculty Options <span class="sr-only">(current)</span></a>
                            <a href="change_password.php">Change Password</a>
                            <a href="logout.php">Logout</a>';
                        }

                        if ($_SESSION['user_level'] == '2') { // User is Administrator
                        echo '
                            <a href="#">Administrator Options <span class="sr-only">(current)</span></a>
							<a href="view_users.php">View All Users</a>
                            <a href="">Other Admin Stuff</a>';
                        }
                        } else {  // User Not Logged In
                        echo '
                            <a href="login.php">Login</a>
							<a href="register.php">Register</a>
                            <a href="">Retrieve Password</a>';
                        }
                        ?>
                    </div>
                </aside>
            </div>
        </div>
