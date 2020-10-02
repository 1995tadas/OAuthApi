<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TokenService
{
    /**
     * @param object $client
     * @param string $email
     * @param string $password
     * @return array|mixed
     */
    public function getTokens(object $client, string $email, string $password)
    {
        $response = Http::post(route('passport.token'),
            [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '*',
            ],
        );

        if ($response) {
            return $response->json();
        }
    }

    /**
     * @param string $type
     * @param object $client
     * @param string $email
     * @param string $password
     * @return object
     */
    public function getTokensAuth(string $type, object $client, string $email, string $password): object
    {
        $response = $this->getTokens($client, $email, $password);
        $success = ['success' => $type . ' was successful!'];
        if (!$response) {
            return response()->json(array_merge($success, ['error' => 'Error occurred while issuing tokens']), 400);
        }

        return response()->json(array_merge($success, ['hint' => 'Save tokens for later use'], $response), 201);
    }

}
