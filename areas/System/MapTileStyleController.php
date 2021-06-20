<?php

namespace Areas\System;


use GuzzleHttp\Client;
use App\Models\MapStyle;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MapTileStyleController extends Controller {
    public function getMapTile(MapStyle $map_style, string $z, string $x, string $file_name): BinaryFileResponse {
        $y =  str_replace('.png', '', $file_name);
        $url = str_replace(array('{z}', '{x}', '{y}'), array($z, $x, $y), $map_style->map_style_source);
       $client = new Client();
       $rel_loc = 'tile/' . $map_style->id . "/$z/$x/$file_name";
        Storage::put($rel_loc, $client->get($url)->getBody());
        return new BinaryFileResponse(storage_path('app/public/' . $rel_loc));
    }
}