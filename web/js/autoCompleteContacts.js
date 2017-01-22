$(function () {

    function split(val) {
        return val.split(/,\s*/);
    }

    function extractLast(term) {
        return split(term).pop();
    }

    var cache = {};
    var $input = $(".autocompleteContacts");
    $input.bind("keydown", function (event) {
            if (event.keyCode === $.ui.keyCode.TAB &&
                $(this).autocomplete("instance").menu.active) {
                event.preventDefault();
            }
        })
        .autocomplete({
            minLength: 2,
            source: function (request, response) {
                var term = request.term;
                if (term in cache) {
                    response(cache[term]);
                    return;
                }
                var $url = $input.data("url");
                $.getJSON($url, {q : term} ,function (data, status, xhr) {
                    response(data.items);
                    }
                );

            },
            select: function( event, ui ) {
                var terms = split( this.value );
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push( ui.item.username );
                // add placeholder to get the comma-and-space at the end
                terms.push( "" );
                this.value = terms.join( ", " );
                return false;
            }
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .append('<a><img src='+item.icon+' class="img-circle" style="max-width: 30px;width: 30px;margin-right: 10px">'+item.username+'<small>('+item.fistname+' '+item.lastname+')</small>' +'</a>')
            .appendTo( ul );
    };




});

