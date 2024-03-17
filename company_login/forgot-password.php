
<?php

session_start();
if (isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: welcome.php");
    die();
}

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

include 'config.php';
$msg = "";

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $code = mysqli_real_escape_string($conn, md5(rand()));

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='{$email}'")) > 0) {
        $query = mysqli_query($conn, "UPDATE users SET code='{$code}' WHERE email='{$email}'");

        if ($query) {        
            echo "<div style='display: none;'>";
            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                 //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'kundangetcode@gmail.com';                     //SMTP username
                $mail->Password   = 'unkw buos prde tpof';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('kundangetcode@gmail.com');
                $mail->addAddress($email);


                //Content
                            // Content
             $mail->isHTML(true); // Set email format to HTML
             $mail->Subject = 'Password Reset Request'; // Sets the subject line of the email.
             
             // Define styles
             $styles = '
                 body {
                     font-family: Arial, sans-serif;
                 }
                 .container {
                     max-width: 600px;
                     margin: auto;
                     padding: 20px;
                     border: 1px solid #ddd;
                     border-radius: 10px;
                 }
                 .heading {
                     font-size: 24px;
                     color: #333;
                     margin-bottom: 20px;
                 }
                 .button {
                     display: inline-block;
                     background-color: #007bff;
                     color: #fff;
                     padding: 10px 20px;
                     text-decoration: none;
                     border-radius: 5px;
                 }
                 .button:hover {
                     background-color: #0056b3;
                 }
                 .footer {
                     font-size: 14px;
                     color: #777;
                     margin-top: 20px;
                 }
             ';
             
             // Verification link
           //  $resetLink = 'http://localhost/ExpenseManagement/company_login/change-password.php/?reset='. $code;
           $resetLink = 'http://localhost/ExpenseManagement/company_login/change-password.php?reset=' . urlencode($code);
             $verificationMessage = 'To reset your password, please click the link below:';
             $verificationLinkText = '<a class="button" href="' . $resetLink . '">Reset Your Password</a>';
             
             // Additional information
             $additionalInfo = 'This password reset link will expire in 24 hours.';
             
             // Combining all the parts into the final email body
             $emailBody = '<style>' . $styles . '</style>
                 <div class="container">
                     <div class="heading">Password Reset Request</div>
                     <div class="content">
                         <p>' . $verificationMessage . '</p>
                         <p>' . $verificationLinkText . '</p>
                         <p>' . $additionalInfo . '</p>
                     </div>
                     <div class="footer">Sincerely,<br/><b>FinTrackX Team</b></div>
                 </div>
             ';
             
             // Set the HTML content of the email body
             $mail->Body = $emailBody;
             
             // Send the email
             $mail->send();

                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            echo "</div>";        
            $msg = "<div class='alert alert-info'>We've send a verification link on your email address.</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>$email - This email address do not found.</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Login Form - FinTrackX</title>
    <!-- Meta tag Keywords -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <meta name="keywords"
        content="Login Form" />
    <!-- //Meta tag Keywords -->

    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!--/Style-CSS -->
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
    <!--//Style-CSS -->

    <script src="https://kit.fontawesome.com/af562a2a63.js" crossorigin="anonymous"></script>

</head>

<body>

    <!-- form section start -->
    <section class="w3l-mockup-form">
        <div class="container">
            <!-- /form -->
            <div class="workinghny-form-grid">
                <div class="main-mockup">
                    <div class="alert-close">
                        <span class="fa fa-close"></span>
                    </div>
                    <div class="w3l_form align-self">
                        <div class="left_grid_info">
                            <img src="images/image3.svg" alt="">
                        </div>
                    </div>
                    <div class="content-wthree">
                    <h2>Forgot Password?</h2>
                    <p>Don't worry! Enter Company Emailaddress below to receive a password reset link. 📧</p>
                        <?php echo $msg; ?>
                        <form action="" method="post">
                            <input type="email" class="email" name="email" placeholder="Enter Company Email"required>
                            <button name="submit" class="btn" type="submit">Send Reset Link</button>
                        </form>
                        <div class="social-icons">
                            <p>Back to! <a href="index.php">Login</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- //form -->
        </div>
    </section>
    <!-- //form section start -->

    <script src="js/jquery.min.js"></script>
    <script>
        $(document).ready(function (c) {
            $('.alert-close').on('click', function (c) {
                $('.main-mockup').fadeOut('slow', function (c) {
                    $('.main-mockup').remove();
                });
            });
        });
    </script>
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
</body>

</html>