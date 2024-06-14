<?php
require_once('classes/database.php');
$con = new database();

session_start();

if (isset($_SESSION['user'])) {
    // Redirect to index.php or another appropriate page
    header('Location: index.php');
    exit();
}


if (isset($_POST['login'])) {
    $username = $_POST['user'];
    $password = $_POST['pass'];
    // Check if 'Remember me' was checked
    $rememberMe = isset($_POST['rememberMe']);
    
    $result = $con->check($username, $password);

    if ($result) {
        $_SESSION['user'] = $result['user'];
        $_SESSION['admin_id'] = $result['admin_id'];

        if ($rememberMe) {
            // Extend session cookie lifetime to 30 days
            $params = session_get_cookie_params();
            setcookie(session_name(), session_id(), time() + (86400 * 30), $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        // No else part needed, as PHP sessions expire when the browser is closed by default

        header('location:index.php');
        exit();
    } else {
        $error = "Incorrect username or password. Please try again.";
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

                                    <h2>Log In</h2>
                                    <p>To continue. Doesn't Have an Account? Contact The Admin</p>

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

                                    <h1>Login</h1>

                                    <!-- Email input -->
                                    <div data-mdb-input-init class="form-outline mb-2">
                                        <label class="form-label" for="form2Example1">Username</label>
                                        <input type="text" id="form2Example1" class="form-control" name="user"/>
                                    </div>
                                
                                    <!-- Password input -->
                                    <div data-mdb-input-init class="form-outline mb-2">
                                        <label class="form-label" for="form2Example2">Password</label>
                                        <input type="password" id="form2Example2" class="form-control" name="pass" />
                                    </div>

                                    <div class="rempas mb-5">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                            <label class="form-check-label" for="inlineCheckbox1" name="rememberMe">Remember me</label>
                                        </div>
                                        <a href="forgot_password.php" style="text-decoration:none;">Forget Password?</a>
                                    </div>
                                
                                    <!-- Submit button -->
                                    <input type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-danger btn-block mb-4" value="Log In" name="login"></input>
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