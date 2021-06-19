<?php

namespace Areas\System;


use GuzzleHttp\Client;
use App\Models\MapStyle;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MapTileStyleController extends Controller {
    public function getMapTile(MapStyle $map_style, string $file_name): BinaryFileResponse {
        $parts = explode('-', str_replace('.png', '', $file_name));
        $url = str_replace(array('{z}', '{x}', '{y}'), array($parts[0], $parts[1], $parts[2]), $map_style->map_style_source);
       $client = new Client();
        Storage::put('/tile/' . $map_style->id . '/' . $file_name, $client->get($url)->getBody());
        return new BinaryFileResponse(storage_path('app/public/tile/' . $map_style->id . '/' . $file_name));
    }
}