<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Log an administrative action.
     *
     * @param string $action 'created', 'updated', 'deleted', 'login', etc.
     * @param string|null $model Model name
     * @param int|null $modelId ID of the object
     * @param string|null $description Human-readable summary
     * @param array|null $properties Data changes [old => ..., new => ...]
     * @return void
     */
    public static function log($action, $model = null, $modelId = null, $description = null, $properties = null)
    {
        if (!auth()->check()) {
            return;
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
