import $ from 'jquery';
const Device = require('@twilio/voice-sdk').Device;

let callStatus = $("#callStatus");
let answerBtn = $(".answerBtn");
let hangUpBtn = $(".hangUpBtn");
let holdBtn = $(".holdBtn");
let startBtn = document.getElementById('startBtn');

let device = null;

function updateCallStatus(status) {
    callStatus.attr('placeholder', status);
}

startBtn.addEventListener("click", setupClient);

function addDeviceListeners(device) {
    device.on('ready', function (_device) {
        console.log('device ready')
        updateCallStatus("Ready");
    });

    /* Report any errors to the call status display */
    device.on('error', function (error) {
        updateCallStatus("ERROR: " + error.message);
    });

    /* Callback for when Twilio Client initiates a new connection */
    device.on('connect', function (connection) {
        // Enable the hang up button and disable the call buttons
        hangUpBtn.prop("disabled", false);
        holdBtn.prop("disabled", false);
        answerBtn.prop("disabled", true);

        // If phoneNumber is part of the connection, this is a call from a
        // support agent to a customer's phone
        if ("phoneNumber" in connection.message) {
            updateCallStatus("In call with " + connection.message.phoneNumber);
        } else {
            // This is a call from a website user to a support agent
            updateCallStatus("In call with support");
        }
    });

    /* Callback for when a call ends */
    device.on('disconnect', function(connection) {
        // Disable the hangup button and enable the call buttons
        hangUpBtn.prop("disabled", true);
        holdBtn.prop("disabled", true);

        updateCallStatus("Ready");
    });

    /* Callback for when Twilio Client receives a new incoming call */
    device.on('incoming', function(connection) {
        updateCallStatus("Incoming support call");

        // Set a callback to be executed when the connection is accepted
        connection.accept(function() {
            updateCallStatus("In call with customer");
        });

        // Set a callback on the answer button and enable it
        answerBtn.click(function() {
            connection.accept();
        });
        answerBtn.prop("disabled", false);
    });
}

function setupClient() {
    console.warn("CLICK");
    $.post("/token", {
        forPage: window.location.pathname,
        _token: $('meta[name="csrf-token"]').attr('content')
    }).done(function (data) {
        device = new Device(data.token);
        addDeviceListeners(device);

        device.register();
    }).fail(function () {
        updateCallStatus("Could not get a token from server!");
    })
}

/* End a call */
window.hangUp = function() {
    device.disconnectAll();
};
