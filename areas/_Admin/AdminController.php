<?php

namespace Areas\_Admin;

use App\Tools\Resp;
use App\Models\Language;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminController {
    public function index(): view {
        return view('_admin.index');
    }

    public function patchCountry(Request $r): JsonResponse {
        $json = json_decode($r->getContent(), true, 512, JSON_THROW_ON_ERROR);
        return $this->listCountry();
    }

    public function listCountry(): JsonResponse {
        return Resp::SQLJson("
            SELECT 
                   c.*,
                   (SELECT COUNT(*) FROM country_language cl WHERE cl.country_code = c.country_code) as language_count,
                   (SELECT COUNT(*) FROM country_fact cf WHERE cf.country_code = c.country_code) as fact_count
            FROM country c ORDER BY c.country_name");
    }

    public function listLanguage(): Collection  {
        return Language::all();
    }
}