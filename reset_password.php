<?php
require_once('classes/database.php');
$con = new database();


if (isset($_POST['Reset'])) {
    $newPassword = $_POST['pass'];
    $confirmPassword = $_POST['cpass'];

    // Check if new password and confirm password match
    if ($newPassword === $confirmPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Identify user by their token
        $token = $_GET['token'];

        // Retrieve user based on the token
        $user = $con->getUserByToken($token);

        if ($user) {
            $updateStatus = $con->updateUserPassword($user['admin_id'], $hashedPassword);

            if ($updateStatus) {
                header('Location: login.php');
                exit; 
            } else {
                echo "<script>alert('Failed to reset password. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Invalid token. Cannot reset password.');</script>";
        }
    } else {
        echo "<script>alert('Passwords do not match. Please try again.');</script>";
    }
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
                                    <!-- <p>To continue. Doesn't Have an Account? Contact The Admin</p> -->

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

                                    <h1>Reset Password</h1>

                                    <!-- Email input -->
                                    <div data-mdb-input-init class="form-outline mb-2">
                                        <label class="form-label" for="form2Example1">New Password</label>
                                        <input type="Pass" id="form2Example1" class="form-control" name="pass"/>
                                    </div>
                                
                                    <!-- Password input -->
                                    <div data-mdb-input-init class="form-outline mb-2">
                                        <label class="form-label" for="form2Example2">Confirm Password</label>
                                        <input type="password" id="form2Example2" class="form-control" name="cpass" />
                                    </div>
                                
                                    <!-- Submit button -->
                                    <input type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-danger btn-block mb-4" value="Reset Password" name="Reset"></input>
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