<!DOCTYPE html>
<html lang="en">

<?php
include("header.php");
include("functions/post_functions_home.php");

if (!isset($_SESSION['user_email'])) {
  header("location: login.php");
}

?>

<style>
  input[type="file"] {
    display: none;
  }
</style>

<body>
  <section id="main-content">
    <section class="wrapper">
      <div class="row">
        <br />
        <div class="col-lg-11">
          <div class="col-md-12">
            <form action="" method="post" id="f" enctype="multipart/form-data">
              <textarea rows="3" id="content" name="content" class="form-control" placeholder="Whats on your mind?"></textarea>
              <div class="grey-style">
                <div class="pull-left">
                  <label class="btn btn-sm btn-theme03"><i class="fa fa-camera"></i>
                    <input type="file" name="upload_image" id="upload">
                  </label>
                </div>
                <div class="pull-right">
                  <button class="btn btn-sm btn-theme03" id="btn-post" name="post">POST</button>
                </div>
              </div>
            </form>
            <?php insertPost();
            ?>
          </div>

          <div class="col-lg-12 mt">
            <div class="row content-panel">
              <div class="panel-heading">
                <ul class="nav nav-tabs nav-justified">
                  <li>
                    <a data-toggle="tab">News</a>
                  </li>
                </ul>
              </div>
              <div class="panel-body">
                <div class="tab-pane active">
                  <?php echo get_posts();
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>

    </section>
  </section>




  <script src="lib/jquery/jquery.min.js"></script><script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script class="include" type="text/javascript" src="lib/jquery.dcjqaccordion.2.7.js"></script>
  <script src="lib/jquery.scrollTo.min.js"></script>
  <script src="lib/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="lib/jquery.sparkline.js"></script>
  <script src="lib/common-scripts.js"></script>
  <script type="text/javascript" src="lib/gritter/js/jquery.gritter.js"></script>
  <script type="text/javascript" src="lib/gritter-conf.js"></script>


  <script type="text/javascript">
    $(document).ready(function() {
      var unique_id = $.gritter.add({
        title: "<?php echo "$user_name welcome to Laboratory !"; ?>",
        text: 'Hover me to enable the Close Button. You can hide the left sidebar clicking on the button next to the logo.',
        image: "<?php echo "$user_image"; ?>",
        sticky: false,
        time: 8000,
        class_name: 'my-sticky-class'
      });

      return false;
    });
  </script>
</body>

</html>