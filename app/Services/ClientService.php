<?php

namespace App\Services;

use App\Models\OauthClient;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientService
{
    public function getGrantClient()
    {
        try {
            return OauthClient::where('name', config('services.passport.grant_client_name'))->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Grant client don\'t exist');
        }
    }
}
