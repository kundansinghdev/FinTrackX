<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();
if (isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: welcome.php");
    die();
}

//Load Composer's autoloader
require 'vendor/autoload.php';

include 'config.php';
$msg = "";

if (isset($_POST['submit'])) {
    $name = $_POST['name']; // Assuming the input is from a form field named 'name'

    // Validate input to contain only characters (alphabets)
    if (preg_match("/^[a-zA-Z]+$/", $name)) {
        // Input contains only alphabets, proceed
        $name = mysqli_real_escape_string($conn, $name);
    } else {
        // Input contains non-alphabetic characters, handle error
        echo "Invalid input. Name should contain only alphabets.";
    }

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm-password']));
    $code = mysqli_real_escape_string($conn, md5(rand()));

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='{$email}'")) > 0) {
        $msg = "<div class='alert alert-danger'>{$email} - This email address has been already exists.</div>";
    } else {
        if ($password === $confirm_password) {
            $uppercase = preg_match('@[A-Z]@', $_POST['password']);
            $lowercase = preg_match('@[a-z]@', $_POST['password']);
            $number    = preg_match('@[0-9]@', $_POST['password']);
            $specialChars = preg_match('@[^\w]@', $_POST['password']);
            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($_POST['password']) < 8) {
                $msg = "<div class='alert alert-danger'>Password should be at least 8 characters in length and include at least one uppercase letter, one lowercase letter, one number, and one special character.</div>";
            } else {
                $sql = "INSERT INTO users (name, email, password, code) VALUES ('{$name}', '{$email}', '{$password}', '{$code}')";
                $result = mysqli_query($conn, $sql);

                if ($result) {
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
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = 'FintrackX Account Verification'; // Sets the subject line of the email.
                        // $mail->Body    = 'Here is the verification link <b><a href="http://localhost/ExpenseManagement/company_login/?verification='.$code.'">http://localhost/ExpenseManagement/company_login/?verification='.$code.'</a></b>';
                        //  $mail->Body = 'Dear User,<br/><br/>Here is your verification link: <a href="http://localhost/ExpenseManagement/company_login/?verification=' . $code . '">Click here</a> to verify your account.<br/><br/>Sincerely,<br/><b>InterviewiQ Team</b>'; // Sets the HTML content of the email body.
                        // Welcome message
                        $welcomeMessage = 'Dear User,<br/><br/>Welcome to our Expense Management System!<br/><br/>';

                        // Verification link
                        $verificationLink = 'http://localhost/ExpenseManagement/company_login/?verification=' . $code;
                        $verificationMessage = 'To complete your registration, please click on the link below:<br/>';
                        $verificationLinkText = '<a href="' . $verificationLink . '">Click here to verify your account</a><br/><br/>';

                        // Additional information
                        $additionalInfo = 'Thank you for choosing our system to manage your expenses. We are excited to have you onboard! Please keep your verification code safe, as you will need it for future logins and account recovery.<br/><br/>';

                        // Note
                        $note = 'Note: If you did not register for an account with us, please disregard this message.<br/><br/>';

                        // Sincerely message
                        $sincerelyMessage = 'Sincerely,<br/><b>FintrackX Team</b>';

                        // Combining all the parts into the final email body
                        $emailBody = $welcomeMessage . $verificationMessage . $verificationLinkText . $additionalInfo . $note . $sincerelyMessage;

                        // Set the HTML content of the email body
                        $mail->Body = $emailBody;
                        $mail->send();
                        echo 'Message has been sent';
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                    echo "</div>";
                    $msg = "<div class='alert alert-info'>We've send a verification link on your email address.</div>";
                } else {
                    $msg = "<div class='alert alert-danger'>Something wrong went.</div>";
                }
            }
        } else {
            $msg = "<div class='alert alert-danger'>Password and Confirm Password do not match</div>";
        }
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
    <meta name="keywords" content="Login Form" />
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
                            <img src="images/image2.svg" alt="">
                        </div>
                    </div>
                    <div class="content-wthree">
                        <h2>Join Our Community</h2>
                        <p>We invite you to become a part of our network. Gain access to exclusive benefits and stay ahead with our updates! 🌟✨ <a href="http://localhost/ExpenseManagement/company_login/register.php">Register Your Company Email</a></p>
                        <?php echo $msg; ?>
                        <form action="" method="post">
                            <input type="text" class="name" name="name" placeholder="Enter Company Name" pattern="[a-zA-Z]+" title="Name should contain only alphabetic characters" value="<?php if (isset($_POST['submit'])) {
                                                                                                                                                                                                echo $name;
                                                                                                                                                                                            } ?>" required>

                            <input type="email" class="email" name="email" placeholder="Enter Company Email" value="<?php if (isset($_POST['submit'])) {
                                                                                                                        echo $email;
                                                                                                                    } ?>" required>
                            <input type="password" class="password" name="password" placeholder="Enter Company  Password" required>
                            <input type="password" class="confirm-password" name="confirm-password" placeholder="Enter Company Confirm Password" required>
                            <button name="submit" class="btn" type="submit">Register</button>
                        </form>
                        <div class="social-icons">
                            <p>Have an account! <a href="index.php">Login</a>.</p>
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
        $(document).ready(function(c) {
            $('.alert-close').on('click', function(c) {
                $('.main-mockup').fadeOut('slow', function(c) {
                    $('.main-mockup').remove();
                });
            });
        });
    </script>
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
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