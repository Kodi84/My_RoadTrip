<?php

require("db.php");
session_start();
print_r('here is post global'.$_POST);
$output = ['success'=>false];
$places_types = [
    'acc_hotels' => 'acc_hotels',
    'acc_motels' => 'acc_motels',
    'acc_camping' => 'acc_camping',
    'att_amusement' => 'att_amusement',
    'att_museums' =>'att_museums',
    'att_zoo' => 'att_zoo',
    'out_beaches' => 'out_beaches',
    'out_trails' => 'out_trails',
    'out_parks' => 'out_parks',
    'gas_gas' => 'gas_gas',
    'gas_service' => 'gas_service',
    'food_restaurant' => 'food_restaurant',
    'food_diners' => 'food_diners',
    'food_fastfood' => 'food_fastfood',
    'food_vegetarian' => 'food_vegetarian',
    'food_bars' => 'food_bars',
    'food_wineries' => 'food_wineries'
];

$to_save = [];
foreach($places_types AS $key=>$value){
    $to_save[$key] = addslashes($_POST['places'][$value]);

}

if(!empty($_POST['destination'])){
    $origin = addslashes($_POST['origin']);
    $destination = addslashes($_POST['destination']);

    $query = "INSERT INTO `original_destination` SET
      `origin` = '$origin',
      `destination` = '$destination',
      `acc_hotels` = {$to_save['acc_hotels']},
      `acc_motels` = {$to_save['acc_motels']},
      `acc_camping` = {$to_save['acc_camping']},
      `att_amusement` = {$to_save['att_amusement']},
      `att_museums` ={$to_save['att_museums']},
      `att_zoo` = {$to_save['att_zoo']},
      `out_beaches` = {$to_save['out_beaches']},
      `out_trails` = {$to_save['out_trails']},
      `out_parks` = {$to_save['out_parks']},
      `gas_gas` = {$to_save['gas_gas']},
      `gas_service` = {$to_save['gas_service']},
      `food_restaurant` = {$to_save['food_restaurant']},
      `food_diners` = {$to_save['food_diners']},
      `food_fastfood` = {$to_save['food_fastfood']},
      `food_vegetarian` = {$to_save['food_vegetarian']},
      `food_bars` = {$to_save['food_bars']},
      `food_wineries` = {$to_save['food_wineries']},
      `user_id` = {$_SESSION['user_id']} 
      ON DUPLICATE KEY UPDATE
      `acc_hotels` = {$to_save['acc_hotels']},
      `acc_motels` = {$to_save['acc_motels']},
      `acc_camping` = {$to_save['acc_camping']},
      `att_amusement` = {$to_save['att_amusement']},
      `att_museums` ={$to_save['att_museums']},
      `att_zoo` = {$to_save['att_zoo']},
      `out_beaches` = {$to_save['out_beaches']},
      `out_trails` = {$to_save['out_trails']},
      `out_parks` = {$to_save['out_parks']},
      `gas_gas` = {$to_save['gas_gas']},
      `gas_service` = {$to_save['gas_service']},
      `food_restaurant` = {$to_save['food_restaurant']},
      `food_diners` = {$to_save['food_diners']},
      `food_fastfood` = {$to_save['food_fastfood']},
      `food_vegetarian` = {$to_save['food_vegetarian']},
      `food_bars` = {$to_save['food_bars']},
      `food_wineries` = {$to_save['food_wineries']}";

    $result = mysqli_query($connection,$query);
    var_dump($result);
    if(!$result){
        $output['errors'][]='query attempt failed';
        die('no query'.mysqli_error($connection));
    }else{
        if(mysqli_affected_rows($connection)>0){
            $output['success']=true;
        }
        else{
            $output['errors'][]='query attempt failed';
            $error = mysqli_error($connection);
            if($error===''){
                $output['errors'][]='nothing to do';
            }
        }
    }
}
print(json_encode($output));

?>