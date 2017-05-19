<?php
include "db.php";
if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $confirm_password = $_POST['confirm_password'];

    define('name','$username');
    function is_unique_email(){
        global $email;
        global $connection;
        $query = "SELECT * FROM `users` WHERE email = '$email'";

        $result = mysqli_query($connection,$query);
        if(mysqli_num_rows($result) > 0){
            return false;
        }else{
            return true;
        }
    }
    function is_unique_username(){
        global $username;
        global $connection;
        $query = "SELECT * FROM `users` WHERE username = '$username'";

        $result = mysqli_query($connection,$query);
        if(mysqli_num_rows($result) > 0){
            return false;
        }else{
            return true;
        }
    }
    if(strlen($username) <4){
        header("location:create_new_account.php?err=". urldecode("The name must be at least 4 characters long"));
        exit();
    }else if ($password != $confirm_password){
        header("location:create_new_account.php?err=". urldecode("Confirm password does NOT match"));
        exit();
    }else if(strlen($password) <4){
        header("location:create_new_account.php?err=". urldecode("Password need to be at least 4 character long"));
        exit();
    }else if(!is_unique_email($email)){
        header("location:create_new_account.php?err=". urldecode("Email already existed"));
        exit();
    }else if(!is_unique_username($username)){
        header("location:create_new_account.php?err=". urldecode("Username already taken"));
        exit();
    }else {
        if(!$connection){
            echo (mysqli_error($connection));
        }else {
            $confirmCode= rand();
            $username = mysqli_real_escape_string($connection,$username);
            $password = mysqli_real_escape_string($connection,$password);

            //            hashing password
            $hash_format = "$2y$10$";
            $salt = "iusesomecrazystrings22";
            $hash_and_salt = $hash_format.$salt;
            $password = crypt($password,$hash_and_salt);
//            end hashing password


            $email = mysqli_real_escape_string($connection,$email);
            $confirmCode = mysqli_real_escape_string($connection,$confirmCode);

            $query = "INSERT INTO `users` VALUES ('','$username','$password','$email','0','$confirmCode')";
            $result = mysqli_query($connection,$query);
            include "php_mailer/mail_handler.php";

            echo '<script language="javascript">';
            echo 'alert("Please verify your email")';
            echo '</script>';

            if(!$result){
                echo(mysqli_error($result));
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RoadTripping</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
    <script src="script.js"></script>
    <script src="events_finder.js"></script>
    <script src="database.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAgbouMURMuy_zBO2i2WZX_UqBppNMQPvY&libraries=places&callback=initMap" defer></script>
</head>
<body class="container">
<div class="text-center">
    <h1>Road Trip</h1>
    <div class="row vertical-offset-100">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Please create your account</h3>
                </div>
                <?php if(isset($_GET['err'])) { ?>
                    <div class="alert alert-danger"><?php echo $_GET['err'] ?></div>
                <?php } ?>
                <div class="panel-body">
                    <form accept-charset="UTF-8" role="form" method="Post">
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" name="username" placeholder="Username at least 4 character" type="text" required >
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="password" placeholder="Password at least 4 character" type="password" required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="confirm_password" placeholder="Confirm Password" type="password" required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="email" placeholder="Email address" type="email" required>
                            </div>
                            <input class="btn btn-lg btn-info btn-block" type="submit" name="submit" value="Create Account">
                        </fieldset>
                    </form>
                    <div class="createAccountCancel">
                        <p>Already have an account?<a href="signin.php"> Sign in</a><p>
                    </div>
                    <hr>
                    <div class="createAccountCancel">
                        <a href="landing_page.php">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>