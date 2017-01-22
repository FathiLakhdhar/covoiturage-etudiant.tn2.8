/**
 * Created by Toshiba on 14-03-2016.
 */

// A $( document ).ready() block.
function AutoComplete($id) {
    // Create the autocomplete object and associate it with the UI input control.
    // Restrict the search to the default country, and to place type "address".
    var $elem = document.getElementById($id);

    $autoCompelt = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */
        ($elem), {
            types: [],
            componentRestrictions: {'country': 'TN'}
        });

    $autoCompelt.addListener('place_changed', function () {
        var place = $autoCompelt.getPlace();
        if (null === place)return;
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }
        if (place.geometry && (typeof map !="undefined")) {
            map.panTo(place.geometry.location);
            map.setZoom(7);
            $($elem).siblings('input[class=latitude]').val(place.geometry.location.lat());
            $($elem).siblings('input[class=longitude]').val(place.geometry.location.lng());

            calculateAndDisplayRoute(directionsService, directionsDisplay);

        } else {
            //window.alert("Autocomplete's returned place contains no geometry");
            console.log("Autocomplete's returned place contains no geometry Or Map not defined");
        }

    });


}


$(document).ready(function () {
    $('[inputAutoComplete]').each(function () {
        //console.log($(this).attr('id'));
        document.getElementById($(this).attr('id')).addEventListener('focusin', function () {
            //console.log($(this).attr('id'));
            AutoComplete($(this).attr('id'));
        });
    });

});