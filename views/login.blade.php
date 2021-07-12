<div class="flex items-center justify-center min-h-screen bg-cover" style="background-image: url('/static/img/backgrounds/mountain.jpg')">
    <div class="py-6 px-10 backdrop-filter backdrop-blur-px rounded-md shadow-xl backdrop-brightness-25">
        <div class="font-bold text-2xl text-gray-200 capitalize">{{t('Select login method')}}</div>
        <div class="mt-6">
            <a href="https://id.twitch.tv/oauth2/authorize?client_id=q8q6jjiuc7f2ef04wmb7m653jd5ra8&redirect_uri={{urlencode(config('app.url') . '/auth/twitch-login')}}&response_type=code&scope=user:read:email&state={{\App\Tools\Req::input('redirect')}}" class="bg-blue-700 flex font-bold gap-4 h-12 hover:scale-105 items-center px-4 py-2 rounded-md shadow-md transform duration-50 hover:shadow-2xl">
                <img src="/static/img/icons/twitch-black.png" alt="Twitch icon" class="h-full">
                <div class="text-2xl text-center text-gray-100 w-full">Twitch</div>
            </a>
        </div>
    </div>
</div>