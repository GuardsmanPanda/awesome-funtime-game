<?php

namespace Areas\System;

use Carbon\Carbon;
use App\Tools\Req;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class AuthenticationController extends Controller {
    private string $client_id = 'q8q6jjiuc7f2ef04wmb7m653jd5ra8';

    public function twitchLogin(Request $r): RedirectResponse {
        $url = "https://id.twitch.tv/oauth2/token?client_id=" . $this->client_id;
        $url .= "&client_secret=" . config('settings.twitch_secret');
        $url .= "&redirect_uri=https://gman.bot/oauth/twitch";
        $url .= "&scope=" . $r->get('scope');
        $url .= "&grant_type=authorization_code";
        $url .= "&code=" . $r->get('code');

        $resp = Http::post($url);
        if ($resp->failed()) {
            Log::warning($resp->body());
            abort(500, 'Failure to log in.');
        }

        $twitch_token =$resp->json();
        $token = $twitch_token['access_token'];
        $resp = Http::withToken($token)
            ->withHeaders(['Client-ID' => $this->client_id])
            ->get("https://api.twitch.tv/helix/users");
        if ($resp->failed()) {
            Log::warning($resp->body());
            abort(500, 'Getting Twitch ID Failed.');
        }
        $twitch_user = $resp->json()['data'][0];
        $user = User::firstWhere('twitch_id', '=', $twitch_user['id']);
        if ($user === null) {
            $user = User::firstWhere('email', '=', $twitch_user['email']);
        }
        if ($user === null) {
            $user = new User();
        }
        $user->twitch_id = $twitch_user['id'];
        $user->display_name = $twitch_user['display_name'];

        return $this->logUserIn($user);
    }

    private function logUserIn(User $user): RedirectResponse {
        $user->country_code = Req::header('CF-IPCountry') ?? 'XX';
        $user->last_login_at = Carbon::now();
        $user->save();
        session()->migrate(true);
        session()->put('login_id', $user->id);
        return Redirect::to('/game', 303);
    }
}
