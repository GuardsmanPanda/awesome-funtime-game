<?php

namespace App\Tools;

use Illuminate\Support\Facades\Log;
use Throwable;

class ErrorLog {
    public const GENERAL = "general";
    public const AUDIT = "audit";

    public static function logException(Throwable $throwable = null, string $message = '', string $type = "general"): void {
        try {
            $values = ['type' => $type, 'message' => $message, 'user_id' => Auth::id() ?? 0];
            if ($throwable !== null) {
                $values['exception_trace'] = $throwable->getTraceAsString();
                $values['exception_message'] = $throwable->getMessage();
            }
            $values['ip'] = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? request()->ip();
            $values['country'] = $_SERVER['HTTP_CF_IPCOUNTRY'] ?? 'XX';
            $values['method'] = $_SERVER['REQUEST_METHOD'];
            $values['url'] = request()->fullUrl();
            $values['parameters'] = json_encode(request()->input(), JSON_THROW_ON_ERROR);
            $values['body'] = request()->getContent();
            AuditErrorLog::create($values);
        } catch (Throwable $x) {
            Log::critical("Could not save exception, type: $type, message: $message, exception: " . $x->getMessage());
        }
    }

    public static function logErrorMessage(string $message, string $type = "general") {
    }
}
