<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MessengerMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessengerController extends Controller
{
    public function verify(Request $request)
    {
        $verify_token = env('MESSENGER_VERIFY_TOKEN');


        $mode = $request->input('hub.mode');
        $token = $request->input('hub.verify_token');
        $challenge = $request->input('hub.challenge');

        if ($mode === 'subscribe' && $token === $verify_token) {
            return response($challenge, 200);
        }

        return response('Forbidden', 403);
    }

    public function handle(Request $request)
    {
        Log::info(json_encode($request->all()));

        $data = $request->all();

        if (isset($data['entry'][0]['messaging'][0])) {
            $messaging = $data['entry'][0]['messaging'][0];
            $senderId = $messaging['sender']['id'];

            if (isset($messaging['message']['text'])) {
                $messageText = $messaging['message']['text'];
                $this->sendMessage($senderId, "Thank you for your message");
            }
        }
        return response('EVENT_RECEIVED', 200);
    }


    private function sendMessage($recipient_id, $message_text)
    {
        $access_token = config('app.messenger_access_token');
        $url = "https://graph.facebook.com/v20.0/me/messages?access_token={$access_token}";

        Http::post($url, [
            'recipient' => ['id' => $recipient_id],
            'message' => ['text' => $message_text],
        ]);
    }
}
