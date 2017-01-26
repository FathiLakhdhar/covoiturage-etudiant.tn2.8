/**
 * Created by TL50 on 23/01/2017.
 */

var webSocket = WS.connect("ws://127.0.0.1:8080");

webSocket.on("socket/connect", function(session){
    //session is an Autobahn JS WAMP session.
    console.log("Successfully Connected!");


    session.subscribe("ws/acme/channel", function(uri, payload){
        console.log("Received message", payload.msg);
    });
    /*session.subscribe("ws/acme/chat/room/1", function(uri, payload){
        console.log("Received message", payload.msg);
    });*/
    session.publish("ws/acme/channel", {msg: "This is a message!"});

});

webSocket.on("socket/disconnect", function(error){
    //error provides us with some insight into the disconnection: error.reason and error.code

    console.log("Disconnected for " + error.reason + " with code " + error.code);
});
