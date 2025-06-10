<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Client\Provider\Google;

class GoogleCalendarController extends Controller
{
    protected Google $provider;

    public function __construct()
    {
        $this->provider = new Google([
            'clientId'     => config('google-calendar.client_id'),
            'clientSecret' => config('google-calendar.client_secret'),
            'redirectUri'  => config('google-calendar.redirect_uri'),
            'accessType'   => 'offline',
            'scopes'       => ['https://www.googleapis.com/auth/calendar'],
            'prompt'       => 'consent',
        ]);
    }

    public function redirectToGoogle()
    {
        $authUrl = $this->provider->getAuthorizationUrl();
        session(['oauth2state' => $this->provider->getState()]);
        return redirect()->away($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $state = $request->input('state');
        if (empty($state) || $state !== session('oauth2state')) {
            session()->forget('oauth2state');
            return redirect()->route('filament.pages.google-calendar')->withErrors('Invalid OAuth state');
        }

        try {
            $token = $this->provider->getAccessToken('authorization_code', [
                'code' => $request->input('code')
            ]);
        } catch (\Exception $e) {
            return redirect()->route('filament.pages.google-calendar')->withErrors('Failed to get access token: ' . $e->getMessage());
        }

        $user = Auth::user();
        $user->setGoogleCalendarToken($token->jsonSerialize());

        return redirect()->route('filament.pages.google-calendar')->with('success', 'Google Calendar connected successfully!');
    }
}
