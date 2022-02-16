<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;

class TokenController extends Controller
{
    public function __construct(AccessToken $accessToken)
    {
        $this->accessToken=$accessToken;
    }

    public function newToken(Request $request)
    {
        $forPage = $request->input('forPage');
        $applicationSid = config('services.twilio')['applicationSid'];

        if ($forPage === route('dashboard', [], false)) {
            $this->accessToken->setIdentity('support_agent');
        } else {
            $this->accessToken->setIdentity('customer');
        }

        // Create Voice grant
        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid($applicationSid);

        // Optional: add to allow incoming calls
        $voiceGrant->setIncomingAllow(true);

        // Add grant to token
        $this->accessToken->addGrant($voiceGrant);

        // render token to string
        $token = $this->accessToken->toJWT();

        return response()->json(['token' => $token]);
    }

    public function getTokenV2(Request $request)
    {
        $identity = $request->get('identity');

        $accessToken = new AccessToken(
            config('services.twilio')['accountSid'],
            config('services.twilio')['apiKey'],
            config('services.twilio')['apiToken'],
            3600,
            $identity
        );

        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid(config('services.twilio')['applicationSid']);

        $voiceGrant->setIncomingAllow(true);

        $accessToken->addGrant($voiceGrant);

        $token = $accessToken->toJWT();

        return response()->json(['identity' => $identity, 'token' => $token]);
    }
}
