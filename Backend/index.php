<?php 
    include_once "init.php";
    
    // User login check
    if (isset($_SESSION['UserId'])) {
      header('Location: templates/3-Dashboard.php');
      exit(); // Always exit after redirect
    }

    // Validate credentials and log the user in
    if (isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if(!empty($username) && !empty($password)) {
            // Sanitize input
            $username = $getFromU->checkInput($username);
            $password = $getFromU->checkInput($password);

            // Attempt login
            if($getFromU->login($username, $password)) {
                // Redirect on successful login
                $_SESSION['swal'] = "<script>
                    Swal.fire({
                        title: 'Welcome!',
                        text: 'You have successfully logged in.',
                        icon: 'success',
                        confirmButtonText: 'Done'
                    });
                </script>";
                header('Location: templates/3-Dashboard.php');
                exit(); // Always exit after redirect
            } else {
                // Set error message for incorrect credentials
                $error = "The username or password is incorrect";
            }
        } else {
            // Set error message for empty fields
            $error = "Please provide both username and password";
        }
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="static/images/wallet.png" sizes="16x16" type="image/png">
    
    <link rel="stylesheet" href="static/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
        integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <title>FinTrackX: Empowering Your Financial Journey</title>
</head>

<body>
    <div class="container">

        <div class="mob-hidden">
            <h1>EMS</h1>
        </div>

        <div class="top-heading">
            <h1>Welcome to FintrackX! <br> Log in to get started.</h1>
        </div>
        <form action="index.php" method="post" onsubmit = "return validate()" id="form1">

            <div class="group">


                <div class="form-controller">
                <i class="fa fa-user-plus u3" aria-hidden="true"></i>
                <input type="text" name="username" placeholder="Username" id="user1" required>
                <br>
                <small></small>
                </div>

                <div class="form-controller">
                <i class="fa fa-key u4" aria-hidden="true"></i>
                <input type="password" name="password" placeholder="Password" id="pass1" autocomplete="on" required>
                <br>
                <small></small>
                </div>

            </div>
            <button type="submit" class="sign-in" name="login">Log In</button>

            <br>
            <?php
                if (isset($error)) {
                    $font = "Source Sans Pro";
                    echo '<div style="color:  red;font-family:'.$font.';">'.$error.'</div>';
                }
            ?>
            
            <div class="new-account">
                <span style="color: rgba(0, 0, 0, 0.54); font-weight: bolder; font-family: 'Source Sans Pro';">Don't have an account?</span> 
                <a href="templates/2-sign-up.php" style="text-decoration: none;"><span style="color: rgba(5, 0, 255, 0.81); font-weight: bolder; font-family: 'Source Sans Pro';">Sign Up Now</span></a>
            </div>

        </form>

        <div class="img-container">
            <img src="static/images/login.png" alt="Login-screen-picture">
        </div>
        <!--Start of Tawk.to Script-->
      <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function () {
          var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
          s1.async = true;
          s1.src = 'https://embed.tawk.to/65bdb9d70ff6374032c8dca0/1hlmhrtos';
          s1.charset = 'UTF-8';
          s1.setAttribute('crossorigin', '*');
          s0.parentNode.insertBefore(s1, s0);
        })();
      </script>
      <!--End of Tawk.to Script-->
    </div>
    

<script src="static/js/index.js"></script>
</body>

</html>