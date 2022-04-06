<?php


$con = mysqli_connect("localhost", "root", "", "projet_web");

session_start();
$user = $_SESSION['user_email'];
$get_user = "select * from users where user_email='$user'";
$run_user = mysqli_query($con, $get_user);
$row = mysqli_fetch_array($run_user);

$user_id = $row['user_id'];
$user_name = $row['user_name'];
$user_describe = $row['user_describe'];
$user_pass = $row['user_pass'];
$user_email = $row['user_email'];
$user_image = $row['user_image'];
$user_job = $row['user_job'];
$posts = $row['posts'];

$user_posts = "select * from posts where user_id='$user_id'";
$run_posts = mysqli_query($con, $user_posts);
$posts = mysqli_num_rows($run_posts);

?>
<script>
  //startTime = new Date();
</script>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title> Laboratory Network</title>
  <link href="images/chemistry.png" rel="icon">
  <!-- Bootstrap core CSS -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <!--external css-->
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="lib/gritter/css/jquery.gritter.css" />
  <link rel="stylesheet" href="lib/xchart/xcharts.css">
  <!-- Custom styles for this template -->
  <link href="css/stylee.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">
  <script src="lib/chart-master/Chart.js"></script>

</head>
<style>
  #posts {
    border: 5px solid #e6e6e6;
    padding: 40px 50px;
  }

  #posts-img {
    padding-top: 5px;
    padding-right: 10px;
    min-width: 102%;
    max-width: 50%;
  }
</style>


<body>
  <section id="container">
    <header class="header black-bg">
      <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
      </div>
      <a href="home.php" class="logo"><b>AW<span>D</span></b></a>
      <div class="nav notify-row" id="top_menu">

        <ul class="nav top-menu">

          <li id="header_inbox_bar" class="dropdown">
            <a class="dropdown-toggle" href="chat.php" target="_parent">
              <i class="fa fa-envelope-o"></i>
            </a>
          </li>

          <li id="header_notification_bar" class="dropdown">
            <a class="dropdown-toggle" href="home.php" target="_parent">
              <i class="fa fa-bell-o"></i>
              <span class="badge bg-warning"> <?php echo $posts; ?> </span>
            </a>

        </ul>

      </div>

      <form method="post">
        <div class="top-menu">
          <ul class="nav pull-right top-menu">
            <li><a href="lock_screen.php" class="logout">Lock Screen</a></li>
            <li><button class="logout" name="logout">Logout</button></li>
          </ul>
        </div>
        <script>

        </script>

        <?php
        if (isset($_POST['logout'])) { ?>
          
          <script>
            function myFunc() {
              endTime = new Date();
              $.ajax({
                url: 'header.php',
                method: 'POST',
                data: {
                  duration: endTime - startTime,
                }
              });
            }
            //myFunc();
          </script>

        <?php

          //$duration = $_POST['duration'];
          $endTime = date("Y-m-d H:i:s");
          $end = strtotime($endTime);
          $startTime = $_SESSION['startTime'] ;
          $start = strtotime($startTime);
          
          $diff = abs($end - $start) ;
          
          $years = floor($diff / (365*60*60*24)); 
           $months = floor(($diff - $years * 365*60*60*24)/ (30*60*60*24));
           $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
           $hours = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
           $minutes = floor(($diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24  - $hours*60*60)/ 60); 
           $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60)); 


          mysqli_query($con, "INSERT INTO time_spent VALUES('','$user_id','$seconds',NOW())");
          session_destroy();
          header("location:login.php");
        }
        ?>
      </form>
    </header>


    <aside>
      <div id="sidebar" class="nav-collapse ">
        <ul class="sidebar-menu" id="nav-accordion">
          <p class="centered"><a href="profile.php"> <?php echo "<img src='$user_image' class='img-circle' width='80'>"; ?> </a></p>
          <h5 class="centered"> <a href="profile.php" style="color:white;"><?php echo $user_name; ?> </a></h5>
          <li class="mt">
            <a href="home.php">
              <i class="fa fa-desktop"></i>
              <span>Home</span>
            </a>
          </li>


          <li class="sub-menu">
            <a href="data_table.php">
              <i class="fa fa-th"></i>
              <span>Data Table</span>
            </a>

          </li>

          <li class="sub-menu">
            <a href="chat.php">
              <i class="fa fa-comments-o"></i>
              <span>Chat Room</span>
            </a>
          <li>
          </li>

          <li class="sub-menu">
            <a href="activity_time.php">
              <i class=" fa fa-bar-chart-o"></i>
              <span>Activity time</span>
            </a>
          </li>
        </ul>
      </div>
    </aside>
