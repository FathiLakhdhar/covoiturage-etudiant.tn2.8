/**
 * Created by Toshiba on 28-02-2016.
 */

function initialize() {
    //var map;
    var mapProp = {
        center: new google.maps.LatLng(33.7774359, 9.4269145),
        zoom: 6,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        panControl: false,
        zoomControl: false,
        streetViewControl: false
    };
    map = new google.maps.Map(document.getElementById("map"), mapProp);


    directionsDisplay.setMap(map);

    document.getElementById('btn-route').addEventListener('click', function () {
        calculateAndDisplayRoute(directionsService, directionsDisplay);
    });


}



// When the user selects a city, get the place details for the city and
// zoom the map in on the city.

function getLocation(value) {
    var name = $(value + "_lieu").val();
    var latitude = $(value + "_latitude").val();
    var longitude = $(value + "_longitude").val();
    if (latitude && longitude) {
        latitude = parseFloat(latitude);
        longitude = parseFloat(longitude);
        return new google.maps.LatLng(latitude, longitude)
    }
    return name
}

function getStopoversLocation($container) {
    var stopoversLocation = [];

    $container.find('input[type=text]').each(function () {
        var name = $(this).attr("id").replace(new RegExp("_lieu$", "gm"), "");
        if ($("#" + name + "_lieu").length > 0) {
            var location = getLocation("#" + name);
            if (location) {
                stopoversLocation.push({ location: location })
            }
        }
    });
    return stopoversLocation;
}


function getDurationText(secs) {

    var sec_num = parseInt(secs, 10); // don't forget the second param
    var hours = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    //if (hours   > 1) {hours   = "0"+hours;}
    if (minutes < 10) {
        minutes = "0" + minutes;
    }
    if (seconds < 10) {
        seconds = "0" + seconds;
    }
    var time = hours + ':' + minutes;


    return time;
}

function getDistanceText(metre) {
    return metre / 1000 + ' km';
}


function prixTotal() {
    var InputTotalPrix;
    var prixTotal = 0;

    if ($('#wanasni_trajetbundle_trajetunique_frequence').val() === 'UNIQUE') {

        InputTotalPrix = $('#wanasni_trajetbundle_trajetunique_totalPrix');
    } else {
        InputTotalPrix = $('#wanasni_trajetbundle_trajetregulier_totalPrix');
    }

    $Segments.find('input.prix').each(function () {
        prixTotal += parseInt($(this).val());
    });

    if (prixTotal) {
        InputTotalPrix.val(prixTotal);
    } else {
        InputTotalPrix.val(0);
    }

}

function setPrixOptimal(input, $metre) {

    var prixOpt = 0;


    var url = $Segments.data("prix-optimal-url");
    url = url.replace("__METRE__", $metre);

    $.getJSON(url, { ajax: "true" }, function (data) {
        prixOpt = data['PrixOptimal'];
        input.val(prixOpt);
        console.log(prixOpt);
        //prixTotal();
    });

}


function NewSegment(route, index) {


    var $prototype = $($Segments.attr('data-prototype')
        .replace(/__name__label__/g, index
        )
        .replace(/__name__/g, index)
    );

    var inputPrix = $prototype.find('input');
    inputPrix.addClass("text-indent form-control input-sm");
    var $segment = $('<ul class="segment alert"></ul>');
    var $seg_info = $(
        '<li>' + route.legs[index].start_address +
        '<i class="ion ion-arrow-right-a"></i>'
        + route.legs[index].end_address + '</li>' +
        '<li>' + route.legs[index].distance.text + '</li>' +
        '<li>' + route.legs[index].duration.text + '</li>'
    );

    $segment.append($seg_info);
    $segment.append($prototype);

    var $a = $('<a href="Javascript:void(0);" data-metre="' + route.legs[index].distance.value + '" class="label label-success">Prix Optimal</a>');

    $a.on('click', function () {
        setPrixOptimal(inputPrix, route.legs[index].distance.value);
    });

    $segment.append($a);

    $Segments.append($segment);



    inputPrix.keypress(function (e) {
        if (e.which < 48 || e.which > 57) {
            return false;
        }
    });

    inputPrix.spinner({
        min: 0
    });

    inputPrix.on("keyup keydown blur", function () {
        prixTotal();
    });


}

function calculateAndDisplayRoute(directionsService, directionsDisplay) {


    if ($Origine.val().length > 0 && $distination.val().length > 0) {

        directionsService.route({
            origin: $Origine.val(),
            destination: $distination.val(),
            waypoints: getStopoversLocation($container),
            travelMode: google.maps.TravelMode.DRIVING
        }, function (response, status) {
            if (status === google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
                console.log(response);
                var route = response.routes[0];


                var totalDistance = 0;
                var input_totalDistance = $('.totalDistance');
                var totalDuration = 0;
                var input_totalDuration = $('.totalDuration');

                var summaryPanel = document.getElementById('directions-panel');
                summaryPanel.innerHTML = '';


                $('ul.Segments .segment').each(function () {
                    $(this).remove();
                });


                // For each route, display summary information.
                for (var i = 0; i < route.legs.length; i++) {
                    totalDuration += route.legs[i].duration.value;
                    totalDistance += route.legs[i].distance.value;
                    NewSegment(route, i);
                }
                input_totalDuration.val(getDurationText(totalDuration));
                input_totalDistance.val(getDistanceText(totalDistance));


            } else {
                console.log('Directions request failed due to ' + status);
            }
        });

    }

}



$(document).ready(function () {


    $Origine= null;
    $distination=null;
    $container = $('#waypoints');
    $Segments = $('#Segments');

    if ($('#wanasni_trajetbundle_trajetunique_frequence').val() === 'UNIQUE') {

        console.log('UNIQUE');

        $Origine = $('#wanasni_trajetbundle_trajetunique_Origine_lieu');
        $distination = $('#wanasni_trajetbundle_trajetunique_Destination_lieu');
        $arrPrix = $('#wanasni_trajetbundle_trajetunique_arrPrix');


    } else {
        console.log('REGULAR');

        $Origine = $('#wanasni_trajetbundle_trajetregulier_Origine_lieu');
        $distination = $('#wanasni_trajetbundle_trajetregulier_Destination_lieu');
        $arrPrix = $('#wanasni_trajetbundle_trajetregulier_arrPrix');
    }

     directionsService = new google.maps.DirectionsService;
     directionsDisplay = new google.maps.DirectionsRenderer;

    initialize();
    

    $Segments.children().each(function () {
        $(this).remove();
    });
    calculateAndDisplayRoute(directionsService, directionsDisplay);
});



