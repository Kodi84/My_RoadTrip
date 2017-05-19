<?php
require("db.php");
session_start();

//email active start
if(isset($_GET['username']) || isset($_GET['code'])){
    $username = $_GET['username'];
    $code = $_GET['code'];

    $query = "SELECT * FROM users ";
    $query .= "WHERE username = '$username'";
    $result = mysqli_query($connection,$query);
    while ($row = mysqli_fetch_assoc($result)){
        $db_code = $row['confirm_code'];
        $confirmed = $row['confirmed'];
    }

    if($code == $db_code){
//    $query_confirmed = "UPDATE `users` SET `confirmed`='1'";
//    $query_confirm_code = "UPDATE `users` SET `confirm_code`='0'";
//   $result_confirmed = mysqli_query($connection,$query_confirmed);
//   $result_confirm_code = mysqli_query($connection,$query_confirm_code);
        $query = "UPDATE users SET confirmed='1', confirm_code='0'WHERE username='$username'";
        $result = mysqli_query($connection, $query);
        if(!$result){
            die('no query'.mysqli_error($result));
        }else{
            header("location:signin.php?err=". urldecode("Thank you, your account now has been activated. Please log in to continue"));
            exit();
        }
    }else{
        header("location:signin.php?err=". urldecode("NOT FOUND"));
        exit();
    }
}
//email activate end


//login start
if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //            hashing password
    $hash_format = "$2y$10$";
    $salt = "iusesomecrazystrings22";
    $hash_and_salt = $hash_format.$salt;
    $password = crypt($password,$hash_and_salt);
//            end hashing password


    $query = "SELECT * FROM users ";
    $query .= "WHERE username = '$username' and password= '$password' ";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if($row['confirmed'] == '1'){
            $_SESSION['auth'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['username'];
            header('Location:http://localhost/Roadtrip/index.php');
        }else {
            header("location:signin.php?err=". urldecode("Please activate your account before log in"));
            exit();
        }
    } else {
        echo '<script language="javascript">';
        echo 'alert("invalid username, password")';
        echo '</script>';
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

<body class="containerHome">
<div class="container">
    <div class="text-center">
        <h1>Road Trip</h1>
        <div class="row vertical-offset-100">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please sign in</h3>
                    </div>
                    <?php if(isset($_GET['err'])) { ?>
                        <div class="alert alert-success"><?php echo $_GET['err'] ?></div>
                    <?php } ?>
                    <div class="panel-body">
                        <form accept-charset="UTF-8" role="form" method="Post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="User Name" name="username" type="text">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password">
                                </div>
                                <input class="btn btn-lg btn-info btn-block" type="submit" name="submit">
                            </fieldset>
                        </form>
                        <hr>
                        <div class="createAccountCancel">
                            <p>Don't have an account! <a href="create_new_account.php">Create one here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>