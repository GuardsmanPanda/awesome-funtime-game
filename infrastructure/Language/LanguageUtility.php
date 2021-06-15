<?php

namespace Infrastructure\Language;

use App\Tools\Req;
use App\Tools\Error;
use App\Models\Language;

class LanguageUtility {
    public static function getAcceptedLanguage(): int {
        $prefLocales = array_reduce(
            explode(',', Req::header('Accept-Language')),
            static function ($res, $el) {
                [$l, $q] = array_merge(explode(';q=', $el), [1]);
                $res[$l] = (float) $q;
                return $res;
            }, []);
        arsort($prefLocales);
        foreach ($prefLocales as $lang => $weight) {
            $target = explode('-', $lang)[0];
            $language = Language::firstWhere('translation_code', '=', $target);
            if ($language === null) {
                Error::logMessage('Could not find language for: ' . $lang . ' -- Header: ' . Req::header('Accept-Language'));
                continue;
            }
            return $language->id;
        }
        Error::logMessage('Could not find _ANY_ language for Header: ' . Req::header('Accept-Language'));
        return 1;
    }
}