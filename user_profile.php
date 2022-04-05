<!DOCTYPE html>
<html lang="en">

<?php
include("header.php");
?>
<?php

if(isset($_GET["u_id"])){
    $u_id = $_GET["u_id"];
}
else{
    echo "<script>alert('Error 404');</script>";
}

$con = mysqli_connect("localhost", "root", "", "projet_web");


$get_user = "select * from users where user_id='$u_id'";
$run_user = mysqli_query($con, $get_user);
$row = mysqli_fetch_array($run_user);
$u_name = $row['user_name'];
$u_describe = $row['user_describe'];
$u_image = $row['user_image'];
$u_job = $row['user_job'];
$u_posts = $row['posts'];

?>

<body>

  <section id="main-content">
    <section class="wrapper site-min-height">
      <div class="row mt">
        <div class="col-lg-12">
          <div class="col-md-4 centered">
            <div class="profile-pic">
              <p> <?php echo "<img src='$u_image' class='mg-circle';" ?> </p>
            </div>
          </div>
          <div class="col-md-4 profile-text">
            <?php
            echo "<h3>$u_name</h3>
            <h6 style='color:#3a3a3a;'>$u_job</h6> 
            <p>$u_describe</p>";
            ?>
          </div>
        </div>
      </div>

      <div class="col-lg-12 mt">
        <div class="row content-panel">
          <div class="panel-heading">
            <ul class="nav nav-tabs nav-justified">
              <li>
                <a data-toggle="tab">Posts</a>
              </li>
            </ul>
          </div>
          <div class="panel-body">
            <div  class="tab-pane active">
              <div class="row">
                <div class="row centered">

                    <?php include('functions/post_profile.php');get_posts_user($u_id);?>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </section>


  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script class="include" type="text/javascript" src="lib/jquery.dcjqaccordion.2.7.js"></script>
  <script src="lib/jquery.scrollTo.min.js"></script>
  <script src="lib/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="lib/common-scripts.js"></script>
</body>

</html>