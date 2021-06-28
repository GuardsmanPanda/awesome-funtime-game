<?php

namespace Areas\_Admin;

use App\Tools\Req;
use Illuminate\Routing\Controller;
use Integrations\Streetview\Streetview;

class StreetviewAdminController extends Controller {
    public function add(): array {
        return Streetview::findNearbyPanorama(Req::input('lat'), Req::input('lng'),false, 15, 20);
    }
}