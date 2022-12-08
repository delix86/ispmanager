<?php

namespace App\Services;

use App\Log;
use App\Logtype;

class HistoryLogger
{
    public function log($taskId, $logTypeName, $text, $userId)
    {
        $logType = Logtype::query()->where('name', $logTypeName)->firstOrFail();

        $log = new Log();
        $log->task_id = $taskId;
        $log->text = $text;
        $log->logtype_id = $logType->getKey();
        $log->user_id = $userId;
        $log->save();
    }
}