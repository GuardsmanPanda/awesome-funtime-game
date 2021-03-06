<?php

namespace Areas\System;

use Carbon\Carbon;
use App\Tools\Req;
use App\Models\User;
use App\Tools\Translator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Infrastructure\Language\LanguageUtility;
use Infrastructure\Achievement\AchievementUtility;

class AuthenticationController extends Controller {
    private string $client_id = 'q8q6jjiuc7f2ef04wmb7m653jd5ra8';

    public function logout(): RedirectResponse  {
        Session::flush();
        return Redirect::to('/game', 303);
    }

    public function twitchLogin(Request $r): RedirectResponse {
        $url = "https://id.twitch.tv/oauth2/token?client_id=" . $this->client_id;
        $url .= "&client_secret=" . config('settings.twitch_secret');
        $url .= "&redirect_uri=".urlencode(config('app.url') . '/auth/twitch-login');
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
        if ($user === null && isset($twitch_user['email'])) {
            $user = User::firstWhere('email', '=', $twitch_user['email']);
        }
        if ($user === null) {
            $user = new User();
            $user->language_id = 1;
            $user->achievement_refresh_needed = true;
        }
        $user->twitch_id = $twitch_user['id'];
        $user->display_name = $twitch_user['display_name'];
        $user->email = $twitch_user['email'] ?? null;
        $this->updateUserAndAddRealm($user, 1);
        return $this->logUserIn($user, $r->get('state') ?? '/game');
    }


    public function loginWithSignedPayload(Request $r): RedirectResponse {
        $p = str_replace(' ', '+', $r->get('payload'));
        $s = base64_decode(str_replace(' ', '+', $r->get('signature')));
        $rr = openssl_verify($p, $s, File::get(storage_path('app/gnx_public_key.pem')), "sha256WithRSAEncryption");
        if ($rr !== 1) {
            abort(403, 'Invalid signature');
        }
        $content = json_decode(base64_decode($p), false, 512, JSON_THROW_ON_ERROR);
        if (Carbon::parse($content->timestamp) < Carbon::now()->subMinutes(2)) {
            abort(403, 'Signature too old');
        }
        $user = User::firstWhere('work_email', '=', $content->email);
        if ($user === null) {
            $user = new User();
            $user->email = $content->email;
            $user->work_email = $content->email;
            $user->language_id = 1;
            $user->achievement_refresh_needed = true;
        }
        $user->display_name = $content->display_name;
        $this->updateUserAndAddRealm($user, 2);
        return $this->logUserIn($user);
    }

    private function updateUserAndAddRealm(User $user, int $realm_id):void {
        $user->country_code = Req::header('CF-IPCountry') ?? 'XX';
        $user->logged_into_realm_id = $realm_id;
        $user->last_login_at = Carbon::now();
        $user->save();
        if ($user->achievement_refresh_needed) {
            AchievementUtility::updateAllUserAchievements($user);
        }
        DB::insert("
            INSERT INTO realm_user (realm_id, user_id) VALUES (?, ?)
            ON CONFLICT (realm_id, user_id) DO NOTHING
        ", [$realm_id, $user->id]);
    }


    private function logUserIn(User $user, string $path = '/game'): RedirectResponse {
        session()->migrate(true);
        session()->put('login_id', $user->id);
        Translator::setSessionLanguage($user);
        return Redirect::to($path, 303);
    }
}
