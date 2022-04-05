<!DOCTYPE html>
<html lang="en">

<?php
include("header.php");
?>


<body>

  <section id="main-content">
    <section class="wrapper site-min-height">
      <div class="row mt">
        <div class="col-lg-12">
          <div class="col-md-4 centered">
            <div class="profile-pic">
              <p> <?php echo "<img src='$user_image' class='mg-circle';" ?> </p>
              <p>
                <a data-toggle="modal" href="profile.php#myModalPhoto" class="btn btn-theme"><i class="fa fa-camera"></i> Edit Photo </a>
                <a data-toggle="modal" href="profile.php#myModalInfo" class="btn btn-theme02" onclick="GetInfo('<?php echo $user_name; ?>','<?php echo $user_job; ?>','<?php echo $user_describe ?>')"> Edit information</a>
               
              </p>
            </div>
          </div>
          <div class="col-md-4 profile-text">
            <?php
            echo "<h3>$user_name</h3>
            <h6 style='color:#3a3a3a;'>$user_job</h6> 
            <p>$user_describe</p>";
            ?>
          </div>
        </div>
      </div>
      <!-- Modal(EditPhoto) -->
      <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModalPhoto" class="modal fade">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Do you want to change your photo?</h4>
            </div>
            <div class="modal-body">
              <form action='profile.php?u_id=<?php echo $user_id ?>' method='post' enctype='multipart/form-data'>
                <p>Enter your photo here.</p>
                <input type="file" name="u_photo" class="form-control placeholder-no-fix" required>

            </div>
            <div class="modal-footer">
              <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
              <button class="btn btn-theme" type="submit" name="submitPhoto">Submit</button>
              </form>

              <?php
              if (isset($_POST['submitPhoto'])) {

                $u_image = $_FILES['u_photo']['name'];
                $image_tmp = $_FILES['u_photo']['tmp_name'];
                $random_number = rand(1, 100);
                
                if ($u_image == '') {
                  echo "<script>alert('Please Select Profile Image on clicking on your profile image')</script>";
                  echo "<script>window.open('profile.php?u_id=$user_id' , '_self')</script>";
                  exit();
                } else {
                  move_uploaded_file($image_tmp, "images/users_images/$u_image.$random_number");
                  $update = "update users set user_image='./images/users_images/$u_image.$random_number' where user_id='$user_id'";

                  $run = mysqli_query($con, $update);

                  if ($run) {
                    echo "<script>alert('Your Profile Updated')</script>";
                    echo "<script>window.open('profile.php?u_id=$user_id' , '_self')</script>";
                  }
                }
              }
              ?>
            </div>
          </div>
        </div>
      </div>
      <!-- modal 1-->


     

      <!-- Modal(EditInformation) --> 
      
      <script>
        function GetInfo(name, job, description) {
          document.getElementById("0").value = name;
          document.getElementById("1").value = job;
          document.getElementById("2").value = description;
        }
      </script>

      <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModalInfo" class="modal fade">
        <div class="modal-dialog">
          <div class="modal-content">
            <div style=" background-color: #222831;padding: 15px;border-bottom: 1px solid #222831;">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title">Do you want to change your information ?</h4>
            </div>
            <div class="modal-body">
              <form method="post" action="">
                <p>Enter your new information.</p>
                <input class="form-control" type="text" name="user_name" id="0" placeholder="User Name" required="required">
                <br>
                <input class="form-control" type="text" name="user_job" id="1" placeholder="User Job" required="required">
                <br>
                <input class="form-control" type="text" name="user_describe" id="2" placeholder="User Description" required="required">
            </div>
            <div class="modal-footer">
              <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
              <button class="btn btn-theme02" type="submit" name="submitInfo">Submit</button>
              </form>

              <?php
              if (isset($_POST['submitInfo'])) {
                $user_name = trim($_POST["user_name"]);
                $user_job =  trim($_POST["user_job"]);
                $user_describe = trim($_POST["user_describe"]);
                $updateinfo = "UPDATE users SET user_name='$user_name', user_job='$user_job', user_describe='$user_describe'  WHERE user_id='$user_id' ";
                $run = mysqli_query($con, $updateinfo);
                if ($run) {
                  echo "<script>alert('Your Profile Updated')</script>";
                  echo "<script>window.open('profile.php?u_id=$user_id' , '_self')</script>";
                }
              }
              ?>
            </div>
          </div>
        </div>
      </div>
      <!-- modal 2 -->


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

                    <?php include('functions/post_profile.php');get_posts($user_id)?>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </section>

  <!-- js placed at the end of the document so the pages load faster -->
  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script class="include" type="text/javascript" src="lib/jquery.dcjqaccordion.2.7.js"></script>
  <script src="lib/jquery.scrollTo.min.js"></script>
  <script src="lib/jquery.nicescroll.js" type="text/javascript"></script>
  <!--common script for all pages-->
  <script src="lib/common-scripts.js"></script>
  <!--script for this page-->
</body>

</html>