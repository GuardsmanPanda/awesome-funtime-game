<?php

namespace Areas\System;


use GuzzleHttp\Client;
use App\Models\MapStyle;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MapTileStyleController extends Controller {
    public function getMapTile(MapStyle $map_style, string $z, string $file_name): BinaryFileResponse {
        $parts = explode('-', str_replace('.png', '', $file_name));
        $url = str_replace(array('{z}', '{x}', '{y}'), array($z, $parts[0], $parts[1]), $map_style->map_style_source);
       $client = new Client();
       $rel_loc = 'tile/' . $map_style->id . "/$z/$file_name";
        Storage::put($rel_loc, $client->get($url)->getBody());
        return new BinaryFileResponse(storage_path('app/public/' . $rel_loc));
    }
}