<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use App\Services\ClientService;
use App\Services\TokenService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    private $client;
    private $error;

    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        $clientService = new ClientService;
        try {
            $this->client = $clientService->getGrantClient();
        } catch (ModelNotFoundException $exception) {
            $this->error = response()->json(['message' => $exception->getMessage()], 404);
        }
    }

    /**
     * @param RegistrationRequest $request
     * @return object
     */
    public function register(RegistrationRequest $request): object
    {

        if ($this->error) {
            return $this->error;
        }

        $credentials = [
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ];
        $user = User::create($credentials);
        if (!$user) {
            return response()->json(['error' => 'Registration failed!'], 422);
        }

        $tokenService = new TokenService();
        return $tokenService->getTokensAuth('Registration', $this->client, $request->email, $request->password);
    }

    /**
     * @param LoginRequest $request
     * @return object
     */
    public function login(LoginRequest $request): object
    {
        if ($this->error) {
            return $this->error;
        }

        if (!Auth::once($request->only('email', 'password'))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $tokenService = new TokenService();
        return $tokenService->getTokensAuth('Login', $this->client, $request->email, $request->password);
    }

    /**
     * @param Request $request
     * @return array|JsonResponse|mixed
     */
    public function refresh(Request $request)
    {
        if ($this->error) {
            return $this->error;
        }

        $refreshToken = $request->header('refresh-token');
        $response = Http::post(route('passport.token'), [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope' => '*',
        ]);

        if ($response) {
            return response()->json($response->json(), $response->status());
        }

        return response()->json(['message' => 'Something went wrong!'], 400);
    }

    /**
     * @param Request $request
     * @return object
     */
    public function logout(Request $request): object
    {
        $response = $request->user()->currentAccessToken()->revoke();
        if ($response) {
            return response()->json(['message' => 'Successfully logged out'], 200);
        }

        return response()->json(['message' => 'Something went wrong!'], 400);
    }
}
