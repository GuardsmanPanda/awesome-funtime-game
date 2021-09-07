<?php

namespace Areas\_Contribute;

use App\Tools\Req;
use App\Tools\Resp;
use App\Models\Language;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class TranslationController extends Controller {
    public function index(): View {
        return view('_contribute.translation.index', [
            'languages' => DB::select("
                SELECT la.id, la.language_name FROM language la WHERE la.has_translation AND la.language_name != 'English'
            "),
        ]);
    }

    public function language(Language $language): View {
        return view('_contribute.translation.language', [
            'lang' => $language,
        ]);
    }

    public function languageList(Language $language): JsonResponse {
        return Resp::SQLJson("
            SELECT
                t.id, t.translation_phrase, COALESCE(t.translation_hint, t.translation_group) as translation_hint,
                t.translation_group,
                tl.translated_phrase, tl.translation_status
            FROM translation t
            LEFT JOIN translation_language tl on tl.translation_id = t.id AND tl.language_id = ?
            WHERE in_use
        ", [$language->id]);
    }

    public function patchTranslation(int $language_id, int $translation_id): Response {
        if (Req::input('translated_phrase') !== null) {
            DB::update("
            UPDATE translation_language SET translated_phrase = ?, translation_status = 'VERIFIED'
            WHERE language_id = ? AND translation_id = ?
        ", [Req::input('translated_phrase'), $language_id, $translation_id]);
        }
       if (Req::input('translation_status') !== null) {
            DB::update("
            UPDATE translation_language SET translation_status = ?
            WHERE language_id = ? AND translation_id = ?
        ", [Req::input('translation_status'), $language_id, $translation_id]);
        }
        return new Response(status: 204);
    }
}