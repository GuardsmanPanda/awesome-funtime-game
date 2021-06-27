<?php

namespace Areas\_Admin;

use App\Tools\Req;
use App\Tools\Resp;
use Illuminate\Routing\Controller;
use Integrations\Streetview\Streetview;
use Symfony\Component\HttpFoundation\JsonResponse;

class StreetviewAdminController extends Controller {
    public function add(): JsonResponse {
        $id = Streetview::findNearbyPanorama(Req::input('lat'), Req::input('lng'),false, 15, 5);
        return Resp::SQLJsonSingle("
            SELECT ST_Y(p.panorama_location::geometry) as lat, ST_X(p.panorama_location::geometry) as lng
            FROM panorama p WHERE p.panorama_id = ?", [$id]);
    }
}