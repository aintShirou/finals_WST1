<?php
require_once('classes/database.php');
$con = new database();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST['reset_password'])) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'atallado008@gmail.com'; // SMTP username
        $mail->Password = 'yrea aklm rono mdwy'; // SMTP password
        $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; // TCP port to connect to

        //Recipients
        $mail->setFrom('atallado008@gmail.com', 'Dynrax');
        $mail->addAddress($_POST['email']); // Add a recipient, the email to which you are sending the reset link

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Reset Your Password';

        // Generate a unique reset token
        $resetToken = generateResetToken($_POST['email'], $con);
        $resetLink = "http://localhost/finals_wst1/reset_password.php?token=" . $resetToken;// Replace with your actual reset link

        $mail->Body    = 'Please click on the following link to reset your password: <a href="' . $resetLink . '">Reset Password</a>';
        $mail->AltBody = 'Please copy and paste the following URL in your browser to reset your password: ' . $resetLink;

        $mail->send();
        echo "<script>console.log('Reset password link has been sent to your email.');</script>";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Function to generate a unique token for password reset
function generateResetToken($email, $con) {
    $token = bin2hex(random_bytes(16)); // Example token generation
    $reset = $con->emailResetPass($email, $token); // Save $token in your database associated with $email
    return $token;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynrax Auto Supply</title>

    <!-- CSS Links -->
    <!-- Style -->
    <link rel="stylesheet" href="login.css?v=<?php echo time(); ?>">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="bootstrap-4.5.3-dist/css/bootstrap.css">

    <!-- Boxicon -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


</head>
<body>
    <div class="main-container">

        <div class="main-content">

            <div class="container-fluid">
                
                <div class="row justify-content-center">

                    <div class="col-lg-6 col-md-0 col-sm-0 d-flex justify-content-center align-items-center">
                     

                            <div class="login">
                            
                                <div class="logo">
                                    <img src="import/Dynrax Web Finals.png" width="350">
                                </div>

                                <div class="login-text">

                                    <h2>Reset Password</h2>
                                    <p>Password reset link will be sent to your email.</p>

                                </div>

                                <div class="login-picture">
                                    <img src="import/13.png" width="950">
                                </div>
    
                            </div>
                     
                    </div>

                    <div class="col-lg-6 col-md-12 col-sm-12 d-flex justify-content-center align-items-center">
                     
                            <div class="formlogin">
                            <div class="login-form">

                                <form method="post">

                                    <h1></h1>

                                    <!-- Email input -->
                                    <div data-mdb-input-init class="form-outline mb-2">
                                        <label class="form-label" for="form2Example1">Email</label>
                                        <input type="text" id="form2Example1" class="form-control" name="email"/>
                                    </div>
                                
                                    <!-- Submit button -->
                                    <input type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-danger btn-block " value="Send Email Link" name="reset_password"></input>
                                    <a href="login.php" class="btn btn-warning btn-block mb-4" role="button">Back to Login</a>
                                </form>
                                
                                </div>
                            </div>
                      
                    </div>

                </div>
            </div>

        </div>

    </div>
</body>
</html>