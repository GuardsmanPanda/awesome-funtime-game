<?php

namespace App\Tools;

use App\Models\AuditError;
use Illuminate\Support\Facades\Log;
use Throwable;

class Error {
    public const GENERAL = "general";
    public const AUDIT = "audit";

    public static function logException(Throwable $throwable = null, string $message = '', string $type = "general"): void {
        try {
            $values = ['type' => $type, 'message' => $message, 'user_id' => Auth::$user_id];
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
            AuditError::create($values);
        } catch (Throwable $x) {
            Log::critical("Could not save exception, type: $type, message: $message, exception: " . $x->getMessage());
            Log::critical($x->getTraceAsString());
        }
    }

    public static function logMessage(string $message, string $type = "general"): void {
        self::logException(message: $message, type: $type);
    }
}
