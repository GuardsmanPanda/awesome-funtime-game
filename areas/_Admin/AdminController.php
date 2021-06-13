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
use Illuminate\Support\Facades\DB;
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
        return view('_admin.country-language-editor', [
            'country' => $country,
            'country_lang' =>DB::select("
                SELECT
                    lang.id, lang.language_name, lang.two_letter_code, lang.three_letter_code, cl.percentage
                FROM country_language cl
                LEFT JOIN language lang ON lang.id = cl.language_id
                WHERE cl.country_code = ?
                ORDER BY cl.percentage DESC
            ", [$country->country_code]),
        ]);
    }

    public function addLanguageToCountry(Country $country): View {
        DB::insert("
            INSERT INTO country_language (country_code, language_id, percentage, created_by_user_id) VALUES (?, ?, ?, ?)
            ON CONFLICT (country_code, language_id) DO UPDATE SET percentage = excluded.percentage
        ", [$country->country_code, Req::input('language_id'), Req::input('percentage'), Auth::$user_id]);
        return $this->getCountryLanguageEditor($country);
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
        $lang->total_speakers = Req::input('total_speakers') === '-1' ?  null : Req::input('total_speakers');
        $lang->native_speakers = Req::input('native_speakers') === '-1' ?  null : Req::input('native_speakers');
        $lang->created_by_user_id = Auth::$user_id;
        $lang->save();
        return view('_admin.language');
    }

    public function patchLanguage(Request $r): JsonResponse {
        $json = json_decode($r->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $language = Language::find($json['id']);
        $language->native_speakers = $json['native_speakers'];
        $language->total_speakers = $json['total_speakers'];
        $language->save();
        return $this->listLanguage();
    }


    public function getCountryFactEditor(Country $country): view {
        return view('_admin.country-fact-editor', [
            'country' => $country,
            'country_fact' =>DB::select("
                SELECT
                    cf.id, cf.fact_text
                FROM country_fact cf
                WHERE cf.country_code = ?
                ORDER BY cf.id
            ", [$country->country_code]),
        ]);
    }

    public function addFactToCountry(Country $country): View {
        DB::insert("
            INSERT INTO country_fact (country_code, fact_text,  created_by_user_id) VALUES (?, ?, ?)
        ", [$country->country_code, Req::input('fact_text'), Auth::$user_id]);
        return $this->getCountryFactEditor($country);
    }

    public function listFact(string $country_code): JsonResponse  {
        return Resp::SQLJson("
            SELECT
                cf.*, u.display_name
            FROM country_fact cf
            LEFT JOIN users u ON cf.created_by_user_id = u.id
            WHERE cf.country_code = ?
            ORDER BY cf.id
        ", [$country_code]);
    }
}