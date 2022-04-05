<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Laboratory Registration</title>
    <link href="images/chemistry.png" rel="icon">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" href="css/registration.css">

</head>

<body>

    <main id="main-area">

        <?php
        try {

            $con = new mysqli('localhost', 'root', "", "projet_web");
            mysqli_set_charset($con, 'utf8');
        } catch (Exception $ex) {
            print "An Exception occurred. Message: " . $ex->getMessage();
        } catch (Error $e) {
            print "The system is busy please try later";
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            include 'functions/helper.php';
            $error = array();

            $firstName = validate_input_text($_POST['firstName']);
            if (empty($firstName)) {
                $error[] = "You forgot to enter your first Name";
            }

            $lastName = validate_input_text($_POST['LastName']);
            if (empty($lastName)) {
                $error[] = "You forgot to enter your Last Name";
            }
            $name = $firstName . " " . $lastName;

            $email = validate_input_email($_POST['email']);
            if (empty($email)) {
                $error[] = "You forgot to enter your Email";
            }

            $password = validate_input_text($_POST['password']);
            if (empty($password)) {
                $error[] = "You forgot to enter your password";
            }

            $confirm_pwd = validate_input_text($_POST['confirm_pwd']);
            if (empty($confirm_pwd)) {
                $error[] = "You forgot to enter your Confirm Password";
            }

            $files = $_FILES['profileUpload'];
            $profileImage = upload_profile('./images/users_images/', $files);

            if (empty($error)) {
                // register a new user
                $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
                $check_email = "select * from users where user_email='$email'";
                $run_email = mysqli_query($con, $check_email);

                $check = mysqli_num_rows($run_email);

                if ($check == 1) {
                    echo "<script>alert('Email already exist, Please try using another email')</script>";
                    echo "<script>window.open('register.php', '_self')</script>";
                    exit();
                }

                
                $query = "INSERT INTO users ( user_name, user_email, user_pass, user_image, user_job,user_describe ,posts) VALUES( '$name', '$email', '$hashed_pass', '$profileImage','Tell us your job','Right a description','NO')";
                $run = mysqli_query($con, $query);

                if ($run) {

                    session_start();
                    $_SESSION['user_email'] = $email;

                    header('location: login.php');
                    exit();
                } else {
                    print "Error while registration...!";
                }
            } else {
                echo 'not validate';
            }
        }
        ?>

        <section id="register">
            <div class="row m-0">
                <div class="col-lg-4 offset-lg-2">
                    <div class="text-center pb-4">

                        <h1 class="login-title text-white" style="text-decoration: underline"> SIGN UP</h1>
                        <span class=" text-white">already have an account ?
                            <h4><br><a href="login.php" style="color:white;text-decoration: underline;">LOGIN</a></h4>
                        </span>
                    </div>
                    <div class="upload-profile-image d-flex justify-content-center pb-4">
                        <div class="text-center">
                            <div class="d-flex justify-content-center">
                                <img class="camera-icon" src="images/camera-solid.svg" style="width: 40px; height: 40px" alt="camera">
                            </div>
                            <img src="images/users_images/default_pic.jpg" style="width: 150px; height: 150px" class="img rounded-circle" alt="profile">
                            <h11 class="form-text text-white">Choose Image</h11>
                            <input type="file" form="reg-form" class="form-control-file" name="profileUpload" id="upload-profile">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <form action="register.php" method="post" enctype="multipart/form-data" id="reg-form">
                            <div class="form-row">
                                <div class="col">
                                    <input type="text" value="<?php if (isset($_POST['firstName'])) echo $_POST['firstName'];  ?>" name="firstName" id="firstName" class="form-control" placeholder="First Name">
                                </div>
                                <div class="col">
                                    <input type="text" value="<?php if (isset($_POST['LastName'])) echo $_POST['LastName'];  ?>" name="LastName" id="LastName" class="form-control" placeholder="Last Name">
                                </div>
                            </div>

                            <div class="form-row my-3">
                                <div class="col">
                                    <input type="email" value="<?php if (isset($_POST['email'])) echo $_POST['email'];  ?>" required name="email" id="email" class="form-control" placeholder="Email*">
                                </div>
                            </div>

                            <div class="form-row my-3">
                                <div class="col">
                                    <input type="password" required name="password" id="password" class="form-control" placeholder="password*">
                                </div>
                            </div>

                            <div class="form-row my-3">
                                <div class="col">
                                    <input type="password" required name="confirm_pwd" id="confirm_pwd" class="form-control" placeholder="Confirm Password*">
                                    <small id="confirm_error" class="text-danger"></small>
                                </div>
                            </div>

                            <div class="submit-btn text-center my-3">
                                <button type="submit" class="btn btn-light rounded-pill  px-4">Continue</button>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </section>


    </main>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script src="js/main.js"></script>
</body>

</html>