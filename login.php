<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Laboratory Login</title>
    <link href="images/chemistry.png" rel="icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" href="css/registration.css">

</head>

<body>

    <main id="main-area">

        <?php

        session_start();
        include 'functions/helper.php';

        $user = array();

        try {
            $con = new mysqli('localhost', 'root', "", "projet_web");
            mysqli_set_charset($con, 'utf8');
        } catch (Exception $ex) {
            print "An Exception occurred. Message: " . $ex->getMessage();
        } catch (Error $e) {
            print "The system is busy please try later";
        }

        if (isset($_SESSION['user_email'])) {
            $mail = $_SESSION['user_email'];
            $select = "SELECT user_id,user_name, user_pass, user_image, user_job,user_describe ,posts from users WHERE user_email='$mail'";
            $run_select = mysqli_query($con, $select);
            $user = mysqli_fetch_array($run_select);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $error = array();

            $email = validate_input_email($_POST['email']);
            if (empty($email)) {
                $error[] = "You forgot to enter your Email";
            }

            $password = validate_input_text($_POST['password']);
            if (empty($password)) {
                $error[] = "You forgot to enter your password";
            }

            if (empty($error)) {
                $query = "SELECT user_id,user_name, user_pass, user_image, user_job,user_describe ,posts from users WHERE user_email='$email' ";
                $run = mysqli_query($con, $query);
                $row = mysqli_fetch_array($run, MYSQLI_ASSOC);

                if (!empty($row)) {
                    if (password_verify($password, $row['user_pass'])) {
                        $_SESSION['user_email'] = $email;

                        $_SESSION['startTime'] = date("Y-m-d H:i:s");
                    
                        header("location: home.php");
                        exit();
                    }
                    else{
                        echo"<script>alert('Verify your password please !')</script>";
                    }
                } else {
                    print "You are not a member please register!";
                }
            }
             else {
                echo "Please Fill out email and password to login!";
            }
        }
        ?>

        <section id="login-form">
            <div class="row m-0">
                <div class="col-lg-4 offset-lg-2">
                    <div class="text-center pb-5">
                        <h1 class="login-title text-white" style="text-decoration: underline">LOGIN</h1>
                        <h5><a href="register.php" style="color:white;text-decoration: underline;">Create a new account</a>
                            <h5>

                    </div>
                    <div class="upload-profile-image d-flex justify-content-center pb-4">
                        <div class="text-center">
                            <img src="<?php echo isset($user['user_image']) ? $user['user_image'] : 'images/users_images/default_pic.jpg'; ?>" style="width: 150px; height: 150px" class="img rounded-circle" alt="profile">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <form action="login.php" method="post" enctype="multipart/form-data" id="log-form">

                            <div class="form-row my-3">
                                <div class="col">
                                    <input type="email" required name="email" id="email" class="form-control" placeholder="Email*">
                                </div>
                            </div>

                            <div class="form-row my-3">
                                <div class="col">
                                    <input type="password" required name="password" id="password" class="form-control" placeholder="password*">
                                </div>
                            </div>

                            <div class="submit-btn text-center my-3">
                                <button type="submit" class="btn btn-light rounded-pill text-dark px-4">Login</button>
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