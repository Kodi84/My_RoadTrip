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
    <h1>TRIP PLANNER</h1>
    <legend></legend>
    <div class="row vertical-offset-100">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Update Profiles</h3>
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
                                <input class="form-control"  name="mpg" placeholder="MPG" type="number" required>
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
                        <a href="homePage.html">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>