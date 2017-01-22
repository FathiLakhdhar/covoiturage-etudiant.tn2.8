/**
 * Created by TL50 on 27/12/2016.
 */

function fillChoiceList() {

    var selectMarque=$("select.marque");
    var selectModele=$("select.modele");

    selectModele.attr("disabled","disabled");
    var marque = selectMarque.val();
    var url = selectMarque.data("car-models-url");
    if (marque.length > 0) {
        $('img.ajax-progress').css("display","block");
        url = url.replace("__MARQUE__", marque);
        $.getJSON(url, {ajax: "true"}, function (data) {
            var options = "";
            for (var i = 0; i < data.length; i++) {
                $.each( data[i], function( key, val ) {
                    options += '<option value="' + key + '">' + val + "</option>"
                });
            }

            selectModele.html(options)
        }).done(function(){
            selectModele.prop("disabled", false);
            $('img.ajax-progress').css("display","none");
        })
    } else {
        selectModele.html("")
    }




}

$(document).ready(function(){

    fillChoiceList();
    $("select.marque").on("change click", fillChoiceList);
    $('.matricule').inputmask(
        {
            mask: "9[9][9]- TU -9[9][9][9]",
            greedy: false
        });
});
