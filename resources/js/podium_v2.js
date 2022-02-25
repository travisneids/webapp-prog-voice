$(function () {
    const initiateButton = document.getElementById('initiateButton')
    const callStatusInput = document.getElementById('callStatusInput')
    const phoneNumberInput = document.getElementById('phoneNumberInput')
    const dialButton = document.getElementById('dialButton')
    const answerButton = document.getElementById('answerButton')
    const hangUpButton = document.getElementById('hangUpButton')
    const holdButton = document.getElementById('holdButton')
    const callControlsContainer = document.getElementById('callControlsContainer')
    const logContainer = document.getElementById('log')

    let device;
    let token;

    dialButton.onclick = (e) => {
        e.preventDefault()
        // makeOutgoingCall()
    }
    initiateButton.addEventListener("click", startupClient)

    async function startupClient() {
        logContainer.classList.remove("hide");
        log("Initializing device");
        log('Testing a log')
        try {
            const data = await $.post("/token", {'identity':'worker1'});
            log("Got a token.");
            token = data.token;

            intitializeDevice();
        } catch (err) {
            console.log(err);
            log("An error occurred. See your browser console for more information.");
        }
    }

    function intitializeDevice() {
        device = new Twilio.Device(token, {
            logLevel: 1,
            codecPreferences: ["opus", "pcmu"]
        });

        addDeviceListeners(device);
        initiateButton.innerText = "Online"
        device.register();
    }

    function addDeviceListeners(device) {
        device.on("registered", function () {
            log("Twilio.Device is ready")
            callStatusInput.value = "Device is online!"
        })

        device.on("error", function (error) {
            log("Twilio.Device Error: " + error.message);
        })

        device.on("incoming", handleIncomingCall);
    }

    function putConferenceOnHold(call) {
        console.log(call)
        log("Putting conference on hold...")

        try {
            const data = $.getJSON("/conference/hold")

            // callOnHold()
        } catch (err) {
            console.log(err)
            log("An error occured putting conference on hold")
        }
    }

    function handleIncomingCall(call) {
        log(`Incoming call from ${call.parameters.From}`);
        callStatusInput.value = `Incoming call from ${call.parameters.From}`
        const webApp = document.querySelector('#webapp')


        answerButton.disabled = false

        answerButton.onclick = () => {
            acceptIncomingCall(call)
        }

        holdButton.onclick = () => {
            putConferenceOnHold(call)
        }

        hangUpButton.onclick = () => {
            hangupIncomingCall(call);
        }

        call.on("cancel", handleDisconnectedIncomingCall);
        call.on("disconnect", handleDisconnectedIncomingCall);
        call.on("reject", handleDisconnectedIncomingCall);
    }

    function rejectIncomingCall(call) {
        call.reject();
        log("Rejected incoming call");
        resetUi()
    }

    function hangupIncomingCall(call) {
        call.disconnect();
        log("Hanging up incoming call");
        resetUi()
    }

    function handleDisconnectedIncomingCall() {
        log("Incoming call ended.");
        resetUi()
    }

    function acceptIncomingCall(call) {
        call.accept();
        answerButton.disabled = true
        hangUpButton.disabled = false
        holdButton.disabled = false
    }

    function resetUi()  {
        //callStatusInput.value = "Device is ready!"
        answerButton.disabled = true
        hangUpButton.disabled = true
        holdButton.disabled = true
    }

    function resetAnswer() {
        answerButton.disabled = true
        hangUpButton.disabled = false
        holdButton.disabled = false
    }

    // Activity log
    function log(message) {
        logContainer.innerHTML += `<p class="log-entry">&gt;&nbsp; ${message} </p>`;
        logContainer.scrollTop = logContainer.scrollHeight;
    }
})
