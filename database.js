//getting needed value from front end.
function send_places_database(){
    console.log('send places database');
    var sendData = {};
    sendData.acc_hotels=false;
    sendData.acc_motels=false;
    sendData.acc_camping=false;
    sendData.att_amusement=false;
    sendData.att_museums=false;
    sendData.att_zoo=false;
    sendData.out_beaches=false;
    sendData.out_trails=false;
    sendData.out_parks=false;
    sendData.gas_gas=false;
    sendData.gas_service=false;
    sendData.food_restaurant=false;
    sendData.food_diners=false;
    sendData.food_fastfood=false;
    sendData.food_vegetarian=false;
    sendData.food_bars=false;
    sendData.food_wineries=false;
    $("input[type=checkbox]:checked").each(function(index, ele) {
        sendData[$(ele).attr('name')] = true;
    });

    $.ajax({
        url: 'http://localhost/Roadtrip/data_from_frontend.php',
        type: "POST",
        data: ({
            places: sendData,
            origin: origin,
            destination:destination
        }),
        success: function(result){
            console.log(result);
            alert('SAVED');
        }
    });
};
$(document).ready(function(){
    $('#save_places_to_database').on('click',send_places_database);
});

