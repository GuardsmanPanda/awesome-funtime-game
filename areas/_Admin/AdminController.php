<?php

namespace Areas\_Admin;

use App\Tools\Req;
use Carbon\Carbon;
use App\Tools\Resp;
use App\Tools\Auth;
use App\Models\Country;
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
        $country = Country::find($json['country_code']);
        $country->population = $json['population'];
        $country->area = $json['area'];
        $country->independence_date = $json['independence_date'] ? Carbon::parse($json['independence_date']) : null;
        $country->save();
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

    public function getCountryLanguageEditor(Country $country): view {
        return view('_admin.country-language-editor');
    }



    public function listLanguage(): JsonResponse  {
        return Resp::SQLJson("
            SELECT
                la.*, u.display_name
            FROM language la
            LEFT JOIN users u ON la.created_by_user_id = u.id
            ORDER BY la.language_name
        ");
    }

    public function createLanguage(): view  {
        $lang = new Language();
        $lang->language_name = Req::input('language_name');
        $lang->two_letter_code = Req::input('two_letter_code');
        $lang->three_letter_code = Req::input('three_letter_code');
        $lang->created_by_user_id = Auth::$user_id;
        $lang->save();
        return view('_admin.language');
    }
}