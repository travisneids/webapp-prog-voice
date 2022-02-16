<?php

namespace App\Http\Controllers;

use App\Models\ActiveCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Twilio\TwiML\VoiceResponse;

class ConferenceController extends Controller
{
    public function wait()
    {
        return $this->generateWaitTwiml();
    }

    public function callStatusChanged(Request $request)
    {
        Log::info('https://tneids.ngrok.io/sip/call-status-changed', $request->all());
        return response()->json("success")->setStatusCode(200);
    }

    public function statusCallback(Request $request)
    {
        Log::info('Status Callback:', $request->all());
        return response()->json("success")->setStatusCode(200);
    }

    public function eventCallback(Request $request)
    {
        Log::info('Event Callback:', $request->all());
        return response()->json("success")->setStatusCode(200);
    }

    public function hold()
    {
        // Retrieve the first model matching the query constraints...
        $activeCall = ActiveCall::where('worker_id', 'worker1')->first();
        Log::info('active call', [$activeCall]);
        return $this->generateHoldTwiml($activeCall->conference_id);
    }

    public function connectClient(Request $request, Client $client)
    {
        $conferenceId = $request->get('CallSid');
        Log::info('call', $request->all());

        //$this->createCall('worker1', $conferenceId, $client, $request, false);
        $this->createCall('sipWorker1', $conferenceId, $client, $request, true);

        $activeCall = ActiveCall::firstOrNew(['worker_id' => 'worker1']);
        $activeCall->conference_id = $conferenceId;
        $activeCall->save();

        return $this->generateConferenceTwiml($conferenceId, false, true, '/conference/wait');
    }

    public function connectWorker1($conferenceId)
    {
        return $this->generateConferenceTwiml($conferenceId, true, true);
    }

    public function connectSipWorker1($conferenceId)
    {
        return $this->generateConferenceTwiml($conferenceId, true, true);
    }

    /**
     * @param $workerId
     * @param $conferenceId
     * @param $client
     * @param $request
     * @return string
     */
    protected function createCall($workerId, $conferenceId, $client, $request, $enableSip): string
    {
        $destinationNumber = 'client:' . $workerId;
        $twilioNumber = config('services.twilio')['number'];
        $path = str_replace($request->path(), '', $request->url()) . 'conference/connect/' . $conferenceId . '/' . $workerId;
        Log::info('createCall Path: ', [$path]);
        // if SIP enabled && sip domain
        // $client->calls->create('16126551428@travis.sip.twilio.io')

        $workerAddress = ($enableSip === true ? 'sip:17755425616@neidssipdemo.sip.twilio.com' : 'client:worker1');

        try {
            $client->calls->create(
                $workerAddress,
                $twilioNumber,
                [
                    'url' => $path
                ]
            );
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
        return 'ok';
    }

    private function generateConferenceTwiml($conferenceId, $startOnEnter, $endOnExit, $waitUrl = null)
    {
        if ($waitUrl === null) {
            $waitUrl = 'http://twimlets.com/holdmusic?Bucket=com.twilio.music.classical';
        }
        $response = new VoiceResponse();
        $dial = $response->dial();
        $dial->conference(
            $conferenceId,
            [
                'startConferenceOnEnter' => $startOnEnter,
                'endConferenceOnExit' => $endOnExit,
                'waitUrl' => $waitUrl,
                'statusCallback' => 'https://tneids.ngrok.io/conference/status-callback',
                'eventCallbackUrl' => 'https://tneids.ngrok.io/conference/event-callback',
                'statusCallbackEvent' => 'start end join leave mute hold'
            ]
        );

        Log::info('Conference Twiml: ', [$response]);

        return response($response)->header('Content-Type', 'application/xml');
    }

    private function generateWaitTwiml(){
        $response = new VoiceResponse();
        $response->say(
            'Thank you for calling Tees Tree Services. Please hold while we connect your call.',
            ['voice' => 'alice', 'language' => 'en-GB']
        );
        $response->play('http://com.twilio.music.classical.s3.amazonaws.com/BusyStrings.mp3');
        return response($response)->header('Content-Type', 'application/xml');
    }

    private function generateHoldTwiml($conferenceId)
    {
        $response = new VoiceResponse();
        $dial = $response->dial();
        $dial->conference(
            $conferenceId,
            [
                'beep' => 'false'
            ]
        );

        Log::info('The response', [$response]);

        return response($response)->header('Content-Type', 'application/xml');
    }
}
