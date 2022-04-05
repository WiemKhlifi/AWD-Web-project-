<?php
include('header.php');
function validate_input_text($textValue){
    if (!empty($textValue)){
        $trim_text = trim($textValue);
        $sanitize_str = filter_var($trim_text, FILTER_SANITIZE_STRING);
        return $sanitize_str;
    }
    return '';
}

?>

<body>
  <section id="main-content">
    <section class="wrapper">
      <div class="row mt">
        <div class="col-md-12">
          <div class="content-panel">

            <p style="float: right;"> <button data-toggle="modal" data-target="#myModalAddData" class="btn btn-warning"> ADD DATA </button>&nbsp&nbsp&nbsp</p>


            <!-- Modal(Add Data) -->

            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModalAddData" class="modal fade">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div style=" background-color: #f0ad4e;padding: 15px;border-bottom: 1px solid #f0ad4e;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Do you want to add your data link ?</h4>
                  </div>
                  <div class="modal-body">
                    <form method="post">
                      <p>Enter your Data information.</p>
                      <input type="text" name="description" class="form-control" placeholder="Enter the description" required>
                      <br>
                      <input type="url" name="link" class="form-control" placeholder="Enter the link" required>
                  </div>
                  <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                    <button type="submit" name="add_data" class="btn btn-warning">Add Data </button>
                    </form>

                    <?php
                    $error = array();

                    if (isset($_POST['add_data'])) {
                      $name = $user_name;
                      $description = validate_input_text($_POST['description']);
                      $link = trim($_POST['link']);
                      if (empty($description)) {
                        $error[] = "You forgot to enter your description";
                      }
                      if (empty($link)) {
                        $error[] = "You forgot to enter your link";
                      }

                      if (empty($error)) {
                        $addlink = "INSERT INTO data (name,description,link,link_date) VALUES ('$name','$description','$link',NOW())";
                        $run = mysqli_query($con, $addlink);
                        if ($run) {
                          echo "<script>alert('Data table Updated')</script>";
                          echo "<script>window.open('data_table.php' , '_self')</script>";
                        } else {
                          echo "<script>alert('ERROR 404 , Try later !)</script>";
                          echo "<script>window.open('data_table.php' , '_self')</script>";
                        }
                      }
                      else {
                        echo "Please Fill out the description and the link to add your data !";
                    }
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <!-- modal  -->

            <table class="table table-striped table-advance table-hover">
              <h4><i class="fa fa-angle-right"></i> Data Table </h4>
              <hr>
              <thead>
                <tr>
                  <th><i class="fa fa-bullhorn"></i> Name</th>
                  <th class="hidden-phone"><i class="fa fa-question-circle"></i> Description</th>
                  <th><i class="fa fa-bookmark"></i>URL</th>
                  <th>Action</th>
                </tr>

              </thead>
              <tbody>
              <?php
              $result = $con->query("SELECT * FROM data ") or die($con->error);
              while ($row = $result->fetch_assoc()) {

              ?>
                <tr>
                  <td><?php echo $row['name']; ?> </td>
                  <td><?php echo $row['description']; ?> </td>
                  <td><a href="<?php echo $row['link']; ?>" > <?php echo $row['link']; ?> </a> </td>
                  <td>
                  <button onclick="GetElement('<?php echo $row['linkid']; ?>','<?php echo $row['description']; ?>','<?php echo $row['link']; ?>')" data-toggle='modal' data-target="#myModaled"  class='btn btn-theme'>Edit</button>
                  <button onclick=" GetId('<?php echo $row['linkid']; ?>')" data-toggle='modal' data-target="#myModaldel"  class='btn btn-theme02'>Delete</button>
                  </td>
                </tr>
            <?php }?>
              </tbody>
            </table>
<!-- Modal(Delete) -->

<script>
  function GetId(id) {
    document.getElementById("100").value = id;

  }
</script>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModaldel" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div style=" background-color: #222831;padding: 15px;border-bottom: 1px solid #222831;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Are you sure to delete this link? </h4>
      </div>
      <div class="modal-body">

        <h5>If yes ,then click on submit button !</h5>
      </div>
      <div class="modal-footer">
        <form method="post">
          <input type="hidden" id="100" name="linkid" value="">
          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
          <button class="btn btn-theme02" type="submit" name="delete">Submit</button>
        </form>

        <?php
        if (isset($_POST['delete'])) {
          $id = $_POST['linkid'];
          $delete_link = "DELETE from data where linkid = $id";
          $run = mysqli_query($con, $delete_link);
          if ($run) {
            echo "<script>alert('A post have been deleted!')</script>";
            echo "<script>window.open('data_table.php','_self')</script>";
          } else {
            echo "<script>alert('You have an error !')</script>";
            echo "<script>window.open('data_table.php','_self')</script>";
          }
        }
        ?>
      </div>
    </div>
  </div>
</div>
<!-- modal 1-->


<!-- Modal(Edit) -->

<script>
  function GetElement(id,descrip,url) {
    document.getElementById("20").value = id;
    document.getElementById("30").value = descrip;
    document.getElementById("40").value = url;


  }
</script>


<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModaled" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Do you want to change the data ?</h4>
      </div>
      <form method="post">
        <div class="modal-body">
          <p>Enter your new changes here.</p>
          <input type="hidden" id="20" name="linkid" value="">
          <input type="text" name="description" id="30" class="form-control" placeholder="Enter the description" required>
          <br>
          <input type="text" name="link" id="40" class="form-control" placeholder="Enter the link" required>
         
        </div>
        <div class="modal-footer">
          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
          <button class="btn btn-theme" type="submit" name="Editlink">Submit</button>
        </div>
      </form>

      <?php
      if (isset($_POST['Editlink'])) {

        $id = $_POST["linkid"];
        $description = $_POST["description"];
        $link = $_POST["link"];

        $updatedata = "UPDATE data SET description='$description',link='$link' WHERE linkid=$id";
        $run = mysqli_query($con, $updatedata);
        if ($run) {
          echo "<script>alert('Your Data Updated')</script>";
          echo "<script>window.open('data_table.php' , '_self')</script>";
        } else {
          echo "<script>alert('You have an error !')</script>";
          echo "<script>window.open('data_table.php','_self')</script>";
        }
      }
      ?>

</div> 
</div>
      </div>
    </section>
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