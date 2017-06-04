<?php
require ("db.php");
session_start();
//getting data back from data base
$user_id = $_SESSION['user_id'];//passing session from sign in page
$query = "SELECT * FROM original_destination WHERE `user_id`='$user_id'";
$result = mysqli_query($connection,$query);
$output = [];
while ($row=mysqli_fetch_assoc($result)){
    $output[]=$row;
}
echo json_encode($output);
?>
