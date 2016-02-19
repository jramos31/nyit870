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
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <link rel="icon" href="../../favicon.ico">

    <title>DP-JR Blackboard</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">DP-JR Blackboard</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Profile</a></li>
            <li><a href="#">Help</a></li>
          </ul>
          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search...">
          </form>
        </div>
      </div>
    </nav>

	
    <div class="container-fluid">
      <div class="row">
	  
		<!-- ******  Start of Nav Sidebar  ****** -->
        <div class="col-sm-3 col-md-2 sidebar">
		
		<!--	
          <ul class="nav nav-sidebar">
            <li class="active"><a href="#">Overview <span class="sr-only">(current)</span></a></li>
            <li><a href="#">Reports</a></li>
            <li><a href="#">Analytics</a></li>
            <li><a href="#">Export</a></li>
          </ul>
		-->
		
		  <ul class="nav nav-sidebar">
            <li><a href="index.php">Home</a></li>
			
			<?php 
			/********  Display links depending on log in status  *********/
			
			if (isset($_SESSION['user_id'])) {  // User is logged in
				
				echo '<li><a href="logout.php">Logout</a></li>
					  <li><a href="">Change Password</a></li>';
				
				if ($_SESSION['user_level'] == '0') { // User is Student
					echo '<li><a href="">Student Link 1</a></li>
					      <li><a href="">Student Link 2</a></li>';
				}
				
				if ($_SESSION['user_level'] == '1') { // User is Professor/Faculty
					echo '<li><a href="">Faculty Link 1</a></li>
					      <li><a href="">Faculty Link 2</a></li>';
				}
				
				if ($_SESSION['user_level'] == '2') { // User is Administrator
					echo '<li><a href="">View All Users</a></li>
					      <li><a href="">Other Admin Stuff</a></li>';
				}
			} else {  // User Not Logged In
				echo '<li><a href="register.php">Register</a></li>
					  <li><a href="login.php">Login</a></li>
					  <li><a href="">Retrieve Password</a></li>';
			}
			?>
			
          </ul>
		  
		  <!-- 
		  <ul class="nav nav-sidebar">
            <li><a href="">Nav item</a></li>
            <li><a href="">Nav item again</a></li>
            <li><a href="">One more nav</a></li>
            <li><a href="">Another nav item</a></li>
            <li><a href="">More navigation</a></li>
          </ul>
		  
		  <ul class="nav nav-sidebar">
            <li><a href="">Nav item again</a></li>
            <li><a href="">One more nav</a></li>
            <li><a href="">Another nav item</a></li>
          </ul>
		  -->
		  
        </div>		
		<!-- ******  End of Nav Sidebar  ****** -->
		