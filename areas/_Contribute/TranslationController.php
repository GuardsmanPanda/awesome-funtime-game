<?php

namespace Areas\_Contribute;

use Illuminate\View\View;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class TranslationController extends Controller {
    public function index(): View {
        return view('_contribute.translation.index', [
            'languages' => DB::select("
                SELECT la.id, la.language_name FROM language la WHERE la.has_translation AND la.language_name != 'English'            
            "),
        ]);
    }
}