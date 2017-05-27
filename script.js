$(document).ready(function(){
    // disable left side div when users not login
    $("#left_sidebar *").attr("disabled", "disabled").off('click');
    // end disable left side

    // disable right side div when users not login
    $("#right_sidebar *").attr("disabled", "disabled").off('click');

    //end disable right side

    $("#mode-selector").hide();
    // $("#getDirectionsButton").hide();
    $("#weatherDisplayContainer").hide();
    getGasolineCost();
    $('#actionSubmit').click(function(){
        slicedNodes();
        checkForCheckedValues();
        startPlaces(nodesToCheck);
        startPlaces(nodesToCheck2);
        startPlaces(nodesToCheck3);
        startPlaces(nodesToCheck4);
        startPlaces(nodesToCheck5);
        startPlaces(nodesToCheck6);
    });
    $('#submit_event').on('click', function() {
        getResults();
    });
    $("#displayData").on('click',function() {
        if(dataPointsBlocker === false){
            show_message("Please select your route first");
        } else {
            $("#myModal").modal('show');
        }
    });

    $("#displayData2").on('click',function () {
        if(findEventBlocker === false){
            show_message("Please select route");
        }else{
            $("#myModal2").modal("show");
        }
    });
});

var map;
var checkedBoxes = [];
var loc = {};
var geocoder;
var trafficLayer= null;
var nodes = null;
var nodesToCheck = null;
var nodesToCheck2 = null;
var nodesToCheck3 = null;
var nodesToCheck4 = null;
var nodesToCheck5 = null;
var nodesToCheck6 = null;
var destination = null;
var origin = null;
var city = null;
var state = null;
var totalMilesofTrip = null;
var pricePerGallon = null;
var weatherLoaded = false;
var cityForEvent = null;
var choice = null;


var dataPointsBlocker = false;
var findEventBlocker = false;


function initMap() {
    geocoder = new google.maps.Geocoder;
    directionsDisplay = new google.maps.DirectionsRenderer;
    map = new google.maps.Map(document.getElementById('map'), {
        mapTypeControl: false,
        center: {lat: 37.0902, lng: -95.7129},
        zoom: 4
    });
    new AutocompleteDirectionsHandler(map);
}
/**
 * @constructor
 */
function AutocompleteDirectionsHandler(map) {
    this.map = map;
    this.originPlaceId = null;
    this.destinationPlaceId = null;
    this.travelMode = 'DRIVING';
    var originInput = document.getElementById('origin-input');
    var destinationInput = document.getElementById('destination-input');
    this.directionsService = new google.maps.DirectionsService;
    this.directionsDisplay = new google.maps.DirectionsRenderer;
    this.directionsDisplay.setMap(map);

    this.directionsDisplay.setPanel(document.getElementById('mySidenav3'));

    var originAutocomplete = new google.maps.places.Autocomplete(
        originInput, {placeIdOnly: true});
    var destinationAutocomplete = new google.maps.places.Autocomplete(
        destinationInput, {placeIdOnly: true});


    this.setupClickListener('changemode-driving', 'DRIVING');
    this.setupPlaceChangedListener(originAutocomplete, 'ORIG');
    this.setupPlaceChangedListener(destinationAutocomplete, 'DEST');

    this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(originInput);
    this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(destinationInput);
}

/**
 *Create event Marker and info_window for marker
 */

function create_event_marker(eventData){
    var lat = parseFloat(eventData.latitude);
    var lng = parseFloat(eventData.longitude);
    var marker_event = new google.maps.Marker({
        position: {lat: lat, lng: lng},
        map: map,
        icon:'images/location_pin_marker.png'
    });
    return marker_event;
}

function create_info_event(marker,result){
    var title = $("<h1>",{
        text: result.title
    });
    var cityLabel = $("<h3>",{
        text: result.city_name
    });
    var address = $("<p>",{
        text: result.venue_address
    });
    var eventLink = $("<a>",{
        href: result.venue_url,
        text: "Click here for more details",
        target:"_blank"

    });

    var container = $("<div>").append(title,cityLabel,address,eventLink);
    var infoWindow2 = new google.maps.InfoWindow({
        content: container.html()
    });

    marker.addListener('click', (
        function(){return function(){
            infoWindow2.open(map,marker);
        };}
    )());
    return marker;
}

AutocompleteDirectionsHandler.prototype.setupClickListener = function(id, mode) {

    var radioButton = document.getElementById(id);
    var me = this;
    radioButton.addEventListener('click', function () {
        me.travelMode = mode;
        me.route();
    });
};

AutocompleteDirectionsHandler.prototype.setupPlaceChangedListener = function (autocomplete, mode) {
    var me = this;
    autocomplete.bindTo('bounds', this.map);
    autocomplete.addListener('place_changed', function () {
        var place = autocomplete.getPlace();
        if (!place.place_id) {
            window.alert("Please select an option from the drop down list.");
            return;
        }
        if (mode === 'ORIG') {
            me.originPlaceId = place.place_id;
            geocoder.geocode({'placeId': place.place_id}, function (results) {
                var latLng = results[0].geometry.location;
                loc.lat = latLng.lat();
                loc.lng = latLng.lng();
            });

        } else {
            me.destinationPlaceId = place.place_id;
        }
        destination = $("#destination-input").val();
        origin = $("#origin-input").val();

        me.route();
    });
};

//auto complete showing route function
AutocompleteDirectionsHandler.prototype.route = function() {
    if (!this.originPlaceId || !this.destinationPlaceId) {
        return;
    }
    cityStateDestination();
    var me = this;
    this.directionsService.route({
        origin: {'placeId': this.originPlaceId},
        destination: {'placeId': this.destinationPlaceId},
        travelMode: this.travelMode

    }, function (response, status) {
        if (status === 'OK') {
            me.directionsDisplay.setDirections(response);
            route = response.routes[0];
            var path = response.routes[0].overview_path;
            totalMilesofTrip = parseFloat(response.routes[0].legs[0].distance.text);
            dataPointsBlocker = true;
            findEventBlocker = true;


            var currentI = 0;
            nodes = [path[0]];
            for(var i =1; i< path.length; i++ ){
                var firstLat = path[currentI].lat();
                var firstLng = path[currentI].lng();
                var secondLat = path[i].lat();
                var secondLng = path[i].lng();
                var solutionLat = Math.pow((secondLat-firstLat),2);
                var solutionLng = Math.pow((secondLng-firstLng),2);
                var squareRoot = Math.sqrt(solutionLng + solutionLat);
                var check = squareRoot * 69 ;
                if(check>60){
                    nodes.push(path[currentI]);
                    currentI = i;
                    console.log("This counts as one places google places api");
                }
            }
            nodes.push(path[path.length-1]);
            $("#getDirectionsButton").show();
        } else {
            window.alert('Directions request failed due to ' + status);
        }
    });
    //marker for events

};

<!-- Mikes JS-->

function checkForCheckedValues(){
    checkedBoxes=[];
    $("input[type=checkbox]:checked").each(function() {
        checkedBoxes.push($(this).val() );
    });
    console.log("users picks to have displayed:" +" "+ checkedBoxes);
}

function slicedNodes() {
    nodesToCheck = nodes.slice();
    var splitPoint = Math.ceil(nodesToCheck.length / 6);
    nodesToCheck2 = nodesToCheck.splice(splitPoint);
    splitPoint = Math.ceil(nodesToCheck.length / 5);
    nodesToCheck3 = nodesToCheck2.splice(splitPoint);
    splitPoint = Math.ceil(nodesToCheck.length / 4);
    nodesToCheck4 = nodesToCheck3.splice(splitPoint);
    splitPoint = Math.ceil(nodesToCheck.length / 3);
    nodesToCheck5 = nodesToCheck4.splice(splitPoint);
    splitPoint = Math.ceil(nodesToCheck.length / 2);
    nodesToCheck6 = nodesToCheck5.splice(splitPoint);
}

function startPlaces(nodes) {
    if(nodes.length == 0){
        return;
    }
    var service = new google.maps.places.PlacesService(map);
    service.nearbySearch({
        location: nodes[0],
        radius: 8046.72, //5 Mile radius
        name: checkedBoxes
    }, function(results, status, pagination){
        nodes.shift();
        startPlaces(nodes);
        processResults(results, status, pagination);
    });
}

function processResults(results, status, pagination) {
    if (status !== google.maps.places.PlacesServiceStatus.OK) {
        return;
    } else {
        createMarkers(results);
        if (pagination.hasNextPage) {
            pagination.nextPage();
        }
    }
}

function createMarkers(places) {
    var  markersArray = [];
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0, place; place = places[i]; i++) {
        var name = place.name;
        var address = places[i].vicinity;
        var content =
            '<div class="infoWindow">' +
            '<h1 class="infoPlaceName">' + name + '</h1>' +
            '<h5 class="infoPlaceAddress>">' + address + '</h5>' +
            '</div>';

        var image = {
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(25, 25)
        };
        let marker = new google.maps.Marker({
            map: map,
            icon: image,
            title: place.name,
            position: place.geometry.location
        });
        let infoWindow = new google.maps.InfoWindow({
            content: content
        });
        markersArray.push(marker);
        marker.addListener('click', function () {
            infoWindow.open(map, marker);
        });
        bounds.extend(place.geometry.location);
    }
    var markerCluster = new MarkerClusterer(map, markersArray,
        {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});

}
<!-- JS nav bar on home page -->
function openNav() {
    document.getElementById("mySidenav").style.width = "100%";
    $('.text-center').hide();

}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    $('.text-center').show();
}
<!-- JS nav bar on index page -->
function openNav2() {
    document.getElementById("mySidenav2").style.width = "250px";
    //document.getElementById("main").style.marginLeft = "250px";
    document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
}
function closeNav2() {
    document.getElementById("mySidenav2").style.width = "0";
}

<!-- JS nav bar for directions -->
function openNav3() {
    if (city == null && state == null) {
        show_message("Please select route");
        return;
    }
    $('#mySidenav3').addClass('col-sm-12');

}
function closeNav3() {
    $('#mySidenav3').removeClass('col-sm-12');
}

function showTraffic() {
    if (document.getElementById('traffic').checked) {
        trafficLayer = new google.maps.TrafficLayer();
        trafficLayer.setMap(map);

    } else {
        trafficLayer.setMap(null);
    }
}

/**
 * Identifies the City and State based on the destination
 * The City and State are going to be used for the weather input
 */
function cityStateDestination (){
    city = destination.split(",")[0];
    state = destination.split(",")[1];
}
var weatherCheck;
function getWeather() {
    if (city == null && state == null) {
        show_message("Please select route");
    }
    else if (weatherLoaded === false){
        $.ajax({
            dataType: 'jsonp',
            method: "GET",
            url: 'http://api.wunderground.com/api/dd19086be18c6fc3/alerts/almanac/conditions/forecast/q/' + state + '/' + city + '.json',

            success: function (result) {
                noAlerts(result);
                mapPageWeatherAccordian();

                var weatherImage = $('<img>', {src: result.current_observation.icon_url});
                var location = result.current_observation.display_location.full;
                var temp = result.current_observation.temp_f + '&#176;' + ' F';
                var humidity = result.current_observation.relative_humidity;
                var wind = result.current_observation.relative_humidity;
                $('#weatherImage').append(weatherImage);
                $('#weatherLocation').append(location);
                $('#weatherAlerts').append(alertmessage);
                $('#weatherTemp').append(temp);
                $('#weatherHumidity').append(humidity+" "+ "Humidity");
                var  forecastSet= result.forecast.simpleforecast.forecastday;
                weatherCheck = result;
                console.log('this is weather', weatherCheck);
                for (var i = 1; i < 4; i++) {
                    var dayOfWeek = forecastSet[i].date.weekday;
                    var weatherIcon = $('<img>', {src: forecastSet[i].icon_url});
                    var highTemp = "High" + " " + forecastSet[i].high.fahrenheit + '&#176;' + ' F';
                    var lowTemp = "Low" + " " + forecastSet[i].low.fahrenheit + '&#176;' + ' F';
                    wind = "Wind"+" "+forecastSet[i].avewind.mph + ' ' + forecastSet[i].avewind.dir;
                    $("#weatherDOW"+[i]).append(dayOfWeek);
                    $("#weatherIcon"+[i]).append(weatherIcon);
                    $("#weatherHigh"+[i]).append(highTemp);
                    $("#weatherLow"+[i]).append(lowTemp);
                    $("#weatherWind"+[i]).append(wind);
                    weatherLoaded = true;
                }
            }
        });
    }
    else{
        mapPageWeatherAccordian();

    }
}

function noAlerts(result){
    if (result.alerts.length === 0) {
        console.log('Test: ' + result.alerts);
        alertmessage = "No weather alerts.";
    } else {
        alertmessage = result.alerts[0].description;
    }
}

function getGasolineCost() {
    $.ajax({
        dataType: 'json',
        method: "POST",
        url: 'http://api.eia.gov/series/?api_key=13465938b39393fc5b239c466cf067b2&series_id=PET.EMM_EPM0_PTE_NUS_DPG.W',

        success: function (result) {
            pricePerGallon = result.series[0].data[0][1];
            console.log("Average price per gallon:"+ "$"+ pricePerGallon);
        }
    })
}

function mapPageWeatherAccordian() {
    $("#weatherDisplayContainer").toggle();
    var acc = document.getElementsByClassName("accordion");
    for (var i = 0; i < acc.length; i++) {
        acc[i].onclick = function () {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight) {
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        }
    }
}

function getInformation(choice,cityForEvent) {
    console.log('call get info at event_finder.js');
    $.ajax({
        data: {
            app_key: "9QPc4kCRH3JtNMsD"
        },

        dataType: 'jsonp',
        method: "get",
        // url: 'http://api.eventful.com/json/events/search?...&keywords=Las Vegas, NV, United States&date=2017020500-2017021500&app_key=9QPc4kCRH3JtNMsD',
        url: 'http://api.eventful.com/json/events/search?...&keywords='+choice+'&location='+cityForEvent+'&date=2017020400-2017071500&app_key=9QPc4kCRH3JtNMsD',

        success: function (result) {
            console.log('here is the result ',result);
            if (result.events === null) {
                show_message("No Events found");
            } else {
                for (var i = result.events.event.length-1; i >=0 ; i--) {
                    // cityEvent = result.events.event[i].city_name;
                    // eventAddress = result.events.event[i].venue_address;
                    // eventTitle = result.events.event[i].title;
                    var marker = create_event_marker(result.events.event[i]);
                    create_info_event(marker,result.events.event[i]);
                }
            }
        },
        error: function(){
            console.log('event finder not sucessful');
        }
    })
}

function getResults(){
    cityForEvent = $('#cityEvent').val();
    choice = $('input[name=choose]:checked').val();
    getInformation(choice,cityForEvent);
}

function set_val_destination(){
    $('#cityEvent').val(destination);
    console.log('des',destination);
}

function show_message(message){
    alert(message);
}


