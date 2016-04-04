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
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
        <link rel="icon" href="../../favicon.ico">
        <title>DP-JR Blackboard</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <!-- Custom styles for this template -->
        <link href="style.css" rel="stylesheet">

    </head>
    <body>
        <section id="body" class="width">
            <aside id="sidebar" class="column-left">
                <header>
                    <h1><a href="#">D&J's Board</a></h1>
                    <h2>By: Dickson and Jean</h2>
                </header>
                    <nav id="mainnav">
                        <ul>
                            <?php
                            if (isset($_SESSION['user_id'])) {  // User is logged in
                            echo '
                                <li><a href="hboard.php">Home</a></li>';

                            if ($_SESSION['user_level'] == '0') { // User is Student
    						$id = $_SESSION['user_id'];
    						echo '
    							<li><a href="announcement_list_all.php?id=' . $id . '">Announcements</a></li>
                                <li><a href="view_grades.php">My Grades</a></li>
                                <li><a href="view_courses.php">My Courses</a></li>
                                <li><a href="#">Calendar</a></li>
                                <li><a href="#">Student Options <span class="sr-only">(current)</span></a></li>
                                <li><a href="change_password.php">Change Password</a></li>
                                <li><a href="logout.php">Logout</a></li>';
                            }

                            if ($_SESSION['user_level'] == '1') { // User is Professor/Faculty
                            echo '
                                <li><a href="announcement_list_all.php?id=' . $id . '">Announcements</a></li>
                                <li><a href="view_grades.php">Student Grades</a></li>
    							<li><a href="view_courses.php">My Courses</a></li>
                                <li><a href="">Faculty Link 2</a></li>
                                <li><a href="#">Professor/Faculty Options <span class="sr-only">(current)</span></a></li>
                                <li><a href="change_password.php">Change Password</a></li>
                                <li><a href="logout.php">Logout</a></li>';
                            }

                            if ($_SESSION['user_level'] == '2') { // User is Administrator
                            echo '
                                <li><a href="#">Administrator Options <span class="sr-only">(current)</span></a></li>
    							<li><a href="view_users.php">View All Users</a></li>
                                <li><a href="">Other Admin Stuff</a></li>';
                            }
                            } else {  // User Not Logged In
                            echo '
                                <li><a href="login.php">Login</a></li>
    							<li><a href="register.php">Register</a></li>
                                <li><a href="">Retrieve Password</a></li>';
                            }
                            ?>
                        </ul>
                    </nav>
            </aside>
