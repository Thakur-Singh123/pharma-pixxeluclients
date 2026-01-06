<?php

namespace App\Services;

use Google\Client;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\Http;


class FirebaseService
{
    private function getAccessToken(): string
    {
        $client = new Client();

        $client->setAuthConfig([
            'type'         => 'service_account',
            'client_email'=> env('FIREBASE_CLIENT_EMAIL'),
            'private_key' => env('FIREBASE_PRIVATE_KEY'),
            'client_id'   => env('FIREBASE_CLIENT_ID'),
            'project_id'  => env('FIREBASE_PROJECT_ID'),
        ]);

        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->fetchAccessTokenWithAssertion();

        return $client->getAccessToken()['access_token'];
    }

    public function sendNotification(string $deviceToken, string $title, string $body, array $data = [])
    {
        try {
        $accessToken = $this->getAccessToken();
         $payload = [
                'message' => [
                    'token' => $deviceToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $data,
                ],
        ];


       //CORRECT URL
        $url = 'https://fcm.googleapis.com/v1/projects/' .env('FIREBASE_PROJECT_ID') .'/messages:send';
        $response = Http::withToken($accessToken)->post($url, $payload);
            return [
            'success'     => $response->successful(),
            'status_code'=> $response->status(),
            'response'   => $response->json(),
           ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function sendToUser($user, array $payload): bool
    {
        // User ke saare device tokens
        $tokens = DeviceToken::where('user_id', $user->id)->pluck('token');
        if ($tokens->isEmpty()) {
            return false;
        }

        foreach ($tokens as $token) {
            $this->sendNotification($token,$payload['title'],$payload['message'],$payload
            );
        }

        return true;
    }
}
