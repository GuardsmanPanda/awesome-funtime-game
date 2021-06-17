<?php

namespace Areas\_Admin;

use App\Tools\Req;
use Illuminate\View\View;
use Illuminate\Routing\Controller;
use Integrations\Streetview\Streetview;

class StreetviewAdminController extends Controller {
    public function add(): string {
        Streetview::findNearbyPanorama(Req::input('lat'), Req::input('lng'),false);
        return 'ok';
    }
}