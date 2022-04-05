<!DOCTYPE html>
<html lang="en">
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

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Laboratory Network</title>
  <link href="images/chemistry.png" rel="icon">

  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="lib/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link href="css/stylee.css" rel="stylesheet">
  <link href="css/style-responsive.css" rel="stylesheet">

</head>

<body onload="getTime()">

  <div class="container">
    <div id="showtime"></div>
    <div class="col-lg-4 col-lg-offset-4">
      <div class="lock-screen">
        <h2><a data-toggle="modal" href="#myModal" ><i class="fa fa-lock"></i></a></h2>
        <h4 style="color:black;">UNLOCK</h4>
        <!-- Modal -->
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Welcome Back <?php echo"$user_name !"; ?></h4>
              </div>
              <form method="post">
              <div class="modal-body">
                <p class="centered"><img class="img-circle" width="80" src="<?php echo "$user_image";?>"></p>
                <input type="password" name="password" placeholder="Password" autocomplete="off" class="form-control placeholder-no-fix">
              </div>
              <div class="modal-footer centered">
                <button data-dismiss="modal" class="btn btn-theme04" type="button">Cancel</button>
                <button name="log" class="btn btn-theme03" >Login</button>
              </form>
              <?php
                if(isset($_POST['log'])){
                    $password = $_POST['password'];
                    if (password_verify($password, $user_pass)) {
                        header("location: home.php");
                        exit();
                    }
                }
              
              ?>

              </div>
            </div>
          </div>
        </div>
        <!-- modal -->
      </div>
    </div>
  </div>


  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="lib/jquery.backstretch.min.js"></script>
  <script>
    $.backstretch("images/dataClouds.jpg", {
      speed:1000
    });
  </script>
  <script>
    function getTime() {
      var today = new Date();
      var h = today.getHours();
      var m = today.getMinutes();
      var s = today.getSeconds();
      // add a zero in front of numbers<10
      m = checkTime(m);
      s = checkTime(s);
      document.getElementById('showtime').innerHTML = h + ":" + m + ":" + s;
      t = setTimeout(function() {
        getTime()
      }, 500);
    }

    function checkTime(i) {
      if (i < 10) {
        i = "0" + i;
      }
      return i;
    }
  </script>
</body>

</html>