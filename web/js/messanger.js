( function ($) {

    $.fn.AddMsgToMenuMsg = function (data) {

        var element = this;

        $.each(data, function (key, $thread) {

            var $class = "";
            if (!$thread.message.isRead) {
                $class = "bg-info";
            }

            $new = $('<li class="' + $class + '">' +
                '<a href="' + $thread.link + '#message_' + $thread.message.id + '">' +
                '<div class="pull-left">' +
                '<img src="' + $thread.message.icon + '" class="img-circle" alt="' + $thread.message.sender + '">' +
                '</div>' +
                '<h4>' +
                $thread.message.sender +
                '<small><i class="fa fa-clock-o"></i>' + $thread.message.createAt + '</small>' +
                '</h4>' +
                '<p>' + $thread.message.body + '</p>' +
                '</a>' +
                '</li>');

            element.append($new);
        });
    };


    $.fn.AddNotifToMenuNotif = function (notifications) {

        var element = this;
        var $url = $('#notification-url').data('notification-url');
        $.each(notifications, function (key, $notif) {

            var $class = "";
            var $icon = "";

            if (!$notif.isRead)$class = "bg-info";

            switch ($notif.type) {
                case "demande":
                    $icon = "ion ion-information-circled text-info";
                    break;
                case "accepte":
                    $icon = "ion ion-checkmark-circled text-success";
                    break;
                case "refuse":
                    $icon = "ion ion-close-circled text-danger";
                    break;
                default:
                    $icon = "";
                    break;
            }

            var $marker_lu='<i class="' + $icon + ' pull-left"></i>';


            $new = $('<li class="' + $class + ' pos-relative">' +
                '<a href="'+$url+'#notif_'+$notif.id+'">' +
                $marker_lu+
                '<p>' + $notif.contenu + '</p>' +
                '</a>' +
                '<i class="ion ion-android-radio-button-on marker-lu"></i>' +
                '</li>');

            element.append($new);
        });
    }


}(jQuery));


function isEqualObjetThread(data, cache) {

    var bool = true;

    if (cache.length == 0 || data.length == 0 || data.length > cache.length) {
        bool = false;
        return false;
    }

    $.each(data, function (key, $thread) {
        if ($thread.id != cache[key].id) {
            bool = false;
            return false;
        }
        if ($thread.message.id != cache[key].message.id) {
            bool = false;
            return false;
        }
    });
    return bool;
}
function isEqualObjetNotif(data, cache) {

    var bool = true;

    if (cache.length == 0 || data.length == 0 || data.length > cache.length) {
        bool = false;
        return false;
    }

    $.each(data, function (key, $notif) {
        if ($notif.id != cache[key].id || ( ($notif.id == cache[key].id)&&($notif.isRead != cache[key].isRead) ) ) {
            bool = false;
            return false;
        }
    });
    return bool;
}

function UpdateMenuMsg() {
    var $cache = [];
    var menu_message = $('#menu_messages');

    $('#envelope_click').click(function () {
        var $url = menu_message.data("url-msg");

        $('#msg_load').show();
        menu_message.children().remove();
        menu_message.AddMsgToMenuMsg($cache);

        $.getJSON($url, function (data) {
            if (isEqualObjetThread(data, $cache)) {
                $('#msg_load').hide();
                return false;
            }
            else {
                $('#msg_load').hide();
                menu_message.children().remove();
                menu_message.AddMsgToMenuMsg(data);
                $cache = data;
            }


        });
    });
}


function UpdateMenuNotif() {
    var $cache = [];
    var menu_notif = $('#menu_notifications');

    $('#notif_click').click(function () {
        var $url = menu_notif.data("url-notif");

        $('#notif_load').show();
        menu_notif.children().remove();
        menu_notif.AddNotifToMenuNotif($cache);

        $.getJSON($url, function (data) {
            if (isEqualObjetNotif(data.notifications, $cache)) {
                $('#notif_load').hide();
                return false;
            }
            else {
                $('#notif_load').hide();
                menu_notif.children().remove();
                menu_notif.AddNotifToMenuNotif(data.notifications);
                $cache = data.notifications;
            }


        });
    });
}


function NotifMessage() {
    var $cache = $('#nb_msg_unread').data('thread-nb-unread');
    var menu_message = $('#menu_messages');
    var htmlelementUnreadMsg = $('li.message_nb_unread');
    var $url = htmlelementUnreadMsg.data("messages-nb-unread");
    setInterval(function () {
        $.getJSON($url, function (data) {
            if ($cache != data['NbUnreadMessages']) {
                $('.nb_msg_unread').each(function () {
                    $(this).html(data['NbUnreadMessages']);
                });
                $('#chatAudio')[0].play();
                $cache = data['NbUnreadMessages'];
            }
        });
    }, 3000);
}


function NotifNotification() {
    var $cache = $('#nb_notif_unread').data('notif-nb-unread');
    var menu_notif = $('#menu_notifications');
    var htmlelementUnreadNotif = $('li.notifications_nb_unread');
    var $url = htmlelementUnreadNotif.data("notifications-nb-unread-url");
    setInterval(function () {
        $.getJSON($url, function (data) {
            if ($cache != data['NbUnreadNotifications']) {
                $('.nb_notif_unread').each(function () {
                    $(this).html(data['NbUnreadNotifications']);
                });
                $('#chatAudio')[0].play();
                $cache = data['NbUnreadNotifications'];
            }
        });
    }, 3000);
}

$(document).ready(function () {
/*
    UpdateMenuMsg();
    UpdateMenuNotif();
    NotifMessage();
    NotifNotification();
    */
});
